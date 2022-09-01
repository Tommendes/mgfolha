<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\OrgaoUaController;
use common\models\OrgaoUa;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoUa */

$this->title = OrgaoUaController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => OrgaoUaController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);
?>
<div class="orgao-ua-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\OrgaoUa::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'attribute' => 'nome',
        'value' => $model->nome,
        'options' => ['readonly' => !Yii::$app->user->identity->gestor],
        'displayOnly' => !Yii::$app->user->identity->gestor,
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cnpj',
                'value' => AppController::setCpfCnpjMask($model->cnpj),
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'codigo',
                'value' => $model->codigo,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || OrgaoUa::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => OrgaoUaController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
