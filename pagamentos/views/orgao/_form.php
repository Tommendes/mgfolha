<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Orgao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orgao-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = substr($model->slug, 1, 8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id
                . '/' . ($model->isNewRecord ? '' :
                ($model->slug)) . ('?modal=' . $modal),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    if ($model->isNewRecord):
        $model->dominio = Yii::$app->user->identity->dominio;
        $model->slug = Yii::$app->security->generateRandomString();
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
    endif;
    ?>
    <?= $form->field($model, 'dominio')->textInput() ?>
    <?= $form->field($model, 'slug')->textInput() ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'evento')->textInput() ?>
    <?= $form->field($model, 'created_at')->textInput() ?>
    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'evento')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'orgao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cep')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logradouro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complemento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_fpas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_gps')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_cnae')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_ibge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_fgts')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mes_descsindical')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cpf_responsavel_dirf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nome_responsavel_dirf')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=
        Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success'])
        . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                    'id' => 'closeAutoModalFooter',
                    'class' => 'btn btn-secondary',
                    'data-dismiss' => 'modal',
                    'aria-label' => 'Close',
                    'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                ]) : '')
        ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>

</div>
<?php
$idGridPJax = Yii::$app->controller->id . '_grid-pjax';
if (!$model->isNewRecord) {
    $js = <<< JS
    $('body').on('beforeSubmit', 'form#$idForm', function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
             return false;
        }
        // submit form
        $.ajax({
           url: form.attr('action'),
           type: 'post',
           data: form.serialize(),
           success: function (response) {
                PNotify.removeAll();
                new PNotify({ 
                    title: 'Sucesso', 
                    text: response,
                    type: 'success',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
                $("#closeAutoModalHeader").click();
                $.pjax.reload({container: '#$containerPJax'}).done(function () {
                    $.pjax.reload({container: '#$idGridPJax'});
                });
           },
           error: function (response) {
                new PNotify({ 
                    title: 'Erro', 
                    text: response.responseText, 
                    type: 'warning',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
           }
        });
//        location.reload();
        return false;
    }); 
JS;
    $this->registerJs($js);
}
?>
