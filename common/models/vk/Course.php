<?php

namespace common\models\vk;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property int $id
 * @property int $type 类型：1 h5
 * @property string $name 课程名称
 * @property string $cover_url 封面路径
 * @property string $introduction 简介
 * @property int $suggest_time 建议学习时间
 * @property string $url 课件/视频路径
 * @property string $teacher_name 老师名称
 * @property string $teacher_avatar_url 老师头像
 * @property int $learning_count 学习过该专题的人数
 * @property int $play_count 播放次数
 * @property int $favorite_count 收藏次数
 * @property int $like_count 点赞/喜欢次数
 * @property int $comment_count 评论次数
 * @property int $is_recommend 是否推荐 0否 1是
 * @property int $is_publish 是否发布 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Course extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'suggest_time', 'learning_count', 'play_count', 'favorite_count', 'like_count', 'comment_count', 'is_recommend', 'is_publish', 'created_at', 'updated_at'], 'integer'],
            [['name', 'teacher_name'], 'string', 'max' => 50],
            [['cover_url', 'introduction', 'url', 'teacher_avatar_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'cover_url' => Yii::t('app', 'Cover Url'),
            'introduction' => Yii::t('app', 'Introduction'),
            'suggest_time' => Yii::t('app', 'Suggest Time'),
            'url' => Yii::t('app', 'Url'),
            'teacher_name' => Yii::t('app', 'Teacher Name'),
            'teacher_avatar_url' => Yii::t('app', 'Teacher Avatar Url'),
            'learning_count' => Yii::t('app', 'Learning Count'),
            'play_count' => Yii::t('app', 'Play Count'),
            'favorite_count' => Yii::t('app', 'Favorite Count'),
            'like_count' => Yii::t('app', 'Like Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'is_recommend' => Yii::t('app', 'Is Recommend'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
