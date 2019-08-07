<?php

use common\models\vk\Course;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Course */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Course}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="course-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-striped table-bordered detail-view wsk-table-detail',
        ],
        'attributes' => [
            'name',
            [
                'label' => Yii::t('app', 'Cover Img'),
                'format' => 'raw',
                'value' => Html::img($model->cover_url, ['width' => 104, 'height' => 69])
            ],
            [
                'attribute' => 'teacher_name',
                'label' => I18NUitl::t('app', '{Teacher}{Name}'),
            ],
            [
                'label' => I18NUitl::t('app', '{Teacher}{Avatar}'),
                'format' => 'raw',
                'value' => Html::img($model->teacher_avatar_url, ['class' => 'img-circle', 'width' => 55, 'height' => 55])
            ],
            [
                'label' => I18NUitl::t('app', '{Suggest}{Learning}{Time}'),
                'value' => $model->suggest_time / 60 . '分钟'
            ],
            [
                'label' => I18NUitl::t('app', '{Course}{Path}'),
                'value' => $model->url
            ],
            [
                'label' => I18NUitl::t('app', '{Course}{Introduction}'),
                'format' => 'raw',
                'value' => "<div class=\"detail-des\">{$model->introduction}</div>"
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
