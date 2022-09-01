<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use yii\widgets\MaskedInput;
use pagamentos\controllers\UserController;
use common\models\User;
use pagamentos\controllers\SisEventsController;
use common\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$usuario = Yii::$app->user->identity->tp_usuario >= 1;

$this->title = Yii::t('yii', UserController::CLASS_VERB_NAME_PL);
if ($usuario) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('yii', UserController::CLASS_VERB_NAME_PL), 'url' => ['index']];
}
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
// Recupera os dados dos clientes em @common/components/Clients
$dadosDosClientes = (Yii::$app->clientes->clientes);
$clienteAtual = strtolower(Yii::$app->user->identity->cliente);
// Lista os serviços do cliente
foreach ($dadosDosClientes[$clienteAtual]['servicos'] as $key => $value) {
    $servicos[] = ['idServico' => $value, 'nomeServico' => ucfirst($value)];
}
$base_servicos = ArrayHelper::map($servicos, 'idServico', 'nomeServico', ['prompt' => 'Selecione...']);

$dominios = ArrayHelper::map(User::getDominiosDoOrgao(), 'chave', 'valor', ['prompt' => 'Selecione...']);

$photo = strtolower("/imagens/usuarios/$model->url_foto");
$photo_root = Yii::getAlias('@common_uploads_root') . $photo;
$photo_url = Yii::getAlias('@common_uploads_url') . $photo;
$label = (($model->url_foto == null) || !file_exists($photo_root)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_male.jpg';
?>
<?php
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == User::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = true;
if ($usuario) {
//if (Yii::$app->user->identity->administrador >= 1) {
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
                'value' => str_replace('_', ' ', $model->cliente),
                'options' => ['readonly' => false,],
                'displayOnly' => Yii::$app->user->identity->administrador < 1,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
//}
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
        'columns' => [
            [
                'attribute' => 'tp_usuario',
                'value' => User::getTpUsuario($model->tp_usuario),
                'type' => DetailView::INPUT_DROPDOWN_LIST,
                'items' => ['0' => 'Servidor', '1' => 'Usuário'],
                'displayOnly' => !(Yii::$app->user->identity->administrador >= 1),
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;'],
            ],
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
        ],
    ];
} else {
    $attributes[] = [
        'attribute' => 'username',
        'options' => ['readonly' => false,],
        'displayOnly' => Yii::$app->user->identity->administrador < 1,
    ];
    $attributes[] = [
        'attribute' => 'cliente',
        'value' => str_replace('_', ' ', $model->cliente),
        'options' => ['readonly' => false,],
        'displayOnly' => Yii::$app->user->identity->administrador < 1,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];
//};
    $attributes[] = [
        'attribute' => 'email',
        'format' => 'raw',
        'options' => ['readonly' => false,],
        'displayOnly' => Yii::$app->user->identity->administrador < 1,
    ];
    $attributes[] = [
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
    ];
}
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
        <div class="col-md-3<?= !$usuario ? ' offset-md-2' : '' ?>">
            <fieldset>
                <legend>Sua foto bem bonita:</legend>
                <?=
                Html::img(Url::home(true) . (($model->url_foto == null) || !file_exists($photo_root) ? $avatar : $photo_url), ['class' => 'rounded mx-auto d-block', 'width' => '70%;',])
                ?> 
                <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;padding-top: 5px;">
                    <?=
                    Html::a('<span class="fa fa-folder-open"></span>&nbsp;' . $label, '#', [
                        'value' => Url::to(['upload', 'id' => $model->id, 'modal' => 1]),
                        'title' => Yii::t('yii', 'Uploading file'),
                        'class' => 'showModalButton btn btn-outline-warning w-50 mr-1 modal-default',
                    ])
                    ?>
                    <?=
                    Html::a('<i class="fa fa-camera"></i>&nbsp;Tirar foto', Url::to(['photo', 'id' => $model->slug]), [
                        'title' => Yii::t('yii', 'Tirar foto'),
                        'class' => 'btn btn-outline-warning w-50',
                    ])
                    ?> 
                </div>
            </fieldset>
        </div>
        <div class="col-md-<?= $usuario ? '9' : '5' ?>">
            <fieldset>
                <legend>Seus dados de usuário:</legend>
                <?=
                DetailView::widget([
                    'model' => $model,
                    'condensed' => false,
                    'hover' => true,
                    'mode' => !isset(Yii::$app->request->queryParams['mv']) || User::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                    'buttons1' => User::STATUS_CANCELADO === $model->status ? $label_statusCancelado : '{update}',
                    'deleteOptions' => [
                        'params' => ['id' => $model->id],
                        'url' => ['dlt', 'id' => $model->id],
                    ],
                    'panel' => [
                        'heading' => 'Dados do ' . strtolower(UserController::CLASS_VERB_NAME) . ' - ' . $model->username . (Yii::$app->user->identity->administrador >= 2 ? ' (id: ' . $model->id . ')' : ''),
                        'type' => User::STATUS_CANCELADO === $model->status ? DetailView::TYPE_WARNING : DetailView::TYPE_INFO,
                    ],
                    'attributes' => $attributes,
                ])
                ?>
            </fieldset>
            <?php if (!$usuario) { ?>
                <fieldset>
                    <legend>Seu último holerite:</legend>
                    <div class="btn-group d-flex" role="group" aria-label="Funções do servidor" style="padding-bottom: 5px;">
                        <?=
                        Html::a('<i class="fas fa-money-check-alt"></i> Seu último holerite',
                                Url::to(['/cad-servidores/print/' . $model->id_cad_servidores]), [
                            'title' => Yii::t('yii', 'Seu último holerite'),
                            'class' => 'btn btn-light btn-lg w-50 mr-1',
                        ])
                        ?>
                        <?=
                        Html::button('<i class="fas fa-check-square"></i> Validar um holerite', [
                            'value' => Url::to(['/../holerite-on-line', 'reloadOnClose' => true]),
                            'title' => Yii::t('yii', 'Validar um holerite'),
                            'class' => 'showModalButton modal-wsm modal-title-null btn btn-light btn-lg w-50',
                        ])
                        ?>
                    </div>
                </fieldset>
            <?php } ?>
        </div>
    </div>

</div>
