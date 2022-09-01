<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;
use frontend\models\Msgs;
use common\models\SisParams;
use frontend\controllers\MsgsController;
use dosamigos\tinymce\TinyMce;
use frontend\controllers\SisEventsController;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\Msgs */
$modal = isset($modal) ? $modal : false;

$this->title = MsgsController::CLASS_VERB_NAME . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msgs-view">

    <!--<h1><?php // echo Html::encode($this->title)                                                 ?></h1>-->
    <?php if (!$modal) { ?>
        <p>
            <?php echo Html::a(Yii::t('yii', 'Updating'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php
            echo Html::a(Html::icon('duplicate') . ' ' . Yii::t('yii', 'Duplicate'), ['duplicate', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Duplicate'),
                'aria-label' => Yii::t('yii', 'Duplicate'),
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
            <?php
            echo Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
            <?=
            Html::button('<span class="glyphicons glyphicons-compressed"></span> Encurtar URL', ['id' => 'btnShort', 'class' => 'btn btn-default'])
            ?>
        </p>
    <?php } ?>

    <?php
    $id_desk_user = MsgsController::getDeskUsers();
    $evento = SisEventsController::findEvt($model->evento);
    $statusCancelado = SisParams::findOne([
                'dominio' => 'mgfolha',
                'grupo' => 'msg_status',
                'parametro' => Msgs::STATUS_CANCELADO
    ]);
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . $statusCancelado->label;
    echo DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || Msgs::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => MsgsController::CLASS_VERB_NAME,
            'type' => Msgs::STATUS_CANCELADO === $model->status ? DetailView::TYPE_WARNING : DetailView::TYPE_INFO,
        ],
        'attributes' => [
//            'id',
//            'slug',
            [
                'columns' => [
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
//                        'value' => SisParams::getParamsLabel($model->status, 'msg_status', 'mgfolha'),
                        'type' => DetailView::INPUT_SELECT2,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'data' => [
                                $model::STATUS_INATIVO => 'Inativo',
                                $model::STATUS_ATIVO => 'Ativo',
                                $model::STATUS_LIDA => 'Lida',
                                $model::STATUS_CANCELADO => 'Cancelada',
                            ],
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'id_pos',
                        'format' => 'raw',
                        'value' => $model->getDeskPos($model->id_pos),
                        'type' => DetailView::INPUT_SELECT2,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'data' => ['0' => 'Superior direito', '1' => 'Inferior direito'],
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'caption',
                        'format' => 'raw',
                        'value' => $model->caption,
                        'displayOnly' => false,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'caption_color_id',
                        'format' => 'raw',
//                        'value' => '<div class="alert" style="background-color: #' + $model->caption_color_id + ' !important;" role="alert">' + $model->caption_color_id + '</div>',
                        'type' => DetailView::INPUT_COLOR,
                        'displayOnly' => false,
                        'widgetOptions' => [
                            'options' => ['placeholder' => 'Selecione ...'],
//                            'showDefaultPalette' => false,
//                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                            'pluginOptions' => [
//                                'showInput' => true,
//                                'showInitial' => true,
//                                'showPalette' => true,
//                                'showPaletteOnly' => true,
//                                'showSelectionPalette' => true,
//                                'showAlpha' => false,
//                                'allowEmpty' => false,
//                                'preferredFormat' => 'name',
//                                'palette' => [
//                                    [
//                                        "white", "black", "grey", "silver", "gold", "brown",
//                                    ],
//                                    [
//                                        "red", "orange", "yellow", "indigo", "maroon", "pink"
//                                    ],
//                                    [
//                                        "blue", "green", "violet", "cyan", "magenta", "purple",
//                                    ],
//                                ]
                            ],
                        ],
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
            [
                'attribute' => 'body',
                'format' => 'raw',
                'value' => yii::t('yii', '<div class="alert" style="background-color: '
                        . '{caption_color_id};" role="alert">'
                        . '<span style="color: #ffffff;">{caption}</span></div>'
                        . $model->body, [
                    'caption' => $model->caption,
                    'caption_color_id' => $model->caption_color_id
                ]),
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => TinyMce::className(),
                    'language' => 'pt_BR',
                    'clientOptions' => [
                        'entity_encoding' => 'raw',
                        'plugins' => [
                            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                            'searchreplace wordcount visualblocks visualchars code fullscreen',
                            'insertdatetime media nonbreaking save table contextmenu directionality',
                            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                        ],
                        'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                        'toolbar2' => "print preview media | forecolor backcolor emoticons | codesample helpimage"
                    ],
                    'options' => [
                        'rows' => 10,
                        'id' => $body = Yii::$app->security->generateRandomString(),
                    ],
                ],
            ],
            [
                'attribute' => 'link',
                'format' => 'raw',
                'value' => Html::a($model->link, $model->link, ['target' => '_blank']),
                'displayOnly' => false,
            ],
            [
                'attribute' => 'id_desk_user',
                'format' => 'raw',
                'value' => $model->getDeskUser($model->id_desk_user),
                'type' => DetailView::INPUT_SELECT2,
                'displayOnly' => false,
                'widgetOptions' => [
                    'data' => ArrayHelper::map($id_desk_user, 'id', 'cli_nome'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
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
                ],
            ],
        ],
    ])
    ?>

</div>
<?php
$app = Url::home(true) . 'sis-shortener/short';
$js = <<< JS
    $("#btnShort").on("click", function() {      
        krajeeDialog.prompt({label:'Insira a URL longa', placeholder:'Url longa...'}, function (result) {
            if (result) {
                $.get("$app?url=" + result, function(data){
                    krajeeDialog.alert('URL curta: <a href="' + data + '" target="_blank">' + data + '</a>');
                    $('#msgs-link').val('https://'+data);
                });
            } else {
                krajeeDialog.alert('Para obter URL curta, por favor informe uma URL longa!');
            }
        });        
    });
JS;
$this->registerJs($js);
?>
