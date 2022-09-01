<?php

use yii\helpers\Html;
use pagamentos\controllers\FinEventosbaseController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventosbase */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinEventosbaseController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinEventosbaseController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-eventosbase-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
