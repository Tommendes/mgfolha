<?php

use yii\helpers\Url;

$url = Url::home(true) . 'user';
?>

Novo usuário registrado no <?= Yii::$app->params['application-name'] ?>

Nome: <?= $model->username ?>
Email: <?= $model->email ?>
Código de liberação: <?= $model->hash ?>

Parabéns!!!

Acesse o painel de usuários para maiores informações <a href="<?= $url ?>">clicando aqui</a>


Atenciosamente,

<?= Yii::$app->name ?>.

*Por favor não responda a essa mensagem. Essa caixa de emails não é verificada. Se precisar enviar uma mensagem utilize nosso formulário em: contato@magov.com.br