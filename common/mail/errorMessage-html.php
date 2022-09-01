<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$eventoUrl = Url::base(true) . '/eventos/' . $compose['evento'];
?>
<div class="error-message">

    <h2>Olá <?= Html::encode($compose['destinatario']) ?>, tudo bem?</h2>
    <p>Foi registrada uma exceção no <?= Yii::$app->name ?></p>
    <p>Segue uma descrição do erro para analise URGENTE</p>

    <ul>
        <li>Requisição que gerou a exceção: <a href="<?= $compose['url'] ?>"><?= $compose['url'] ?></a></li>
        <li>Evento: <a href="<?= $eventoUrl ?>"><?= $eventoUrl ?></a></li>
        <li>Mensagem de exceção: <?= $compose['erro'] ?></li>
    </ul>

    <br>
    <br>
    <p>Favor verificar urgente</p>
    <h2>+55 82 9 8149 90 24(Vivo)</h2>
    <h2></h2>
    <p><em><strong> <?= Yii::$app->name ?> </strong></em></p>
</div>
