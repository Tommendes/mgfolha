<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use frontend\controllers\FolhaController;
use yii\helpers\ArrayHelper;
use frontend\models\OrgaoUa;
use common\models\Bancos;
use frontend\models\Folha;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FolhaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FolhaController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="cad-bconvenios-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['cad-bconvenios/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FolhaController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->cadastros < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['cad-bconvenios/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FolhaController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-bconvenios/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->cadastros < 4 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
//            'duplicate' => function ($url, $model) {
//                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                $url = Url::home(true) . 'cad-bconvenios/dpl/' . $model->slug;
//                return Yii::$app->user->identity->cadastros < 2 ? '' : Html::a(
//                                '<span class="fa fa-clone"></span>', '#', [
//                            'title' => Yii::t('yii', 'Duplicate') . ' item',
//                            'aria-label' => Yii::t('yii', 'Duplicate'),
//                            'onclick' => "
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
//                ]);
//            },
        ],
    ];

    $columns[] = [
        'attribute' => 'id_cad_bancos',
        'format' => 'raw',
        'value' => function($model) {
            return Bancos::getBanco($model->id_cad_bancos);
//            return ArrayHelper::map(Folha::findAll(), $from, $to);
        },
        'filter' => Bancos::getBancos(),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 250px;text-align: left;'],
    ];
    $columns[] = [
        'attribute' => 'id_orgao_ua',
        'format' => 'raw',
        'value' => function($model) {
            $ua = frontend\models\OrgaoUa::findOne($model->id_orgao_ua);
            if (!$ua == null) {
                $retorno = $ua->nome;
            } else {
                $retorno = 'Não definido';
            }
            return $retorno;
        },
        'filter' => ArrayHelper::map(OrgaoUa::find()
                        ->select([
                            'id' => 'orgao_ua.id',
                            'nome' => 'concat(orgao_ua.nome, "(", orgao_ua.codigo, ")")'
                        ])
                        ->join('join', 'orgao', 'orgao.id = orgao_ua.id_orgao')
                        ->where([
                            'orgao_ua.status' => OrgaoUa::STATUS_ATIVO,
                            'orgao.dominio' => Yii::$app->user->identity->dominio
                        ])->asArray()->all(), 'id', 'nome'),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 250px;text-align: left;',],
    ];
    $columns[] = [
        'attribute' => 'convenio',
        //'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->convenio;
        }
    ];
    $columns[] = [
        'attribute' => 'cod_compromisso',
        //'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->cod_compromisso;
        }
    ];
    //$columns[] = [
    //'attribute' => 'agencia',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->agencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'a_digito',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->a_digito;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'conta',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->conta;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'c_digito',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->c_digito;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'convenio_nr',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->convenio_nr;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'tipo_servico',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->tipo_servico;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FolhaController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FolhaController::CLASS_VERB_NAME))
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
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#$idGridPJax'});",
                ]) .
                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Remover todos os filtros'),
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
