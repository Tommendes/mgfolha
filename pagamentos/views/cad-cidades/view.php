<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadCidadesController;
use pagamentos\models\CadCidades;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCidades */

$this->title = CadCidadesController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadCidadesController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cad-cidades-view">

    <!--    <h1>Html::encode($this->title) ?></h1>
    
        <p>
            Html::a(, ['u', ], ['class' => 'btn btn-primary']) ?>
            Html::a(, ['dlt', ], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>-->

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadCidades::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'attribute' => 'uf_nome',
        'format' => 'raw',
        'value' => $model->uf_nome,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'uf_id',
        'format' => 'raw',
        'value' => $model->uf_id,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'uf_abrev',
        'format' => 'raw',
        'value' => $model->uf_abrev,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'municipio_id',
        'format' => 'raw',
        'value' => $model->municipio_id,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'municipio_nome',
        'format' => 'raw',
        'value' => $model->municipio_nome,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'data_registro',
        'format' => 'raw',
        'value' => $model->data_registro,
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
        'mode' => !isset(Yii::$app->request->queryParams ['mv']) || CadCidades::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams ['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadCidadesController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
