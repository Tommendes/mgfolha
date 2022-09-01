<?php

use yii\helpers\Html;
use pagamentos\controllers\FinBasefixaController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinBasefixa */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinBasefixaController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin Basefixas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-basefixa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
