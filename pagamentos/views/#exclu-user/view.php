<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use yii\widgets\MaskedInput;
use pagamentos\controllers\UserController;
use common\models\User;
use pagamentos\controllers\SisEventsController;
use common\models\UserDomains;
use common\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model common\models\User */
$this->title = Yii::t('yii', UserController::CLASS_VERB_NAME_PL);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', UserController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('yii', UserController::CLASS_VERB_NAME) . ' ' . $model->username;
$alcadas = [
    '0' => '0-Acesso negado',
    '1' => '1-Pode exibir',
    '2' => '2-Pode criar',
    '3' => '3-Pode editar',
    '4' => '4-Pode excluir/cancelar',
];
if (Yii::$app->user->identity->administrador >= 1) {
    $status = [
        User::STATUS_INATIVO => 'Inativo',
        User::STATUS_ATIVO => 'Ativo',
        User::STATUS_NEW_USER => 'Novo usuário',
        User::STATUS_NEW_USER_UNREGISTERED => 'Sem domínio',
        User::STATUS_CANCELADO => 'Cancelado',
    ];
} else {
    $status = [
        User::STATUS_INATIVO => 'Inativo',
        User::STATUS_ATIVO => 'Ativo',
    ];
}
$sn = ['1' => 'Sim', '0' => 'Não'];
$evento = SisEventsController::findEvt($model->evento);
$base_servicos = explode(',', User::find()
                ->where('servicos is not null')
                ->andWhere([
                    'status' => User::STATUS_ATIVO,
                    'username' => Yii::$app->user->identity->username,
                ])
                ->one()->servicos);
sort($base_servicos);
foreach ($base_servicos as $key => $value) {
    $servicos[] = ['cliente' => trim($value), 'dominio' => AppController::str_capitalizar(trim($value))];
}
$base_servicos = ArrayHelper::map($servicos, 'cliente', 'dominio', ['prompt' => 'Selecione...']);

$dominios = ArrayHelper::map(User::getDominiosDoOrgao(), 'chave', 'valor', ['prompt' => 'Selecione...']);

$photo = '/imagens/' . Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/usuarios/' . Yii::$app->user->identity->url_foto;
$photo_root = strtolower(Yii::getAlias('@uploads_root') . $photo);
$photo_url = Yii::getAlias('@uploads_url') . $photo;
$label = ((Yii::$app->user->identity->url_foto == null) || !file_exists($photo_root)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_male.jpg';
?>
<?php
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == User::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = true;
if (Yii::$app->user->identity->administrador >= 1) {
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'username',
                'options' => ['readonly' => false,],
                'displayOnly' => Yii::$app->user->identity->administrador < 1,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'cliente',
                'options' => ['readonly' => false,],
                'displayOnly' => Yii::$app->user->identity->administrador < 1,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
}
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'email',
            'format' => 'raw',
            'options' => ['readonly' => false,],
            'displayOnly' => Yii::$app->user->identity->administrador < 1,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'tel_contato',
            'options' => ['readonly' => false, 'class' => 'form-control'],
            'displayOnly' => false,
            'type' => DetailView::INPUT_WIDGET,
            'widgetOptions' => [
                'class' => MaskedInput::className(),
                'name' => 'input-tel_contato',
                'mask' => ['(99)9999-9999', '(99)99999-9999'],
                'options' => [
                ],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'gestor',
            'options' => ['readonly' => false,],
            'displayOnly' => Yii::$app->user->identity->administrador < 1,
            'format' => 'raw',
            'value' => $model->getUsuariosSN($model->gestor),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $sn,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'usuarios',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosAlcadas($model->usuarios),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $alcadas,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'cadastros',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosAlcadas($model->cadastros),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $alcadas,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'folha',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosAlcadas($model->folha),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $alcadas,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'financeiro',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosAlcadas($model->financeiro),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $alcadas,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'parametros',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosAlcadas($model->parametros),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $alcadas,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'status',
            'options' => ['readonly' => false,],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'value' => $model->getUsuariosStatus($model->status),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $status,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'servicos',
            'options' => ['readonly' => !(Yii::$app->user->identity->administrador >= 1)],
            'displayOnly' => !(Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 3),
            'format' => 'raw',
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ],
];
if (count(User::getDominiosDoOrgao()) > 1) {
    $dominioCliente = [
        'attribute' => 'dominio',
        'value' => AppController::str_capitalizar($model->dominio),
        'type' => DetailView::INPUT_DROPDOWN_LIST,
        'items' => $dominios,
        'displayOnly' => !(Yii::$app->user->identity->administrador >= 1),
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];
} else {
    $dominioCliente = [
        'attribute' => 'dominio',
        'value' => AppController::str_capitalizar($model->dominio),
        'displayOnly' => true,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];
}

$attributes[] = [
    'columns' => [
        [
            'attribute' => 'base_servico',
            'value' => AppController::str_capitalizar($model->base_servico),
            'type' => DetailView::INPUT_DROPDOWN_LIST,
            'items' => $base_servicos,
            'displayOnly' => !(Yii::$app->user->identity->administrador >= 1),
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;'],
        ],
        $dominioCliente,
    ],
];
$attributes[] = [
    'group' => true,
    'label' => $model->getAttributeLabel('evento') . ' ' . (
    Yii::$app->user->identity->gestor ?
    Html::button($evento, [
        'value' => Url::to(['/sis-events/' . $model->evento]),
        'title' => Yii::t('yii', 'Ver evento'),
        'class' => 'showModalButton button-as-link'
    ]) : $evento) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
    . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
    'groupOptions' => ['class' => 'table-info text-center']
];
?>
<div class="user-view">
    <div class="row">
        <div class="col-md-3">
            <fieldset>
                <legend>Sua foto bem bonita:</legend>
                <?=
                Html::img(Url::home(true) . ((Yii::$app->user->identity->url_foto == null) || !file_exists($photo_root) ? $avatar : $photo_url), ['class' => 'rounded', 'width' => '100%;',])
                ?> 
                <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
                    <?=
                    Html::a('<span class="fa fa-folder-open"></span>&nbsp;' . $label, '#', [
                        'value' => Url::to(['upload', 'id' => $model->id, 'modal' => 1]),
                        'title' => Yii::t('yii', 'Uploading file'),
                        'class' => 'showModalButton btn btn-warning w-50 modal-default',
                    ])
                    ?>
                    <?=
                    Html::a('<i class="fa fa-camera"></i>&nbsp;Tirar foto', Url::to(['photo', 'id' => $model->slug]), [
                        'title' => Yii::t('yii', 'Tirar foto'),
                        'class' => 'btn btn-warning w-50',
                    ])
                    ?> 
                </div>
            </fieldset>
        </div>
        <div class="col-md-9">
            <?=
            DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || User::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => User::STATUS_CANCELADO === $model->status ? $label_statusCancelado : '{update} {delete}',
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => 'Dados do ' . strtolower(UserController::CLASS_VERB_NAME) . ' - ' . $model->username,
                    'type' => User::STATUS_CANCELADO === $model->status ? DetailView::TYPE_WARNING : DetailView::TYPE_INFO,
                ],
                'attributes' => $attributes,
            ])
            ?>
        </div>
    </div>

</div>
