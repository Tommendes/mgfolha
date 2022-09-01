<?php

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;
use frontend\models\Msgs;
use frontend\controllers\MsgsController;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\Msgs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="msgs-form form-bg">

    <?php
    $id_desk_user = MsgsController::getDeskUsers();
    $idForm = substr(Yii::$app->security->generateRandomString(), 1, 8);
    Pjax::begin(['id' => $idForm . '-form-create']);
    $form = ActiveForm::begin([
                'action' => Url::home(true) . 'msgs/' . ($model->isNewRecord ? 'create' : ('u' . '/' . $model->slug)),
                'id' => $idForm,
    ]);
    ?>
    <?php
    echo $form->field($model, 'status')->hiddenInput(['value' => $model->isNewRecord ? Msgs::STATUS_ATIVO : $model->status])->label(false); // Armazena o status inicial
    echo $form->field($model, 'slug')->hiddenInput(['value' => $model->isNewRecord ? strtolower(sha1($model->tableName() . time())) : $model->slug])->label(false); // Armazena o slug inicial
    echo $form->field($model, 'dominio')->hiddenInput(['value' => Yii::$app->user->identity->dominio])->label(false);
    echo $form->field($model, 'evento')->hiddenInput(['value' => $model->isNewRecord ? 0 : $model->evento])->label(false);
    echo $form->field($model, 'created_at')->hiddenInput(['value' => $model->isNewRecord ? time() : $model->created_at])->label(false);
    echo $form->field($model, 'updated_at')->hiddenInput(['value' => $model->isNewRecord ? time() : $model->updated_at])->label(false);
    $body = Yii::$app->security->generateRandomString();
    ?> 

    <?= $form->field($model, 'id_desk_user')->dropDownList(ArrayHelper::map($id_desk_user, 'id', 'cli_nome')) ?>

    <?= $form->field($model, 'caption')->textInput(['maxlength' => true]) ?>

    <?=
            $form->field($model, 'body')
            ->widget(TinyMce::className(), [
                'language' => 'pt_BR',
                'clientOptions' => [
                    'entity_encoding' => 'raw',
                    'plugins' => [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                    ],
                    'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    'toolbar2' => "print preview media | forecolor backcolor emoticons | codesample helpimage"
                ],
                'options' => [
                    'rows' => 10,
                    'id' => $body,
                ],
            ])
    ?>

    <?=
    $form->field($model, 'link', [
        'addon' => [
            'append' => [
                'content' => Html::button('<span class="glyphicons glyphicons-compressed"></span> Encurtar URL', ['id' => 'btnShort', 'class' => 'btn btn-default']),
                'asButton' => false
            ]
        ]
    ])->textInput()
    ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>

</div>
<?php
$homeUrl = Url::home(true);
$app = $homeUrl . 'ss/short';
$js = <<< JS
    $("#btnShort").on("click", function() {      
        krajeeDialog.prompt({label:'Insira a URL longa', placeholder:'Url longa...'}, function (result) {
            if (result) {
                res = "$app?url=" + result;
//                alert(res);
                $('#msgs-link').val(res);
        
                ajax = $.get(res);

                ajax.done(function(data){
//                    alert("done: " + data.retorno);
                $('#msgs-link').val(data.retorno);
                }).fail(function(data){
                    alert("falha: " + data.retorno);
                });
        
//                $.get("$app?url=" + result, function(data){
//                    krajeeDialog.alert('URL curta: <a href="' + data + '" target="_blank">' + data + '</a>');
//                    $('#msgs-link').val(data);
//                });
            } else {
                krajeeDialog.alert('Para obter URL curta, por favor informe uma URL longa!');
            }
        });        
    });
JS;
$this->registerJs($js);

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
                $.pjax.reload({container: '#msgs_grid-pjax'});
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
