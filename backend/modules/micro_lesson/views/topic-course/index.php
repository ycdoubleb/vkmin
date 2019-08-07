<?php

use backend\modules\micro_lesson\models\TopicCourseSearch;
use common\models\vk\Topic;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $topicModel Topic */
/* @var $searchModel TopicCourseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', "{Topic}{Course}{List} > {$topicModel->name}");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-course-index">

    <div class="col-sm-12 clean-padding" style="margin-bottom: 20px;">
        <?php
            // 按钮
            echo Html::a(Yii::t('app', 'Add'), ['course-list', 'topic_id' => $topicModel->id], [
                'class' => 'btn btn-success btn-flat',
                'onclick' => 'showModal($(this).attr("href"));return false;'
                
            ]);
            echo ' ' . Html::a(Yii::t('app', 'Moveout'), ['moveout', 'topic_id' => $topicModel->id], [
                'class' => 'btn btn-danger btn-flat',
                'onclick' => 'onClick(this);return false;'
            ]);
            
            // 表单
            $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'id' => 'wsk_form',
                    'class' => 'wsk-form form-horizontal col-sm-2 clean-padding pull-right'
                ],
            ]); 
            
            // 输入框
            echo $form->field($searchModel, 'course_name', [
                'options' => ['class' => ''],
                'template' => "<div class=\"col-sm-12 clean-padding\">{input}</div>",  
            ])->textInput([
                'placeholder' => '课程名',
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
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => [
                    'style' => 'width:30px;'
                ]
            ],
            
            [
                'attribute' => 'course.name',
                'headerOptions' => [
                    'style' => 'width:280px;'
                ]
            ],
            [
                'label' => Yii::t('app', 'Cover Img'),
                'format' => 'raw',
                'value' => function($model){
                    if(!empty($model->course)){
                        return Html::img($model->course->cover_url, ['width' => 104, 'height' => 69]);
                    }else{
                        return null;
                    }
                },
                'headerOptions' => [
                    'style' => 'width:190px;'
                ]
            ],
            [
                'attribute' => 'course.teacher_name',
                'label' => I18NUitl::t('app', '{Teacher}{Name}'),       
                'headerOptions' => [
                    'style' => 'width:190px;'
                ]
            ], 
            [
                'label' => I18NUitl::t('app', '{Teacher}{Avatar}'),
                'format' => 'raw',
                'value' => function($model){
                    if(!empty($model->course)){
                        return Html::img($model->course->teacher_avatar_url, ['class' => 'img-circle', 'width' => 55, 'height' => 55]);
                    }else{
                        return null;
                    }
                },
                'headerOptions' => [
                    'style' => 'width:140px;'
                ]
            ],   
            [
                'label' => I18NUitl::t('app', '{Suggest}{Learning}{Time}'),
                'value' => function($model){
                    if(!empty($model->course)){
                        return $model->course->suggest_time / 60 . '分钟';
                    }else{
                        return null;
                    }
                },
                'headerOptions' => [
                    'style' => 'width:190px;'
                ]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model){
                        return Html::a(Yii::t('app', 'View'), ['course/view', 'id' => $model->course_id], [
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

<?= $this->render('/layouts/modal') ?>

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
            window.location.href = url + '&ids=' + val;
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