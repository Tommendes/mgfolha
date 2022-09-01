<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use frontend\controllers\FolhaController;
use frontend\controllers\CadSfuncionalController;
use frontend\controllers\FinSfuncionalController;
use frontend\controllers\FinRubricasController;
use frontend\models\CadSfuncional;
use frontend\models\FinSfuncional;
use frontend\models\FinRubricas;
use frontend\controllers\SiteController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Folha */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = frontend\controllers\FinParametrosController::getS();
}
?>

<div class="cad-bconvenios-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ('?modal=' . $modal),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    $model->dominio = Yii::$app->user->identity->dominio;
    $model->status = $model::STATUS_ATIVO;
    $model->ano = Yii::$app->user->identity->per_ano;
    $model->mes = Yii::$app->user->identity->per_mes;
    $model->parcela = Yii::$app->user->identity->per_parcela;
    ?>
    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ano')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'mes')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'parcela')->hiddenInput()->label(false) ?>

    <div class="row"> 
        <?php if (1 == 2) { ?>
            <div class="col-md-12">
                <?= Html::label('Inclusão dos registros ' . strtolower(CadSfuncionalController::CLASS_VERB_NAME_PLL . ' (' . $getCountCadSFuncional = FolhaController::getCountCadSFuncional() . ')'), null, []) ?>
                <div class="progress" style="height: 20px;">
                    <div id="<?= $pb_cf = 'pb_' . CadSfuncional::tableName() ?>" class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="<?= $getCountCadSFuncional ?>" style="width: 0%;">0%</div>
                </div> 
            </div>
            <div class="col-md-12">
                <?= Html::label('Inclusão dos registros ' . strtolower(FinSfuncionalController::CLASS_VERB_NAME_PLL . ' (' . $getCountFinSFuncional = FolhaController::getCountFinSFuncional() . ')'), null, []) ?>
                <div class="progress" style="height: 20px;">
                    <div id="<?= $pb_ff = 'pb_' . FinSfuncional::tableName() ?>" class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="<?= $getCountFinSFuncional ?>" style="width: 0%;">0%</div>
                </div> 
            </div>
            <div class="col-md-12">
                <?= Html::label('Lançamento das ' . strtolower(FinRubricasController::CLASS_VERB_NAME_PL . ' (' . $getCountFinRubricas = FolhaController::getCountFinRubricas() . ')'), null, []) ?>
                <div class="progress" style="height: 20px;">
                    <div id="<?= $pb_fr = 'pb_' . FinRubricas::tableName() ?>" class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="<?= $getCountFinRubricas ?>" style="width: 0%;">0%</div>
                </div> 
            </div>
        <?php } ?>
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-12">
            <?= Html::label('', null, []) ?>
            <div class="form-group" style="padding-top: 9px;">
                <div class="btn-group d-flex" role="group">
                    <?=
                    Html::button(Yii::t('yii', 'Fechar as folhas anteriores e gerar a folha ' . SiteController::getFolhaAtual()), [
                        'id' => $btnIncrementar = Yii::$app->security->generateRandomString(8),
                        'class' => 'btn btn-secondary w-' . (isset($modal) && $modal ? '50' : '100'),
                        'onclick' => "gfolha();"
                    ]) . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                                'id' => 'closeAutoModalFooter',
                                'class' => 'btn btn-default w-50',
                                'data-dismiss' => 'modal',
                                'aria-label' => 'Close',
                                'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                            ]) : '')
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>

</div>
<?php
$this->registerJsFile(Url::home() . Yii::getAlias('@assets_frontend') . '/js/sc.js');
$periodo = SiteController::getFolhaAtual();
$idGridPJax = Yii::$app->controller->id . '_grid-pjax';
$url = Url::to(['s-c?o=']);
$urlTgf = Url::to(['/params/z?r=tgf&v=']);
$tgf = \common\controllers\SisParamsController::getParamByGroup('tgf');
$js = <<< JS
    $('#$btnIncrementar').click(function () {        
        krajeeDialog.prompt({label:'Confirma a geração da folha para o período $periodo?<br>' +
            '<strong>Todas as folhas anteriores serão fechadas</strong>', placeholder:'Digite \"CONFIRMO\"'}, function (result) {
            if (result == 'CONFIRMO') {
                if ($('#loader').is(':visible')) {
                    $('#loader').hide();
                } else {
                    $('#loader').show();
                }
                PNotify.removeAll();
                new PNotify({
                    title: 'Aviso',
                    text: 'Processo de geração iniciado',
                    type: 'info',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
                dyn_notice('$tgf');
                sc('$url', '$urlTgf');
                timer = setInterval(function () {
                    tgf++;
                }, 1000);
            } else {
                krajeeDialog.alert('Para concluir a operação digite \"CONFIRMO\" com letras maiúsculas!')
            }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>
