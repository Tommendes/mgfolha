<?php

return [
    'id' => 'mgsistemas',
    'name' => 'MG Sistemas',
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@self_update' => '@vendor/../supd.upd',
        '@kvgrid' => '@vendor/kartik-v/yii2-grid',
        '@kvdrp' => '@vendor/kartik-v/yii2-date-range',
        '@imgs_root' => __DIR__ . '/../../assets/_arquivos/imgs',
        '@imgs_url' => '../assets/_arquivos/imgs',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'file-input*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__FILE__) . '/../vendor/2amigos/yii2-file-input-widget/src/messages/',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@common/messages",
                    'sourceLanguage' => 'pt_BR', // put your language here
                    'fileMap' => [
                        'yii' => 'yii.php',
                    ]
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'AIzaSyDqV4ca0xMBabOQ0TtbDA_dhVkiyDSngsc',
                        'language' => 'pt_BR',
                        'version' => '3.1.18'
                    ]
                ]
            ]
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => include 'urls.php',
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            'downloadAction' => 'gridview/export/download',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true
            ]
        ]
    ],
];
