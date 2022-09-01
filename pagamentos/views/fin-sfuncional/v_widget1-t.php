<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use pagamentos\controllers\FinSfuncionalController;
use pagamentos\controllers\FinReferenciasController;
use common\models\Listas;
use pagamentos\models\FinRubricas;
use pagamentos\controllers\FinRubricasController;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncional */
?>
<div class="fin-sfuncional-v_widget1">

    <?php
    $referencias = FinReferenciasController::getVctoBasico(
        $model->id_cad_servidores,
        Yii::$app->user->identity->per_ano,
        Yii::$app->user->identity->per_mes,
        Yii::$app->user->identity->per_parcela
    );
    ?>
    <div class="row">
        <div class="col-12">
            <?= Json::encode($referencias) ?>
        </div>
    </div>
</div>