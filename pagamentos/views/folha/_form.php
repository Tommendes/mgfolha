<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use pagamentos\models\FinParametros;
use yii\helpers\ArrayHelper;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\Folha */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="folha-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'folha-printer-form-inline',
                'options' => ['target' => '_blank'],
//                'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);
    ?>
    <?php
    $model->dominio = Yii::$app->user->identity->dominio;
    $model->status = 10;
    $model->ano = Yii::$app->user->identity->per_ano;
    $model->mes = Yii::$app->user->identity->per_mes;
    $model->parcela = Yii::$app->user->identity->per_parcela;
    ?>

    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'titulo')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'descricao')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'id_negado')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'SUBREPORT_DIR')->hiddenInput()->label(false) ?>
    <div class="row" style="padding-bottom: 5px;">
        <div class="offset-md-1 col-md-10 bd-example">
            <div class="row">
                <div class="col-2">
                    <?php
                    $id_per_mes = 'id_per_mes_' . Yii::$app->security->generateRandomString(8);
                    $id_per_parcela = 'id_per_parc_' . Yii::$app->security->generateRandomString(8);
                    $anos_cliente = FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->groupBy('ano')->orderBy('ano desc')->asArray()->all();
                    $urlLists = Yii::$app->urlManager->createUrl('/fin-parametros/lists/');
                    echo $form->field($model, 'ano')->dropDownList(ArrayHelper::map($anos_cliente, 'ano', 'ano'), [
                        'id' => $id_per_ano = 'id_per_ano_' . Yii::$app->security->generateRandomString(8),
                        'prompt' => 'Selecione...',
                        'onchange' => " 
                            var url = '$urlLists/'+$(this).val()+'?p=ano&r=mes';
                            $.post( url, function( data ) {
                                $('select#$id_per_mes').html( data );
                            });
                        ",
                    ])
                    ?>
                </div>
                <div class="col-2">
                    <?php
                    $meses_cliente = FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                                'ano' => Yii::$app->user->identity->per_ano,
                            ])->groupBy('mes')->orderBy('mes desc')->asArray()->all();
                    echo $form->field($model, 'mes')
                            ->dropDownList(ArrayHelper::map($meses_cliente, 'mes', 'mes'), [
                                'id' => $id_per_mes,
                                'prompt' => 'Selecione uma opção...',
                                'onchange' => " 
                                    var url = '$urlLists/'+$(this).val()+'?p=mes&r=parcela&p2=ano&pv2='+$('#$id_per_ano').val();
                                    $.post( url, function( data ) {
                                        $('select#$id_per_parcela').html( data );
                                    });
                                ",
                    ]);
                    ?>
                </div>
                <div class="col-2">
                    <?php
                    $parcelas_cliente = FinParametros::find()->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                                'ano' => Yii::$app->user->identity->per_ano,
                                'mes' => Yii::$app->user->identity->per_mes,
                            ])->groupBy('parcela')->orderBy('parcela desc')->asArray()->all();
                    echo $form->field($model, 'parcela')
                            ->dropDownList(ArrayHelper::map($parcelas_cliente, 'parcela', 'parcela'), [
                                'id' => $id_per_parcela,
                                'prompt' => 'Selecione uma opção...',
                    ]);
                    ?>
                </div>
                <div class="col-6 form-inline">
                    <div class="row">
                        <?php
                        $QuebraOpcoes = [-1 => 'Nenhuma quebra', 0 => 'Quebra por centro de custo', 1 => 'Quebra por departamento', 2 => 'Quebra por cargo'];
                        $i = 0;
                        foreach ($QuebraOpcoes as $key => $value) {
                            ?>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-check form-group mr-2 mx-md-6 mx-sm-12 align-left">
                                    <input type="radio" id="folha-quebra--<?= $i++ ?>" class="form-check-input checkmark" name="Folha[quebra]" value="<?= $key ?>" <?= $model->quebra == $key ? "checked" : "" ?>
                                           data-index="<?= $i++ ?>" labeloptions="{&quot;class&quot;:&quot;form-check-label&quot;}">
                                    <label class="form-check-label" for="folha-quebra--<?= $i++ ?>"><?= $value ?></label>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5px;">
        <div class="col-12">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-link collapsed" title="Clique para expandir e ver ou para recolher" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Centros de custo
                                </button>
                            </div>
                            <div class="col-md-10">
                                <?=
                                Html::button('Todos', ['id' => 'btn-id-cad-centros', 'class' => 'btn btn-primary active'])
                                . Html::button('Nenhum', ['id' => 'btn-id-cad-centros-desmarcar', 'class' => 'btn btn-primary'])
                                ?>                                
                            </div>
                        </div>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">                            
                            <div class="col-12">
                                <input type="hidden" name="Folha[id_cad_centros]" value="">
                                <div id="folha-id_cad_centros" aria-invalid="false">
                                    <div class="form-group highlight-addon field-folha-id_cad_centros has-success">
                                        <div class="row">
                                            <?php
                                            $CentrosOpcoes = ArrayHelper::map(pagamentos\models\CadCentros::find()
                                                                    ->select([
                                                                        'id' => 'id',
                                                                        'nome' => 'concat(nome_centro,"(",lpad(cast(cod_centro as unsigned), 4, "0"), ")")'
                                                                    ])
                                                                    ->where(['dominio' => Yii::$app->user->identity->dominio])
                                                                    ->orderBy('nome_centro')->asArray()->all(), 'id', 'nome');
                                            $i = 0;
                                            foreach ($CentrosOpcoes as $key => $value) {
                                                ?>
                                                <div class="col-md-4 col-sm-12 form-check"><input type="checkbox" id="folha-id-cad-centros--<?= $i ?>" class="form-check-input checkmark" name="Folha[id_cad_centros][]" value="<?= $key ?>" data-index="<?= $i ?>" labeloptions="{&quot;class&quot;:&quot;form-check-label&quot;}"><label class="form-check-label lbl-impressoes" for="folha-id-cad-centros--<?= $i++ ?>"><?= $value ?></label></div>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="help-block invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-link collapsed" title="Clique para expandir e ver ou para recolher" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Departamentos
                                </button>
                            </div>
                            <div class="col-md-10">
                                <?=
                                Html::button('Todos', ['id' => 'btn-id-cad-departamentos', 'class' => 'btn btn-primary active'])
                                . Html::button('Nenhum', ['id' => 'btn-id-cad-departamentos-desmarcar', 'class' => 'btn btn-primary'])
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-12">
                                <input type="hidden" name="Folha[id_cad_departamentos]" value="">
                                <div id="folha-id_cad_departamentos" aria-invalid="false">
                                    <div class="form-group highlight-addon field-folha-id_cad_departamentos has-success">
                                        <div class="row">
                                            <?php
                                            $DepartamentosOpcoes = ArrayHelper::map(pagamentos\models\CadDepartamentos::find()
                                                                    ->select([
                                                                        'id' => 'id',
                                                                        'nome' => 'concat(departamento,"(",lpad(id_departamento, 4, "0"), ")")'
                                                                    ])
                                                                    ->where(['dominio' => Yii::$app->user->identity->dominio])
                                                                    ->orderBy('departamento')->asArray()->all(), 'id', 'nome');
                                            $i = 0;
                                            foreach ($DepartamentosOpcoes as $key => $value) {
                                                ?>
                                                <div class="col-md-4 col-sm-12 form-check"><input type="checkbox" id="folha-id-cad-departamentos--<?= $i ?>" class=" form-check-input checkmark" name="Folha[id_cad_departamentos][]" value="<?= $key ?>" data-index="<?= $i ?>" labeloptions="{&quot;class&quot;:&quot;form-check-label&quot;}"><label class="form-check-label lbl-impressoes" for="folha-id-cad-departamentos--<?= $i++ ?>"><?= $value ?></label></div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="help-block invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-link collapsed" title="Clique para expandir e ver ou para recolher" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Cargos
                                </button>
                            </div>
                            <div class="col-md-10">
                                <?=
                                Html::button('Todos', ['id' => 'btn-id-cad-cargos', 'class' => 'btn btn-primary active'])
                                . Html::button('Nenhum', ['id' => 'btn-id-cad-cargos-desmarcar', 'class' => 'btn btn-primary'])
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-12">
                                <input type="hidden" name="Folha[id_cad_cargos]" value="">
                                <div id="folha-id_cad_cargos" aria-invalid="false">
                                    <div class="form-group highlight-addon field-folha-id_cad_cargos has-success">
                                        <div class="row">
                                            <?php
                                            $CargosOpcoes = ArrayHelper::map(pagamentos\models\CadCargos::find()
                                                                    ->select([
                                                                        'id' => 'id',
                                                                        'nome' => 'concat(nome,"(",lpad(id_cargo, 4, "0"), ")")'
                                                                    ])
                                                                    ->where(['dominio' => Yii::$app->user->identity->dominio])
                                                                    ->orderBy('nome')->asArray()->all(), 'id', 'nome');
                                            $i = 0;
                                            foreach ($CargosOpcoes as $key => $value) {
                                                ?>
                                                <div class="col-md-4 col-sm-12 form-check"><input type="checkbox" id="folha-id-cad-cargos--<?= $i ?>" class=" form-check-input checkmark" name="Folha[id_cad_cargos][]" value="<?= $key ?>" data-index="<?= $i ?>" labeloptions="{&quot;class&quot;:&quot;form-check-label&quot;}"><label class="form-check-label lbl-impressoes" for="folha-id-cad-cargos--<?= $i++ ?>"><?= $value ?></label></div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="help-block invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-link collapsed" title="Clique para expandir e ver ou para recolher" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Locais de trabalho
                                </button>
                            </div>
                            <div class="col-md-10">
                                <?=
                                Html::button('Todos', ['id' => 'btn-id-cad-locais-trabalho', 'class' => 'btn btn-primary active'])
                                . Html::button('Nenhum', ['id' => 'btn-id-cad-locais-trabalho-desmarcar', 'class' => 'btn btn-primary'])
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-12">
                                <input type="hidden" name="Folha[id_cad_locais_trabalho]" value="">
                                <div id="folha-id_cad_locais_trabalho" aria-invalid="false">
                                    <div class="form-group highlight-addon field-folha-id_cad_locais_trabalho has-success">
                                        <div class="row">
                                            <?php
                                            $LocaisOpcoes = ArrayHelper::map(pagamentos\models\CadLocaltrabalho::find()
                                                                    ->select([
                                                                        'id' => 'id',
                                                                        'nome' => 'concat(nome,"(",lpad(id_local_trabalho, 4, "0"), ")")'
                                                                    ])
                                                                    ->where(['dominio' => Yii::$app->user->identity->dominio])
                                                                    ->orderBy('nome')->asArray()->all(), 'id', 'nome');
                                            $i = 0;
                                            foreach ($LocaisOpcoes as $key => $value) {
                                                ?>
                                                <div class="col-md-4 col-sm-12 form-check"><input type="checkbox" id="folha-id-cad-locais-trabalho--<?= $i ?>" class=" form-check-input checkmark" name="Folha[id_cad_locais_trabalho][]" value="<?= $key ?>" data-index="<?= $i ?>" labeloptions="{&quot;class&quot;:&quot;form-check-label&quot;}"><label class="form-check-label lbl-impressoes" for="folha-id-cad-locais-trabalho--<?= $i++ ?>"><?= $value ?></label></div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="help-block invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingServidores">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-link collapsed" title="Clique para expandir e ver ou para recolher" type="button" data-toggle="collapse" data-target="#collapseServidores" aria-expanded="false" aria-controls="collapseServidores">
                                    Servidores
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="collapseServidores" class="collapse" aria-labelledby="headingServidores" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-12">
                                <!--<input type="hidden" name="Folha[id_cad_servidores]" value="">-->
                                <?php
                                echo '<label class="control-label">Digite nome, matricula ou cpf para selecionar um ou mais servidores. Se nenhum for selecionado então <strong>todos</strong> serão inseridos no relatório</label>';
                                echo Typeahead::widget([
                                    'name' => 'th_cad_servidores',
                                    'options' => ['placeholder' => Yii::t('yii', 'Filter as you type ...')],
                                    'scrollable' => true,
                                    'pluginOptions' => ['highlight' => true],
                                    'dataset' => [
                                        [ 
                                            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                            'display' => 'value',
                                            'remote' => [
                                                'url' => Url::to(['cad-servidores/z']) . '?id=%QUERY',
                                                'wildcard' => '%QUERY'
                                            ]
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        'typeahead:select' => 'function(ev, resp) { 
                                            $("#itensServidores").val(addItem(($("#itensServidores").val() + ", " + resp.value))); 
                                            $("#folha-id_cad_servidores").val(addItem(($("#folha-id_cad_servidores").val() + ", " + resp.id))); 
                                        }',
                                    ]
                                ]);
                                echo Html::label('Servidores selecionados', 'itensServidores');
                                echo '<textarea readonly class="form-control-plaintext bd-example" id="itensServidores" rows="5"></textarea>';
                                echo $form->field($model, 'id_cad_servidores')->hiddenInput()->label(false);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('yii', 'Print'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div> 
<?php
$js = <<<JS
const addItem = function(valNovo){ 
    return valNovo.substring(0,1) == ',' ? valNovo.substring(1).trim() : valNovo.trim()
}
$(document).ready(function(){
    $("#btn-id-cad-centros").click(function(){     
        $('input[name="Folha[id_cad_centros][]"]').prop('checked', true)
    })
    $("#btn-id-cad-centros-desmarcar").click(function(){   
        $('input[name="Folha[id_cad_centros][]"]').prop('checked', false)
    })
    $("#btn-id-cad-departamentos").click(function(){  
        $('input[name="Folha[id_cad_departamentos][]"]').prop('checked', true)
    })
    $("#btn-id-cad-departamentos-desmarcar").click(function(){  
        $('input[name="Folha[id_cad_departamentos][]"]').prop('checked', false)
    })
    $("#btn-id-cad-cargos").click(function(){     
        $('input[name="Folha[id_cad_cargos][]"]').prop('checked', true)
    })
    $("#btn-id-cad-cargos-desmarcar").click(function(){     
        $('input[name="Folha[id_cad_cargos][]"]').prop('checked', false)
    })
    $("#btn-id-cad-locais-trabalho").click(function(){        
        $('input[name="Folha[id_cad_locais_trabalho][]"]').prop('checked', true)
    })
    $("#btn-id-cad-locais-trabalho-desmarcar").click(function(){        
        $('input[name="Folha[id_cad_locais_trabalho][]"]').prop('checked', false)
    })
    // Marcar todos os itens da tela
    $("#btn-id-cad-centros").click()
    $("#btn-id-cad-departamentos").click()
    $("#btn-id-cad-cargos").click()
    $("#btn-id-cad-locais-trabalho").click()
})
JS;
$this->registerJs($js);
$js = <<<JS
        alert('Esta página é melhor visualizada em desktop')
JS;
$this->registerJs(Yii::$app->mobileDetect->isMobile() ? $js : '');
?>
