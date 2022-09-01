<?php

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

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
    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <div class="row">

        <div class="col-md-3">

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'github')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'tel_contato')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'administrador')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'gestor')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'usuarios')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'cadastros')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'folha')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'financeiro')->textInput() ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'parametros')->textInput() ?>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

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

        </div>

    </div>

    <?php
    ActiveForm::end();
    Pjax::end()
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
                $.pjax.reload({container: '#$idGridPJax'});
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
        location.reload();
        return false;
    }); 
JS;
    $this->registerJs($js);
}
?>