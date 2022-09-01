<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinRubricasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-rubricas-search">

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

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'id_cad_servidores') ?>

    <?php // echo $form->field($model, 'id_fin_eventos') ?>

    <?php // echo $form->field($model, 'ano') ?>

    <?php // echo $form->field($model, 'mes') ?>

    <?php // echo $form->field($model, 'parcela') ?>

    <?php // echo $form->field($model, 'referencia') ?>

    <?php // echo $form->field($model, 'valor_base') ?>

    <?php // echo $form->field($model, 'valor') ?>

    <?php // echo $form->field($model, 'valor_patronal') ?>

    <?php // echo $form->field($model, 'valor_maternidade') ?>

    <?php // echo $form->field($model, 'prazo') ?>

    <?php // echo $form->field($model, 'prazot') ?>

    <?php // echo $form->field($model, 'valor_desconto') ?>

    <?php // echo $form->field($model, 'valor_percentual')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
