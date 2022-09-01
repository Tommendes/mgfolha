<?php

use yii\helpers\Url;

$url = Url::home(true) . 'user/boas-vindas?r=' . $model->auth_key;
?>
<div class="welcome-user">
    <h2>Olá <?= $model->username ?>, tudo bem?</h2>
    <h2>Estamos quase lá</h2>
    <p>Mas antes de continuar, você deve copiar o código a seguir e 
        colar no campo devido na página de cadastro do sistema.</p>
    >>> <strong><?= strrev(substr($model->auth_key, 5, 8)) ?></strong> <<<
    <p>Caso você tenha fechado seu navegador de internet, 
        poderá voltar à pagina clicando <a href="<?= $url ?>">aqui</a> ou copiando e colando o 
        endereço abaixo na barra de endereços de seu navegador de internet.</p>
    >>> <strong><?= $url ?></strong> <<<
    <p>Seja bem-vindo e conte com a gente para o que precisar.</p>
    <p>Está com dúvidas? Ligue pra gente!</p>
    <h2>+55 82 9 8149 90 24(Vivo)</h2>
    <h2></h2>
    <p><em><strong><?= Yii::$app->name ?></em></strong>.</p>
</div>
