<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSdependentesController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadServidores;
use pagamentos\controllers\AppController;
use pagamentos\models\CadSdependentes;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSdependentes */


$servidor = CadSdependentes::getCadServidor($model->id_cad_servidores);
$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadSdependentesController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadSdependentesController::CLASS_VERB_NAME_PL), 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
$cadServidor = CadServidores::findOne($model->id_cad_servidores);
?>
<div class="cad-sdependentes-create">

    <h1><?= Html::encode($this->title) . ' de ' . AppController::tratar_nome($cadServidor->nome) . ' (' . $cadServidor->matricula . ')' ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
