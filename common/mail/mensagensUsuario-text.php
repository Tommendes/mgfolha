<?php

use common\models\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$url = Yii::$app->urlManager->createAbsoluteUrl(['mensagens/view/', 'id' => $model->id]);
$destinatario = User::findOne($model->id_destinatario);
?>

Olá <?= $destinatario->username ?>, tudo bem?
Você recebeu uma mensagem atravéz do <?= Yii::$app->name ?> 
Para responder a mensagem, acesse o endereço abaixo:  
<?= $url ?> 

Veja a seguir uma prévia do texto enviado:  
<?= substr($model->mensagem, 0, 80) ?>...


Seja sempre bem-vindo e conte com a gente para o que precisar.z
Está com dúvidas? Ligue pra gente!
+55 82 9 8149 90 24(Vivo)

<?= Yii::$app->name ?>
