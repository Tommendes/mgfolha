<?php

use yii\helpers\Url;

$url = Url::home(true) . 'user';
?>
<div class="welcome-user">
    <h2>Novo usuário <?= $model->username ? 'web' : 'desktop'?> registrado no <?= Yii::$app->params['application-name'] ?></h2>
    <p>Nome: <?= $model->username ? $model->username : $model->cli_nome_comput ?></p>
    <p>Email: <?= $model->email ? $model->email : $model->cli_nome ?></p>
    <?php if ($model->hash) { ?>
        <p>Código de liberação: <?= $model->hash ?></p>
    <?php } ?>
    <p>Parabéns!!!</p>
    <p>Acesse o painel de usuários <?= $model->username ? 'web' : 'desktop'?> para maiores informações <a href="<?= $url ?>">clicando aqui</a></p>

    <p><em><strong>Atenciosamente,</strong></em></p>
    <p><em><strong><?= Yii::$app->name ?></em></strong>.</p>
    <br />
    <p>*Por favor não responda a essa mensagem. Essa caixa de emails não é verificada. Se precisar enviar uma mensagem utilize nosso formulário em: <a href="mailto:contato@magov.com.br">contato@magov.com.br</a></p>
</div>