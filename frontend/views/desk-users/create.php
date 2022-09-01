<?php

use yii\helpers\Html;
use frontend\controllers\DeskUsersController;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeskUsers */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => DeskUsersController::CLASS_VERB_NAME]);
$this->title = 'Create Desk Users';
$this->params['breadcrumbs'][] = ['label' => 'Desk Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="desk-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
