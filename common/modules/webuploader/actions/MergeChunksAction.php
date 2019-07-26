<?php

namespace common\modules\webuploader\actions;

use common\components\getid3\MediaInfo;
use common\modules\webuploader\models\Uploadfile;
use common\modules\webuploader\models\UploadfileChunk;
use common\modules\webuploader\models\UploadResponse;
use common\utils\FfmpegUtil;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\HttpException;

/**
 * 合并分片
 * 1、合并分片为一个文件
 * 2、移动文件到upload文件
 * 3、删除分片数据和文件
 * 4、创建视频数据及缩略图
 * 5、创建文件数据
 *
 * @author Administrator
 */
class MergeChunksAction extends BaseAction {

    public function run() {
        $params = $_REQUEST;
        //应用web路径，默认会放本应用的web下，通过设置root_path可改变目标路径
        $root_path = isset($params["root_path"]) ? $params["root_path"] . '/' : '';                             // 根目录
        $dir_path = isset($params["dir_path"]) ? '/' . $params["dir_path"] : '';                                // 文件在要存放的目录
        $targetDir = $root_path . 'upload/webuploader/upload_tmp';                                              // 临时文件夹
        $uploadDir = $root_path . 'upload/webuploader/upload' . $dir_path;                                      // 文件在根目录下要存放的目录
        $name = ArrayHelper::getValue($params, 'name', 'no_name.temp');                                         // 文件名
        //查询将要被替换的文件
        $replace_id = ArrayHelper::getValue($params, 'replace_id', '');                                         // 被替换文件id
        $replace_file = Uploadfile::findOne(['id' => $replace_id]);                                             

        // Create target dir
        $this->mkdir($targetDir);
        $this->mkdir($uploadDir);

        // Chunking might be enabled
        //文件md5
        $fileMd5 = isset($params["fileMd5"]) ? $params["fileMd5"] : '';
        //文件大小
        $fileSize = isset($params["size"]) ? (integer) $params["size"] : 0;
        //分片数
        $chunks = isset($params["chunks"]) ? (integer) $params["chunks"] : 1;
        //文件路径
        $uploadPath = $uploadDir . '/' . $fileMd5 . strrchr($name, '.');

        if ($fileMd5 == '') {
            return new UploadResponse(UploadResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'fileMd5']);
        } else {
            //查出所有分片记录
            $fileChunks = UploadfileChunk::find()->where(['file_md5' => $fileMd5 , 'is_del' => 0])->orderBy('chunk_index')->all();
            if ($fileChunks == null) {
                return new UploadResponse(UploadResponse::CODE_FILE_CHUNKS_NOT_FOUND);
            } else {
                /* @var $fileChunk UploadfileChunk  */
                $unFoundChunks = [];
                foreach ($fileChunks as $index => $fileChunk) {
                    if (!file_exists($fileChunk->chunk_path)) {
                        unset($fileChunks[$index]);
                        $unFoundChunks [] = $fileChunk->chunk_id;
                        //return new UploadResponse(UploadResponse::CODE_CHUNK_NOT_FOUND, null, null, ['chunkPath' => $fileChunk->chunk_path]);
                    }
                }
                //删除无用分片数据
                if(count($unFoundChunks)>0){
                    UploadfileChunk::updateAll(['is_del' => 1], ['chunk_id' => $unFoundChunks]);
                }
                
                //检查分片数量是否确
                if(count($fileChunks) != $chunks){
                    return new UploadResponse(UploadResponse::CODE_CHUNK_NUM_WRONG,"fc=$fileChunks,chunks=$chunks");
                }

                if (!$out = @fopen($uploadPath, "wb")) {
                    return new UploadResponse(UploadResponse::CODE_OPEN_OUPUT_STEAM_FAIL);
                }
                if (flock($out, LOCK_EX)) {
                    //合并分片
                    foreach ($fileChunks as $fileChunk) {
                        if (!$in = @fopen($fileChunk->chunk_path, "rb")) {
                            break;
                        }
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        @fclose($in);
                        //@unlink($fileChunk->chunk_path);
                    }
                    flock($out, LOCK_UN);
                }
                @fclose($out);

                /*
                 * 写入数据库
                 */
                $dbFile = Uploadfile::findOne(['md5' => $fileMd5]);
                if ($dbFile == null) {
                    $dbFile = new Uploadfile(['md5' => $fileMd5]);
                }
                //设置 thumb_path,duration,width,height,bitrate
                $info = $this->getFileInfo($uploadPath);
                $thumb_path = $info['thumb_path'];
                unset($info['thumb_path']);

                $dbFile->name = $name;
                $dbFile->path = $uploadPath;
                $dbFile->thumb_url = $thumb_path;
                $dbFile->created_by = Yii::$app->user->id;
                $dbFile->size = $fileSize == 0 ? filesize($uploadPath) : $fileSize;
                $dbFile->ext = strtolower(pathinfo($uploadPath,PATHINFO_EXTENSION));
                $dbFile->is_del = 0;
                $dbFile->oss_upload_status = Uploadfile::OSS_UPLOAD_STATUS_NO;
                $dbFile->oss_key = $replace_file == null ? "" : $replace_file->oss_key;
                $dbFile->metadata = json_encode($info);

                if ($dbFile->save()) {
                    //删除临时文件
                    foreach ($fileChunks as $fileChunk) {
                        @unlink($fileChunk->chunk_path);
                    }
                    //删除数据库分片数据记录
                    UploadfileChunk::updateAll(['is_del' => 1], ['file_md5' => $fileMd5]);
                    //上传到OSS
                    try {
                        $dbFile->uploadOSS();
                    } catch (\Exception $ex) {
                        return new UploadResponse(UploadResponse::CODE_UPLOAD_OSS_FAIL, null, $ex->getMessage());
                    }
                    // Return Success JSON-RPC response
                    return new UploadResponse(UploadResponse::CODE_COMMON_OK, null, $dbFile->toProcessedArray());
                } else {
                    return new UploadResponse(UploadResponse::CODE_FILE_SAVE_FAIL, null, $dbFile->getErrorSummary(true));
                }
            }
        }
        return new UploadResponse(UploadResponse::CODE_COMMON_UNKNOWN);
    }
    /**
     * 获取文件信息
     * 视频：width,height,duration,bitrate,thumb_path
     * 音频：duration
     * 图片：thumb_path
     * @param string $filepath
     */
    private function getFileInfo($filepath) {
        $info = ['duration' => 0, 'thumb_path' => ""];
        try {
            $filter = [
                'video' => ['mp4', 'flv', 'wmv', 'mov', 'avi', 'mpg', 'rmvb', 'rm', 'mkv'],
                'audio' => ['mp3'],
                'image' => ['jpg', 'jpeg', 'png', 'gif'],
            ];
            $type = '';
            foreach ($filter as $key => $filters) {
                if (in_array(strtolower(pathinfo($filepath, PATHINFO_EXTENSION)), $filters)) {
                    $type = $key;
                    break;
                }
            }
            switch ($type) {
                case 'video':
                    $info = FfmpegUtil::getVideoInfoByUfileId($filepath);
                case 'image':
                    $info['thumb_path'] = $this->createThumb($type, $filepath);
                    break;
                case 'audio':
                    $info['duration'] = MediaInfo::getMediaInfo($filepath)['playtime_seconds'];
                    break;
            }
        } catch (\Exception $ex) {
            
        }

        return $info;
    }

    /**
     * 创建文件缩略图，只会对视频和图片生成缩略图
     * @param string $type          资源类型 video,image
     * @param string $filepath      文件路径
     * @param type $width           缩略图宽度
     * @param type $height          缩略图高度
     * @param type $mode            模式：outbound填满高宽，inset等比缩放
     * @return string   生成缩略图路径
     */
    private function createThumb($type, $filepath, $width = 128, $height = null, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND) {
        $fileinfo = pathinfo($filepath);
        $thumbpath = $fileinfo['dirname'] . '/thumbs/' . $fileinfo['filename'] . '.jpg';
        $this->mkdir($fileinfo['dirname'] . '/thumbs/');
        Image::$thumbnailBackgroundColor = '000';
        switch ($type) {
            case 'video':
                //创建视频缩略图
                //先截屏视频，再创建缩略图
                $filepath = FfmpegUtil::createVideoImageByUfileId($fileinfo['filename'], $filepath, $fileinfo['dirname'] . '/thumbs/');
                break;
            case 'image':
                //创建图片缩略图
                Image::thumbnail($filepath, $width, $height, $mode)->save($thumbpath);
                break;
        }
        return $thumbpath;
    }

    /**
     * 获取对应文件类型图标
     * @param suffix        文件后缀
     * @returns {string}
     * @private
     */
    private function getExt($suffix) {
        //无法生成缩略图的文件图标
        $exts = [
            'doc' => ['doc', 'docx'],
            'xls' => ['xls', 'xlsx'],
            'ppt' => ['ppt', 'pptx'],
            'ai' => ['ai'],
            'audio' => ['mp3', 'wma'],
            'gif' => ['gif'],
            'jpg' => ['jpg', 'jpeg'],
            'pdf' => ['pdf'],
            'psd' => ['psd'],
            'video' => ['mp4', 'avi', 'mpg', 'wmv', 'rmvb', 'rm', 'mov'],
            'zip' => ['zip', 'rar', 'tar', 'gz'],
        ];
        $ext = 'other';
        $suffix = strtolower($suffix);
        foreach ($exts as $key => $ext_arr) {
            if (in_array($suffix, $ext_arr)) {
                return $key;
            }
        }
        return $ext;
    }

    /**
     * 创建目录
     * @param string $path
     */
    private function mkdir($path) {
        if (!file_exists($path)) {
            if (!(@mkdir($path, 0777, true))) {
                throw new HttpException(500, '创建目录失败');
            }
        }
    }
}
