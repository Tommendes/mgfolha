<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisShortener */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sis-shortener-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'form-login',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'fieldConfig' => ['autoPlaceholder' => true]
    ]);
    ?>

    <?=
    $form->field($model, 'url', [
        'addon' => [
            'append' => [
                'content' => Html::submitButton(Yii::t('yii', 'Encurtar'), ['class' => 'btn btn-success']),
                'asButton' => true
            ],
            'prepend' => [
                'content' => Html::submitButton(Yii::t('yii', 'Encurtar'), ['class' => 'btn btn-success']),
                'asButton' => true
            ]
        ]
    ])->textInput(['maxlength' => true])->label(!$t == 's' ? false : 'URL longa')
    ?>

    <?php // echo $form->field($model, 'shortened')->textInput(['maxlength' => true])   ?>

    <!--    <div class="form-group">
    <?php // echo Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success'])  ?>
        </div>-->

    <?php ActiveForm::end(); ?>

</div>
