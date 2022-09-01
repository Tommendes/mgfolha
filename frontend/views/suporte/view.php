<?php

use kartik\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use frontend\controllers\SisEventsController;
use yii\helpers\Url;
use common\models\SisParams;
use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;
use frontend\models\Suporte;
use frontend\controllers\SuporteController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Suporte */


$this->title = SuporteController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Suportes'), 'url' => [
        Yii::$app->user->identity->administrador == 1 ? 'adm' : 'index'
        ]];
$this->params['breadcrumbs'][] = $this->title;
$status = ArrayHelper::map(Params::find()
                        ->where([
                            'dominio' => '_app',
                            'grupo' => 'sis_status'
                        ])
                        ->orderBy('parametro')
                        ->all(), 'parametro', 'label');
$statusCancelado = SisParams::find()
        ->where([
            'dominio' => '_app',
            'grupo' => 'sis_status',
            'parametro' => Suporte::STATUS_CANCELADO
        ])
        ->orderBy('parametro')
        ->one();
$label_statusCancelado = Html::icon('warning-sign') . ' ' . $statusCancelado->label;
?>
<div class="suporte-view">

    <h1><?= Html::encode(SuporteController::CLASS_VERB_NAME) ?></h1>

    <p>

        <?=
        Html::a(Html::icon('plus'), ['create'], [
            'title' => Yii::t('yii', 'Save Create'),
            'aria-label' => Yii::t('yii', 'Save Create'),
            'class' => 'btn btn-success'
        ])
        ?>
        <!--        echo Html::a(Html::icon('pencil'), ['update', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Update'),
                'aria-label' => Yii::t('yii', 'Update'),
                'class' => 'btn btn-primary'
                ])
                ?>-->
        <!--        echo Html::a(Html::icon('trash'), ['delete', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Delete'),
                'aria-label' => Yii::t('yii', 'Delete'),
                'class' => 'btn btn-danger',
                'data' => [
                'confirm' => ,
                'method' => 'post',
                ],
                ]) 
                ?>-->
        <?=
        Html::a(Html::icon('duplicate'), ['duplicate', 'id' => $model->id], [
            'title' => Yii::t('yii', 'Duplicate'),
            'aria-label' => Yii::t('yii', 'Duplicate'),
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?php $evento = SisEventsController::findEvt($model->evento); ?>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || Suporte::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => Suporte::STATUS_CANCELADO === $model->status ? $label_statusCancelado : '{update} {delete}',
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['delete', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => 'Suporte - ' . $model->descricao,
            'type' => Suporte::STATUS_CANCELADO === $model->status ? DetailView::TYPE_WARNING : DetailView::TYPE_INFO,
        ],
        'attributes' => [
//            [
//                'attribute' => 'id',
//                'displayOnly' => true,
//            ],
            [
                'columns' => [
                    [
                        'attribute' => 'dominio',
                        'format' => 'raw',
                        'value' => $model->getDominio($model->dominio),
                        'type' => DetailView::INPUT_SELECT2,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map($model->getDominio(), 'id', 'dominio'),
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'grupo',
                        'format' => 'raw',
                        'value' => $model->getGrupo($model->grupo),
                        'type' => DetailView::INPUT_SELECT2,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map($model->getGrupo(), 'id', 'label'),
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
            [
                'attribute' => 'slug',
                'format' => 'raw',
                'value' => '<a href="' . Url::home(true) . 'suporte/artigos/' . $model->slug
                . '" target="_empty">' . Url::home(true) . 'suporte/artigos/' . $model->slug . '</a> ',
                'label' => 'Endereço',
//                'displayOnly' => true,
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'descricao',
                        'displayOnly' => false,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => SisParams::getParamsLabel($model->status, 'sis_status'),
                        'type' => DetailView::INPUT_SELECT2,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'data' => $status,
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
            [
                'attribute' => 'texto',
                'format' => 'raw',
                'value' => $model->texto,
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
                        'rows' => 12,
                        'id' => Yii::$app->security->generateRandomString(),
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'created_at',
                        'value' => date('d-m-Y H:i:s', $model->created_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => date('d-m-Y H:i:s', $model->updated_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ]
            ],
            [
                'attribute' => 'evento',
                'value' => Yii::$app->user->identity->gestor ?
                        '<a href="' . Url::home(true) . 'eventos/' .
                        $model->evento . '" target="_empty">' . $evento . '</a> ' : $evento,
                'format' => 'raw',
                'label' => 'Último evento relacionado',
                'displayOnly' => true,
            ],
        ],
    ])
    ?>

</div>

<?php
$js = <<< JS
JS;
$this->registerJs($js);
?>