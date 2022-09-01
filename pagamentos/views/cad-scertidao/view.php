<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadScertidaoController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadScertidao;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;
use yii\widgets\MaskedInput;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadScertidao */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadScertidaoController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug]];
$this->params['breadcrumbs'][] = ['label' => CadScertidaoController::CLASS_VERB_NAME_PL . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cad-scertidao-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadScertidao::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'tipo',
                'format' => 'raw',
                'value' => \common\models\Listas::getCertidaoTipo($model->tipo),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => \common\models\Listas::getCertidaoTipos(),
                    'options' => ['prompt' => 'Selecione ...',],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'certidao',
                'format' => 'raw',
                'value' => $model->certidao,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'emissao',
                'format' => 'raw',
                'value' => $model->emissao,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'cartorio',
                'format' => 'raw',
                'value' => $model->cartorio,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'uf',
                'format' => 'raw',
                'value' => $model->uf,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'cidade',
                'format' => 'raw',
                'value' => $model->cidade,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'termo',
                'format' => 'raw',
                'value' => $model->termo,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'livro',
                'format' => 'raw',
                'value' => $model->livro,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'folha',
        'format' => 'raw',
        'value' => $model->folha,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;']
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
        'mode' => !Yii::$app->user->identity->base_servico == 'pagamentos' || !isset(Yii::$app->request->queryParams['mv']) || CadScertidao::STATUS_CANCELADO === $model->status || !$sf || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null ? DetailView::MODE_VIEW : (Yii::$app->request->queryParams['mv'] == DetailView::MODE_EDIT ? DetailView::MODE_EDIT : DetailView::MODE_VIEW),
        'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? ((isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado) : '',
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadScertidaoController::CLASS_VERB_NAME . ' de ' . AppController::tratar_nome($model->getCadServidor()->nome) . ' (' . $model->getCadServidor()->matricula . ')',
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
