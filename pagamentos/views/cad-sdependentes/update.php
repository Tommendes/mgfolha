<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSdependentesController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadServidores;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSdependentes */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadSdependentesController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadSdependentesController::CLASS_VERB_NAME_PL), 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
$cadServidor = CadServidores::findOne($model->id_cad_servidores);
?>
<div class="cad-sdependentes-update">

    <h1><?= Html::encode($this->title) . ' de ' . AppController::tratar_nome($cadServidor->nome) . ' (' . $cadServidor->matricula . ')' ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
