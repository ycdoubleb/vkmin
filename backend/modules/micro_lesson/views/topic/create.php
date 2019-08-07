<?php

use common\models\vk\Topic;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Topic */

$this->title = I18NUitl::t('app', '{Create}{Topic}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Topic}{Admin}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
