<?php

namespace common\models\vk;

use Yii;

/**
 * This is the model class for table "{{%topic_favorite}}".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $topic_id 专题ID，关联topic,id
 * @property string $group 分组
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class TopicFavorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic_favorite}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'topic_id', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['group'], 'string', 'max' => 50],
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
            'topic_id' => Yii::t('app', 'Topic ID'),
            'group' => Yii::t('app', 'Group'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
