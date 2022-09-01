<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSrecadastroController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadServidores;
use pagamentos\models\CadSrecadastro;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSrecadastro */


$cadServidor = CadServidores::findOne($model->id_cad_servidores);
$servidor = CadSrecadastro::getCadServidor($model->id_cad_servidores);
$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => strtolower(CadSrecadastroController::CLASS_VERB_NAME)]);
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', (CadSrecadastroController::CLASS_VERB_NAME_PL)) . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-srecadastro-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
