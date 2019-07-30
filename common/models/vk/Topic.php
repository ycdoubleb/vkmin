<?php

namespace common\models\vk;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%topic}}".
 *
 * @property int $id
 * @property string $name 专辑名称
 * @property string $cover_url 封面路径
 * @property string $introduction 简介
 * @property int $learning_count 学习过该课程的人数
 * @property int $play_count 播放次数
 * @property int $favorite_count 收藏次数
 * @property int $like_count 点赞/喜欢次数
 * @property int $comment_count 评论次数
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Topic extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic}}';
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
            [['learning_count', 'play_count', 'favorite_count', 'like_count', 'comment_count', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['cover_url', 'introduction'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'cover_url' => Yii::t('app', 'Cover Url'),
            'introduction' => Yii::t('app', 'Introduction'),
            'learning_count' => Yii::t('app', 'Learning Count'),
            'play_count' => Yii::t('app', 'Play Count'),
            'favorite_count' => Yii::t('app', 'Favorite Count'),
            'like_count' => Yii::t('app', 'Like Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
