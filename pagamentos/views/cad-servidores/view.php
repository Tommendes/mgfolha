<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\ContextMenusController;
use pagamentos\models\CadServidores;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;
use yii\widgets\MaskedInput;
use pagamentos\controllers\CadCidadesController;
use yii\helpers\ArrayHelper;
use kartik\cmenu\ContextMenu;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = $model && $model->evento && SisEventsController::findEvt($model->evento) || 0;
$modal = !isset($modal) ? false : $modal;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadServidores */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
    // Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadServidoresController::CLASS_VERB_NAME . ($model && $model->sexo == 1 ? 'a' : '');
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$nome = AppController::tratar_nome($model && $model->nome);
$this->params['breadcrumbs'][] = $this->title . " $nome";
\yii\web\YiiAsset::register($this);
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model && $model->status == CadServidores::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = Yii::$app->user->identity->cadastros >= 3;
?>
<div class="cad-servidores-view">
    <div class="row">
        <?php if ($model && $model->getFalecimento() !== null) { ?>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <h2>
                        <p class="text-center">Este servidor faleceu em <?= $model->getFalecimento()->d_afastamento ?><br>Algumas funções estão desabilitadas para este registro</p>
                    </h2>
                </div>
            </div>
        <?php } ?>
        <?php
        $mv = isset(Yii::$app->request->queryParams['mv']) && !empty(Yii::$app->request->queryParams['mv']) ? Yii::$app->request->queryParams['mv'] : DetailView::MODE_VIEW;
        if ($model && !$modal) :
        ?>
            <div class="col-md-3">
                <?=
                    $this->render('v_widget1', [
                        'model' => $model,
                        'mv' => $mv,
                    ])
                ?>
            </div>
        <?php endif; ?>
        <div class="col-md-<?= !$modal ? '9' : '12' ?>">
            <?php
            $attributes[] = [
                'attribute' => 'nome',
                'value' => $model->nome,
                'displayOnly' => !$buttons,
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'cpf',
                        'value' => AppController::setCpfCnpjMask($model->cpf),
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'nascimento_d',
                        'value' => !$model->nascimento_d == "" ? $model->nascimento_d
                            . ($model->getFalecimento() !== null ? ' <i class="fas fa-cross"></i> ' . $model->getFalecimento()->d_afastamento : '')
                            . '<br>(' . AppController::calc_idade(AppController::date_converter($model->nascimento_d), ($model->getFalecimento() !== null ? AppController::date_converter($model->getFalecimento()->d_afastamento) : null)) . ')' : '',
                        'type' => DetailView::INPUT_WIDGET,
                        'format' => 'raw',
                        'widgetOptions' => [
                            'class' => MaskedInput::classname(),
                            'clientOptions' => [
                                'alias' => 'date',
                                'class' => 'form-control',
                            ],
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'rg',
                        'value' => $model->rg,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'rg_d',
                        'value' => $model->rg_d,
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => MaskedInput::classname(),
                            'clientOptions' => [
                                'alias' => 'date',
                                'class' => 'form-control',
                            ],
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'rg_emissor',
                        'value' => $model->rg_emissor,
                        'options' => [
                            'readonly' => !$buttons,
                            'style' => 'text-transform:uppercase;',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'rg_uf',
                        'value' => common\models\Listas::getUf($model->rg_uf),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getUfs(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'pai',
                        'value' => $model->pai,
                        'options' => [
                            'readonly' => !$buttons,
                            'style' => 'text-transform:uppercase;',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'mae',
                        'value' => $model->mae,
                        'options' => [
                            'readonly' => !$buttons,
                            'style' => 'text-transform:uppercase;',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $cad_cidades = ArrayHelper::map(CadCidadesController::getCadList($model->uf), 'municipio_nome', 'municipio_nome', ['prompt' => 'Selecione...']);
            $id_mun = 'id_mun_' . Yii::$app->security->generateRandomString(8);
            $url_cidades = Yii::$app->urlManager->createUrl('/cad-cidades/lists/');
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'naturalidade_uf',
                        'format' => 'raw',
                        'value' => common\models\Listas::getUf($model->naturalidade_uf),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getUfs(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'placeholder' => 'Selecione ...',
                            'maxlength' => 2,
                            'onchange' => " 
                                    var url = '$url_cidades/'+$(this).val();
                                    $.post( url, function( data ) {
                                      $('select#$id_mun').html( data );
                                    });
                                ",
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'naturalidade',
                        'value' => $model->naturalidade,
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => $cad_cidades,
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'id' => $id_mun, 'placeholder' => 'Selecione ...',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'tipodeficiencia',
                        'value' => common\models\Listas::getTipodeficiencia($model->tipodeficiencia),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getTiposdeficiencia(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'email',
                        'value' => $model->email,
                        'format' => 'email',
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'telefone',
                        'value' => $model->telefone,
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => MaskedInput::className(),
                            'name' => 'input-telefone',
                            'mask' => ['(99)9999-9999', '(99)99999-9999'],
                            'options' => [],
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'celular',
                        'value' => $model->celular,
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => MaskedInput::className(),
                            'name' => 'input-celular',
                            'mask' => ['(99)9999-9999', '(99)99999-9999'],
                            'options' => [],
                        ],
                        'options' => [
                            'readonly' => !$buttons,
                            'class' => 'form-control',
                        ],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'nacionalidade',
                        'format' => 'raw',
                        'value' => common\models\Listas::getNacionalidade($model->nacionalidade),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getNacionalidades(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'sexo',
                        'format' => 'raw',
                        'value' => common\models\Listas::getSexo($model->sexo),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getSexos(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'raca',
                        'format' => 'raw',
                        'value' => common\models\Listas::getRaca($model->raca),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getRacas(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'estado_civil',
                        'format' => 'raw',
                        'value' => common\models\Listas::getEstado_civil($model->estado_civil),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getEstados_civis(),
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons, 'placeholder' => 'Selecione ...'],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'attribute' => 'd_admissao',
                'format' => 'raw',
                'value' => !$model->d_admissao == "" ? $model->d_admissao
                    . ($model->getFalecimento() !== null ? ' <i class="fas fa-cross"></i> ' . $model->getFalecimento()->d_afastamento : '')
                    . '<br>(' . AppController::calc_idade(AppController::date_converter($model->d_admissao), ($model->getFalecimento() !== null ? AppController::date_converter($model->getFalecimento()->d_afastamento) : null)) . ')' : '',
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
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'vertical-align: middle;']
            ];
            $attributes[] = [
                'group' => true,
                'label' => $model->getAttributeLabel('evento') . ' ' . (Yii::$app->user->identity->gestor ?
                    Html::button($evento, [
                        'value' => Url::to(['/sis-events/' . $model->evento]),
                        'title' => Yii::t('yii', 'Ver evento'),
                        'class' => 'showModalButton button-as-link'
                    ]) : $evento) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
                    . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
                'groupOptions' => ['class' => 'table-info text-center']
            ];
            $urlView = Url::home(true) . "cad-servidores/$model->slug";
            $urlEdit = Url::home(true) . "cad-servidores/$model->slug?mv=edit";

            if ($baseServico = Yii::$app->user->identity->base_servico == 'pagamentos') {
                ContextMenu::begin(['items' => ContextMenusController::getItemsImpressServidor($model->id), 'options' => ['id' => 'ctxmImpressao',]]);
            }
            echo DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || CadServidores::STATUS_CANCELADO === $model->status || !$sf || $model->getFalecimento() !== null ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? ((isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons && $sf && $model->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado)) : '',
                'viewOptions' => [
                    'url' => '#',
                    'onclick' => "window.location.replace('$urlView')"
                ],
                'resetOptions' => [
                    'url' => '#',
                    'onclick' => "window.location.replace('$urlView')"
                ],
                'updateOptions' => [
                    'url' => '#',
                    'onclick' => "window.location.replace('$urlEdit')"
                ],
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => CadServidoresController::CLASS_VERB_NAME . ($model->sexo == 1 ? 'a' : '') . ': ' . $model->matricula . (Yii::$app->user->identity->administrador >= 2 ? '(' . $model->id . ')' : ''),
                    'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
                ],
                'attributes' => $attributes,
            ]);

            if ($baseServico == 'pagamentos') {
                ContextMenu::end();
            }
            ?>
        </div>
    </div>
</div>