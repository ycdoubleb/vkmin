<?php

use common\models\vk\Course;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model Course */

$this->title = I18NUitl::t('app', '{Update}{Course}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Course}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
