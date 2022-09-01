<?php

use kartik\helpers\Html;
use frontend\controllers\UserController;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('yii', 'Update {modelClass}: {ref}', [
            'modelClass' => UserController::CLASS_VERB_NAME,
            'ref' => $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', UserController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'See {modelClass} {ref}', [
        'modelClass' => UserController::CLASS_VERB_NAME,
        'ref' => $model->username,
    ]), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Updating');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (isset($p) && $p) {
        $form = '_form_periodo';
    } else {
        $form = '_form';
    }

    echo $this->render($form, [
        'model' => $model,
    ])
    ?>

</div>
