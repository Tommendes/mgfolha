<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'mgfolha',
    'name' => 'Pagamentos', 
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'pagamentos\controllers',
    'aliases' => [
        '@common_uploads_root' => __DIR__ . '/../../assets/_arquivos/uploads',
        '@common_uploads_url' => '../assets/_arquivos/uploads',        
        '@uploads_root' => __DIR__ . '/../assets_b/_arquivos/uploads',
        '@uploads_url' => 'assets_b/_arquivos/uploads',
        '@assets_pagamentos' => 'assets_b',
        '@imgs_apres_url' => '@assets_pagamentos/imgs/apresentacao',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-pagamentos',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-pagamentos', 'httpOnly' => true],
        ],
        'session' => [
            // A sessão é o que faz os aplicativos/serviços compartilhar o mesmo usuário
            'name' => 'folha-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
