<?php

use yii\helpers\Url;

$url = Url::home(true) . 'user/boas-vindas?r=' . $model->auth_key;
?>
Olá <?= $model->username ?>, tudo bem?
Estamos quase lá
Mas antes de continuar, você deve copiar o código a seguir e colar no campo devido na página de cadastro do sistema.
>>> <?= strrev(substr($model->auth_key, 5, 8)) ?> <<<
Caso você tenha fechado seu navegador de internet, poderá voltar clicando ou copiando e colando o endereço abaixo na barra de endereços de seu navegador de internet.
>>> <?= $url ?> <<<
Seja bem-vindo e conte com a gente para o que precisar.
Está com dúvidas? Ligue pra gente!
+55 82 9 8149 90 24(Vivo)

<?= Yii::$app->name ?>.