<?php

use yii\helpers\Url;

$url = Url::home(true) . 'contato';
?>
<div class="welcome-user">
    <h2>Olá <?= $model->username ?>, seja bem vindo!</h2>
    <h2>Estamos felizes de ter você conosco</h2>
    <p>Mas antes de continuar, você deve informar ao seu gestor o código <strong><?= $model->hash ?></strong> para que ele libere seu acesso.</p>
    <p>Seja bem-vindo e conte com a gente para o que precisar</p>
    <p>Está com dúvidas? <a href="<?= $url ?>">Contate-nos</a></p>

    <p><em><strong>Atenciosamente,</strong></em></p>
    <p><em><strong><?= Yii::$app->name ?></strong></em>.</p>
    <br/>
    <p>*Por favor não responda a essa mensagem. Essa caixa de emails não é verificada. Se precisar enviar uma mensagem utilize nosso formulário em: <a href="mailto:contato@magov.com.br">contato@magov.com.br</a></p>
</div>
