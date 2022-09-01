<?php

use yii\helpers\Html;
use pagamentos\controllers\CadScertidaoController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadScertidao;
use pagamentos\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadScertidao */

$servidor = CadScertidao::getCadServidor($id_cad_servidores);
$this->title = Yii::t('yii', 'Nova {modelClass}', ['modelClass' => CadScertidaoController::CLASS_VERB_NAME . ' do servidor']);
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadScertidaoController::CLASS_VERB_NAME_PL) . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-scertidao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
