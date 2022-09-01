<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;
use common\controllers\AppController;
use frontend\controllers\SisEventsController;
use common\models\SisParams;

/* @var $this yii\web\View */
/* @var $model common\models\SisParams */
/* @var $form yii\widgets\ActiveForm */
$class_id = AppController::str_replace_first('_', '-', $model->tableName());
$class_id = str_replace('_', '', $class_id);
?>

<div class="<?= $class_id ?>-form">

    <?php
    $time = time();
    $idForm = $class_id . substr($model->slug, 1, 8);
    $containerPJax = $idForm . '-form' . $time;
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::home(true) . $class_id . '/' . ($model->isNewRecord ? 'create' : ($model->slug)),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    ?>
    <?php
    if ($model->isNewRecord) {
        $model->slug = strtolower(Yii::$app->security->generateRandomString());
        $model->created_at = time();
        $model->dominio = Yii::$app->user->identity->dominio;
    }
    echo $form->field($model, 'slug')->hiddenInput()->label(false);
    echo $form->field($model, 'dominio')->hiddenInput(['maxlength' => true])->label(false);
    echo $form->field($model, 'created_at')->hiddenInput()->label(false);
    echo $form->field($model, 'updated_at')->hiddenInput(['value' => time()])->label(false);
    echo $form->field($model, 'status')->hiddenInput()->label(false);
    echo $form->field($model, 'grupo')->hiddenInput()->label(false);
    echo $form->field($model, 'label')->hiddenInput()->label(false);
    ?>

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?=
            $form->field($model, $fld)->widget(TinyMce::className(), [
                'language' => 'pt_BR',
                'clientOptions' => [
//                    'plugins' => [
//                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
//                        'searchreplace wordcount visualblocks visualchars code fullscreen',
//                        'insertdatetime media nonbreaking save table contextmenu directionality',
//                        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
//                    ],
//                    'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | codesample helpimage",
                    'toolbar2' => ""
                ],
                'options' => [
                    'rows' => 5,
                    'id' => Yii::$app->security->generateRandomString(),
                ],
            ])->label(false)
//                            ->textarea(['rows' => 6])->textarea(['rows' => 6]) 
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="form-group">
                <?=
                Html::submitButton(Html::icon('floppy-disk') . ' ' .
                        ($model->isNewRecord ? Yii::t('yii', 'Save Create') : Yii::t('yii', 'Save Update')), ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary')]) .
//                Html::button('Voltar', [
//                    'value' => 'value',
//                    'id' => 'btnUrlGb',
//                    'title' => Yii::t('yii', 'Voltar'),
//                    'class' => 'showModalButton btn btn-default',
//                    'onclick' => '$("#btnUrlGb").attr("value", getCookie("urlgb"));'
//                ]) .
                Html::button('Fechar janela&nbsp;×', [
                    'id' => 'closeAutoModalFooter',
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal',
                    'aria-label' => 'Close',
                    'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                    'onclick' => 'javascript:location.reload();'
                ])
                ?>
            </div>
            <?php
            echo $form->errorSummary($model);
            ?>
        </div>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
</div>

<?php
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
         return false;
    });                       
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }
    
JS;
    $this->registerJs($js);
}
?>