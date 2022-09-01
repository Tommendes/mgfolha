<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSdependentesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cad-sdependentes-search">

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

    <?php // echo $form->field($model, 'matricula') ?>

    <?php // echo $form->field($model, 'nome') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'permanente') ?>

    <?php // echo $form->field($model, 'nascimento_d') ?>

    <?php // echo $form->field($model, 'inss_prz') ?>

    <?php // echo $form->field($model, 'irrf_prz') ?>

    <?php // echo $form->field($model, 'rpps_prz') ?>

    <?php // echo $form->field($model, 'rg') ?>

    <?php // echo $form->field($model, 'rg_emissor') ?>

    <?php // echo $form->field($model, 'rg_uf') ?>

    <?php // echo $form->field($model, 'rg_d') ?>

    <?php // echo $form->field($model, 'certidao') ?>

    <?php // echo $form->field($model, 'livro') ?>

    <?php // echo $form->field($model, 'folha') ?>

    <?php // echo $form->field($model, 'certidao_d') ?>

    <?php // echo $form->field($model, 'carteira_vacinacao') ?>

    <?php // echo $form->field($model, 'historico_escolar') ?>

    <?php // echo $form->field($model, 'plano_saude') ?>

    <?php // echo $form->field($model, 'cpf') ?>

    <?php // echo $form->field($model, 'relacao_dependecia') ?>

    <?php // echo $form->field($model, 'pensionista') ?>

    <?php // echo $form->field($model, 'irpf')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
