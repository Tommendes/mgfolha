<?php

use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;
use kartik\bs4dropdown\Dropdown;

$menuItemsL = [
    ["label" => '<i class="fa fa-pencil-square-o"></i>' . "Cadastros", "active" => (Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'empresa' || Yii::$app->controller->id == 'eventos'), "url" => "#",
        "items" => [
            ["label" => "Usuários", "url" => ["/user"],
                "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1],
            ["label" => "Empresa", "url" => ["/empresa"],
                "visible" => !Yii::$app->user->isGuest && (Yii::$app->user->identity->financeiro >= 3 || Yii::$app->user->identity->gestor >= 1)],
            ["label" => "Eventos do sistema", "url" => (!Yii::$app->mobileDetect->isMobile() &&
                !Yii::$app->mobileDetect->isTablet()) ? "#" : ["/eventos"],
                "items" => [
                    ["label" => "Todos", "url" => ["/eventos"]],
                    ["label" => "Hoje", "url" => ["/eventos/?today"]],
                    ["label" => "Esta semana", "url" => ["/eventos/?week"]],
                    ["label" => "Última semana", "url" => ["/eventos/?lastweek"]],
                    ["label" => "Este mês", "url" => ["/eventos/?month"]],
                    ["label" => "Este ano", "url" => ["/eventos/?year"]]
                ],
                "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
            ],
        ], "visible" => !Yii::$app->user->isGuest
    ],
    ["label" => "<i class='fa fa-calendar'></i> " . "Folha", "active" => (Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'empresa' || Yii::$app->controller->id == 'eventos'), "url" => "#", "visible" => !Yii::$app->user->isGuest],
    ["label" => "<i class='fa fa-print'></i> " . "Relatórios", "active" => (Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'empresa' || Yii::$app->controller->id == 'eventos'), "url" => "#", "visible" => !Yii::$app->user->isGuest],
    ["label" => "<i class='fa fa-sign-out'></i> " . "Dados externos", "active" => (Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'empresa' || Yii::$app->controller->id == 'eventos'), "url" => "#",
        "items" => [
            ["label" => "Remessas", "url" => ["/user"],
                "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1],
            ["label" => "Consignações", "url" => ["/empresa"],
                "visible" => !Yii::$app->user->isGuest && (Yii::$app->user->identity->financeiro >= 3 || Yii::$app->user->identity->gestor >= 1)],
            ["label" => "Eventuais", "url" => (!Yii::$app->mobileDetect->isMobile() &&
                !Yii::$app->mobileDetect->isTablet()) ? "#" : ["/eventos"],
                "items" => [
                    ["label" => "Todos", "url" => ["/eventos"]],
                    ["label" => "Hoje", "url" => ["/eventos/?today"]],
                    ["label" => "Esta semana", "url" => ["/eventos/?week"]],
                    ["label" => "Última semana", "url" => ["/eventos/?lastweek"]],
                    ["label" => "Este mês", "url" => ["/eventos/?month"]],
                    ["label" => "Este ano", "url" => ["/eventos/?year"]]
                ],
                "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
            ],
        ], "visible" => !Yii::$app->user->isGuest
    ],
];
$menuItemsR[] = ["label" => "<i class='fa fa-question'></i> " . "Ajuda", "active" => (Yii::$app->controller->id == 'sis-reviews' || Yii::$app->controller->id == 'suporte' || Yii::$app->controller->action->id == 'apresentacao'), "url" => "#",
    "items" => [
        ["label" => "Artigos", "url" => ["/suporte/artigos"]],
        ["label" => "Histórico de Atualizações", "url" => ["/sis-reviews/reviews"]],
        ["label" => "Apresentação", "url" => ["/apresentacao"]],
    ],
];
$menuItemsR[] = ["label" => "<i class='fa fa-cog'></i> " . "Gestão", "active" => (Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'empresa' || Yii::$app->controller->id == 'eventos'), "url" => "#",
    "items" => [
        ["label" => "Usuários", "url" => ["/user"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1],
        ["label" => "Empresa", "url" => ["/empresa"],
            "visible" => !Yii::$app->user->isGuest && (Yii::$app->user->identity->financeiro >= 3 || Yii::$app->user->identity->gestor >= 1)],
        ["label" => "Eventos do sistema", "url" => ["/eventos"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
        ["label" => "Parâmetros", "url" => ["/params"]
            , "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
    ], "visible" => !Yii::$app->user->isGuest
];
$menuItemsR[] = ["label" => "<i class='fa fa-cogs'></i> " . "Administrar", "active" => (Yii::$app->controller->id == 'mensagens-sistema' || Yii::$app->controller->id == 'com-prospeccao' || Yii::$app->controller->id == 'com-prospeccao'), "url" => "#",
    "items" => [
        ["label" => "Usuários Desk", "url" => ["/desk-users"],],
        ["label" => "Artigos", "url" => ["/suporte/adm"],],
        ["label" => "Reviews", "url" => ["/sis-reviews"],],
        ["label" => "Mensagens do sistema", "url" => ["/msgs"]],
    ], "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1
];
if (Yii::$app->user->isGuest) {
    $menuItemsR[] = ['label' => 'Acessar', 'url' => ['/login#signin']];
    $menuItemsR[] = ['label' => 'Assinar', 'url' => ['/login#signup']];
} else {
    $menuItemsR[] = [
        'label' => (isset($user_pic_file) && !empty($user_pic_file)) ? '<div class="img-rounded profile-img"></div>' : Yii::$app->user->identity->username, 'url' => ['#'],
        'items' => [
            ['label' => 'Perfil', 'url' => ['/user/' . Yii::$app->user->identity->slug],],
            ['label' => 'Trocar a senha', 'url' => ['/request-password-reset'],],
            ['label' => 'Sair', 'url' => '#', 'linkOptions' => ['id' => 'logout'],],
        ]
    ];
}

NavBar::begin([
    'brandLabel' => Yii::$app->params['application-name'],
    'brandUrl' => Yii::$app->homeUrl,
    'brandOptions' => ['class' => 'p-0'],
    'options' => [
        'class' => 'navbar navbar-expand-sm fixed-top navbar-light bg-light justify-content-end',
        'style' => 'padding-top: 0px;padding-bottom: 0px;',
    ],
]);
echo Nav::widget([
    'items' => $menuItemsL,
    'dropdownClass' => Dropdown::classname(),
    'options' => ['class' => 'navbar-nav mr-auto'],
    'activateParents' => true,
    'encodeLabels' => false
]);
echo Nav::widget([
    'items' => $menuItemsR,
    'dropdownClass' => Dropdown::classname(),
    'options' => ['class' => 'navbar-nav'],
    'activateParents' => true,
    'encodeLabels' => false
]);
NavBar::end();
