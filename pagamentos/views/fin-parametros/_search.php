<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinParametrosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-parametros-search">

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

    <?php // echo $form->field($model, 'ano') ?>

    <?php // echo $form->field($model, 'mes') ?>

    <?php // echo $form->field($model, 'parcela') ?>

    <?php // echo $form->field($model, 'ano_informacao') ?>

    <?php // echo $form->field($model, 'mes_informacao') ?>

    <?php // echo $form->field($model, 'parcela_informacao') ?>

    <?php // echo $form->field($model, 'descricao') ?>

    <?php // echo $form->field($model, 'situacao') ?>

    <?php // echo $form->field($model, 'd_situacao') ?>

    <?php // echo $form->field($model, 'mensagem') ?>

    <?php // echo $form->field($model, 'mensagem_aniversario') ?>

    <?php // echo $form->field($model, 'manad_tipofolha')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
