<?php

use yii\helpers\Html;
use pagamentos\controllers\FinParametrosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinParametros */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinParametrosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinParametrosController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-parametros-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
