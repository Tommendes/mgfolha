<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-sfuncional-search">

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

    <?php // echo $form->field($model, 'ano') ?>

    <?php // echo $form->field($model, 'mes') ?>

    <?php // echo $form->field($model, 'parcela') ?>

    <?php // echo $form->field($model, 'situacao') ?>

    <?php // echo $form->field($model, 'situacaofuncional') ?>

    <?php // echo $form->field($model, 'id_cad_cargos') ?>

    <?php // echo $form->field($model, 'id_cad_centros') ?>

    <?php // echo $form->field($model, 'id_cad_departamentos') ?>

    <?php // echo $form->field($model, 'id_pccs') ?>

    <?php // echo $form->field($model, 'desconta_irrf') ?>

    <?php // echo $form->field($model, 'tp_previdencia') ?>

    <?php // echo $form->field($model, 'desconta_sindicato') ?>

    <?php // echo $form->field($model, 'lanca_anuenio') ?>

    <?php // echo $form->field($model, 'lanca_trienio') ?>

    <?php // echo $form->field($model, 'lanca_quinquenio') ?>

    <?php // echo $form->field($model, 'lanca_decenio') ?>

    <?php // echo $form->field($model, 'lanca_salario') ?>

    <?php // echo $form->field($model, 'lanca_funcao') ?>

    <?php // echo $form->field($model, 'n_faltas') ?>

    <?php // echo $form->field($model, 'decimo_aniv') ?>

    <?php // echo $form->field($model, 'n_horaaula') ?>

    <?php // echo $form->field($model, 'categoria_receita') ?>

    <?php // echo $form->field($model, 'previdencia') ?>

    <?php // echo $form->field($model, 'tipobeneficio') ?>

    <?php // echo $form->field($model, 'd_beneficio') ?>

    <?php // echo $form->field($model, 'retorno_ocorrencia') ?>

    <?php // echo $form->field($model, 'retorno_data') ?>

    <?php // echo $form->field($model, 'retorno_valor') ?>

    <?php // echo $form->field($model, 'retorno_documento')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
