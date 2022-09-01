<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeskUsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="desk-users-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'status_desk') ?>

    <?= $form->field($model, 'dll_cod') ?>

    <?= $form->field($model, 'dll_cnpj') ?>

    <?php // echo $form->field($model, 'dll_nome') ?>

    <?php // echo $form->field($model, 'cli_cnpj') ?>

    <?php // echo $form->field($model, 'cli_nome') ?>

    <?php // echo $form->field($model, 'cli_nome_comput') ?>

    <?php // echo $form->field($model, 'cli_nome_user') ?>

    <?php // echo $form->field($model, 'versao_desk') ?>

    <?php // echo $form->field($model, 'evento') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at')  ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
