<?php

use backend\modules\micro_lesson\models\CourseSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel CourseSearch */
/* @var $dataProvider ActiveDataProvider */


?>
<div class="select-course-list">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!--模态框头部-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode('选择要添加的课程') ?></h4>
            </div>
            <!--模态框body-->
            <div id="myModalBody" class="modal-body">
                <div class="col-sm-12 clean-padding">
                    <?php
                        // 表单
                        $form = ActiveForm::begin([
                            'method' => 'get',
                            'options' => [
                                'id' => 'wsk_course_form',
                                'class' => 'wsk-form form-horizontal col-sm-12 clean-padding',
                                'onkeydown' => 'if(event.keyCode==13) return false;'
                            ],
                        ]);

                        // 输入框
                        echo $form->field($searchModel, 'keyword', [
                            'options' => ['class' => ''],
                            'template' => "<div class=\"col-sm-12 clean-padding\">{input}</div>",
                        ])->textInput([
                            'placeholder' => '课程或老师名',
                            'onchange' => 'submitCourseForm()'
                        ]);

                        ActiveForm::end();
                    ?>
                </div>

                <!--表格-->
                <?= GridView::widget([
                    'id' => 'course_list',
                    'dataProvider' => $dataProvider,
                    'tableOptions' => [
                        'class' => 'table wsk-table table-striped table-bordered',
//                        'style' => 'margin-bottom:5px;'
                    ],
                    'layout' => "{items}\n{summary}\n{pager}",
                    'pager' => [
                        'options' => ['class' => 'pagination clean-margin'],
                        'linkOptions' => ['onclick' => 'clickPageReload(this);return false;'],
                        'maxButtonCount' => 5,
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'selection_course',
                            'headerOptions' => [
                                'style' => 'width:30px;'
                            ]
                        ],

                        [
                            'label' => I18NUitl::t('app', '{Course}{Name}'),
                            'value' => function($model){
                                return $model->name;
                            },
                            'headerOptions' => [
                                'style' => 'width:260px;'
                            ]
                        ],
                        [
                            'label' => Yii::t('app', 'Cover Img'),
                            'format' => 'raw',
                            'value' => function($model){
                                return Html::img($model->cover_url, ['width' => 104, 'height' => 69]);
                            },
                            'headerOptions' => [
                                'style' => 'width:170px;'
                            ]
                        ],
                        [
                            'label' => I18NUitl::t('app', '{Teacher}{Name}'),       
                            'value' => function($model){
                                return $model->teacher_name;
                            },
                            'headerOptions' => [
                                'style' => 'width:170px;'
                            ]
                        ], 
                        [
                            'label' => I18NUitl::t('app', '{Teacher}{Avatar}'),
                            'format' => 'raw',
                            'value' => function($model){
                                return Html::img($model->teacher_avatar_url, ['class' => 'img-circle', 'width' => 55, 'height' => 55]);
                            },
                            'headerOptions' => [
                                'style' => 'width:170px;'
                            ]
                        ],   
                        [
                            'label' => I18NUitl::t('app', '{Suggest}{Learning}{Time}'),
                            'value' => function($model){
                                return $model->suggest_time / 60 . '分钟';
                            },
                            'headerOptions' => [
                                'style' => 'width:170px;'
                            ]
                        ],       
                    ],
                ]); ?>
            </div>
            <!--模态框尾部-->
            <div id="myModalFooter" class="modal-footer">
                
                <?php
                    echo Html::button(Yii::t('app', 'Close'), ['id' => 'btn_close', 'class' => 'btn btn-default btn-flat', 'data-dismiss' => 'modal']);
                    echo ' ' . Html::a(Yii::t('app', 'Confirm'), ['add', 'topic_id' => $topicId], [
                        'class' => 'btn btn-primary btn-flat',
                        'onclick' => 'submitSave(this);return false;'
                    ]);
                ?>
                
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    
    /**
     * 提交表单
     * @returns {undefined}
     */
    function submitCourseForm() {
        $('.myModal').modal("show").load('<?= Url::to(['course-list']) ?>', $('#wsk_course_form').serialize());
    }

    /**
     * 单击保存
     * @param {Object} elem
     * @returns {undefined}
     */
    function submitSave(elem) {
        var val = getCheckBoxsCourse(),
            url = $(elem).attr('href');
        // 当前选中一个才有效 
        if (val.length > 0) {
            $.post(url, {'course_id': val});
        } else {
            alert("<?= Yii::t('app', 'Please select at least one.') ?>");
        }
    }

    /**
     * 获取 getCheckBoxsValue
     * @returns {Array|getcheckBoxsValue.val}
     */
    function getCheckBoxsCourse() {
        var val = [],
            checkBoxs = $('input[name="selection_course[]"]');
        // 循环组装素材id
        for (i in checkBoxs) {
            if (checkBoxs[i].checked) {
                val.push(checkBoxs[i].value);
            }
        }

        return val;
    }
    
    /**
     * 单击分页重新load页面
     * @param {Object} elem
     * @returns {undefined}     
     */
    function clickPageReload(elem){
        $('.myModal').modal("show").load($(elem).attr('href'));
    }
   
</script>