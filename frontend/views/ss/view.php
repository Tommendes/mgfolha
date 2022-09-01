<?php

use kartik\detail\DetailView;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisShortener */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Sis Shorteners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sis-shortener-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?php
    echo DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'], //DetailView::MODE_VIEW,
        'buttons1' => /* $statusCancelado === $model->status ? '' : */isset(Yii::$app->user->identity->gestor) && Yii::$app->user->identity->gestor >= 1 ? '{update} {delete}' : '',
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['delete', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => 'Url Curta',
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
//            'id',
//            'url:url',
            [
                'attribute' => 'shortened',
                'format' => 'raw',
                'value' => '<a href="' . 'http://sh.mgfolha.com.br/' . $model->shortened . '">sh.mgfolha.com.br/' . $model->shortened . '</a>',
//                'type' => DetailView::INPUT_HTML5,
                'label' => 'Url curta',
            ],
        ],
    ]);
    ?>

</div>
