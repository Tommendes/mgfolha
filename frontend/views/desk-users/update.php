<?php

use yii\helpers\Html;
use frontend\controllers\DeskUsersController;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeskUsers */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(DeskUsersController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => 'Desk Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="desk-users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
