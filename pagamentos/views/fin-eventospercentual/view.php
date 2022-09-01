<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinEventospercentualController;
use pagamentos\models\FinEventospercentual;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventospercentual */

$this->title = FinEventospercentualController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinEventospercentualController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-eventospercentual-view">

    <!--    <h1>Html::encode($this->title) ?></h1>
    
        <p>
            Html::a(, ['u', ], ['class' => 'btn btn-primary']) ?>
            Html::a(, ['dlt', ], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>-->

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinEventospercentual::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_fin_eventos',
                'label' => \pagamentos\controllers\FinEventosController::CLASS_VERB_NAME,
                'format' => 'raw',
                'value' => Html::a($model->getFinEvento()->evento_nome . ' (' . $model->getFinEvento()->id_evento . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $model->getFinEvento()->id . ')') : '') . ')'
                        , '#', [
                    'value' => Url::to(['/fin-eventos/' . $model->getFinEvento()->id, 'modal' => 1, 'mv' => '0']),
                    'title' => Yii::t('yii', 'Ver evento'),
                    'class' => "showModalButton",
                ]),
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'id_eventos_percentual',
                'format' => 'raw',
                'value' => $model->id_eventos_percentual,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 2,
                        'digitsOptional' => true,
                        'radixPoint' => '.',
                        'groupSeparator' => '',
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'data',
                'value' => $model->data,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'type' => DetailView::INPUT_WIDGET,
                'format' => 'raw',
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                        'class' => 'form-control',
                    ],
                ],
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'percentual',
                'format' => 'raw',
                'value' => $model->percentual,
                'format' => 'raw',
                'type' => DetailView::INPUT_MONEY,
                'widgetOptions' => [
//                    'name' => 'amount_BR',
//                    'value' => 0.01,
                    'pluginOptions' => [
                        'prefix' => 'R$ ',
                        'thousands' => '',
                        'decimal' => '.',
                        'precision' => 2
                    ],
//                    'class' => MaskMoney::classname(),
//                    'clientOptions' => [
//                        'alias' => 'date',
//                        'class' => 'form-control',
//                    ],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinEventospercentual::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinEventospercentualController::CLASS_VERB_NAME . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $model->id . ')') : ''),
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
