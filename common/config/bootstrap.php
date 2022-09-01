<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
// nomes dos clientes em letras minúsculas 
$base_servicos = ['pagamentos', 'cash'];
foreach ($base_servicos as $base_servico) {
    Yii::setAlias("@$base_servico", dirname(dirname(__DIR__)) . "/$base_servico");
}
