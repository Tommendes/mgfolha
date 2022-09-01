<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinFaixasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-faixas-search">

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

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'faixa') ?>

    <?php // echo $form->field($model, 'v_final1') ?>

    <?php // echo $form->field($model, 'v_faixa1') ?>

    <?php // echo $form->field($model, 'v_deduzir1') ?>

    <?php // echo $form->field($model, 'v_final2') ?>

    <?php // echo $form->field($model, 'v_faixa2') ?>

    <?php // echo $form->field($model, 'v_deduzir2') ?>

    <?php // echo $form->field($model, 'v_final3') ?>

    <?php // echo $form->field($model, 'v_faixa3') ?>

    <?php // echo $form->field($model, 'v_deduzir3') ?>

    <?php // echo $form->field($model, 'v_final4') ?>

    <?php // echo $form->field($model, 'v_faixa4') ?>

    <?php // echo $form->field($model, 'v_deduzir4') ?>

    <?php // echo $form->field($model, 'v_final5') ?>

    <?php // echo $form->field($model, 'v_faixa5') ?>

    <?php // echo $form->field($model, 'v_deduzir5') ?>

    <?php // echo $form->field($model, 'deduzir_dependente') ?>

    <?php // echo $form->field($model, 'salario_vigente') ?>

    <?php // echo $form->field($model, 'inss_teto') ?>

    <?php // echo $form->field($model, 'inss_patronal') ?>

    <?php // echo $form->field($model, 'inss_rat') ?>

    <?php // echo $form->field($model, 'inss_fap')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
