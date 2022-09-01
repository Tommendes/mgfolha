<?php

use yii\helpers\Html;
use pagamentos\controllers\CadServidoresController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadServidores */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadServidoresController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadServidoresController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-servidores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
