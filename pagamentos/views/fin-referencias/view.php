<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinReferenciasController;
use pagamentos\models\FinReferencias;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinReferencias */

$this->title = FinReferenciasController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinReferenciasController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-referencias-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinReferencias::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'attribute' => 'id_pccs',
        'format' => 'raw',
        'value' => (($cadPccs = pagamentos\models\CadPccs::find()->where([
            'id_pccs' => $model->id_pccs,
            'dominio' => Yii::$app->user->identity->dominio,
        ])->one()) !== null) ? $cadPccs->nome_pccs . ' (' . str_pad($cadPccs->id_pccs, 4, '0', STR_PAD_LEFT) . ')' : 'Não definido',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(pagamentos\models\CadPccs::find()
                            ->select([
                                'id' => 'cad_pccs.id_pccs',
                                'nome_pccs' => 'CONCAT(cad_pccs.nome_pccs, " (", LPAD(cad_pccs.id_pccs,4,"0"), ")")',
                            ])
                            ->join('join', 'fin_referencias', 'fin_referencias.id_pccs = cad_pccs.id_pccs')
                            ->orderBy('cad_pccs.nome_pccs')->asArray()->all(), 'id', 'nome_pccs'),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_classe',
                'format' => 'raw',
                'value' => (($cadClasses = pagamentos\models\CadClasses::findOne($model->id_classe)) !== null) ? $cadClasses->nome_classe : 'Não definido',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => ArrayHelper::map(pagamentos\models\CadClasses::find()
                                    ->select([
                                        'id' => 'cad_classes.id_classe',
                                        'nome_classe' => 'cad_classes.nome_classe',
                                    ])
                                    ->join('join', 'fin_referencias', 'fin_referencias.id_classe = cad_classes.id_classe')
                                    ->groupBy('cad_classes.nome_classe')
                                    ->orderBy('cad_classes.nome_classe')->asArray()->all(), 'id', 'nome_classe'),
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
                'attribute' => 'referencia',
                'format' => 'raw',
                'value' => $model->referencia,
                'type' => DetailView::INPUT_SPIN,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'buttonup_class' => 'btn btn-outline-secondary bootstrap-touchspin-down ml-0',
                        'verticaldown' => '<i class="fas fa-minus"></i>',
                        'buttondown_class' => 'btn btn-outline-secondary bootstrap-touchspin-down mr-0',
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
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'valor',
                'format' => 'raw',
                'value' => $model->valor,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'data',
                'format' => 'raw',
                'value' => $model->data,
                'options' => ['readonly' => !$buttons],
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinReferencias::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinReferenciasController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
