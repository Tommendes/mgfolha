<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSferiasController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadServidores;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSferias */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadSferiasController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadSferiasController::CLASS_VERB_NAME_PL), 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Editando');
?>
<div class="cad-sferias-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
