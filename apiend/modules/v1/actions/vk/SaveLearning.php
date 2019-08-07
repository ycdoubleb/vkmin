<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\api\ApiResponse;
use common\models\vk\Course;
use common\models\vk\CourseLearning;
use Yii;
use yii\db\Exception;

/**
 * 获取课程数据
 */
class SaveLearning extends BaseAction {

    /**
     * 设置接口检验必须的参数
     * @var type 
     */
    protected $requiredParams = ['course_id', 'user_id'];

    public function run() {
        // 课程id
        $course_id = $this->getSecretParam('course_id');
        // 用户id
        $user_id = $this->getSecretParam('user_id');

        if (Yii::$app->request->isPost) {
            // 开启事务
            $trans = Yii::$app->db->beginTransaction();
            try {
                $model = CourseLearning::findOne([
                    'course_id' => $course_id, 
                    'user_id' => $user_id,
                    'is_del' => 0
                ]);
                if($model == null){
                    $model = new CourseLearning([
                        'course_id' => $course_id, 
                        'user_id' => $user_id,
                        'start_time' => time()
                    ]);
                    if ($model->save()) {
                        $couser = Course::findOne(['id' => $course_id]);
                        $couser->learning_count = $couser->learning_count + 1;
                        $couser->update(true, ['learning_count']);
                    }
                }
                $trans->commit();  //提交事务
                return new Response(Response::CODE_COMMON_OK, null, $model->toArray());
            } catch (Exception $ex) {
                $trans->rollBack(); //回滚事务
                return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex->getTraceAsString());
            }
        }else{
            return new Response(Response::CODE_COMMON_UNKNOWN, '未知错误', ['course_id' => $course_id, 'user_id' => $user_id]);
        }  
    }
}
