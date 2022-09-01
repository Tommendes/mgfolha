<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SisParamsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="params-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'dominio') ?>

    <?= $form->field($model, 'grupo') ?>

    <?php // echo $form->field($model, 'parametro') ?>

    <?php // echo $form->field($model, 'label') ?>

    <?php // echo $form->field($model, 'data_registro') ?>

    <?php // echo $form->field($model, 'evento') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
