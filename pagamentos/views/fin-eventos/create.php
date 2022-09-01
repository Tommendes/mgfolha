<?php

use yii\helpers\Html;
use pagamentos\controllers\FinEventosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventos */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinEventosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin SisEvents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-eventos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
