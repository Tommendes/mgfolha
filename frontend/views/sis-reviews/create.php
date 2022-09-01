<?php

use kartik\helpers\Html;
use frontend\controllers\SisReviewsController;

/* @var $this yii\web\View */
/* @var $model common\models\SisReviews */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => SisReviewsController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => SisReviewsController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sis-reviews-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
