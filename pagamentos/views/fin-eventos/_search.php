<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-eventos-search">

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

    <?php // echo $form->field($model, 'id_evento') ?>

    <?php // echo $form->field($model, 'evento_nome') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'consignado') ?>

    <?php // echo $form->field($model, 'consignavel') ?>

    <?php // echo $form->field($model, 'deduzconsig') ?>

    <?php // echo $form->field($model, 'automatico') ?>

    <?php // echo $form->field($model, 'vinculacao_dirf') ?>

    <?php // echo $form->field($model, 'i_prioridade') ?>

    <?php // echo $form->field($model, 'fixo') ?>

    <?php // echo $form->field($model, 'sefip') ?>

    <?php // echo $form->field($model, 'rais')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
