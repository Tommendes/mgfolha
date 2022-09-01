<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$eventoUrl = Url::base(true) . '/eventos/' . $compose['evento'];
?>
Olá <?= $compose['destinatario'] ?>, tudo bem?
Foi registrada uma exceção no <?= Yii::$app->name ?>
Segue uma descrição do erro para analise URGENTE
-> Requisição que gerou a exceção: <?= $compose['url'] ?>
-> Evento: <?= $eventoUrl ?></a>
-> Mensagem de exceção: <?= $compose['erro'] ?>

Favor verificar urgente
Está com dúvidas? Ligue pra gente!
+55 82 9 8149 90 24(Vivo)

<?= Yii::$app->name ?>
