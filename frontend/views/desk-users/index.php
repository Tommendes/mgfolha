<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use frontend\controllers\DeskUsersController;
use frontend\models\DeskUsers;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DeskUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = DeskUsersController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="desk-users-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  
    ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view}{update}{delete}', //  {duplicate}
        'width' => '120px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                    'value' => Url::to(['desk-users/' . $model->id . '?modal=1&mv=0']),
                    'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => DeskUsersController::CLASS_VERB_NAME]),
                    'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::button('<span class="fa fa-pen-square"></span>', [
                    'value' => Url::to(['desk-users/u/' . $model->id . '?modal=1']),
                    'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => DeskUsersController::CLASS_VERB_NAME]),
                    'class' => 'showModalButton button-as-link'
                ]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'desk-users/dlt/' . $model->id;
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
                                                            $.pjax.reload({container: '#desk-users_grid-pjax'});
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
            //                $url = Url::home(true) . 'desk-users/dpl/' . $model->id;
            //                return Yii::$app->user->identity->administrador < 1 ? '' : Html::a(
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

    //    $columns[] = ['class' => 'kartik\grid\SerialColumn'];

    // $columns[] = [
    //     'attribute' => 'id',
    //     //'format' => ['decimal', 2],
    //     //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //     'value' => function ($model) {
    //         return $model->id;
    //     }
    // ];
    //    $columns[] = [
    //        'attribute' => 'status',
    //        //'format' => ['decimal', 2],
    //        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //        'value' => function ($model) {
    //            return $model->status;
    //        }
    //    ];
    $columns[] = [
        'attribute' => 'status_desk',
        //'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'filter' => \frontend\models\DeskUsers::getStatusDesk(),
        'value' => function ($model) {
            return $model->getDeskUserStatus($model->status_desk);
        }
    ];
    // $columns[] = [
    //     'attribute' => 'dll_cod',
    //     //'format' => ['decimal', 2],
    //     //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //     'value' => function ($model) {
    //         return $model->dll_cod;
    //     }
    // ];
    $columns[] = [
        'attribute' => 'dll_cnpj',
        //'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->dll_cnpj;
        }
    ];
    $columns[] = [
        'attribute' => 'dll_nome',
        //    'format' => ['decimal', 2],
        //    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->dll_nome;
        }
    ];
    //$columns[] = [
    //'attribute' => 'cli_cnpj',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cli_cnpj;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cli_nome',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cli_nome;
    //}
    //];
    $columns[] = [
        'attribute' => 'cli_nome_comput',
        //    'format' => ['decimal', 2],
        //    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->cli_nome_comput;
        }
    ];
    $columns[] = [
        'attribute' => 'cli_nome_user',
        //    'format' => ['decimal', 2],
        //    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->cli_nome_user;
        }
    ];
    $columns[] = [
        'attribute' => 'versao_desk',
        //    'format' => ['decimal', 2],
        //    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->versao_desk;
        }
    ];
    $columns[] = [
        'attribute' => 'e_social',
        //    'format' => ['decimal', 2],
        //    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->e_social;
        }
    ];
    //$columns[] = [
    //'attribute' => 'evento',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->evento;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'created_at',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->created_at;
    //}
    //];
    $columns[] = [
        'attribute' => 'updated_at',
        'label' => 'Último acesso',
        //        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return date('d-m-Y H:i:s', $model->updated_at);
        }
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
        'modelClass' => strtolower(Html::encode(DeskUsersController::CLASS_VERB_NAME))
    ]), [
        'id' => 'btn_n_' . Yii::$app->controller->id,
        'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
        'title' => Yii::t('yii', 'Create {modelClass}', [
            'modelClass' => strtolower(Html::encode(DeskUsersController::CLASS_VERB_NAME))
        ]),
        'class' => 'showModalButton btn btn-warning'
    ]);

    $clientes = \frontend\models\DeskUsers::find()
        ->where('dll_nome is not null and dll_cod != "031F"')
        ->groupBy('dll_nome, status_desk')
        ->orderBy('dll_nome, status_desk')
        ->all();
    $total = 0;
    $p = '<div class=row">';
    foreach ($clientes as $cliente) {
        $status = DeskUsers::getDeskUserStatus($cliente->status_desk);
        $p .= '<div class="col-md-4">' . ($total + 1) . ': ' . strtoupper(substr($cliente->dll_nome, 0, 1)) . strtolower(substr($cliente->dll_nome, 1)) . "($status)" . '</div>';
        $total++;
    }
    $p .= '</div>';
    $ttip = Html::tag('spam', '(' . $total . ' clientes)', ['data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Clique para ver todos', 'style' => 'color: #fff;',]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => $idGridPJax = 'desk-users_grid-pjax',
            ]
        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . " 
                <button type='button' class='button-as-link' data-toggle='modal' data-target='#clientesModal'>$ttip</button> " . $buttonCreate . '</h3>',
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
            Yii::$app->user->identity->administrador >= 1 ? '{export}' : '',
            '{toggleData}',
        ] : null,
    ]);
    ?>
</div>

<div class="modal fade" id="clientesModal" tabindex="-1" role="dialog" aria-labelledby="clientesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientesModalLabel">Clientes desktop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= Html::tag('p', $p) ?>
            </div>
        </div>
    </div>
</div>