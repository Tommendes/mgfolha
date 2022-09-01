<?php

use yii\helpers\Url;
use kartik\helpers\Html;

$url = Url::home(true) . 'contato';
?>
<div class="welcome-user">
    <?php if (!$gestor) { ?>
        <h2>Olá <?= $model->username ?>!</h2>
        <h2>Seu perfil de usuário acaba de ser alterado.</h2>
        <p>Por favor acesse seu perfil em <?= Url::to(['/user/' . $model->slug], true) ?> e confira seus dados.</p>
    <?php } else { ?>
        <h2>Olá <?= $gestor->username ?>!</h2>
        <h2>Este email confirma a alteração de perfil de usuário de <?= $model->username ?></h2>
    <?php } ?>
    <p>Está com dúvidas? <a href="<?= $url ?>">Contate-nos</a></p>

    <p><em><strong>Atenciosamente,</strong></em></p>
    <p><em><strong><?= Yii::$app->name ?></strong></em>.</p>
    <br/>
    <p>*Por favor não responda a essa mensagem. Essa caixa de emails não é verificada. Se precisar enviar uma mensagem utilize nosso formulário em: <a href="mailto:contato@magov.com.br">contato@magov.com.br</a></p>
</div>
