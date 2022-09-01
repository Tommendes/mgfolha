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
$photo = strtolower(Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/imagens/servidores/' . $model->url_foto);
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
                'attribute' => 'titulo',
                'value' => $model->titulo,
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('titulo'),
                ],
                'displayOnly' => !$buttons,
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'titulosecao',
                        'value' => $model->titulosecao,
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('titulosecao'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'titulozona',
                        'value' => $model->titulozona,
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('titulozona'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'pispasep',
                        'value' => $model->pispasep,
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => MaskedInput::classname(),
                            'mask' => '999.99999.99.9',
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('pispasep'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'pispasep_d',
                        'value' => date('d-m-Y', strtotime($model->pispasep_d)),
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
                            'placeholder' => $model->getAttributeLabel('pispasep_d'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'ctps',
                        'value' => $model->ctps,
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('ctps'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'ctps_serie',
                        'value' => $model->ctps_serie,
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('ctps_serie'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'ctps_uf',
                        'value' => common\models\Listas::getUf($model->ctps_uf),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getUfs(),
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('ctps_uf'),
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'ctps_d',
                        'value' => date('d-m-Y', strtotime($model->ctps_d)),
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
                            'placeholder' => $model->getAttributeLabel('ctps_d'),
                        ],
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