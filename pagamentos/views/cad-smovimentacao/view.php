<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadSmovimentacaoController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadSmovimentacao;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;
use yii\widgets\MaskedInput;
use kartik\widgets\DepDrop;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSmovimentacao */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadSmovimentacaoController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug]];
$this->params['breadcrumbs'][] = ['label' => CadSmovimentacaoController::CLASS_VERB_NAME_PL . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
$id_ca = Yii::$app->security->generateRandomString(8);
$id_md = Yii::$app->security->generateRandomString(8);
$id_cr = Yii::$app->security->generateRandomString(8);
$id_dr = Yii::$app->security->generateRandomString(8);
$id_da = Yii::$app->security->generateRandomString(8);
\yii\web\YiiAsset::register($this);
?>
<div class="cad-smovimentacao-view">
    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadSmovimentacao::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $url_listas = Yii::$app->urlManager->createUrl('cad-smovimentacao/z/');

    $attributes[] = [
        'attribute' => 'codigo_afastamento',
        'format' => 'raw',
        'value' => common\models\Listas::getCodAfastamento($model->codigo_afastamento),
        'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => common\models\Listas::getCodsAfastamento(),
            'pluginOptions' => [
                'id' => $id_ca . '_po_' . Yii::$app->security->generateRandomString(8),
                'multiple' => false,
            ],
        ],
        'options' => [
            'id' => $id_ca = Yii::$app->security->generateRandomString(8),
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...',
            'onchange' =>
            "   
                if($('#$id_ca').val() == 'Q1' && $('#$id_da').val().length >= 10){
                    $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
                    $('#$id_dr').prop('readonly', true);
                } else {
                    $('#$id_dr').val(null).change();
                    $('#$id_dr').removeAttr('readonly'); 
                }
                if($('#$id_ca').prop('selectedIndex')>3 && $('#$id_ca').prop('selectedIndex')<=11){
                    $('#$id_md').removeAttr('disabled'); 
                } else {
                    $('#$id_md').val(null).change();
                    $('#$id_md').prop('disabled', true);
                }     
                if($('#$id_ca').prop('selectedIndex')>3){
                    $('#$id_dr').prop('readonly', true);
                } else {
                    $('#$id_dr').removeAttr('disabled');
                } 
            ",
        ],
        'displayOnly' => !$buttons,
    ];

    if (!(isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0' && empty($model->motivo_desligamentorais))) {
        $attributes[] = [
            'attribute' => 'motivo_desligamentorais',
            'format' => 'raw',
            'value' => common\models\Listas::getCodDesligamento($model->motivo_desligamentorais),
            'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => common\models\Listas::getCodsDesligamento(),
                'pluginOptions' => [
                    'id' => $id_md . '_po_' . Yii::$app->security->generateRandomString(8),
                    'placeholder' => 'Selecione...',
                    'multiple' => false,
                    'allowClear' => true,
                ],
            ],
            'options' => [
                'id' => $id_md,
                'readonly' => !$buttons,
                'placeholder' => 'Selecione ...'
            ],
            'displayOnly' => !$buttons,
        ];
    }
    $diferenca = '';
    if (strlen($model->d_afastamento) == 10 && strlen($model->d_retorno) == 10) {
        $daf = new DateTime(date("Y-m-d", strtotime(AppController::date_converter($model->d_afastamento))));
        $dret = new DateTime(date("Y-m-d", strtotime(AppController::date_converter($model->d_retorno))));
        if ($daf->diff($dret)->days <= 365) {
            $diferenca = ' <span class="not-set">(' . $daf->diff($dret)->days . ' dias)</span>';
        } else {
            $diferenca = ' <span class="not-set">(' . AppController::calc_idade(AppController::date_converter($model->d_afastamento), AppController::date_converter($model->d_retorno)) . ')</span>';
        }
    }
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'd_afastamento',
                'format' => 'raw',
                'value' => $model->d_afastamento,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'id' => $id_da,
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('d_afastamento') . ' dd/mm/aaaa',
                    'onblur' => "   
                        if($('#$id_ca').val() == 'Q1'){
                            $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
                            $('#$id_dr').prop('readonly', true);
                        } else {
                            $('#$id_dr').val(null).change();
                            $('#$id_dr').removeAttr('readonly'); 
                        }
                    ",
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'd_retorno',
                'format' => 'raw',
                'value' => $model->d_retorno . $diferenca,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'id' => $id_dr,
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('d_retorno') . ' dd/mm/aaaa',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'group' => true,
        'label' => $model->getAttributeLabel('evento') . ' ' . (
        Yii::$app->user->identity->gestor ?
        Html::button($evento, [
            'value' => Url::to(['/sis-events/' . $model->evento]),
            'title' => Yii::t('yii', 'Ver evento'),
            'class' => 'showModalButton button-as-link'
        ]) : $evento) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
        . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
        'groupOptions' => ['class' => 'table-info text-center']
    ];
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !Yii::$app->user->identity->base_servico == 'pagamentos' || isset(Yii::$app->request->queryParams['mv']) || CadSmovimentacao::STATUS_CANCELADO === $model->status || !$sf || ($model->getCadServidor()->getFalecimento() !== null && !Yii::$app->user->identity->gestor) ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0' && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado) : '',
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadSmovimentacaoController::CLASS_VERB_NAME . ($model->getCadServidor()->getFalecimento() !== null && !Yii::$app->user->identity->gestor ? ". Este servidor faleceu em " . $model->getCadServidor()->getFalecimento()->d_afastamento . ". Apenas o gestor pode alterar esta movimentação!" : ''),
            'type' => $buttons && $sf && ($model->getCadServidor()->getFalecimento() == null) ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
<?php
$js = <<< JS
    if($('#$id_ca').prop('selectedIndex')>0){
        $('#$id_dr').removeAttr('readonly'); 
    }
    if($('#$id_ca').val() == 'Q1' && $('#$id_da').val().length >= 10){
        $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
        $('#$id_dr').prop('readonly', true);
    } else {
        $('#$id_dr').val(null).change();
        $('#$id_dr').removeAttr('readonly'); 
    }
    if($('#$id_ca').prop('selectedIndex')>3 && $('#$id_ca').prop('selectedIndex')<=11){
        $('#$id_md').removeAttr('disabled'); 
    } else {
        $('#$id_md').val(null).change();
        $('#$id_md').prop('disabled', true);
    } 
    if($('#$id_ca').prop('selectedIndex')>3){
        $('#$id_dr').prop('readonly', true);
    } else {
        $('#$id_dr').removeAttr('disabled');
    } 
JS;
$this->registerJs($js);
