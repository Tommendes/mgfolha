<?php

use yii\helpers\Html;

$url = Yii::$app->urlManager->createAbsoluteUrl(['reviews/']);
$apresentacao = Yii::$app->urlManager->createAbsoluteUrl(['pt/apresentacao']);
?>

Olá <?= Html::encode($user->username) ?>, tudo bem?

O <?= Yii::$app->name ?> foi atualizado
Nova versão do app: <?= $review->versao . '.' . str_pad($review->lancamento, 2, 0, STR_PAD_LEFT) . '.' . str_pad($review->revisao, 3, 0, STR_PAD_LEFT) ?>

Você pode conhecer esta e outras atualizações clicando <?= Html::a(Html::encode('aqui'), $url) ?>
Se preferir poderá copiar e colar o endereço abaixo na barra de endereços de seu navegador
<?= $url ?>

Seja sempre bem-vindo e conte com a gente para o que precisar.

Está com dúvidas? Ligue pra gente ou mande uma mensagem através do formulário de contato!

Atenciosamente,

<?= Yii::$app->params['application-name'] ?>
<?= Yii::$app->params['application-description'] ?>
