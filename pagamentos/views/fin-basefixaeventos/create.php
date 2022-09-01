<?php

use yii\helpers\Html;
use pagamentos\controllers\FinBasefixaeventosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinBasefixaeventos */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinBasefixaeventosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin Basefixaeventos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-basefixaeventos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
