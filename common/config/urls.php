<?php

/*
 * 
 */
$actions = 'r|z|c|a-c|s-c|s|u|p|dlt|dpl|index|auth|captcha|contato'
        . '|upl|send-email|replicate|preview|report|reviews|lists'
        . '|termos-uso|politica-privacidade|apresentacao|set-geo'
        . '|artigos|reviews|get-deskversao|upload|boas-vindas|script'
        . '|photo|short|print|sobre|holerite-on-line|servidor-by-token';

// a-c acronimo para after-create ou altera-campo 
// s-c acronimo para self-create
// s acronimo para situacao

return [
    /* Os seguintes são redirecionamentos diretos que se sobrepõem aos 'controller actions' */
    'login' => 'site/login',
    'signup' => 'site/signup',
    'logout' => 'site/logout',
    'upload' => 'site/upload',
    'sobre' => 'site/about',
    'holerite-on-line' => 'site/holerite-on-line',
    'token' => 'site/servidor-by-token',
    'reviews' => 'sis-reviews/reviews',
    'request-password-reset' => 'site/request-password-reset',
    'reset-password' => 'site/reset-password',
    'contato' => 'site/contato',
    'auth' => 'site/auth',
    'termos-uso' => 'site/termos-uso',
    'politica-privacidade' => 'site/politica-privacidade',
    'apresentacao' => 'site/apresentacao',
    /* Fim dos redirecionamentos */
    '<controller:\w+>' => '<controller>',
    '<controller:>/<action:(' . $actions . ')>/<id:[-a-zA-Z0-9]+>' => '<controller>/<action>',
    '<controller:>/<action:(' . $actions . ')>' => '<controller>/<action>',
    '<controller:>/<id:[-a-zA-Z0-9]+>' => '<controller>/view',
];
