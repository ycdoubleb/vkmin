<?php

namespace common\models\vk;

use Yii;

/**
 * This is the model class for table "{{%topic_course}}".
 *
 * @property int $id
 * @property int $topic_id 专题ID，关联topic,id
 * @property int $course_id 课程ID，关联course,id
 * @property int $is_del 是否删除 0否 1是
 */
class TopicCourse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic_course}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['topic_id', 'course_id', 'is_del'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'topic_id' => Yii::t('app', 'Topic ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
}
