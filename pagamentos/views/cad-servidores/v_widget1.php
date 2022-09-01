<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use pagamentos\controllers\CadSfuncionalController;
use pagamentos\models\CadServidores;
use kartik\icons\FontAwesomeAsset;
use kartik\cmenu\ContextMenu;

FontAwesomeAsset::register($this);
$dominio = Yii::$app->user->identity->dominio;
$cliente = Yii::$app->user->identity->cliente;

$photoPath = Yii::getAlias('@uploads_root') . strtolower("/imagens/$cliente/$dominio/servidores/");
$photo = pathinfo($model->url_foto);
$photo = $photoPath . $photo['basename'];

$label = (!file_exists($photo)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_' . ($model && $model->sexo === 1 ? 'fe' : '') . 'male.jpg';
$baseServico = Yii::$app->user->identity->base_servico;
?>
<fieldset>
    <legend>Foto do servidor <?= $model && $model->getFalecimento() !== null ? '' : ($baseServico == 'pagamentos' ? '(botão direito para opções)' : '') ?></legend>
    <div class="row">
        <div class="offset-2 col-md-8">
            <?=
                Html::img(!file_exists($photo) ? Url::home(true) . $avatar : $model->url_foto, ['class' => 'rounded mx-auto d-block', 'width' => '70%;',])
            ?>
        </div>
    </div>
    <?php if ($baseServico == 'pagamentos') { ?>
        <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;padding-top: 5px;">
            <?=
                Html::a('<span class="fa fa-folder-open"></span>&nbsp;Enviar foto', '#', [
                    'value' => Url::to(['/cad-servidores/upload', 'id' => $model->id, 'modal' => 1]),
                    'title' => Yii::t('yii', 'Uploading file'),
                    'class' => 'showModalButton btn btn-outline-warning w-50 mr-1 modal-default',
                ])
            ?>
            <?=
                Html::a(
                    '<span class="fa fa-folder-open"></span>&nbsp;Tirar foto',
                    ['/cad-servidores/photo', 'id' => $model->slug],
                    [
                        'title' => Yii::t('yii', 'Tirar foto'),
                        'class' => 'btn btn-outline-warning w-50',
                    ]
                )
            ?>
        </div>
    <?php } ?>
</fieldset>
<fieldset>
    <legend>Dados adicionais:</legend>
    <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
        <?=
            Html::a('<span class="fa fa-university"></span>&nbsp;Bancário', '#', [
                'value' => Url::to(['view', 'id' => $model->id, 'modal' => 1, 'tp' => 1, 'mv' => $mv]),
                'title' => Yii::t('yii', 'Dados bancários'),
                'class' => 'showModalButton modal-md btn btn-outline-danger w-50 mr-1',
                'role' => 'button',
            ])
        ?>
        <?=
            Html::a('<span class="fa fa-map-marked-alt"></span>&nbsp;Endereço', '#', [
                'value' => Url::to(['view', 'id' => $model->id, 'modal' => 1, 'tp' => 2, 'mv' => $mv]),
                'title' => Yii::t('yii', 'Endereço'),
                'class' => 'showModalButton modal-md btn btn-outline-danger w-50',
                'role' => 'button',
            ])
        ?>
    </div>
    <?php
    $count_certidoes = \pagamentos\models\CadScertidao::find()->where([
        'id_cad_servidores' => $model->id,
        'dominio' => $model->dominio
    ])->count();
    if (Yii::$app->user->identity->administrador >= 2) {
        $count_dependentes = \pagamentos\models\CadSdependentes::find()->where([
            'id_cad_servidores' => $model->id,
            'dominio' => $model->dominio
        ])->count();
        $count_ferias = \pagamentos\models\CadSferias::find()->where([
            'id_cad_servidores' => $model->id,
            'dominio' => $model->dominio
        ])->count();
        $loc = \pagamentos\models\CadSfuncional::tableName();
        $mes = Yii::$app->user->identity->per_mes > 12 ? 12 : Yii::$app->user->identity->per_mes;
        $ano = Yii::$app->user->identity->per_ano;
        $funcional = \pagamentos\models\CadSfuncional::find()
            ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
            ->where([
                'id_cad_servidores' => $model->id,
                $loc . '.dominio' => $model->dominio,
                $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            ])
            ->andWhere("LAST_DAY(CONCAT(cad_sfuncional.ano, '/', cad_sfuncional.mes, '/01')) <= LAST_DAY('$ano/$mes/01')")
            ->orderBy('ano desc, mes desc')
            ->limit(1)
            ->one();
        $financeiro = \pagamentos\models\FinSfuncional::find()->where([
            'id_cad_servidores' => $model->id,
            'dominio' => $model->dominio,
            'ano' => Yii::$app->user->identity->per_ano,
            'mes' => Yii::$app->user->identity->per_mes,
            'parcela' => Yii::$app->user->identity->per_parcela,
        ])->one();
        $rubricas = \pagamentos\models\FinRubricas::find()->where([
            'id_cad_servidores' => $model->id,
            'dominio' => $model->dominio,
            'ano' => Yii::$app->user->identity->per_ano,
            'mes' => Yii::$app->user->identity->per_mes,
            'parcela' => Yii::$app->user->identity->per_parcela,
        ])
            ->one();
        $count_movimentacao = \pagamentos\models\CadSmovimentacao::find()->where([
            'id_cad_servidores' => $model->id,
            'dominio' => $model->dominio
        ])->count();
    }
    $count_recadastro = \pagamentos\models\CadSrecadastro::find()->where([
        'id_cad_servidores' => $model->id,
        'dominio' => $model->dominio
    ])->count();
    ?>
    <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
        <?php
        $valueCertid = null;
        if ($baseServico == 'cash' && $count_certidoes == 0) {
            $msgCertid = 'krajeeDialog.alert("O servidor não tem certidões registradas");';
        } else {
            $msgCertid = '';
            $valueCertid = Url::to(['/cad-scertidao' . ($count_certidoes == 0 ? '/c' : ''), 'id_cad_servidores' => $model->id, 'modal' => 1]);
        }
        echo Html::a('<span class="fa fa-file-contract"></span>&nbsp;Certidões', '#', [
            'value' => $valueCertid,
            'onclick' => $msgCertid,
            'title' => Yii::t('yii', 'Certidões'),
            'class' => ($model->getFalecimento() !== null ? 'disabled ' : 'showModalButton modal-md ') . 'btn btn-outline-danger w-50 mr-1',
            'role' => 'button',
        ])
        ?>
        <?=
            Html::a('<span class="fa fa-id-card"></span>&nbsp;Documentos', '#', [
                'value' => Url::to(['view', 'id' => $model->id, 'modal' => 1, 'tp' => 3, 'mv' => $mv]),
                'title' => Yii::t('yii', 'Mais Documentos'),
                'class' => 'showModalButton modal-md btn btn-outline-danger w-50',
                'role' => 'button',
            ])
        ?>
    </div>
    <?php
    if (Yii::$app->user->identity->administrador >= 2) {
    ?>
        <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
            <?php
            $valueDepends = null;
            if ($baseServico == 'cash' && $count_dependentes == 0) {
                $msgDepends = 'krajeeDialog.alert("O servidor não tem dependentes registrados");';
            } else {
                $msgDepends = '';
                $valueDepends = Url::to(['/cad-sdependentes' . ($count_dependentes == 0 ? '/c' : ''), 'id_cad_servidores' => $model->id, 'modal' => 1]);
            }
            echo Html::a('<span class="fa fa-child"></span><span class="fa fa-child"></span>&nbsp;Depend.' . ($count_dependentes > 0 ? '(' . $count_dependentes . ')' : ''), '#', [
                'value' => $valueDepends,
                'onclick' => $msgDepends,
                'title' => Yii::t('yii', 'Dependentes'),
                'class' => ($model->getFalecimento() !== null ? 'disabled ' : 'showModalButton modal-md ') . 'btn btn-outline-danger w-50 mr-1',
                'role' => 'button',
            ])
            ?>
            <?php
            $valueFerias = null;
            if ($baseServico == 'cash' && $count_ferias == 0) {
                $msgFerias = 'krajeeDialog.alert("O servidor não tem férias registradas");';
            } else {
                $msgFerias = '';
                $valueFerias = Url::to(['/cad-sferias' . ($count_ferias == 0 ? '/c' : ''), 'id_cad_servidores' => $model->id, 'modal' => 1]);
            }
            echo Html::a('<span class="fa fa-plane-departure"></span>&nbsp;Férias' . ($count_ferias > 0 ? '(' . $count_ferias . ')' : ''), '#', [
                'value' => $valueFerias,
                'onclick' => $msgFerias,
                'title' => Yii::t('yii', 'Férias'),
                'class' => ($model->getFalecimento() !== null ? 'disabled ' : 'showModalButton modal-md ') . ' btn btn-outline-danger w-50',
                'role' => 'button',
            ])
            ?>
        </div>
        <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
            <?php
            if ($baseServico == 'cash') {
                echo Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-briefcase"></span>&nbsp;Funcional', [
                    'value' => Url::to(["/cad-sfuncional/" . $model->matricula, 'modal' => '1', 'mv' => '0', 'reloadOnClose' => true]),
                    'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadSfuncionalController::CLASS_VERB_NAME]),
                    'class' => 'showModalButton modal-md btn btn-outline-danger w-50 mr-1',
                ]);
            } else {
                echo Html::a(
                    '<span class="fa fa-briefcase"></span>&nbsp;Funcional',
                    (!empty($funcional->slug)) ? Url::to([
                        '/cad-sfuncional/' . $model->matricula, 'modal' => 0, 'mv' => $mv
                    ]) : 'javascript:naF("funcional");',
                    [
                        'title' => Yii::t('yii', 'Funcional'),
                        'class' => 'btn btn-outline-danger w-50 mr-1',
                        'role' => 'button',
                    ]
                );
            }
            ?>
            <?php
            $valueMovim = null;
            if ($baseServico == 'cash' && $count_movimentacao == 0) {
                $msgMovim = 'krajeeDialog.alert("O servidor não tem movimentos registrados");';
            } else {
                $msgMovim = '';
                $valueMovim = Url::to(['/cad-smovimentacao' . ($count_movimentacao == 0 ? '/c' : ''), 'id_cad_servidores' => $model->id, 'modal' => 1]);
            }
            echo Html::a('<span class="fa fa-hospital"></span>&nbsp;Movim.' . ($count_movimentacao > 0 ? '(' . $count_movimentacao . ')' : ''), '#', [
                'value' => $valueMovim,
                'onclick' => $msgMovim,
                'title' => Yii::t('yii', 'Movimentação'),
                'class' => 'showModalButton modal-md btn btn-outline-danger w-50',
                'role' => 'button',
            ])
            ?>
        </div>
    <?php
    }
    ?>
    <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
        <?=
            Yii::$app->user->identity->administrador >= 2 ? Html::a('<span class="fa fa-file-invoice-dollar"></span>&nbsp;Financeiro', (!empty($financeiro->slug)) ? Url::to(['/fin-sfuncional/' . $model->matricula, 'modal' => 0, 'mv' => $mv]) : 'javascript:naF("financeiro");', [
                'title' => Yii::t('yii', 'Financeiro'),
                'class' => ' btn btn-outline-danger w-50 mr-1',
            ]) : ''
        ?>
        <?php
        $msprint = Yii::t('yii', 'Confirma a impressão da ficha cadastral?');
        $url = Url::to(['/cad-srecadastro/p/' . $model->id]);
        $urlPrint = Url::to(['/cad-srecadastro/print/' . $model->id]);
        echo $buttonCreate = Yii::$app->user->identity->cadastros < 3 ? '' :
            Html::a('<span class="fa fa-retweet"></span>&nbsp;Recadastro' . ($count_recadastro > 0 ? 's (' . $count_recadastro . ')' : ''), '#', [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'title' => Yii::t('yii', 'Print') . ' recadastro',
                'aria-label' => Yii::t('yii', 'Print'),
                'onclick' => "
                    krajeeDialog.prompt({label:'$msprint', placeholder:'Digite \"CONFIRMO\"'}, function (result) { 
                        if (result == 'CONFIRMO') {                                     
                            // window.open('$urlPrint', '_blank');
                            // PNotify.removeAll();
                            // new PNotify({
                            //     title: 'Aviso!',
                            //     text: 'Por favor aguarde',
                            //     type: 'info',
                            //     styling: 'fontawesome',
                            //     animate: {
                            //         animate: true,
                            //         in_class: 'rotateInDownLeft',
                            //         out_class: 'rotateOutUpRight'
                            //     }
                            // });
                            // let retorno = undefined;
                            $.ajax({
                                url: '$url',
                                type: 'post',
                                success: function (response) {                                    
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Sucesso!',
                                        text: 'O documento será aberto para impressão em um nova aba',
                                        type: 'success',
                                        styling: 'fontawesome',
                                        animate: {
                                            animate: true,
                                            in_class: 'rotateInDownLeft',
                                            out_class: 'rotateOutUpRight'
                                        }
                                    });
                                    window.open('$urlPrint', '_blank');
                                },
                            });  
                            return false;                          
                        } else {
                            krajeeDialog.alert('Para concluir a operação digite \"CONFIRMO\" com letras maiúsculas!')
                        }
                    });
                ",
                'class' => 'btn btn-outline-warning w-50'
            ]);
        ?>
    </div>
    <!-- <div class="btn-group d-flex" role="group" style="padding-bottom: 5px;">
        <?php
        // echo Html::a('<span class="fas fa-columns"></span>&nbsp;Rúbricas', $rubricas != null ? '#' : 'javascript:naF("rubricas");', [
        //     'value' => $rubricas != null ? Url::to(['/fin-rubricas?matricula=' . $model->matricula, 'modal' => 1]) : '',
        //     'title' => Yii::t('yii', 'Rúbricas'),
        //     'class' => ($rubricas != null ? 'showModalButton ' : '') . 'btn btn-outline-danger w-50 mr-1',
        // ])
        ?>
    </div> -->
</fieldset>