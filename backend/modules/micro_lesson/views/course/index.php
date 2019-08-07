<?php

use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Course}{List}');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">
    
    <div class="col-sm-12 clean-padding" style="margin-bottom: 20px;">
        
        <?php
            // 按钮
            echo Html::a(I18NUitl::t('app', '{Create}{Course}'), ['create'], ['class' => 'btn btn-success btn-flat']);
            echo ' ' . Html::a(Yii::t('app', 'Publish'), ['publish'], [
                        'class' => 'btn btn-danger btn-flat',
                        'onclick' => 'onClick(this);return false;',
                    ]);
            echo ' ' . Html::a(Yii::t('app', 'Downshelf'), ['downshelf'], [
                        'class' => 'btn btn-danger btn-flat',
                        'onclick' => 'onClick(this);return false;'
                    ]);
            
            // 表单
            $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'id' => 'wsk_form',
                    'class' => 'wsk-form form-horizontal col-sm-4 clean-padding pull-right'
                ],
            ]); 
            
            // 输入框
            echo $form->field($searchModel, 'keyword', [
                'options' => ['class' => ''],
                'template' => "<div class=\"col-sm-6\">{input}</div>",  
            ])->textInput([
                'placeholder' => '课程或老师名',
                'onchange' => 'submitForm()'
            ]);
            // 下拉框
            echo $form->field($searchModel, 'is_recommend', [
                'options' => ['class' => ''],
                'template' => "<div class=\"col-sm-3 clean-padding\">{input}</div>",  
            ])->widget(Select2::class, [
                'data' => ['否', '是'],
                'hideSearch' => true,
                'options' => ['placeholder' => Yii::t('app', 'Recommend')],
                'pluginEvents' => [
                    'change' => 'function(){submitForm()}'
                ]
            ]);
            // 下拉框
            echo $form->field($searchModel, 'is_publish', [
                'options' => ['class' => ''],
                'template' => "<div class=\"col-sm-3 clean-padding\">{input}</div>",  
            ])->widget(Select2::class, [
                'data' => ['否', '是'],
                'hideSearch' => true,
                'options' => ['placeholder' => Yii::t('app', 'Publish')],
                'pluginEvents' => [
                    'change' => 'function(){submitForm()}'
                ]
            ]);
            
            ActiveForm::end();
        ?>
        
    </div>
    
    <!--表格-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table wsk-table table-striped table-bordered table-fixed'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => [
                    'style' => 'width:30px;'
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
                'attribute' => 'teacher_name',
                'label' => I18NUitl::t('app', '{Teacher}{Name}'),       
                'headerOptions' => [
                    'style' => 'width:190px;'
                ]
            ], 
            [
                'attribute' => 'teacher_avatar_url',
                'label' => I18NUitl::t('app', '{Teacher}{Avatar}'),
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->teacher_avatar_url, ['class' => 'img-circle', 'width' => 55, 'height' => 55]);
                },
                'headerOptions' => [
                    'style' => 'width:140px;'
                ]
            ],   
            [
                'attribute' => 'suggest_time',
                'label' => I18NUitl::t('app', '{Suggest}{Learning}{Time}'),
                'value' => function($model){
                    return $model->suggest_time / 60 . '分钟';
                },
                'headerOptions' => [
                    'style' => 'width:140px;'
                ]
            ],       
            [
                'attribute' => 'is_recommend',
                'label' => I18NUitl::t('app', '{Is}{Recommend}'),
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => [
                    'style' => 'width:80px;'
                ]
            ],
            [
                'attribute' => 'is_publish',
                'label' => I18NUitl::t('app', '{Is}{Publish}'),
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => [
                    'style' => 'width:80px;'
                ]
            ],
           
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model){
                        return Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], [
                            'class' => 'btn btn-default btn-flat'
                        ]);
                    },
                ],
                'headerOptions' => [
                    'style' => 'width:100px;'
                ],
                'template' => '{view}',
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

    /**
     * 单击事件
     * @param {Object} elem
     * @returns {undefined}
     */
    function onClick(elem){
        var val = getCheckBoxsValue(), 
            url = $(elem).attr('href');
        
        if(val.length > 0){
            window.location.href = url + '?ids=' + val;
        }else{
           alert("<?= Yii::t('app', 'Please select at least one.') ?>");
        }
    }

    /**
     * 获取 getCheckBoxsValue
     * @returns {Array|getcheckBoxsValue.val}
     */
    function getCheckBoxsValue(){
        var val = [],
            checkBoxs = $('input[name="selection[]"]');
        // 循环组装素材id
        for(i in checkBoxs){
            if(checkBoxs[i].checked){
               val.push(checkBoxs[i].value);
            }
        }
        
        return val
    }
    
</script>
