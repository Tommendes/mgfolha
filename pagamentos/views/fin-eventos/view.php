<?php

use yii\helpers\Url;
use yii\helpers\Json;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinEventosController;
use pagamentos\models\FinEventos;
use kartik\icons\FontAwesomeAsset;
use common\models\Listas;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

$buttons = Yii::$app->user->identity->financeiro >= 3;
$mv = (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || !isset(Yii::$app->request->queryParams['mv']) || FinEventos::STATUS_CANCELADO === $model->status || !$buttons ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'];

$check = '<i class="fas fa-check green"></i>';

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventos */

$this->title = FinEventosController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinEventosController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-eventos-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinEventos::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_evento',
                'format' => 'raw',
                'value' => $model->id_evento,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons || $model->ev_root,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'evento_nome',
                'format' => 'raw',
                'value' => $model->evento_nome,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'tipo',
                'format' => 'raw',
                'value' => Listas::getCD($model->tipo),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getCDs(),
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
                'attribute' => 'consignado',
                'format' => 'raw',
                'value' => Listas::getSN($model->consignado),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'consignavel',
                'format' => 'raw',
                'value' => Listas::getSN($model->consignavel),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'deduzconsig',
                'format' => 'raw',
                'value' => Listas::getSN($model->deduzconsig),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'automatico',
                'format' => 'raw',
                'value' => Listas::getSN($model->automatico),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'vinculacao_dirf',
                'format' => 'raw',
                'value' => $model->vinculacao_dirf,
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => yii\helpers\ArrayHelper::map(
                            \pagamentos\models\FinEventos::find()
                                    ->where([
                                        'dominio' => Yii::$app->user->identity->dominio,
                                    ])
//                                    ->andWhere('vinculacao_dirf is not null and length(trim(vinculacao_dirf)) > 0')
                                    ->asArray()->groupBy('vinculacao_dirf')->orderBy('vinculacao_dirf')->all(), 'vinculacao_dirf', 'vinculacao_dirf'),
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
                'attribute' => 'i_prioridade',
                'format' => 'raw',
                'value' => $model->i_prioridade,
                'type' => DetailView::INPUT_SPIN,
                'widgetOptions' => [
//                    'class' => kartik\touchspin\TouchSpin::classname(),
                    'pluginOptions' => [
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'verticaldown' => '<i class="fas fa-minus"></i>'
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control mr-0 ml-0',
                    'placeholder' => 'Prioridade...',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'fixo',
                'format' => 'raw',
                'value' => Listas::getSN($model->fixo),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'sefip',
                'format' => 'raw',
                'value' => Listas::getSN($model->sefip),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
                'attribute' => 'rais',
                'format' => 'raw',
                'value' => Listas::getSN($model->rais),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
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
    if (($getBaseFixa = $model->getBaseFixa()) != null) {
        $nome_ev = $getBaseFixa->nome_base_fixa . ' (' . $getBaseFixa->id_base_fixa . ')';
        $value = $nome_ev . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $getBaseFixa->id . ')') : '');
        $attributes[] = [
            'label' => \pagamentos\controllers\FinBasefixaController::CLASS_VERB_NAME,
            'format' => 'raw',
            'value' => $value,
            'options' => ['readonly' => true,],
            'displayOnly' => true,
            'valueColOptions' => ['class' => 'gv-warning',],
            'labelColOptions' => ['class' => 'gv-warning', 'style' => 'width: 20%;text-align: right;display: all;'],
        ];
        $nome_ev = $getBaseFixa->fixa_refer_descricao . ' de ' . date('d-m-Y', strtotime($getBaseFixa->fixa_refer_data)) . ' e valor R$ ' . number_format($getBaseFixa->fixa_refer_valor, 2, ',', '.') . ' (' . $getBaseFixa->fixa_refer_id_fixa . ')';
        $value = $nome_ev . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $getBaseFixa->fixa_refer_id . ')') : '');
        $attributes[] = [
            'label' => \pagamentos\controllers\FinBasefixareferController::CLASS_VERB_NAME,
            'format' => 'raw',
            'value' => $value,
            'options' => ['readonly' => true,],
            'displayOnly' => true,
            'valueColOptions' => ['class' => 'gv-warning',],
            'labelColOptions' => ['class' => 'gv-warning', 'style' => 'width: 20%;text-align: right;display: all;'],
        ];
//        $value = Html::a("$nome_ev $check" . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $getBaseFixa->id . ')') : ''), '#', [
//            'value' => Url::to(['/fin-basefixa/' . $getBaseFixa->slug, 'modal' => 1, 'mv' => '0']),
//            'title' => Yii::t('yii', $nome_ev),
//            'class' => "showModalButton",
//        ]);
    }

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
    <?php $countUseOfIdEvento = $model->getUseOfIdEvento()['quant']  ?>
    <?php $pluralEventos01 = $countUseOfIdEvento > 1 ? 's' : '' ?>
    <?php $pluralEventos02 = $countUseOfIdEvento > 1 ? 'm' : '' ?>
    <div class="row">
        <div class="col-12">
            <?php
            
            echo DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinEventos::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($countUseOfIdEvento > 0 && $buttons ? '{update}' : ($buttons ? '{update} {delete}' : $label_statusCancelado)),
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => !$modal ? [
                    'heading' => FinEventosController::CLASS_VERB_NAME . (Yii::$app->user->identity->administrador >= 2 ? ' (' . $model->id . ')' : '')
                    . ($countUseOfIdEvento > 0 ? ' | ' . $countUseOfIdEvento . " registro$pluralEventos01 financeiro$pluralEventos01 utiliza$pluralEventos02 esse " . strtolower(FinEventosController::CLASS_VERB_NAME) . ($countUseOfIdEvento > 0 ? '. Ele não pode ser excluído!' : '') : '')
                    . ($model->ev_root ? ' | ATENÇÃO !!! Este evento pertence ao sistema.' . ($countUseOfIdEvento == 0 ? ' Altere com consciência!' : '') : ''),
                    'type' => $buttons && !$model->ev_root ? DetailView::TYPE_INFO : DetailView::TYPE_DEFAULT,
                        ] : null,
                'attributes' => $attributes,
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <!--SisEvents da base de calculo-->
            <?php
            $searchModel = new \pagamentos\models\FinEventosbaseSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);

            echo $this->render('/fin-eventosbase/index', [
                'searchModel' => null,
                'dataProvider' => $dataProvider,
                'id_fin_eventos' => $model->id,
                'cl' => ['id_fin_eventosbase'],
                'perfectScrollbar' => true,
                'perfectScrollbarHeight' => null,
                'mv' => $mv,
            ]);
            ?>
        </div>
        <!-- Fim de sis-events da base de calculo-->
        <!--SisEvents de desconto da base de calculo-->
        <div class="col-4">
            <?php
            $searchModel = new \pagamentos\models\FinEventosdescontoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);

            echo $this->render('/fin-eventosdesconto/index', [
                'searchModel' => null,
                'dataProvider' => $dataProvider,
                'id_fin_eventos' => $model->id,
                'cl' => ['id_fin_eventosdesconto'],
                'perfectScrollbar' => true,
                'perfectScrollbarHeight' => null,
                'mv' => $mv,
            ]);
            ?>
        </div>
        <!-- Fim de sis-events de desconto da base de calculo-->
        <!--Percentuais do evento-->
        <div class="col-4">
            <?php
            $searchModel = new \pagamentos\models\FinEventospercentualSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);

            echo $this->render('/fin-eventospercentual/index', [
                'searchModel' => null,
                'dataProvider' => $dataProvider,
                'id_fin_eventos' => $model->id,
                'cl' => ['id_eventos_percentual', 'data', 'percentual'],
                'perfectScrollbar' => true,
                'perfectScrollbarHeight' => null,
                'mv' => $mv,
            ]);
            ?>
        </div>
        <!-- Fim de percentuais do evento-->
    </div>

</div>
