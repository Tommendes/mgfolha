<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use frontend\controllers\SisEventsController;
use common\models\SisParams;
use common\controllers\SisParamsController;
use frontend\controllers\SisReviewsController;
use common\models\SisReviews;
use kartik\icons\FontAwesomeAsset;
use common\models\User;
use kartik\widgets\TouchSpin;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model common\models\SisReviews */

$this->title = SisReviewsController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => SisReviewsController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sis-reviews-view">
    <p style="padding-top: 5px;">
        <?=
        Html::a('<span class="fa fa-plus"></span> Nova', ['create'], [
            'title' => Yii::t('yii', 'Save Create'),
            'aria-label' => Yii::t('yii', 'Save Create'),
            'class' => 'btn btn-success'
        ])
        ?>
        <?=
        Html::a('<span class="fa fa-copy"></span> Duplicar', ['duplicate', 'id' => $model->id], [
            'title' => Yii::t('yii', 'Duplicate'),
            'aria-label' => Yii::t('yii', 'Duplicate'),
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a('<span class="fa fa-envelope-square"></span> Enviar', ['send-email', 'id' => $model->slug], [
            'title' => Yii::t('yii', 'Send by mail'),
            'aria-label' => Yii::t('yii', 'Send by mail'),
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to send this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\SisReviews::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'versao',
                'format' => 'raw',
                'value' => $model->versao,
                'displayOnly' => false,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => TouchSpin::classname(),
                    'options' => ['id' => Yii::$app->security->generateRandomString(8), 'placeholder' => 'Versão...'],
                    'pluginOptions' => [
                        'initval' => 1,
                        'min' => 1,
                        'max' => 99,
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'verticaldown' => '<i class="fas fa-minus"></i>'
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'lancamento',
                'format' => 'raw',
                'value' => $model->lancamento,
                'displayOnly' => false,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => TouchSpin::classname(),
                    'options' => ['id' => Yii::$app->security->generateRandomString(8), 'placeholder' => 'Versão...'],
                    'pluginOptions' => [
                        'initval' => 1,
                        'min' => 1,
                        'max' => 9999,
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'verticaldown' => '<i class="fas fa-minus"></i>'
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'revisao',
                'format' => 'raw',
                'value' => $model->revisao,
                'displayOnly' => false,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => TouchSpin::classname(),
                    'options' => ['id' => Yii::$app->security->generateRandomString(8), 'placeholder' => 'Versão...'],
                    'pluginOptions' => [
                        'initval' => 1,
                        'min' => 1,
                        'max' => 99,
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'verticaldown' => '<i class="fas fa-minus"></i>'
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
//                'attribute' => 'dominio',
                'value' => '',
                'label' => '',
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
//            [
//                'attribute' => 'dominio',
//                'format' => 'raw',
//                'value' => SisReviewsController::getDominio($model->dominio)['dominio'],
//                'type' => DetailView::INPUT_SELECT2,
//                'displayOnly' => false,
//                'widgetOptions' => [
//                    'data' => ArrayHelper::map(SisReviewsController::getDominios(), 'cliente', 'cliente'),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'titulo',
                'displayOnly' => false,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status == $model::STATUS_ATIVO ? 'Ativo' : 'Inativo',
                'type' => DetailView::INPUT_SELECT2,
                'displayOnly' => false,
                'widgetOptions' => [
                    'data' => $status,
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'descricao',
        'format' => 'raw',
        'value' => $model->descricao,
        'type' => DetailView::INPUT_TEXTAREA,
        'widgetOptions' => [
//                    'class' => TinyMce::className(),
            'options' => [
                'rows' => 20,
            ],
//                    'language' => 'pt_BR',
//                    'clientOptions' => [
//                        'plugins' => [
////                                "advlist autolink lists link charmap print preview anchor",
////                                "searchreplace visualblocks code fullscreen",
////                                "insertdatetime media table contextmenu paste textcolor"
//                            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
//                            'searchreplace wordcount visualblocks visualchars code fullscreen',
//                            'insertdatetime media nonbreaking save table contextmenu directionality',
//                            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
//                        ],
////                            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
//                        'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
//                        'toolbar2' => "print preview media | forecolor backcolor emoticons | codesample helpimage"
//                    ]
        ],
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
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || SisReviews::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => SisReviewsController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

    <?php
    $usuarios = User::findAll(['status' => User::STATUS_ATIVO]);
    $users = null;
    foreach ($usuarios as $usuario) {
        $users .= '<span class="badge badge-success">' . $usuario->email . '</span> ';
    }
    ?>
    <?=
    '<div class="alert alert-info" role="alert">'
    . '<div class="row">'
    . '     <div class="col-md-2">'
    . '     Destinatários:'
    . '     </div>'
    . '         <div class="col-md-10">'
    . $users
    . '         </div>'
    . '     </div>'
    . '</div>'
    . '</div>'
    ?>

</div>
