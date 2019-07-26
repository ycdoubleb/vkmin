<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property int $profile_id
 * @property string $user_id    用户ID
 * @property string $province   省
 * @property string $city       市
 * @property string $district   区
 * @property string $twon       镇
 * @property string $address    详细地址
 * @property string $job_title  职称
 * @property int    $level      等级
 * @property string $company    公司
 * @property string $department 部门
 * @property int    $is_certificate 是否认证
 * @property string $sign       签名
 * @property int    $created_at 创建时间
 * @property int    $updated_at 更新时间
 */
class UserProfile extends ActiveRecord
{
    /** 认证-No */
    const CERTIFICATE_NO = 0;
    /** 认证-Yes */
    const CERTIFICATE_YES = 1;
    
    /**
     * 认证
     * @var array 
     */
    public static $certificateIs = [
      self::CERTIFICATE_NO => '否',  
      self::CERTIFICATE_YES => '是',  
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'province', 'city', 'district', 'twon', 'level', 'is_certificate', 'created_at', 'updated_at'], 'integer'],
            [['address', 'job_title', 'company', 'department', 'sign'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => Yii::t('app', 'Profile ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'twon' => Yii::t('app', 'Twon'),
            'address' => Yii::t('app', 'Address'),
            'job_title' => Yii::t('app', 'Job Title'),
            'level' => Yii::t('app', 'Level'),
            'company' => Yii::t('app', 'Company'),
            'department' => Yii::t('app', 'Department'), 
            'is_certificate' => Yii::t('app', 'Is Certificate'), 
            'sign' => Yii::t('app', 'Sign'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
