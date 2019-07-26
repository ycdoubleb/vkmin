<?php

namespace common\models\system;

use Yii;
use yii\db\ActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property int $id 表id
 * @property string $name 地区名称
 * @property int $level 地区等级 分省市县区
 * @property int $parent_id 父id
 */
class Region extends ActiveRecord
{

    public static $cache;
    public static $cacheKey = 'region';
    public static $regionJson;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '表id'),
            'name' => Yii::t('app', '地区名称'),
            'level' => Yii::t('app', '地区等级 分省市县区'),
            'parent_id' => Yii::t('app', '父id'),
        ];
    }

    /**
     * 获取地区列表
     * @param array $condition
     * 
     * @return array [{id:name},...]
     */
    public static function getRegionList($condition = [])
    {
        return ArrayHelper::map(self::findAll($condition), 'id', 'name');
    }

    /**
     * 返回地区名
     * @param array $ids
     */
    public static function getRegionName($ids)
    {
        return ArrayHelper::getColumn(Region::findAll(['id' => $ids]), 'name');
    }

    /**
     * 反回json格式的省市区
     * @param int $maxLevel     最大层级，建议不要超过4级
     */
    public static $_regionGroups = null;
    public static $_regions = null;
    public static $_count = 0;

    public static function getRegionByJson($maxLevel)
    {
        $regions = Region::find()->where(['<=', 'level', $maxLevel])->asArray()->all();
        self::$_regions = ArrayHelper::index($regions, 'id');
        self::$_regionGroups = ArrayHelper::index($regions, null, 'level');
        for ($i = $maxLevel; $i > 1; $i--) {
            self::_findRegionParents($i);
        }
        $result = self::_findRegions(1);
        return $result;
    }

    /**
     * 从缓存里加载省市区数据
     * @return jsonString
     */
    public static function getRegionByJsonFromCache()
    {
        self::loadFromCache();
        return self::$regionJson;
    }

    /**
     * 设置children
     * @param int $level
     */
    private static function _findRegionParents($level)
    {
        $regions = &self::$_regionGroups[$level];
        $parentRegion;
        $t_region;
        foreach ($regions as $region) {
            self::$_count ++;
            $t_region = self::$_regions[$region['id']];
            $parentRegion = &self::$_regions[$region['parent_id']];
            $r = [
                'id' => $region['id'],
                'n' => $region['name'],
            ];
            if (isset($t_region['c'])) {
                $r['c'] = $t_region['c'];
            }
            $parentRegion['c'][] = $r;
        }
    }

    private static function _findRegions($level)
    {
        $regions = [];
        foreach (self::$_regions as $region) {
            self::$_count ++;
            if ($region['level'] == $level) {
                $r = [
                    'id' => $region['id'],
                    'n' => $region['name'],
                ];
                if (isset($region['c'])) {
                    $r['c'] = $region['c'];
                }

                $regions [] = $r;
            }
        }
        return $regions;
    }

    /**
     * 清除缓存
     */
    public static function invalidateCache()
    {
        if (self::$cache !== null) {
            self::$cache->delete(self::$cacheKey);
            self::$regionJson = null;
        }
    }

    /**
     * 从缓存加载
     * @return type
     */
    public static function loadFromCache()
    {
        if (self::$regionJson !== null) {
            return;
        }

        if (!self::$cache) {
            self::$cache = Instance::ensure('yii\caching\FileCache', 'yii\caching\CacheInterface');
        }

        self::$regionJson = self::$cache->get(self::$cacheKey);
        if (self::$regionJson) {
            return;
        }

        self::$regionJson = self::getRegionByJson(4);

        self::$cache->set(self::$cacheKey, self::$regionJson);
    }

}
