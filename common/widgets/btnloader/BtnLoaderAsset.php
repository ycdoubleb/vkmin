<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\btnloader;

use yii\web\AssetBundle;

/**
 * Description of StatisticsAsset
 *
 * @author Administrator
 */
class BtnLoaderAsset extends AssetBundle{
    //put your code here
    public $sourcePath = '@common/widgets/btnloader';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];  
    public $js = [
        'js/loader.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
