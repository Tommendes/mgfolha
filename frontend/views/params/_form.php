<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <?php
    $status = [Params::STATUS_INATIVO => 'Inativo',
        SisParams::STATUS_ATIVO => 'Ativo',
        SisParams::STATUS_CANCELADO => 'Cancelado'];
    ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'status')->dropDownList($status) ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?=
            $form->field($model, 'grupo')->dropDownList(
                    ArrayHelper::map(Params::find()
                                    ->select([
                                        'grupo' => 'grupo'
                                    ])
                                    ->groupBy('grupo')
                                    ->all(), 'grupo', 'grupo')
            )
            ?>
        </div>
        <div class="col-xs-12 col-md-12">
            <?=
            $form->field($model, 'parametro')->textarea(['rows' => 5,
                'id' => Yii::$app->security->generateRandomString(8),])
            ?>
        </div>
        <div class="col-xs-12 col-md-12">
            <?=
            $form->field($model, 'label')->textarea(['rows' => 5,
                'id' => Yii::$app->security->generateRandomString(8),])
            ?>
        </div>
    </div>

    <?php
    $evento = SisEventsController::findEvt($model->evento);
    echo Html::tag('p', 'Último evento:&nbsp;' . (Yii::$app->user->identity->gestor ?
                    Html::button($evento, [
                        'id' => 'btn_' . strtolower(SisEventsController::CLASS_VERB_NAME),
                        'value' => Url::to(['eventos/' . $model->evento]),
                        'title' => Yii::t('yii', 'Show {modelClass}', [
                            'modelClass' => Html::encode(SisEventsController::CLASS_VERB_NAME)
                        ]),
                        'class' => 'showModalButton button-as-link'
                    ]) : $evento) . date('d-m-Y H:i:s', $model->updated_at));
    ?>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Html::icon('floppy-disk') . ' ' . ($model->isNewRecord ? Yii::t('yii', 'Save Create') : Yii::t('yii', 'Save Update')), ['id' => $submit_cadas_id = Yii::$app->security->generateRandomString(8), 'class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary')])// . ' disabled'    ?>
                <?=
                (isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                            'id' => 'closeAutoModalFooter',
                            'class' => 'btn btn-default',
                            'data-dismiss' => 'modal',
                            'aria-label' => 'Close',
                            'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                        ]) : ''
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
//        location.reload();
        return false;
    }); 
JS;
    $this->registerJs($js);
}
?>
