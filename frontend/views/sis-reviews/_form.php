<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SisReviews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sis-reviews-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'versao')->textInput() ?>

    <?= $form->field($model, 'lancamento')->textInput() ?>

    <?= $form->field($model, 'revisao')->textInput() ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'evento')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?=
        Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success'])
        . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                    'id' => 'closeAutoModalFooter',
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal',
                    'aria-label' => 'Close',
                    'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                ]) : '')
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
