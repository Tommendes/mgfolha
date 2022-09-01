<?php

use yii\helpers\Html;

$url = Yii::$app->urlManager->createAbsoluteUrl(['reviews/']);
$apresentacao = Yii::$app->urlManager->createAbsoluteUrl(['pt/apresentacao']);
?>
<div class="password-reset">

    <h2>Olá <?= Html::encode($user->username) ?>, tudo bem?</h2>
    <p>O <?= Yii::$app->name ?> foi atualizado</p>
    <p>Nova versão do app: <?= $review->versao . '.' . str_pad($review->lancamento, 2, 0, STR_PAD_LEFT) . '.' . str_pad($review->revisao, 3, 0, STR_PAD_LEFT) ?></p>
    <p>Você pode conhecer esta e outras atualizações clicando <?= Html::a(Html::encode('aqui'), $url) ?></p>
    <p>Se preferir poderá copiar e colar o endereço abaixo na barra de endereços de seu navegador</p>
    <p><?= $url ?></p>
<!--    <p>Sempre que quiser poderá recorrer à apresentação do app clicando <?php // echo Html::a(Html::encode('aqui'),$apresentacao)    ?></p>
    <p>Se preferir poderá copiar e colar o endereço abaixo na barra de endereços de seu navegador</p>
    <p><?php // echo $apresentacao    ?></p>-->
    <br>
    <br>
    <p>Seja sempre bem-vindo e conte com a gente para o que precisar.</p>
    <p>Está com dúvidas? Ligue pra gente ou mande uma mensagem através do formulário de contato!</p>
    <br>
    <br>
    <p><em><strong>Atenciosamente,</strong></em></p>
    <p><em><strong><?= Yii::$app->params['application-name'] ?></em></strong>. 
        <?= Yii::$app->params['application-description'] ?>.</p>
</div>
