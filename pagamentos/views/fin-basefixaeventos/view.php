<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinBasefixaeventosController;
use pagamentos\models\FinBasefixaeventos;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinBasefixaeventos */

$this->title = FinBasefixaeventosController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinBasefixaeventosController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-basefixaeventos-view">

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
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinBasefixaeventos::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'attribute' => 'id_fin_eventos',
        'format' => 'raw',
        'value' => $model->id_fin_eventos,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'id_fin_base_fixa',
        'format' => 'raw',
        'value' => $model->id_fin_base_fixa,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];


    $attributes[] = [
        'attribute' => 'evento',
        'value' => Yii::$app->user->identity->gestor ?
        Html::button($evento, [
            'value' => Url::to(['/sis-events/' . $model->evento]),
            'title' => Yii::t('yii', 'Ver evento'),
            'class' => 'showModalButton button-as-link'
        ]) : $evento,
        'format' => 'raw',
        'label' => 'Último evento',
        'displayOnly' => true,
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => date('d-m-Y H:i:s', $model->created_at),
                'options' => ['readonly' => true],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => date('d-m-Y H:i:s', $model->updated_at),
                'options' => ['readonly' => true],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinBasefixaeventos::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinBasefixaeventosController::CLASS_VERB_NAME . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $model->id . ')') : ''),
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
