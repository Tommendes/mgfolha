<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;
use kartik\bs4dropdown\Dropdown;
use common\models\User;
use pagamentos\controllers\FolhaController;

$atual_inss;
$atual_inss_sf;
$atual_rpps;
$atual_rpps_sf;
$atual_irrf;

$menuItemsL[] = [
    "label" => '<i class="fa fa-edit"></i>' . "Cadastros", "active" => false, "url" => "#",
    "items" => [
        [
            "label" => "Órgão", "url" => ["/orgao"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->cadastros >= 1,
        ],
        [
            "label" => "Servidores", "url" => ["#"],
            "items" => [
                ["label" => "Todos", "url" => ["/cad-servidores"],],
                [
                    "label" => "Novo", "url" => ["/cad-servidores/c"],
                    "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->cadastros >= 2 && $sf
                ],
            ],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->cadastros >= 1,
        ],
        [
            "label" => "Diversos", "url" => ["#"],
            "items" => [
                ["label" => "Convênios bancários", "url" => ["/cad-bconvenios"],],
                ["label" => "Cargos", "url" => ["/cad-cargos"],],
                ["label" => "Centros", "url" => ["/cad-centros"],],
                ["label" => "Funções", "url" => ["/cad-funcoes"],],
                ["label" => "Departamentos", "url" => ["/cad-departamentos"],],
                ["label" => "Locais de trabalho", "url" => ["/cad-localtrabalho"],],
                ["label" => "PCC", "url" => ["/cad-pccs"],],
                ["label" => "Eventos", "url" => ["/fin-eventos"],],
                ["label" => "Parâmetros", "url" => ["/fin-parametros"],],
                [
                    "label" => "Tabelas", "url" => ["#"], "items" => [
                        [
                            "label" => "INSS", "url" => ["#"], "items" => [
                                ["label" => "Ver", "url" => ["/fin-faixas/z?tipo=0"],],
                                ["label" => "Pesquisar", "url" => ["/fin-faixas?faixa=0"],],
                            ],
                        ],
                        [
                            "label" => "Salário família INSS", "url" => ["#"], "items" => [
                                ["label" => "Ver", "url" => ["/fin-faixas/z?tipo=3"],],
                                ["label" => "Pesquisar", "url" => ["/fin-faixas?faixa=3"],],
                            ],
                        ],
                        [
                            "label" => "RPPS", "url" => ["#"], "items" => [
                                ["label" => "Ver", "url" => ["/fin-faixas/z?tipo=4"],],
                                ["label" => "Pesquisar", "url" => ["/fin-faixas?faixa=4"],],
                            ],
                        ],
                        [
                            "label" => "Salário família RPPS", "url" => ["#"], "items" => [
                                ["label" => "Ver", "url" => ["/fin-faixas/z?tipo=2"],],
                                ["label" => "Pesquisar", "url" => ["/fin-faixas?faixa=2"],],
                            ],
                        ],
                        [
                            "label" => "IRRF", "url" => ["#"], "items" => [
                                ["label" => "Ver", "url" => ["/fin-faixas/z?tipo=1"],],
                                ["label" => "Pesquisar", "url" => ["/fin-faixas?faixa=1"],],
                            ],
                        ],
                    ],
                ],
            ],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->financeiro >= 1,
        ],
    ], "visible" => !Yii::$app->user->isGuest
];
$menuItemsL[] = [
    "label" => "<i class='fa fa-calendar'></i>", "active" => false, "url" => "#", 'linkOptions' => ['title' => 'Folha de pagamento'],
    "items" => [
        [
            "label" => Yii::t('yii', '{operacao} {modelClass}', ['operacao' => 'Registrar', 'modelClass' => strtolower(FolhaController::CLASS_VERB_NAME)]),
            "url" => '#', 'linkOptions' => [
                'id' => $btnIncrementar,
                'title' => Yii::t('yii', '{operacao} {modelClass} {folha}', [
                    'operacao' => 'Registrar',
                    'modelClass' => strtolower(FolhaController::CLASS_VERB_NAME), 'folha' => pagamentos\controllers\SiteController::getFolhaAtual()
                ]),
            ], "visible" => $sf,
        ],
    ], "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->folha >= 1 && $sf
];
$menuItemsL[] = [
    "label" => "<i class='fa fa-print'></i>", "active" => false, "url" => "#", 'linkOptions' => ['title' => 'Relatórios e filtros'],
    "items" => [
        [
            "label" => Yii::t('yii', 'Relatório da folha'),
            "url" => ['folha/print?f=' . ($f = 'resumoGeral')], 'linkOptions' => [
                'id' => $btnIncrementar,
                'title' => Yii::t('yii', '{operacao} {titulo} {folha}', [
                    'operacao' => 'Imprimir',
                    'titulo' => strtolower(FolhaController::CLASS_VERB_NAME),
                    'folha' => pagamentos\controllers\SiteController::getFolhaAtual()
                ]),
            ], "visible" => true, //$sf,
        ],
    ], "visible" => !Yii::$app->user->isGuest
];
$menuItemsL[] = ["label" => "<i class='fa fa-file-export'></i>", "active" => false, "url" => "#", 'linkOptions' => ['title' => 'Exportar dados'], "visible" => !Yii::$app->user->isGuest];
$menuItemsL[] = (!Yii::$app->user->isGuest && Yii::$app->user->identity->cadastros >= 1) ?
        $this->render('/cad-servidores/_search', ['model' => $searchCadModel = new \pagamentos\models\CadServidoresSearch()]) : '';
$menuItemsL[] = (!Yii::$app->user->isGuest) ?
        $this->render('@frontend/views/user/_form_periodo', ['model' => User::findOne(Yii::$app->user->identity->id)]) : '';
// Insere a validação da folha
if (!Yii::$app->user->isGuest and 1 == 2) {
    $errosFolha = pagamentos\controllers\FolhaController::getValidaFolha();
    if ($errosFolha > 0) {
        $menuItemsR[] = [
            "label" => "<i class='fas fa-bell red'></i>", "active" => false, "url" => "#", 'linkOptions' => [
                "title" => "Há $errosFolha erros na folha de pagamento. Clique para corrigir",
                'id' => Yii::$app->security->generateRandomString(),
                'value' => Url::to(['/folha/z']),
                'class' => 'showModalButton'
            ],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
        ];
    }
}
$menuItemsR[] = [
    "label" => "<i class='fa fa-question-circle'></i>", "active" => false, "url" => "#",
    "items" => [
        ["label" => "Artigos", "url" => ["/suporte/artigos"]],
        ["label" => "Histórico de Atualizações", "url" => ["/../reviews"]],
        ["label" => "Apresentação", "url" => ["/apresentacao"]],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
];
$menuItemsR[] = [
    "label" => "<i class='fa fa-cogs'></i>", "active" => false, "url" => "#",
    "items" => [
        ["label" => "Usuários Desk", "url" => ["/../desk-users"],],
        ["label" => "Artigos", "url" => ["/../suporte/adm"],],
        ["label" => "Reviews", "url" => ["/../sis-reviews"],],
        ["label" => "Mensagens do sistema", "url" => ["/../msgs"]],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
];
$menuItemsR[] = [
    "label" => "<i class='fa fa-cog'></i>", "active" => false, "url" => "#",
    "items" => [
        [
            "label" => "Usuários", "url" => ["/user"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
        [
            "label" => "Eventos do sistema", "url" => ["/sis-events"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
        [
            "label" => "Parâmetros do sistema", "url" => ["/../params"], "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1,
];
if (Yii::$app->user->isGuest) {
    $menuItemsR[] = ['label' => 'Acessar', 'url' => ['/login#signin']];
    //    $menuItemsR[] = ['label' => 'Assinar', 'url' => ['/login#signup']];
} else {
    $photo = strtolower("/imagens/usuarios/" . Yii::$app->user->identity->url_foto);
    $photo_root = Yii::getAlias('@common_uploads_root') . $photo;
    $photo_url = Yii::getAlias('@common_uploads_url') . $photo;
    $avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_male.jpg';
    $menuItemsR[] = [
        'label' => Html::img(Url::home(true) . ((is_null(Yii::$app->user->identity->url_foto) || !file_exists($photo_root)) ? $avatar : $photo_url), ['class' => 'rounded', 'width' => '40px;',])/* . ' ' . explode(" ", Yii::$app->user->identity->username)[0] */, 'url' => ['#'],
        'items' => [
            ['label' => 'Perfil', 'url' => ['/user/' . Yii::$app->user->identity->slug],],
            ['label' => 'Trocar a senha', 'url' => ['/request-password-reset'],],
            ['label' => 'Sair', 'url' => '#', 'linkOptions' => ['id' => 'logout'],],
        ],
        'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    ];
}

NavBar::begin([
    'brandLabel' => Html::img(Url::home(true) . Yii::getAlias('@imgs_url') . "/logo_folha-no-bg.png", ['width' => '132px']), //"<i class='fa fa-home'></i>",
    'brandUrl' => Yii::$app->homeUrl,
    'brandOptions' => ['class' => 'p-0'],
    'innerContainerOptions' => ['class' => 'container-fluid'],
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-light fixed-top bg-light',
        'style' => 'padding-top: 0px;padding-bottom: 0px;',
    ],
]);
echo Nav::widget([
    'items' => $menuItemsL,
    'dropdownClass' => Dropdown::classname(),
    'options' => ['class' => 'navbar-nav mr-auto'],
    'activateParents' => true,
    'encodeLabels' => false,
]);
echo Nav::widget([
    'items' => $menuItemsR,
    'dropdownClass' => Dropdown::classname(),
    'options' => ['class' => 'navbar-nav container-smooth'],
    'activateParents' => true,
    'encodeLabels' => false,
]);
NavBar::end();
