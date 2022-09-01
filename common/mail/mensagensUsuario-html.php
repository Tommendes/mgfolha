<?php

use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$url = Yii::$app->urlManager->createAbsoluteUrl(['mensagens/view/', 'id' => $model->id]);
$destinatario = User::findOne($model->id_destinatario);
?>
<div class="password-reset">

    <h2>Olá <?= Html::encode($destinatario->username) ?>, tudo bem?</h2>
    <p>Você recebeu uma mensagem atravéz do <?= Yii::$app->name ?></p>
    <p>Para responder a mensagem, acesse o endereço abaixo:</p>
    <p> <?= Html::a(Html::encode($url), $url) ?></p>
    <br>
    <p>Veja a seguir uma prévia do texto enviado:</p>
    <p> <?= substr($model->mensagem, 0, 80) ?>...</p>
    <br>
    <br>
    <p>Seja sempre bem-vindo e conte com a gente para o que precisar.</p>
    <p>Está com dúvidas? Ligue pra gente!</p>
    <h2>+55 82 9 8149 90 24(Vivo)</h2>
    <h2></h2>
    <p><em><strong> <?= Yii::$app->name ?> </strong></em>.</p>
</div>
