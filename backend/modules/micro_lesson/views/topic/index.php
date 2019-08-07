<?php

use backend\modules\micro_lesson\models\TopicSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel TopicSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Topic}{Admin}');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-index">
    
    <div class="col-sm-12 clean-padding" style="margin-bottom: 20px;">
        <?php
            // 按钮
            echo Html::a(I18NUitl::t('app', '{Create}{Topic}'), ['create'], ['class' => 'btn btn-success btn-flat']);
            
            // 表单
            $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'id' => 'wsk_form',
                    'class' => 'wsk-form form-horizontal col-sm-2 clean-padding pull-right'
                ],
            ]);
            
            // 输入框
            echo $form->field($searchModel, 'name', [
                'options' => ['class' => ''],
                'template' => "<div class=\"col-sm-12 clean-padding\">{input}</div>", 
            ])->textInput([
                'placeholder' => I18NUitl::t('app', '{Topic}{Name}'),
                'onchange' => 'submitForm()'
            ]);
            
            ActiveForm::end();
        ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table wsk-table table-striped table-bordered table-fixed'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width:60px;'
                ]
            ],

            [
                'attribute' => 'name',
                'headerOptions' => [
                    'style' => 'width:280px;'
                ]
            ],
            [
                'attribute' => 'cover_url',
                'label' => Yii::t('app', 'Cover Img'),
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->cover_url, ['width' => 104, 'height' => 69]);  
                },
                'headerOptions' => [
                    'style' => 'width:190px;'
                ]
            ],
            [
                'attribute' => 'introduction',
                'label' => Yii::t('app', 'Introduction'),
                'headerOptions' => [
                    'style' => 'width:470px;'
                ]
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'couser' => function($url, $model){
                        return Html::a(Yii::t('app', 'Course'), ['topic-course/index', 'topic_id' => $model->id], [
                            'class' => 'btn btn-primary btn-flat'
                        ]);
                    },
                    'view' => function($url, $model){
                        return ' ' . Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], [
                            'class' => 'btn btn-default btn-flat'
                        ]);
                    },
                ],
                'headerOptions' => [
                    'style' => 'width:200px;'
                ],
                'template' => '{couser}{view}',
            ],
        ],
    ]); ?>
</div>

<script type="text/javascript">
    
    /**
     * 提交表单
     * @returns {undefined}
     */
    function submitForm(){
        $('#wsk_form').submit();
    }
    
</script>
