<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;
use common\models\SisParams;
use common\controllers\SisParamsController;
use frontend\controllers\SisEventsController;
use yii\helpers\ArrayHelper;
use kartik\icons\FontAwesomeAsset;
use yii\helpers\Url;
use dosamigos\tinymce\TinyMce;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\SisParams */

$this->title = common\controllers\SisParamsController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => SisParamsController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);
$dominios = ArrayHelper::map(Params::find()->select(['dominio' => 'dominio'])
                        ->orderBy('dominio')->groupBy('dominio')->all(), 'dominio', 'dominio');
?>

<?php
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\SisParams::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = true;

$attributes[] = [
    'columns' => [
        [
            'attribute' => 'dominio',
            'value' => $model->dominio,
            'format' => 'raw',
            'label' => 'Domínio',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $dominios,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->administrador,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'status',
            'options' => ['readonly' => false,],
            'displayOnly' => (Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3) ? false : true,
            'format' => 'raw',
            'value' => $model->getStatusLabel($model->status),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $status,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
        ],
    ],
];
$attributes[] = [
    'attribute' => 'label',
    'options' => ['readonly' => false,],
    'format' => 'raw',
    'value' => $model->label,
    'type' => DetailView::INPUT_TEXTAREA,
    'widgetOptions' => [
        'options' => [
            'rows' => 5,
            'id' => Yii::$app->security->generateRandomString(8),
        ],
    ],
];
$attributes[] = [
    'attribute' => 'parametro',
    'options' => ['readonly' => false,],
    'format' => 'raw',
    'value' => $model->grupo,
    'type' => DetailView::INPUT_TEXTAREA,
    'widgetOptions' => [
        'options' => [
            'rows' => 5,
            'id' => Yii::$app->security->generateRandomString(8),
        ],
    ],
];
$attributes[] = [
    'attribute' => 'grupo',
    'value' => $model->grupo,
    'format' => 'raw',
    'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
];
$attributes[] = [
    'group' => true,
    'label' => $model->getAttributeLabel('evento') . ' ' . (
    Yii::$app->user->identity->gestor ?
    Html::button($evento, [
        'value' => Url::to(['/eventos/' . $model->evento]),
        'title' => Yii::t('yii', 'Ver evento'),
        'class' => 'showModalButton button-as-link'
    ]) : $evento) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
    . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
    'groupOptions' => ['class' => 'table-info text-center']
];
?>
<div class="params-view">
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || SisParams::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => SisParamsController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
