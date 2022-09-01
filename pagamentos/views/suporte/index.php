<?php

use kartik\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use pagamentos\controllers\SuporteController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\SisSuporteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('yii', 'Sis Suportes');
//$this->params['breadcrumbs'][] = $this->title;
$this->title = SuporteController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = SuporteController::CLASS_VERB_NAME;
?>
<div class="<?= SuporteController::CLASS_VERB_NAME ?>-index grid-bg">

    <h1 class="text-center">Artigos do suporte</h1>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'id',
//            'dominio',
            [
                'attribute' => 'grupo',
                'format' => 'raw',
                'filter' => ArrayHelper::map($searchModel->getGrupo(), 'id', 'label'),
                'value' => function ($data) {
                    return $data->getGrupo($data->grupo);
                },
                'contentOptions' => ['style' => 'width: 200px;'],
            ],
//            'slug',
            [
                'attribute' => 'descricao',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Yii::t('yii', $data->descricao, ['app_name' => Yii::$app->name]), ['suporte/artigos/' . $data->slug], ['target' => '_blank']);
                }
            ],
        // 'texto:ntext',
        // 'status',
        // 'evento',
        // 'created_at',
        // 'updated_at',
        //['class' => 'yii\grid\ActionColumn'],
//            [
//                'class' => 'yii\grid\ActionColumn',
////                                              /*'template' => $searchModel->status_params >= 10 ? '{view}' : '{view}{update}{delete}',*/
//                'template' => '{view} {update} {delete} {duplicate}',
//                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
//                                    'title' => Yii::t('yii', 'View'),
//                                    'aria-label' => Yii::t('yii', 'View'),
//                        ]);
//                    },
//                            'update' => function ($url, $model) {
//                        return /* $model->status >= X ? '' : */Html::a(
//                                        '<span class="glyphicon glyphicon-pencil"></span>', $url, [
//                                    'title' => Yii::t('yii', 'Update'),
//                                    'aria-label' => Yii::t('yii', 'Update'),
//                        ]);
//                    },
//                            'delete' => function ($url, $model) {
//                        return /* $model->status >= X ? '' : */Html::a(
//                                        '<span class="glyphicon glyphicon-trash"></span>', $url, [
//                                    'title' => Yii::t('yii', 'Delete'),
//                                    'aria-label' => Yii::t('yii', 'Delete'),
//                                    'data' => [
//                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                                        'method' => 'post',
//                                    ],
//                        ]);
//                    },
//                            'duplicate' => function ($url, $model) {
//                        return Html::a(
//                                        '<span class="glyphicon glyphicon-duplicate"></span>', ['duplicate', 'id' => $model->id], [
//                                    'title' => Yii::t('yii', 'Duplicate'),
//                                    'aria-label' => Yii::t('yii', 'Duplicate'),
//                                    'data' => [
//                                        'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
//                                        'method' => 'post',
//                                    ],
//                        ]);
//                    },
//                        ],
//                    ],
        ],
    ]);
    ?>
</div>