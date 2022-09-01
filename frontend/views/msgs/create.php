<?php

use yii\helpers\Html;
use frontend\controllers\MsgsController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Msgs */

$this->title = Yii::t('yii', 'Createa {modelClass}', [
            'modelClass' => strtolower(MsgsController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msgs-create">

    <?php
    if (Yii::$app->controller->id === 'msgs' &&
            Yii::$app->controller->action->id === 'create') {
        ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php }
    ?>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
