<?php

use common\models\vk\Course;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Course */

$this->title = I18NUitl::t('app', '{Create}{Course}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Course}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
