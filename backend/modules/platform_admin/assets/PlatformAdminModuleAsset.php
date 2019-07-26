<?php

namespace backend\modules\platform_admin\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Main backend application asset bundle.
 */
class PlatformAdminModuleAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/platform_admin/assets';
    public $baseUrl = '@backend/modules/platform_admin/assets';
    public $css = [
        'css/platform_admin_module.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
