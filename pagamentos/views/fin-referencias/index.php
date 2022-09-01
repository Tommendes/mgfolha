<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinReferenciasController;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinReferenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinReferenciasController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="fin-referencias-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['fin-referencias/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinReferenciasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-referencias/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinReferenciasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-referencias/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinReferenciasController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'fin-referencias/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->financeiro < 4 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
                            'title' => Yii::t('yii', 'Delete') . ' item',
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'onclick' => "
                                if (confirm('$msdelete')) {
                                    $.ajax({
                                        url: '$urldelete',
                                        type: 'post',
                                        success: function (response) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: response['stat'] == true ? 'Sucesso': 'Ops!',
                                                text: response['mess'],
                                                type: response['class'],
                                                styling: 'fontawesome',
                                                animate: {
                                                    animate: true,
                                                    in_class: 'rotateInDownLeft',
                                                    out_class: 'rotateOutUpRight'
                                                }
                                            });
                                            $.pjax.reload({container: '#$idGridPJax'});
                                            if (response['reloadPage'] == true){
                                                javascript:location.reload();
                                            } 
                                        },
//                                                error: function (response) {
//                                                    new PNotify({
//                                                        title: 'Erro',
//                                                        text: response['mess'],
//                                                        type: response['class'], 
//                                                        styling: 'fontawesome',
//                                                        animate: {
//                                                            animate: true,
//                                                            in_class: 'rotateInDownLeft',
//                                                            out_class: 'rotateOutUpRight'
//                                                        }
//                                                    });
//                                                }
                                    });
                                    return false;
                                }
                                return false;
                            ",
                ]);
            },
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'fin-referencias/dpl/' . $model->slug;
//                        return Yii::$app->user->identity->financeiro < 2 ? '' : Html::a(
//                                        '<span class="fa fa-clone"></span>', '#', [
//                                    'title' => Yii::t('yii', 'Duplicate') . ' item',
//                                    'aria-label' => Yii::t('yii', 'Duplicate'),
//                                    'onclick' => "
//                                            krajeeDialog.confirm('$msduplicate', function(out){
//                                                    if(out) {
//                                                        $.ajax('$url', {
//                                                        type: 'POST'
//                                                    }).done(function(data) {
//                                                        new PNotify({ 
//                                                            title: 'Sucesso', 
//                                                            text: data,
//                                                            type: 'success',
//                                                            styling: 'fontawesome',
//                                                            animate: {
//                                                                animate: true,
//                                                                in_class: 'rotateInDownLeft',
//                                                                out_class: 'rotateOutUpRight'
//                                                            }
//                                                        });
//                                                    });
//                                                }
//                                            })
//                                        ",
//                        ]);
//                    },
        ],
    ];

    //    $columns[] = ['class' => 'kartik\grid\SerialColumn'];

    $columns[] = [
        'attribute' => 'id_pccs',
        'width' => '50%',
        'value' => function ($model, $key, $index, $widget) {
            return (($cadPccs = pagamentos\models\CadPccs::find()->where([
                        'id_pccs' => $model->id_pccs,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])->one()) !== null) ? $cadPccs->nome_pccs/* . ' (' . $cadPccs->nivel_pccs . ')' */ : 'Não definido';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(pagamentos\models\CadPccs::find()
                        ->select([
                            'id' => 'cad_pccs.id',
                            'nome_pccs' => 'cad_pccs.nome_pccs',
                        ])
                        ->join('join', 'fin_referencias', 'fin_referencias.id_pccs = cad_pccs.id')
                        ->orderBy('cad_pccs.nome_pccs')->asArray()->all(), 'id', 'nome_pccs'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Selecione...'],
//        'group' => true, // enable grouping,
//        'groupedRow' => false, // move grouped column to a single grouped row
//        'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
//        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align: left;', 'font' => 'size: 5'],
    ];
    $columns[] = [
        'attribute' => 'id_classe',
        'width' => '10%',
        'value' => function ($model, $key, $index, $widget) {
            return (($cadClasses = pagamentos\models\CadClasses::findOne($model->id_classe)) !== null) ? $cadClasses->nome_classe : 'Não definido';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(pagamentos\models\CadClasses::find()
                        ->select([
                            'id' => 'cad_classes.id',
                            'nome_classe' => 'cad_classes.nome_classe',
                        ])
                        ->join('join', 'fin_referencias', 'fin_referencias.id_classe = cad_classes.id')
                        ->groupBy('cad_classes.nome_classe')
                        ->orderBy('cad_classes.nome_classe')->asArray()->all(), 'id', 'nome_classe'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Selecione...'],
//        'group' => true, // enable grouping,
//        'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
//        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align: left;', 'font' => 'size: 5'],
    ];
    $columns[] = [
        'attribute' => 'referencia',
        'width' => '10%',
//        'value' => function ($model, $key, $index, $widget) {
//            return (($cadClasses = pagamentos\models\CadClasses::findOne($model->id_classe)) !== null) ? $cadClasses->nome_classe : 'Não definido';
//        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(pagamentos\models\FinReferencias::find()
                        ->groupBy('referencia')
                        ->orderBy('referencia')->asArray()->all(), 'referencia', 'referencia'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Selecione...'],
//        'group' => true, // enable grouping,
//        'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
//        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align: left;', 'font' => 'size: 5'],
    ];
    $columns[] = [
        'attribute' => 'valor',
        'width' => '10%',
        'contentOptions' => ['style' => 'text-align: right;'],
        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Valor ou parte'],
        'value' => function ($model) {
            return $model->valor;
        }
    ];
    $columns[] = [
        'attribute' => 'data',
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align: right;'],
        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'dd/mm/aaaa ou parte'],
        'value' => function ($model) {
            return $model->data;
        }
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 ? '' : Html::button(Yii::t('yii', 'Nova {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FinReferenciasController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Nova {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinReferenciasController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => $idGridPJax = Yii::$app->controller->id . '_grid-pjax',
            ]
        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . ' ' . $buttonCreate . '</h3>',
            'type' => 'primary',
            'before' => '',
            'after' => '', //Html::a('<i class="fa fa-repeat"></i> Resetar Grid', ['index'], ['class' => 'btn btn-info']),
            'footer' => $dataProvider->totalCount > $dataProvider->pagination->pageSize ? '' : false,
        ],
        'toolbar' => !$modal ? [
            [
                'content' =>
                $buttonCreate .
                Html::button('<i class="fa fa-sync-alt"></i>', [
                    'class' => 'btn btn-secondary',
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#$idGridPJax'});",
                ]) .
                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                    'class' => 'btn btn-secondary',
                    'title' => Yii::t('yii', 'Remover todos os filtros'),
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>

<?php
$js = <<<JS
    $('#finreferenciassearch-valor').on('input', function () {
        var txt = $(this).val();
        $(this).val(txt.replace(",", "."));
    });
    
JS;
$this->registerJs($js);
?>
