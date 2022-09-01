<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;
use kartik\bs4dropdown\Dropdown;
use common\models\User;

$menuItemsL[] = ["label" => Yii::$app->params['application-name'], 'url' => '#',
    "visible" => true,
];
$menuItemsR[] = (!Yii::$app->user->isGuest) ?
        $this->render('@frontend/views/user/_form_periodo', ['model' => User::findOne(Yii::$app->user->identity->id)]) : '';
// Insere a validação da folha
if (!Yii::$app->user->isGuest and 1 == 2) {
    $errosFolha = frontend\controllers\FolhaController::getValidaFolha();
    if ($errosFolha > 0) {
        $menuItemsR[] = ["label" => "<i class='fas fa-bell red'></i>"
            , "active" => false, "url" => "#", 'linkOptions' => [
                "title" => "Há $errosFolha erros na folha de pagamento. Clique para corrigir",
                'id' => Yii::$app->security->generateRandomString(),
                'value' => Url::to(['/folha/z']),
                'class' => 'showModalButton'
            ],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
        ];
    }
}
$menuItemsR[] = ["label" => "<i class='fa fa-question-circle'></i>", "active" => false, "url" => "#",
    "items" => [
        ["label" => "Artigos", "url" => ["/suporte/artigos"]],
        ["label" => "Histórico de Atualizações", "url" => ["/sis-reviews/reviews"]],
        ["label" => "Apresentação", "url" => ["/apresentacao"]],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
];
$menuItemsR[] = ["label" => "<i class='fa fa-cogs'></i>", "active" => false, "url" => "#",
    "items" => [
        ["label" => "Usuários Desk", "url" => ["/desk-users"],],
        ["label" => "Artigos", "url" => ["/suporte/adm"],],
        ["label" => "Reviews", "url" => ["/sis-reviews"],],
        ["label" => "Mensagens do sistema", "url" => ["/msgs"]],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1,
];
$menuItemsR[] = ["label" => "<i class='fa fa-cog'></i>", "active" => false, "url" => "#",
    "items" => [
        ["label" => "Usuários", "url" => ["/user"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1],
        ["label" => "Eventos do sistema", "url" => ["/eventos"],
            "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
        ["label" => "Parâmetros do sistema", "url" => ["/params"]
            , "visible" => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1
        ],
    ],
    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1,
];
if (Yii::$app->user->isGuest) {
    $menuItemsR[] = ['label' => 'Acessar', 'url' => '#'/*['/login#signin']*/,'linkOptions' => [
                "title" => "Acessar o sistema",
                'id' => Yii::$app->security->generateRandomString(),
                'value' => Url::to(['/login']),
                'class' => 'showModalButton modal-wsm modal-title-null'
            ]];
    $menuItemsR[] = ['label' => 'Registrar', 'url' => '#'/*['/login#signin']*/,'linkOptions' => [
                "title" => "Novo usuário",
                'id' => Yii::$app->security->generateRandomString(),
                'value' => Url::to(['/signup']),
                'class' => 'showModalButton modal-wsm modal-title-null'
            ]];
//    $menuItemsR[] = ['label' => 'Assinar', 'url' => ['/login#signup']];
} else {
//    $photo = strtolower(Yii::$app->user->identity->dominio . '/imagens/usuarios/' . Yii::$app->user->identity->url_foto);
    $photo = strtolower('/imagens/' . Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/usuarios/' . Yii::$app->user->identity->url_foto);
     $photo_root = Yii::getAlias('@common_uploads_root') . $photo;
    $photo_url = Yii::getAlias('@common_uploads_url') . $photo;
    $avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_male.jpg';
    $menuItemsR[] = [
        'label' => Html::img(Url::home(true) . (!file_exists($photo_root) ? $avatar : $photo_url), ['class' => 'rounded', 'width' => '40px;',])/* . ' ' . explode(" ", Yii::$app->user->identity->username)[0] */, 'url' => ['#'],
        'items' => [
            ['label' => 'Perfil', 'url' => ['/pagamentos/user/' . Yii::$app->user->identity->slug],],
            ['label' => 'Trocar a senha', 'url' => ['/pagamentos/request-password-reset'],],
            ['label' => 'Sair', 'url' => '#', 'linkOptions' => ['id' => '/pagamentos/logout'],],
        ],
        'dropdownOptions' => ['class' => 'dropdown-menu-right'],
    ];
}

NavBar::begin([
    'brandLabel' => "<i class='fa fa-home'></i>",
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
