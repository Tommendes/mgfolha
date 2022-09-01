<?php

use yii\helpers\Html;
use app\controllers\LocalParamsController;

/* @var $this yii\web\View */
/* @var $model app\models\LocalParams */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => LocalParamsController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Local SisParams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-params-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
