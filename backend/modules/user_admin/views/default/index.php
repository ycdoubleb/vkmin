<?php

use common\models\AdminUser;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @ver $dataProvider ActiveDataProvider */
/* @var $model AdminUser */

$this->title = '管理用户';
?>
<div class="user-index">
    <p>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",  
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => [
                    'style' => [
                        'width' => '20px',
                    ]
                ]
            ],
            
            'username',
            'nickname',
            [
                'attribute' => 'type',
                'filter' => AdminUser::$typeName,
                'value' => function ($model) {
                    return AdminUser::$typeName[$model->type];
                }
            ],
            'email',
            'guid',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
                    
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>