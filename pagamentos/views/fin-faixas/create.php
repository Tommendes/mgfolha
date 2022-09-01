<?php

use yii\helpers\Html;
use pagamentos\controllers\FinFaixasController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinFaixas */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinFaixasController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin Faixas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-faixas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
