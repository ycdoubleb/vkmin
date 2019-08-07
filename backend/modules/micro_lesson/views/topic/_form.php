<?php

use common\models\vk\Topic;
use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Topic */
/* @var $form ActiveForm */
?>

<div class="topic-form">

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

    <!--专题名称-->
    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t('app', 'Select Placeholder'),
    ])->label(I18NUitl::t('app', '{Topic}{Name}')) ?>

    <!--专题封面-->
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
    ])->label(I18NUitl::t('app', '{Topic}{Cover Img}')) ?>

    <!--专题简介-->
    <?= $form->field($model, 'introduction')->textarea([
        'rows' => 4,
        'placeholder' => '请输入专题简介，简单描述专题里面的内容',
    ])->label(I18NUitl::t('app', '{Topic}{Introduction}')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
