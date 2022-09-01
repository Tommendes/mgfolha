<?php

use yii\helpers\Html;
use pagamentos\controllers\FinReferenciasController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinReferencias */

$this->title = Yii::t('yii', 'Nova {modelClass}', ['modelClass' => FinReferenciasController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinReferenciasController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-referencias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
