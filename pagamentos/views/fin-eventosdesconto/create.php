<?php

use yii\helpers\Html;
use pagamentos\controllers\FinEventosdescontoController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventosdesconto */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinEventosdescontoController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinEventosdescontoController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-eventosdesconto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
