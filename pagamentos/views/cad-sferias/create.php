<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSferiasController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadServidores;
use pagamentos\controllers\AppController;
use pagamentos\models\CadSferias;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSferias */


$cadServidor = CadServidores::findOne($model->id_cad_servidores);
$servidor = CadSferias::getCadServidor($model->id_cad_servidores);
$this->title = Yii::t('yii', 'Nova {modelClass}', ['modelClass' => strtolower(CadSferiasController::CLASS_VERB_NAME)]);
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', (CadSferiasController::CLASS_VERB_NAME_PL)) . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-sferias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
