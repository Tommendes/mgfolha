<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadServidores;
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
/* @var $model pagamentos\models\CadServidores */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadServidoresController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$photo = strtolower(Yii::$app->user->identity->dominio . '/imagens/servidores/' . $model->url_foto);
$photo_root = Yii::getAlias('@uploads_root') . '/' . $photo;
$photo_url = Yii::getAlias('@uploads_url') . '/' . $photo;
$label = (!file_exists($photo_root)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_' . ($model->sexo === 1 ? 'fe' : '') . 'male.jpg';
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadServidores::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = Yii::$app->user->identity->cadastros >= 3;
?>
<div class="cad-servidores-view">
    <div class="row">
        <div class="col-md-12">
            <?php
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'idbanco',
                        'value' => common\models\Bancos::getBanco($model->idbanco),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Bancos::getBancos(),
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'banco_agencia',
                        'value' => $model->banco_agencia,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'banco_agencia_digito',
                        'value' => $model->banco_agencia_digito,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'banco_conta',
                        'value' => $model->banco_conta,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'banco_conta_digito',
                        'value' => $model->banco_conta_digito,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'banco_operacao',
                        'value' => $model->banco_operacao,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
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
                        'value' => date('d-m-Y H:i:s', $model->created_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => date('d-m-Y H:i:s', $model->updated_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            echo DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || CadServidores::STATUS_CANCELADO === $model->status || !$sf || $model->getFalecimento() !== null ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => '', //(isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons && $sf && $model->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado),
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => CadServidoresController::CLASS_VERB_NAME . ': ' . str_pad($model->matricula, 8, '0', STR_PAD_LEFT),
                    'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
                ],
                'attributes' => $attributes,
            ])
            ?>

        </div>
    </div>

</div>