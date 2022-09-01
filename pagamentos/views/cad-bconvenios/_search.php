<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadBconveniosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cad-bconvenios-search">

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

    <?= $form->field($model, 'evento') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'id_cad_bancos') ?>

    <?php // echo $form->field($model, 'id_orgao_ua') ?>

    <?php // echo $form->field($model, 'convenio') ?>

    <?php // echo $form->field($model, 'cod_compromisso') ?>

    <?php // echo $form->field($model, 'agencia') ?>

    <?php // echo $form->field($model, 'a_digito') ?>

    <?php // echo $form->field($model, 'conta') ?>

    <?php // echo $form->field($model, 'c_digito') ?>

    <?php // echo $form->field($model, 'convenio_nr') ?>

    <?php // echo $form->field($model, 'tipo_servico')  ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
