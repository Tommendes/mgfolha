<?php

use yii\helpers\Html;
use pagamentos\controllers\FinSfuncionalController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadServidores;
use pagamentos\models\FinSfuncional;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncional */

$servidor = FinSfuncional::getCadServidor($id_cad_servidores);
$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinSfuncionalController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinSfuncionalController::CLASS_VERB_NAME_PL), 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
$cadServidor = CadServidores::findOne($model->id_cad_servidores);
?>
<div class="fin-sfuncional-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
