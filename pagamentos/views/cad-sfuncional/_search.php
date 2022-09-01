<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSfuncionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cad-sfuncional-search">

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

    <?php // echo $form->field($model, 'id_cad_servidores') ?>

    <?php // echo $form->field($model, 'id_local_trabalho') ?>

    <?php // echo $form->field($model, 'id_cad_principal') ?>

    <?php // echo $form->field($model, 'id_escolaridade') ?>

    <?php // echo $form->field($model, 'escolaridaderais') ?>

    <?php // echo $form->field($model, 'rais') ?>

    <?php // echo $form->field($model, 'dirf') ?>

    <?php // echo $form->field($model, 'sefip') ?>

    <?php // echo $form->field($model, 'sicap') ?>

    <?php // echo $form->field($model, 'insalubridade') ?>

    <?php // echo $form->field($model, 'decimo') ?>

    <?php // echo $form->field($model, 'id_vinculo') ?>

    <?php // echo $form->field($model, 'id_cat_sefip') ?>

    <?php // echo $form->field($model, 'ocorrencia') ?>

    <?php // echo $form->field($model, 'carga_horaria') ?>

    <?php // echo $form->field($model, 'molestia') ?>

    <?php // echo $form->field($model, 'd_laudomolestia') ?>

    <?php // echo $form->field($model, 'manad_tiponomeacao') ?>

    <?php // echo $form->field($model, 'manad_numeronomeacao') ?>

    <?php // echo $form->field($model, 'd_tempo') ?>

    <?php // echo $form->field($model, 'd_tempofim') ?>

    <?php // echo $form->field($model, 'd_beneficio') ?>

    <?php // echo $form->field($model, 'n_valorbaseinss')  ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
