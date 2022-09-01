<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinParametrosController;
use pagamentos\models\FinParametros;
use kartik\icons\FontAwesomeAsset;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinParametros */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);


$this->title = FinParametrosController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinParametrosController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-parametros-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinParametros::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'ano',
                'format' => 'raw',
                'value' => $model->ano,
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => yii\helpers\ArrayHelper::map(
                            pagamentos\models\FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->groupBy('ano')->orderBy('ano')->all(), 'ano', 'ano'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'mes',
                'format' => 'raw',
                'value' => common\models\Listas::getMes($model->mes),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => common\models\Listas::getMesesAbrev(true),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'parcela',
                'format' => 'raw',
                'value' => $model->parcela,
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => yii\helpers\ArrayHelper::map(
                            pagamentos\models\FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->groupBy('parcela')->orderBy('parcela')->all(), 'parcela', 'parcela'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'ano_informacao',
                'format' => 'raw',
                'value' => $model->ano_informacao,
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => yii\helpers\ArrayHelper::map(
                            pagamentos\models\FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->groupBy('ano_informacao')->orderBy('ano_informacao')->all(), 'ano_informacao', 'ano_informacao'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'mes_informacao',
                'format' => 'raw',
                'value' => $model->mes_informacao,
                'value' => common\models\Listas::getMes($model->mes_informacao),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => common\models\Listas::getMesesAbrev(true),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'parcela_informacao',
                'format' => 'raw',
                'value' => $model->parcela_informacao,
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => yii\helpers\ArrayHelper::map(
                            pagamentos\models\FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->groupBy('parcela_informacao')->orderBy('parcela_informacao')->all(), 'parcela_informacao', 'parcela_informacao'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
//        'columns' => [
//            [ 
        'attribute' => 'descricao',
        'format' => 'raw',
        'value' => $model->descricao,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//            [
//                'attribute' => 'tgf',
//                'format' => 'raw',
//                'value' => $model->tgf,
//                'options' => [
//                    'readonly' => !$buttons,
//                    'class' => 'form-control',
//                ],
//                'displayOnly' => true,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'situacao',
                'format' => 'raw',
                'value' => common\models\Listas::getSitParametro($model->situacao),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => common\models\Listas::getSitsParametro(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'd_situacao',
                'format' => 'raw',
                'value' => $model->d_situacao,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'mensagem',
        'format' => 'raw',
        'value' => $model->mensagem,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
    ];

    $attributes[] = [
        'attribute' => 'mensagem_aniversario',
        'format' => 'raw',
        'value' => $model->mensagem_aniversario,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
    ];

    $attributes[] = [
        'attribute' => 'manad_tipofolha',
        'format' => 'raw',
        'value' => common\models\Listas::getManadTipoFolha($model->manad_tipofolha),
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => common\models\Listas::getManadTipoFolhas(),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinParametros::STATUS_CANCELADO === $model->status || !$sf ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons && $sf ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinParametrosController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
