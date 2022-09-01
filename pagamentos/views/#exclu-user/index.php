<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\UserController;
use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = UserController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
$idGridPJax = Yii::$app->controller->id . '_grid-pjax';
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{update} {delete} {duplicate}',
//        'contentOptions' => ['style' => ''], 
        'width' => '90px',
        'buttons' => [
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->gestor < 1 ? '' : Html::button('<span class="fa fa-pen-square"></span>', [
                            'value' => Url::to(['user/' . $model->slug . '?modal=1&mv=edit']),
                            'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => UserController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link'
                ]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'user/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->gestor < 1 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
//                $url = Url::home(true) . 'user/dpl/' . $model->slug;
//                return Yii::$app->user->identity->gestor < 1 ? '' : Html::a(
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
//    $columns[] = [
//        'attribute' => 'id',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->id;
//        }
//    ];
    $columns[] = [
        'attribute' => 'username',
        //'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->username;
        }
    ];
//    $columns[] = [
//        'attribute' => 'hash',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->hash;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'auth_key',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->auth_key;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'password_hash',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->password_hash;
//        }
//    ];
    //$columns[] = [
    //'attribute' => 'password_reset_token',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->password_reset_token;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'github',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->github;
    //}
    //];
    $columns[] = [
        'attribute' => 'email',
        'format' => ['email'],
//    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->email;
        }
    ];
    $columns[] = [
        'attribute' => 'tel_contato',
//    'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->tel_contato;
        }
    ];
    //$columns[] = [
    //'attribute' => 'slug',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->slug;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'status',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->status;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'dominio',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->dominio;
    //}
    //];
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
    //$columns[] = [
    //'attribute' => 'updated_at',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->updated_at;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'administrador',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->administrador;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'gestor',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->gestor;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'usuarios',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->usuarios;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cadastros',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cadastros;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'folha',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->folha;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'financeiro',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->financeiro;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'parametros',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->parametros;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = (Yii::$app->user->identity->gestor >= 1 ? PopoverX::widget([
                'header' => 'Insira o código do usuário',
                'type' => PopoverX::TYPE_SUCCESS,
                'placement' => PopoverX::ALIGN_BOTTOM_LEFT,
                'content' => $this->render('_search', ['model' => $searchModelNewUser]),
                'toggleButton' => [
                    'label' => Html::icon('plus') . ' ' . Yii::t('yii', 'Create record {modelClass}', [
                        'modelClass' => UserController::CLASS_VERB_NAME,
                    ]),
                    'class' => 'btn btn-success',
                    'title' => Yii::t('yii', 'Search'),
                    'aria-label' => Yii::t('yii', 'Search'),
                ],
            ]) . ' ' : '');

    $dataProvider = strpos($_SERVER['REQUEST_URI'], 'UserSearchNewUser') > 0 ? $dataProviderNewUser : $dataProvider;
    $filterModel = strpos($_SERVER['REQUEST_URI'], 'UserSearchNewUser') > 0 ? null : $searchModel;

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => $idGridPJax,
            ]
        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . '</h3>',
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

