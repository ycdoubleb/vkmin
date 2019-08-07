<?php

use common\models\vk\Topic;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Topic */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Topic}{Admin}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="topic-view">

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
            [
                'label' => I18NUitl::t('app', '{Topic}{Name}'),
                'value' => $model->name
            ],
            [
                'label' => I18NUitl::t('app', '{Topic}{Cover Img}'),
                'format' => 'raw',
                'value' => Html::img($model->cover_url, ['width' => 104, 'height' => 69])
            ],
            [
                'label' => I18NUitl::t('app', '{Course}{Num}'),
                'value' => 50
            ],
            [
                'label' => I18NUitl::t('app', '{Topic}{Introduction}'),
                'format' => 'raw',
                'value' => "<div class=\"detail-des\">{$model->introduction}</div>"
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
