<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadBconveniosController;
use pagamentos\models\CadBconvenios;
use kartik\icons\FontAwesomeAsset;
use common\models\OrgaoUa;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadBconvenios */

$this->title = CadBconveniosController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadBconveniosController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cad-bconvenios-view">
    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadBconvenios::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'attribute' => 'id_orgao_ua',
        'format' => 'raw',
        'value' => (($ua = OrgaoUa::find()
                ->select([
                    'id' => 'orgao_ua.id',
                    'nome' => 'concat(orgao_ua.nome, "(", orgao_ua.codigo, ")")'
                ])
                ->join('join', 'orgao', 'orgao.id = orgao_ua.id_orgao')
                ->where([
                    'orgao_ua.status' => OrgaoUa::STATUS_ATIVO,
                    'orgao.dominio' => Yii::$app->user->identity->dominio,
                    'orgao_ua.id' => $model->id_orgao_ua,
                ])->one()) !== null) ? $ua->nome : 'Não definido',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(OrgaoUa::find()
                            ->select([
                                'id' => 'orgao_ua.id',
                                'nome' => 'concat(orgao_ua.nome, "(", orgao_ua.codigo, ")")'
                            ])
                            ->join('join', 'orgao', 'orgao.id = orgao_ua.id_orgao')
                            ->where([
                                'orgao_ua.status' => OrgaoUa::STATUS_ATIVO,
                                'orgao.dominio' => Yii::$app->user->identity->dominio
                            ])->asArray()->all(), 'id', 'nome'),
            'options' => ['prompt' => 'Selecione ...',],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
    ];
    $attributes[] = [
        'attribute' => 'convenio',
        'format' => 'raw',
        'value' => $model->convenio,
        'displayOnly' => !$buttons,
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cod_compromisso',
                'format' => 'raw',
                'value' => $model->cod_compromisso,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'id_cad_bancos',
                'format' => 'raw',
                'value' => common\models\Bancos::getBanco($model->id_cad_bancos),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => common\models\Bancos::getBancos(),
                    'options' => [],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons, 'prompt' => 'Selecione ...'],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'agencia',
                'format' => 'raw',
                'value' => $model->agencia,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'a_digito',
                'format' => 'raw',
                'value' => $model->a_digito,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'conta',
                'format' => 'raw',
                'value' => $model->conta,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'c_digito',
                'format' => 'raw',
                'value' => $model->c_digito,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'convenio_nr',
                'format' => 'raw',
                'value' => $model->convenio_nr,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'tipo_servico',
                'format' => 'raw',
                'value' => $model->tipo_servico,
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || CadBconvenios::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadBconveniosController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
