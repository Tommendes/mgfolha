<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fhX0yiPG8NH0_tf_TrDHd9ENQcE1JXi4',
        ],
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '6LccJMsUAAAAAOOiGdjR7Sype_cOLcnZu7N6-Oki',
            'secretV2' => '6LccJMsUAAAAAIJKOKqipkxZsyKlZyXmVwLImVBP',
//            'siteKeyV3' => 'your siteKey v3',
//            'secretV3' => 'your secret key v3',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
