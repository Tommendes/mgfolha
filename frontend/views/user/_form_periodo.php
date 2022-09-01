<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use pagamentos\models\FinParametros; 

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$modal = !isset($modal) ? false : $modal;
$idForm = Yii::$app->security->generateRandomString(8) . '-form';
$containerPJax = $idForm . '-pjax';
$urlFrom = str_replace(Url::base(true) . '/', '', Url::current([], true));
$form = ActiveForm::begin([
            'action' => Url::to(['user/p/' . $model->slug,
                'controller' => $urlFrom,
            ]),
            'id' => $idForm,
            'class' => 'form-inline',
            'type' => ActiveForm::TYPE_INLINE,
            'fieldConfig' => ['options' => ['class' => 'form-group mr-0']],
        ]);

// Recupera os dados dos clientes em @common/components/Clients
$dadosDosClientes = (Yii::$app->clientes->clientes);

// Lista os nomes dos clientes e seus ids para um array
foreach ($dadosDosClientes as $key => $value) {
    $clientes[] = ['idCliente' => trim(strtolower($key)), 'nomeCliente' => ucwords(str_replace('_', ' ', $key))];
}
$title = "Selecione o cliente: ";
$xyz = count($clientes);
foreach ($clientes as $cliente_atual) {
    switch ($xyz) {
        case 2 : $title .= $cliente_atual['nomeCliente'] . " ou ";
            break;
        case $xyz > 2 : $cliente_atual['nomeCliente'] . ", ";
            break;
        default: $title .= $cliente_atual['nomeCliente'];
    }
    $xyz--;
}
$title .= "(Função restrita ao suporte)";
echo (Yii::$app->user->identity->administrador >= 1 && count($clientes) > 1) ?
        $form->field($model, 'cliente')
                ->dropDownList(ArrayHelper::map($clientes, 'idCliente', 'nomeCliente'), [
                    'onchange' => "this.form.submit();",
                    'title' => $title,
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                ]) : '';

// Lista os dominios do clientes e seus ids para um array  
$clienteAtual = strtolower(Yii::$app->user->identity->cliente);
foreach ($dadosDosClientes[$clienteAtual]['dominios'] as $value) {
    $dominios[] = ['idDominio' => $value, 'nomeDominio' => ucfirst($value)];
}
$title = "Selecione o dominio: ";
$xyz = count($dominios);
foreach ($dominios as $dominio_atual) {
    switch ($xyz) {
        case 2 : $title .= $dominio_atual['nomeDominio'] . " ou ";
            break;
        case $xyz > 2 : $dominio_atual['nomeDominio'] . ", ";
            break;
        default: $title .= $dominio_atual['nomeDominio'];
    }
    $xyz--;
}
$title .= "(Função restrita ao suporte)";
echo (Yii::$app->user->identity->administrador >= 1 && count($dominios) > 1) ?
        $form->field($model, 'dominio')
                ->dropDownList(ArrayHelper::map($dominios, 'idDominio', 'nomeDominio'), [
                    'onchange' => "this.form.submit();",
                    'title' => $title,
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                ]) : '';

// Lista os serviços do cliente
foreach ($dadosDosClientes[$clienteAtual]['servicos'] as $key => $value) {
    $servicos[] = ['idServico' => $value, 'nomeServico' => ucfirst($value)];
}
$title = "Selecione o servico: ";
$xyz = count($servicos);
foreach ($servicos as $servico_atual) {
    switch ($xyz) {
        case 2 : $title .= $servico_atual['nomeServico'] . " ou ";
            break;
        case $xyz > 2 : $servico_atual['nomeServico'] . ", ";
            break;
        default: $title .= $servico_atual['nomeServico'];
    }
    $xyz--;
}
$title .= "(Função restrita ao suporte)";
echo (Yii::$app->user->identity->administrador >= 1  && count($servicos) > 1) ?
        $form->field($model, 'base_servico')
                ->dropDownList(ArrayHelper::map($servicos, 'idServico', 'nomeServico'), [
                    'onchange' => "this.form.submit();",
                    'title' => $title,
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                ]) : '';

$anos = '';
$ano_min = FinParametros::find()->where([
            'dominio' => Yii::$app->user->identity->dominio,
        ])->groupBy('ano')->orderBy('ano desc')->all();
foreach ($ano_min as $ano) {
    $anos .= '<option value="' . $ano->ano . '"' . (Yii::$app->user->identity->per_ano == $ano->ano ? ' selected' : '') . '>' . $ano->ano . '</option>' . "\n";
}
$id_per_mes = 'id_per_mes_' . Yii::$app->security->generateRandomString(8);
$id_per_parcela = 'id_per_parc_' . Yii::$app->security->generateRandomString(8);
?>
<div class="form-group mr-0 highlight-addon hide-errors field-user-per_ano required">
    <label class="sr-only has-star" for="user-dominio">Domínio do cliente</label>
    <select id="user-per_ano" onchange="this.form.submit()" class="form-control" name="User[per_ano]" title="Selecione o ano do período" data-toggle="tooltip" data-placement="bottom">
        <?= $anos ?>
    </select>
</div>
<?=
$form->field($model, 'per_mes')->dropDownList(ArrayHelper::map(FinParametros::find()
                        ->select([
                            'mes' => 'mes',
                        ])
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano
                        ])
                        ->asArray()->all(), 'mes', 'mes'), [
    'id' => $id_per_mes,
    'onchange' => "this.form.submit()",
    'title' => "Selecione o mês do período",
    'data-toggle' => "tooltip",
    'data-placement' => "bottom",
])
?>
<?=
$form->field($model, 'per_parcela')->dropDownList(ArrayHelper::map(FinParametros::find()
                        ->select([
                            'parcela' => 'parcela',
                        ])
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes
                        ])
                        ->asArray()->all(), 'parcela', 'parcela'), [
    'id' => $id_per_parcela,
    'onchange' => "this.form.submit()",
    'title' => "Selecione a parcela do mês",
    'data-toggle' => "tooltip",
    'data-placement' => "bottom",
])
?>

<?php
//echo Json::encode($nomesCliente) . ' ' . Json::encode($idClientes);
?>


<?php
ActiveForm::end();
$js = <<< JS
    $('#$idForm').submit(function(e) {
        e.preventDefault();
        this.submit();

        setTimeout(function(){ // Delay for Chrome            
                javascript:location.reload();
        }, 0);
    });
    $('body').on('beforeSubmit', 'form#$idForm', function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
             return false;
        }
        // submit form
        $.ajax({
           url: form.attr('action'),
           type: 'post',
           data: form.serialize(),
           success: function (response) {
                PNotify.removeAll();
                new PNotify({ 
                    title: 'Sucesso', 
                    text: response,
                    type: 'success',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
                javascript:location.reload();
           },
           error: function (response) {
                new PNotify({ 
                    title: 'Erro', 
                    text: response.responseText, 
                    type: 'warning',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
           }
        });
//        location.reload();
        return false;
    }); 
JS;
$this->registerJs($js);
