<?php

$components = [
    'components' => [
        'db' => [
            'class' => 'common\components\ConnectionExtra',
            'dsn' => 'mysql:host=http://162.214.208.90/;dbname=mgfolha_folha',
            'dbname' => 'mgfolha_folha',
        ],
        // Conexões adicionais de bancos de dados sendo um para cada cliente
        // Os nomes abaixo correspondem à coluna cliente em db->user->cliente 
        // 'olho_dagua_grande' => [
        //     'class' => 'common\components\ConnectionExtra',
        //     'dsn' => 'mysql:host=br766.hostgator.com.br;dbname=mgfolha_olhodaguagrande',
        //     'dbname' => 'mgfolha_olhodaguagrande',
        // ],
        // 'igaci' => [
        //     'class' => 'common\components\ConnectionExtra',
        //     'dsn' => 'mysql:host=br766.hostgator.com.br;dbname=mgfolha_folhaigaci',
        //     'dbname' => 'tommen22_folhaigaci',
        // ],
        'cacimbinhas_antigo' => [
            'class' => 'common\components\ConnectionExtra',
            'dsn' => 'mysql:host=217.196.61.20;dbname=mgfolha_folhacacimbinhas',
            'dbname' => 'mgfolha_folhacacimbinhas',
        ],
        //        'ftp' => [ 
        //            'class' => 'yii\ftp\Connection',
        //            'username_ftp' => 'lynkos', 
        //            'password' => 'a5a54dcd93',
        //        ],
        'mobileDetect' => [
            'class' => '\skeeks\yii2\mobiledetect\MobileDetect'
        ],
        'clientes' => [
            'class' => 'common\components\Clientes'
        ],
        'mailer' => [
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //            'useFileTransport' => true,
            'viewPath' => '@common/mail',
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.titan.email',
                'username' => 'suporte@mgfolha.com.br',
                'password' => 'x8gPX6a4cQxxmvm',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8', 
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                //                'google' => [
                //                    'class' => 'yii\authclient\clients\Google',
                //                    'clientId' => '???',
                //                    'clientSecret' => '???',
                //                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '320861258369440',
                    'clientSecret' => 'ddefdcd996b27431e4b0fc54bd3fc980',
                    'attributeNames' => ['email', 'id', 'cover', 'name', 'first_name', 'last_name', 'age_range', 'link', 'gender', 'locale', 'picture', 'timezone', 'updated_time', 'verified'],
                ],
                // etc.
            ],
        ],
    ],
];
return $components;
