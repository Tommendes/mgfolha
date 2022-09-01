<?php

use kartik\helpers\Html;
use frontend\controllers\UserController;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => UserController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => UserController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
