<?php

use kartik\helpers\Html;
use frontend\controllers\SisReviewsController;

/* @var $this yii\web\View */
/* @var $model common\models\SisReviews */

$this->title = Yii::t('yii', 'Update {modelClass}: {ref}', [
            'modelClass' => SisReviewsController::CLASS_VERB_NAME,
            'ref' => $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', SisReviewsController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', SisReviewsController::CLASS_VERB_NAME) . ' ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Updating');
?>
<div class="sis-reviews-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
