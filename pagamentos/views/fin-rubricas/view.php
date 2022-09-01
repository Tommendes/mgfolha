<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinRubricasController;
use pagamentos\models\FinRubricas;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\CadServidoresController;
use common\controllers\AppController;
use pagamentos\controllers\CadSfuncionalController;
use pagamentos\controllers\FinSfuncionalController;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinRubricas */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
$buttons = Yii::$app->user->identity->cadastros >= 3;
$mv = (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || !isset(Yii::$app->request->queryParams['mv']) || FinRubricas::STATUS_CANCELADO === $model->status || !$buttons ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'];

$this->title = FinRubricasController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug]];
$this->params['breadcrumbs'][] = ['label' => CadSfuncionalController::CLASS_VERB_NAME, 'url' => ['/cad-sfuncional/' . $model->getCadServidor()->matricula . '?mv=' . $mv]];
$this->params['breadcrumbs'][] = ['label' => FinSfuncionalController::CLASS_VERB_NAME_PL, 'url' => ['/fin-sfuncional/' . $model->getCadServidor()->matricula . '?mv=' . $mv]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-rubricas-view">

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
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinRubricas::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_fin_eventos',
                'format' => 'raw',
                'value' => $model->id_fin_eventos,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'referencia',
                'format' => 'raw',
                'value' => $model->referencia,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];


    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'valor_base',
                'format' => 'raw',
                'value' => $model->valor_base,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'valor',
                'format' => 'raw',
                'value' => $model->valor,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'valor_patronal',
                'format' => 'raw',
                'value' => $model->valor_patronal,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'valor_maternidade',
                'format' => 'raw',
                'value' => $model->valor_maternidade,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'prazo',
                'format' => 'raw',
                'value' => $model->prazo,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'prazot',
                'format' => 'raw',
                'value' => $model->prazot,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'valor_desconto',
                'format' => 'raw',
                'value' => $model->valor_desconto,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'valor_percentual',
                'format' => 'raw',
                'value' => $model->valor_percentual,
                'options' => [
                    'readonly' => !$buttons,
                    'addon' => [
                        'append' => [
                            'content' => Html::submitButton(Yii::t('yii', 'Encurtar'), ['class' => 'btn btn-success']),
                            'asButton' => true
                        ],
                        'prepend' => [
                            'content' => Html::submitButton(Yii::t('yii', 'Encurtar'), ['class' => 'btn btn-success']),
                            'asButton' => true
                        ]
                    ],
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinRubricas::STATUS_CANCELADO === $model->status || !$sf || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinRubricasController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
