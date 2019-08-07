<?php

use common\models\vk\Topic;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Topic */

$this->title = I18NUitl::t('app', '{Update}{Topic}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Topic}{Admin}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="topic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
