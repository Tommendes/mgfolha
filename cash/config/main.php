<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'mgfolha',
    'name' => 'Cash', 
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'cash\controllers',
    'aliases' => [
        '@common_uploads_root' => __DIR__ . '/../../assets/_arquivos/uploads',
        '@common_uploads_url' => '../assets/_arquivos/uploads',        
        '@uploads_root' => __DIR__ . '/../assets_b/_arquivos/uploads',
        '@uploads_url' => '../cash/assets_b/_arquivos/uploads',
    ],
//    'aliases' => [
//        '@bower' => '@vendor/bower-asset',
//        '@npm' => '@vendor/npm-asset',
//        '@self_update' => '@vendor/../supd.upd',
//        '@kvgrid' => '@vendor/kartik-v/yii2-grid',
//        '@kvdrp' => '@vendor/kartik-v/yii2-date-range',
//        '@imgs_url' => '../assets/_arquivos/imgs',
//        '@imgs_apres_url' => '../assets/_arquivos/imgs/apresentacao',
//        '@uploads_root' => __DIR__ . '/../../pagamentos/assets_b/_arquivos/uploads',
//        '@assets_cash' => 'assets_b',
//        '@assets_base' => '../assets',
//    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-cash',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-cash', 'httpOnly' => true],
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
