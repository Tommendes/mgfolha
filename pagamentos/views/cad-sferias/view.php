<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadSferiasController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadSferias;
use kartik\icons\FontAwesomeAsset;
use dosamigos\tinymce\TinyMce;
use yii\widgets\MaskedInput;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSferias */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadSferiasController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug]];
$this->params['breadcrumbs'][] = ['label' => 'Todas as ' . strtolower(CadSferiasController::CLASS_VERB_NAME_PL), 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="cad-sferias-view">

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
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadSferias::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'd_ferias',
                'format' => 'raw',
                'value' => $model->d_ferias,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'periodo',
                'format' => 'raw',
                'value' => $model->periodo,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'observacoes',
        'format' => 'raw',
        'value' => $model->observacoes,
        'type' => DetailView::INPUT_WIDGET,
        'widgetOptions' => [
            'class' => TinyMce::className(),
            'language' => 'pt_BR',
            'clientOptions' => [
                'plugins' => [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                ],
                'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | codesample helpimage",
//                            'toolbar2' => ""
            ],
            'options' => [
                'rows' => 3,
                'id' => Yii::$app->security->generateRandomString(),
            ],
        ],
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
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
        'mode' => !Yii::$app->user->identity->base_servico == 'pagamentos' || !isset(Yii::$app->request->queryParams['mv']) || CadSferias::STATUS_CANCELADO === $model->status || !$sf || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null ? DetailView::MODE_VIEW : (Yii::$app->request->queryParams['mv'] == DetailView::MODE_EDIT ? DetailView::MODE_EDIT : DetailView::MODE_VIEW),
        'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? ((isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado) : '',
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadSferiasController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
