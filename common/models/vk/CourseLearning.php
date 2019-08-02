<?php

namespace common\models\vk;

use Yii;

/**
 * This is the model class for table "{{%course_learning}}".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $course_id 课程ID，关联course,id
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $learning_time 已学习总时间（秒）
 * @property string $progress 学习进度
 * @property int $is_finish 是否已完成 0否 1是
 * @property int $is_del 是否删除 0否 1是
 * @property string $data 学习数据json
 */
class CourseLearning extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%course_learning}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id', 'start_time', 'end_time', 'learning_time', 'is_finish', 'is_del'], 'integer'],
            [['progress'], 'number'],
            [['data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'learning_time' => Yii::t('app', 'Learning Time'),
            'progress' => Yii::t('app', 'Progress'),
            'is_finish' => Yii::t('app', 'Is Finish'),
            'is_del' => Yii::t('app', 'Is Del'),
            'data' => Yii::t('app', 'Data'),
        ];
    }
}
