<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Config */

$this->title = Yii::t(null, '{Create}{Config}',[
            'Create' => Yii::t('app', 'Create'),
            'Config' => Yii::t('app', 'Config'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Config}{Administration}',[
    'Config' => Yii::t('app', 'Config'),
    'Administration' => Yii::t('app', 'Administration'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
