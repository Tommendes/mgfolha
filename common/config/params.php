<?php

$email = 'suporte@mgfolha.com.br';
$admEmail = 'contato@tommendes.com.br';
$emailSuporte = 'suporte@mgfolha.com.br';
$startDate = 2001;
$years = date('Y') - $startDate + 1;
return [
    'bsVersion' => '4.x', // configuração global do Bootstrap 4.x para todas as extensões Krajee
    'adminEmail' => $admEmail,
    'supportEmail' => $emailSuporte,
    'noreplyEmail' => $email,
    // tamanho máximo do arquivo para upload de imagens = 2mb
    'uploadImageMaxSize' => '2048',
    // tamanho máximo do arquivo para upload de arquivos em geral = 2mb
    'uploadFileMaxSize' => '1024',
    'user.rememberMeDuration' => 3600 * 12 * 1, // doze horas   
    'user.passwordResetTokenExpire' => 3600, // uma hora
    // armazena dados carregados de BD.user_options
    'usuarioOpcoes' => [],
    // parametros da aplicação
    'application-name' => 'MG Sistemas',
    'application-description' => "Há $years anos a Mega Assessoria e Tecnologia Ltda atua no desenvolvimento de softwares e na assessoria à gestão pública",
    'application-object-description' => 'Descomplicar o controle e gestão de tributos, patrimônio, remuneração, pessoas, folha de pagamento e consignados',
    'application-keywords' => '',
    'application-dev' => 'Solisyon Smart Apps',
    'telContactNumber' => '+55 82 3424 1153',
    // armazena em parâmetro a mensagem de erro para folha fechada
    'mmf' => 'Você está trabalhando numa folha fechada. <strong style="font-size: 20px;"><em>Nenhuma edição de servidores ou financeiro será salva.</em></strong>',
    'mmf2' => '<strong>Não são permitidas edições de servidores em folhas fechadas</strong>',
];

