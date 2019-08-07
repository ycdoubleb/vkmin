<?php

use common\models\vk\Course;
use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Course */
/* @var $form ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'wsk_form',
            'class' => 'wsk-form form-horizontal'
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-12 clean-padding\">{input}</div>\n<div class=\"col-sm-12 clean-padding\">{error}</div>", 
            'labelOptions' => [
                'class' => 'control-label'
            ]
        ]
    ]); ?>
    
    <!--课程名-->
    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t('app', 'Select Placeholder'),
    ])->label(I18NUitl::t('app', '{Course}{Name}')) ?>

    <!--课程封面-->
    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class, [
        'id' => Html::getInputId($model, 'cover_url'),
        'pluginOptions' =>[
            'fileSingleSizeLimit' => 1*1024*1024,
            'compress' => true,
            //设置允许选择的文件类型
            'accept' => [
                'mimeTypes' => 'image/jpeg',
            ],
        ],
    ])->label(I18NUitl::t('app', '{Course}{Cover Img}')) ?>

   <!--老师名称-->
    <?= $form->field($model, 'teacher_name')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t('app', 'Select Placeholder'),
    ])->label(I18NUitl::t('app', '{Teacher}{Name}')) ?>

   <!--老师头像-->
    <?= $form->field($model, 'teacher_avatar_url')->widget(ImagePicker::class, [
        'id' => Html::getInputId($model, 'teacher_avatar_url'),
        'pluginOptions' =>[
            'fileSingleSizeLimit' => 1*1024*1024,
            //设置允许选择的文件类型
            'accept' => [
                'mimeTypes' => 'image/jpeg',
            ],
        ],
    ])->label(I18NUitl::t('app', '{Teacher}{Avatar}')) ?>
   
    <!--建议学习时间-->
    <?= $form->field($model, 'suggest_time')->textInput([
        'type' => 'number',
        'pattern' => '[0-9]',
        'min' => 0,
        'placeholder' => '请输入秒为单位的学习时间 如 10 分钟 输入 600',
    ])->label(I18NUitl::t('app', '{Suggest}{Learning}{Time}')) ?>

    <!--课程路径-->
    <?= $form->field($model, 'url')->textInput([
        'maxlength' => true,
        'placeholder' => '请输入课程路径，课程路径请前往 http://mediacloud.studying8.com 进行购买',
    ])->label(I18NUitl::t('app', '{Course}{Path}')) ?>
  
    <!--课程简介-->
    <?= $form->field($model, 'introduction')->textarea([
        'rows' => 4,
        'placeholder' => '请输入课程简介，简单描述课程里面的内容',
    ])->label(I18NUitl::t('app', '{Course}{Introduction}')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
