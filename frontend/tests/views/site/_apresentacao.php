<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Url;
use kartik\affix\Affix;
use kartik\helpers\Html;
use atvs\models\Params;

$this->title = Yii::$app->name . ' | Apresentação';

$btn_mais = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_mais.png';
$btn_mais_vd = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_mais_vd.png';
$btn_duplicar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_duplicar.png';
$btn_lapis = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_lapis.png';
$btn_ver = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_ver.png';
$btn_canc_mudancas = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_canc_mudancas.png';
$btn_salvar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_salvar.png';
$btn_salvar_registro = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_salvar_registro.png';
$btn_lixeira = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_lixeira.png';
$btn_novo_cadastro = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_novo_cadastro.jpg';
$btn_versionar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_versionar.jpg';
$btn_map_marker = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_map_marker.png';
$btn_pesquisar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_pesquisar.jpg';
$icn_search = Html::icon('search');
$btn_search = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_search.png';
$btn_ok_azul = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_ok_azul.png';
$btn_nova_prospec = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_nova_prospeccao.png';
$btn_desfazer = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . 'btn_desfazer.png';

$apresentacao = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Desde 2014, nossa equipe vem trabalhando para oferecer a melhor aplicação para gestão de empresas comerciais.</p>  
            </div>
        </div>

        <p>O LynkOs foi desenvolvido por quem usa. E isso quer dizer que assim como você, nós buscamos o melhor resultado.</p>

        <p>Nossas apresentações de dados são lógicas e coerentes.</p>

        <p>Visamos a excelencia e precisão nos dados.</p>

        <p>Sem dúvida, o LynkOs é o atalho para o seu resultado.</p>
HTML;
$logica = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>A lógica por trás do LynkOs depende dos dados informados.</p>  
            </div>
        </div>

        <p>Os dados são a maior riqueza em termos de sistema.</p>
        <p>Uma vez que sua equipe informar os dados com a maior precisão e riqueza possível, o LynkOs cruza as informações e retorna os dados filtrados e direcionados para a gestão da empresa.</p>
        <p>Alguns dos dados mais importantes são o cadastro dos Clientes e de Leads. Com essas informações o LynkOs será capaz de demostrar em tempo real, quais clientes estão ou não comprando, sendo ou não visitados e etc. Assim, ficará mais fácil para sua gestão comercial prever quais clientes precisam de mais atenção e efetivamente dedicar mais tempo apenas com o que mais importa: seus clientes.</p>
HTML;
$segurança = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>O LynkOs foi desenvolvido utilizando as mais modernas práticas de segurança.</p>  
            </div>
        </div>

        <p>O LynkOs está hospedado em servidores com grande capacidade de armazenamento e processamento e tudo isso com redundância.</p>
        <p>É verdade. Isso é muito técnico. Mas é pra você saber que estamos sempre preocupados com a segurança dos seus dados.</p>
        <p>Um gravador de eventos armazena em tempo real cada operação feita no seu banco de informações. Sempre que alguém acessar uma informação ou clicar num botão o LynkOs irá registrar essa operação.</p>
        <p>O LynkOs utiliza mais uma camada de segurança que é a sua localização. Por isso não deixe de permitir que o LynkOs acesse a localização de seu navegador.</p>
        <p>No LynkOs os usuários tem alçadas liberadas ou não no seu perfil. O gestor da empresa libera ou nega as funções para cada usuário ativo.</p>

        <ol style="list-style-type:lower-alpha; padding-left: 32px">
            <li>Servidores seguros na web</li>
            <li>Backup diário redundante dos dados da aplicação</li>
            <li>Localização</li>
            <li>Gravador de eventos</li>
            <li>Gestão de telas com alçada por usuário</li>
        </ol>
HTML;
$importancia = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>CRM (Customer Relationship Management) é um termo muitas vezes mal empregado que muitas empresas utilizam para se referir ao software e a outras tecnologias utilizadas para implantar soluções de CRM. O CRM é uma filosofia, uma estratégia e um processo.Ela inclui os três pilares e outros princípios de marketing de relacionamento, e se baseia em dados e conversas de clientes, e é facilitada pela tecnologia. Fonte: <a href="https://pt.wikipedia.org/wiki/Sistemas_de_CRM" target="_blank">wikipedia</a></p>
            </div>
        </div>
        
        <p>Assim, o LynkOs não é um CRM. O LynkOs é uma aplicação web que vai te ajudar a cruzar as informações mais relevantes ao seu negócio.</p>
        
        <p>Sem uma boa ferramenta como o LynkOs sua empresa dependerá da memória de algumas pessoas e <strong>a equipe deixará de prever possíveis negócios</strong>.</p>
HTML;
$login_000 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '000.jpg';
$login_001 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '001.jpg';
$linkos = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p><strong>Importante!</strong></p>
                <p>O LynkOs é responsivo e se adapta a qualquer tamanho de tela. Por isso, as telas a seguir podem ser um pouco diferentes em formato ou tamanho a depender de onde você está acessando esta apresentação</p>
            </div>
        </div>
HTML;
$login = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <p class="text-center"><img src="$login_000" alt="Tela de Login" style="width:100%;max-width:300px;"></p>
                        <p class="text-center">Após o seu acesso ter sido liberado, o usuário deverá acessar a aplicação através de seu login(email ou nome de usuário) e senha previamente cadastrados</p>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <p class="text-center"><img src="$login_001" alt="Tela de Login" style="width:100%;max-width:300px;"></p>
                        <p class="text-center">Caso seja seu primeiro acesso, você deverá se registrar autilizando a tela de registro</p>
                        <p class="text-center">Para acessar clique em "Crie sua conta" conforma a imagem anterior</p>
                    </div>
                </div>
            </div>
        </div>
HTML;
$registro_us_002 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002.jpg';
$registro_us_002_iniciar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_iniciar.jpg';
$registro_us_002_reenviar = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_reenviar.jpg';
$registro_us_002_email1 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_email1.jpg';
$registro = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <p class="text-center"><img src="$registro_us_002" alt="Registre-se" style="width:100%;max-width:100%;"></p>
                    </div>        
                    <div class="col-xs-12 col-md-6">
                        <p class="text-center">Antes de usar o sistema você deverá confirmar que foi você quem solicitou o registro</p>
                    </div>
                </div>
            </div>
        </div>        
        <p>Após informar pela primeira vez o seu usuário, email e senha para acessar o sistema, vá até sua caixa de entrada de emails do email informado e localize a mensagem contendo o código de confirmação conforme abaixo:</p>
        <p class="text-center"><img src="$registro_us_002_email1" alt="Email 01 de confirmação de registro" style="width:100%;max-width:100%;"></p>
        <p>Insira seu telefone com DDD e o código de confirmação que foi entregue no email e clique em "Iniciar o uso do sistema".<img src="$registro_us_002_iniciar" alt="Botão iniciar" style="width:180px;"></p>
        <p><strong>Importante!</strong></p>
        <p>Caso não tenha recebido o email em sua caixa de entrada, por favor verifique também sua caixa de lixo eletrônico ou <em>SPAM</em>. Depois de algum tempo, caso o email não seja entregue, clique em "Reenviar código".<img src="$registro_us_002_reenviar" alt="Botão reenviar" style="width:130px;"></p>
HTML;
$boas_vindas_002_banner = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_banner.jpg';
$boas_vindas_002_localiz = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_localiz.jpg';
$boas_vindas_002_email2 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '002_email2.jpg';
$boas_vindas = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p class="text-center"><img src="$boas_vindas_002_banner" alt="Boas vindas" style="width:100%;max-width:470px;"></p>
                <p class="text-center">Após seguir os passos acima, seja bem vindo ao Universo LynkOs</p>
            </div>
        </div>        
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p>Uma das medidas de segurança do LynkOs é capturar a localização dos usuários conectados. Assim, ao ser solicitado a fornecer sua localização, por favor concorde e se desejar memorize a sua decisão conforme na imagem a seguir.</p>
            </div>        
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$boas_vindas_002_localiz" alt="Compartilhar sua localização" style="width:100%;max-width:420px;"></p>
            </div>        
        </div>        
        <p>Para confirmar que tudo correu bem com seu registro, você deverá ter recebido em seu email uma segunda mensagem de confirmação.</p>
        <p class="text-center"><img src="$boas_vindas_002_email2" alt="Email 01 de boas vindas" style="width:100%;max-width:100%;"></p>  
HTML;
$inicio_003 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '003.jpg';
$inicio = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>O primeiro acesso após se registrar</p>
                <img src="$inicio_003" alt="Tela inicial" align="middle" style="width:100%;max-width:900px;">
            </div>
        </div>
        <p>Na tela inicial, após o registro inicial e login você deverá selecionar uma das opções a seguir, conforme a imagem acima:</p>        
        <ol style="padding-left: 32px">
            <li>Faço parte de uma equipe existente</li>
            <li>Sou o gestor de uma nova equipe</li>
        </ol>
        <p>A seguir, para cada caso siga as instruções abaixo:</p>   
HTML;
$inicio_003_usuario = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '003_usuario.jpg';
$inicio_usuario = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Faço parte de uma equipe existente</p>
                <img src="$inicio_003_usuario" alt="Tela inicial" align="middle" style="width:100%;max-width:100%;">
            </div>
        </div>
        <p>Se você é membro de uma equipe existente ou de uma empresa que já usa o LynkOs você deverá informar ao seu gestor o código sombreado conforme na imagem acima</p>        
HTML;
$inicio_003_gestor = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '003_gestor.jpg';
$inicio_gestor = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Sou o gestor de uma nova equipe</p>
                <img src="$inicio_003_gestor" alt="Tela inicial" align="middle" style="width:100%;max-width:100%;">
            </div>
        </div>
        <p>Se sua empresa vai começar a usar o Lynkos, então...</p>        
        <ol style="padding-left: 32px">
            <li>Clique na palavra "aqui" conforme demostrado na imagem acima</li>
            <li>Na tela a seguir preencha os dados de sua empresa</li>
        </ol>
HTML;
$empresa_004 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '004_empresa.jpg';
$empresa_004_resp = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '004_resp_emp.jpg';
$perfil_005_menu = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '005_perfil_menu.jpg';
$cadastro_006_novo_endereco = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_endereco.jpg';
$cadastro_006_novo_contato = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_contato.jpg';
$empresa = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Registre sua empresa</p>
                <img src="$empresa_004" alt="Registrar empresa" align="middle" style="width:100%;max-width:100%;">
            </div>
        </div>
        <p>Informe na tela de registro o máximo de informações possível. Alguns dados são obrigatórios.</p>        
        <p><em>Para maiores informações sobre como utilizamos seus dados, leia nossa <a href="https://mgfolha.com.br/atvs/suporte/artigos/politica-privacidade" target="_blank">"Política de privacidade"</a></em></p>        
        <p>Para alterar os dados da empresa o usuário precisará ter acesso de gestor da aplicação. Caso necessário solicite acesso ou solicite suporte.</p>
        <p>Os dados informados nessa tela serão os mesmo que serão informados nos documentos.</p>
        <p>Na tela a seguir, você poderá corrigir ou não os seus dados profissionais informados. Caso deseje alterar algum dado, clique no lápis no canto superior direito da janela. Se quiser, poderá inserir endereços ou formas de contato utilizando para isso as opções + Endereços <img src="$btn_mais" alt="Botão mais"> e/ou + Contatos <img src="$btn_mais" alt="Botão mais">.</p>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_novo_endereco" alt="Novo endereço no cadastro" style="width:100%;max-width:300px;"></p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_novo_contato" alt="Novo contato no cadastro" style="width:100%;max-width:300px;"></p>
            </div>
        </div>
        <p><img src="$empresa_004_resp" alt="Registrar responsável" align="middle" style="width:100%;max-width:100%;"></p>
        <p>Após esse passo a passo você perceberá que terá acesso a todas as operações do menu pois seu usuário foi alterado para ter acesso de gestor.</p>
        <p><strong>Importante!</strong></p>
        <p>O perfil de <em>Gestor</em> tem acesso a todas as informações e funções da aplicação. Como gestor, o usuário poderá inclusive ativar ou desativar usuários na aplicação.</p>
        <div class="row">
            <div class="col-xs-12 col-md-6">
               <ol style="padding-left: 32px">
                    <li>Para acessar seu perfil clique em seu nome no canto superior da aplicação e em seguida clique em perfil</li>
                    <li>Clique em Redefinir senha para receber email de troca de senha</li>
                    <li>Clique em Sair para sair da aplicação</li>
                </ol>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$perfil_005_menu" alt="Menu perfil" style="width:100%;max-width:300px;"></p>
            </div>
        </div>
HTML;
$perfil_005 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '005.jpg';
$perfil_005_btn_facebook = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '005_btn_facebook.jpg';
$perfil_005_btn_trocar_senha = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '005_btn_trocar_senha.jpg';
$perfil = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Seu perfil</p>
                <img src="$perfil_005" alt="Perfil de usuário" align="middle" style="width:100%;max-width:100%;">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$perfil_005_btn_facebook" alt="Acessar com facebook" style="width:100%;max-width:300px;"></p>
                <p class="text-center">Caso já não tenha criado seu perfil com o facebook, clique nesse botão para passar a acessar a aplicação com apenas um clique</p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$perfil_005_btn_trocar_senha" alt="Trocar senha" style="width:100%;max-width:240px;"></p>
                <p class="text-center">Se for necessário e a qualquer tempo, você poderá resetar sua senha. Para isso, utilize a função no menu, conforme explicado antes ou clique nesse botão para receber seu email com um link de de redefinição</p>
            </div>
        </div>
HTML;
$botoes = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p class="text-center">Devido à padronização visual, é possível definir os botões e o menu conforme a seguir:</p> 
            </div>
        </div>
        <p>Botões</p> 
	<ol style="list-style-type:lower-alpha; padding-left: 32px">
		<li>Inserir registro <img src="$btn_mais" alt="Inserir"> ou <img src="$btn_mais_vd" alt="Inserir verde">. Em alguns casos o ícone será acompanhado de um texto conforme a seguir: <img src="$btn_novo_cadastro"alt="Novo cadastro"></li>
		<li>Duplicar/Versionar registro <img src="$btn_duplicar" alt="Inserir">. Em alguns casos o ícone será acompanhado de um texto conforme a seguir: <img src="$btn_versionar"alt="Versionar/Duplicar"></li>
		<li>Pesquisar $icn_search. Em alguns casos o ícone será acompanhado de um texto conforme a seguir: <img src="$btn_pesquisar"alt="Pesquisar"></li>
		<li>Nova prospecção <img src="$btn_map_marker" alt="Marcar no mapa">. Em alguns casos o ícone será acompanhado de um texto conforme a seguir: <img src="$btn_nova_prospec" alt="Nova prospecção"></li></li>
		<li>Editar registro <img src="$btn_lapis" alt="Editar"></li>
		<li>Excluir/Cancelar/Desativar registro <img src="$btn_lixeira" alt="Excluir/Cancelar/Desativar"></li>
		<li>Desfazer edição/criação <img src="$btn_desfazer" alt="Desfazer"></li>
		<li>Ver registro <img src="$btn_ver" alt="Ver"></li>
		<li>Cancelar mudanças <img src="$btn_canc_mudancas" alt="Cancelar mudanças"></li>
		<li>Salvar edição <img src="$btn_salvar" alt="Salvar edição">. Em alguns casos o ícone será acompanhado de um texto conforme a seguir: <img src="$btn_salvar_registro"></li>
	</ol>       
        
        <p>Menu lateral</p>        
        
        <ol style="padding-left: 32px">
            <li>Cadastros
                <ol style="list-style-type:none; padding-left: 32px">
                        <li>Utilize para registrar
                            <ol style="list-style-type:none; padding-left: 32px">
                                    <li><strong>Arquitetos <sup>*</sup></strong></li>
                                    <li><strong>Pessoas</strong></li>
                                    <li><strong>Empresas</strong></li>
                            </ol>
                        </li>
                </ol>
                <ol style="list-style-type:none; padding-left: 32px">
                    <li style="font-size: 8px;"><sup>*</sup> Esta opção é customizável</li>
                </ol>
            </li>
            <li>Prospecção
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Utilize para consultar ou registrar visitas pessoais ou contatos feitos aos clientes</li>
                </ol>
            </li>
            <li>Gestor de documentos (Ged)
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Utilize para consultar ou registrar propostas, pedidos e documentos em geral</li>
                </ol>
            </li>
            <li>Produtos
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Utilize para consultar ou registrar produtos que serão oferecidos nas propostas comerciais</li>
                </ol>
            </li>
            <li>Propostas
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Utilize para consultar ou registrar propostas comerciais <sup>*</sup></li>
                </ol>
                <ol style="list-style-type:none; padding-left: 32px">
                    <li style="font-size: 8px;"><sup>*</sup> Este módulo está em fase de conclusão</li>
                </ol>
            </li>
            <li>Pós vendas  
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Todos os pedidos geram um registro no pós vendas e estes podem ser consultados nessa tela</li>
                </ol>
            </li>
            <li>Suporte aos clientes
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Ordens de serviço e chamados externos serão gerados e consultados com essa opção</li>
                </ol>
            </li>
            <li>Montagem
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Caso venda algo que precise ser montado, utilize essa função para gerar ordens de serviço específicas para esse tipo de atividade</li>
                </ol>
            </li>
            <li>Ajuda
                <ol style="list-style-type:lower-alpha; padding-left: 32px">
                    <li>Histórico das atualizações do sistema</li>
                    <li>Artigos do suporte</li>
                    <li>Esta apresentação</li>
                    <li>Acesso remoto</li>
                </ol>
            </li>
            <li>Gestão
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Usuários</li>
                    <li>Empresa
                        <ol style="list-style-type:lower-alpha; padding-left: 32px">
                            <li>Nesta tela você poderá acessar os dados da empresa bem como os alterar</li>
                            <li>Resumo dos perfis de usuários</li>
                            <li>Histórico de faturamentos gerados pelo LynkOs</li>
                        </ol>
                    </li>
                    <li>Eventos do sistema
                        <ol style="list-style-type:none; padding-left: 32px">
                            <li>Filtre os dados por usuário, data e diversas outras opções para saber quando, como e por quem determinada operação foi feita</li>
                            <li>É possível utilizar uma das predefinições a seguir:
                                <ol style="list-style-type:lower-alpha; padding-left: 32px">
                                    <li>Todos</li>
                                    <li>Hoje</li>
                                    <li>Esta semana</li>
                                    <li>Última semana</li>
                                    <li>Este mês</li>
                                    <li>Este ano</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                </ol>
            </li>
            <li>Parâmetros
                <ol style="list-style-type:lower-alpha; padding-left: 32px">
                    <li>Ged</li>
                </ol>
            </li>
        </ol>
        
        <p>Menu superior</p>        
        
        <ol style="padding-left: 32px">
            <li>Contato</li>
            <li>Sobre</li>
            <li>Usuário
                <ol style="list-style-type:none; padding-left: 32px">
                    <li>Perfil</li>
                    <li>Redefinir senha</li>
                    <li>Sair do sistema</li>
                </ol>
            </li>
        </ol>
HTML;
$menu_00 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '003.jpg';
$menu = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>O primeiro acesso após se registrar</p>
                <img src="$inicio_003" alt="Tela inicial" align="middle" style="width:100%;max-width:900px;">
            </div>
        </div>
        <p>Na tela inicial, após o registro inicial e login você deverá selecionar uma das opções a seguir, conforme a imagem acima:</p>        
        <ol style="padding-left: 32px">
            <li>Faço parte de uma equipe existente</li>
            <li>Sou o gestor de uma nova equipe</li>
        </ol>
        <p>A seguir, para cada caso siga as instruções abaixo:</p>        
        
        <p>Menu</p>        
        
        <ol style="padding-left: 32px">
	<li>Cadastros
	<ol style="list-style-type:lower-alpha; padding-left: 32px">
		<li>Servidores</li>
		<li>Fornecedores</li>
		<li>Empresa</li>
	</ol>
	</li>
	<li>Patrimônio
	<ol style="list-style-type:lower-alpha; padding-left: 32px">
		<li>Objetos</li>
		<li>Unidades</li>
		<li>Setores</li>
		<li>Departamentos</li>
		<li>Locaç&otilde;es</li>
	</ol>
	</li>
	<li>Documentos
	<ol style="list-style-type:lower-alpha; padding-left: 32px">
		<li>Termo circunstanciado administrativo (Sendo concluído)</li>
		<li>Termo de responsabilidade</li>
	</ol>
	</li>
	<li>Contato</li>
	<li>Suporte</li>
	<li>Configuraç&otilde;es</li>
	<li>Usuário
	<ol style="list-style-type:lower-alpha; padding-left: 32px">
		<li>Perfil</li>
		<li>Resetar senha</li>
		<li>Logout</li>
	</ol>
	</li>
</ol>
HTML;
$cadastro_006 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006.jpg';
$cadastro_006_novo_cadastro = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_novo_cadastro.png';
$cadastro_006_nome_repetido = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_nome_repetido.jpg';
$cadastro_006_cpf_repetido = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cpf_repetido.jpg';
$cadastro_006_form_search = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_form_search.jpg';
$cadastro_006_prospecto = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_prospecto.jpg';
$cadastro_006_view = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_prospecto.jpg';
$cadastro_cons = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Grid de consultas</p>
                <p><img src="$cadastro_006" alt="Cadastro de pessoas e fornecedores" style="width:100%;max-width:100%;"></p>
            </div>
        </div>
        <p>Na tela acima você poderá pesquisar utilizando várias informações inclusive em conjunto para localizar um registro. Poderá por exemplo pesquisar por tipo de registro, área de atuação, nome e poderá também pesquisar por mais de um filtro.</p>        
        <p>Através desta tela o usuário poderá também: ver <img src="$btn_ver" alt="Ver">, editar <img src="$btn_lapis" alt="Editar"> ou excluir <img src="$btn_lixeira" alt="Excluir"> o registro.</p>
        <p>Se desejar poderá utilizar o filtro avançado clicando o ícone laranja <img src="$btn_search" alt="Botão search"> na parte superior da tela acima e conforme pode ver a seguir um formulário de pesquisa surgirá. Então você poderá usar ainda mais opções de consulta.</p>
        <p><img src="$cadastro_006_form_search" alt="Formulário de consulta" style="width:100%;max-width:100%;"></p>
HTML;
$cadastro_cadas = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p><img src="$cadastro_006_novo_cadastro" alt="Formulário de cadastro" style="width:100%;max-width:100%;"></p>
            </div>
        </div>
        <p>Para cadastrar empresas, fornecedores ou pessoas em geral, nessa tela devem ser informados o máximo de dados disponíveis. Alguns dados como CPF ou CNPJ serão obrigatórios e verificados.</p>        
        <p>Após concluir o registro não esqueça de clicar em salvar para salvar os dados registrados.</p>        
        <p>Observe que no menu vertical à direita da tela (caso use um computador de mesa ou notebook) ou abaixo (caso use dispositivo móvel), podem ser cadastrados infinitos endereços como p.ex.: entrega, faturamento, financeiro e qualquer departamento  e também infinitos telefones e formas de contato. Apenas os primeiros cinco aparecerão na lista desta tela. Para acessar todos os registros de endereços e contatos disponíveis clique em "Endereços" para ver os endereços ou em "Contatos" para ver os contatos.</p>        
        <p>Esse menu vertical só será mostrado após o cadastro ser salvo.</p>        
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_novo_endereco" alt="Novo endereço no cadastro" style="width:100%;max-width:300px;"></p>
                <p>Utilize esta opção para registrar quantos endereços forem necessários.</p>        
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_novo_contato" alt="Novo contato no cadastro" style="width:100%;max-width:300px;"></p>
                <p>É possível que você queira registrar vários emails, telefones, sites e muitas outras informações do seu novo cadastro.</p>        
            </div>
        </div>        
HTML;
$cadastro_prosp = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p><strong>Prospecto?</strong></p>
                <p class="text-center"><img src="$cadastro_006_prospecto" alt="Prospecto"> Para o LynkOs assim como de fato se define, um prospecto é um cadastro que ainda não foi validado.</p>
            </div>
        </div>
        <p>Via de regra, todo cadastro ou pertence a um fornecedor ou a um prospectivo cliente. Assim, ou o usuário afirma no ato do registro que ele não é um prospecto ou numa outra ocasião ele terá que fazer isso. Caso contrário, esse cadastro não poderá ser utilizado para registrar propostas e pedidos pois lhe falta a confirmação de que é de fato um registro validado.</p>
        <p>Mesmo não sendo validado, ele poderá ser utilizado para registrar visitas e suporte técnico. Apenas pedidos serão restritos para um prospecto.</p>    
HTML;
$cadastro_regras = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p><strong>Regras de validação</strong></p>
                <p><img src="$cadastro_006_cpf_repetido" alt="CPF/CNPJ repetido" style="width:100%;max-width:100%;"></p>
            </div>
        </div>
        <p>Duas das regras de validação mais importantes do cadastro não permitem que se cadastre duas vezes o mesmo CPF ou CNPJ.</p>
        <p>Sendo assim, caso o usuário tente registrar um documento já informado o sistema emitirá uma mensagem como a acima indicando isso. Nessa situação o usuário terá três opções:</p>
        <ol style="padding-left: 32px">
            <li>Editar o regitro existente</li>
            <li>Trocar o número do documento informado</li>
            <li>Desistir de cadastrar</li>
        </ol>
        <p>Caso opte por editar o registro existente, basta responder clicando no botão "OK" na mensagem que surgiu</p>
        <p>Caso contrário, basta clicar em cancelar e trocar o nr do documento ou desistir de cadastrar. O botão <img src="$btn_salvar_registro" alt="Botão salvar"> estará desabilitado</p>
HTML;
$cadastro_repetido = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p><img src="$cadastro_006_nome_repetido" alt="Nome repetido" style="width:100%;max-width:100%;"></p>
            </div>
        </div>
        <p>Outra possível situação é o usuário estar registrando um cadastro com mesmo nome de outro já existente.</p>
        <p>Nesse caso o usuário terá três opções:</p>
        <ol style="padding-left: 32px">
            <li>Editar um dos regitros existentes localizados clicando num deles</li>
            <li>Pressionar cancelar e continuar cadastrando, pois às vezes, só para mencionar um exemplo uma empresa tem vários CNPJ e apenas uma razão social. Ou pode ser mesmo que hajam duas empresas ou pessoas com o mesmo nome</li>
            <li>Desistir de cadastrar</li>
        </ol>
        <p>Caso opte por editar o registro existente, basta responder clicando no botão "OK" na mensagem que surgiu e prosseguir</p>        
HTML;
$cadastro_006_editar_registro = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastros_view.jpg';
$cadastro_006_novo_ged = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_ged.jpg';
$cadastro_006_novo_prospeccao = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_prospeccao.jpg';
$cadastro_006_novo_pv = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_pv.jpg';
$cadastro_006_novo_suporte = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_suporte.jpg';
$cadastro_006_novo_montagem = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_cadastro_novo_montagem.jpg';
$cadastro_editar = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p><img src="$cadastro_006_editar_registro" alt="Editar um cadastro" style="width:100%;max-width:100%;"></p>
            </div>
        </div>
        <p>Para editar um registro de cadastro você poderá clicar no ícone <img src="$btn_lapis" alt="Editar um cadastro"> no grid de consultas apresentado no início dessa sessão.</p>
        <p>Outra opção é clicar no ícone <img src="$btn_ver" alt="Ver um cadastro"> apenas para ver o registro e após ser direcionado para essa página, clicar no ícone <img src="$btn_lapis" alt="Editar um cadastro"> caso deseje alterar algum dado. Lembre-se de que tudo o que for editado ou inserido será registrado em seu nome de usuário.</p>
        <p>Nesta tela também é possível:</p>      
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <ol style="list-style-type:none; padding-left: 32px">
                    <li><img src="$cadastro_006_novo_endereco" alt="Endereços"></li>
                    <li class="text-center">Consultar, inserir e editar endereços e inserir visitas e contatos com o cliente</li>
                    <li><img src="$cadastro_006_novo_contato" alt="Contatos"></li>
                    <li class="text-center">Consultar, inserir e editar meios de contatos</li>
                    <li><img src="$cadastro_006_novo_ged" alt="Ged"></li>
                    <li class="text-center">Consultar, inserir e editar documentos</li>
                </ol>
            </div>
            <div class="col-xs-12 col-md-6">
                <ol style="list-style-type:none; padding-left: 32px">
                    <li><img src="$cadastro_006_novo_prospeccao" alt="Prospecção"></li>
                    <li class="text-center">Consultar visitas e contatos com o cliente</li>
                    <li><img src="$cadastro_006_novo_pv" alt="Pós vendas"></li>
                    <li class="text-center">Consultar registros de pós vendas</li>
                    <li><img src="$cadastro_006_novo_suporte" alt="Ordens de serviço"></li>
                    <li class="text-center">Consultar, inserir e editar ordens de serviço e suporte ao cliente</li>
                    <li><img src="$cadastro_006_novo_montagem" alt="Montagem"></li>
                    <li class="text-center">Consultar, inserir e editar ordens de serviço de montagem de produtos</li>
                </ol>
            </div>
        </div> 
HTML;
$cadastro_006_endereco_form = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_endereco_form.jpg';
$cadastro_006_endereco_view = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_endereco_view.jpg';
$cadastro_endereco = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Registrar e editar endereços</p>
                <p class="text-center"><img src="$cadastro_006_novo_endereco" alt="Novo endereço"></p>
            </div>
        </div>
        <p>Para inserir um registro de endereço no cadastro você poderá clicar no ícone <img src="$btn_mais" alt="Novo endereço"> no menu lateral do registro do cliente conforme acima</p>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p>Você será direcionado para o formulário a seguir onde deverá informar os dados disponíveis. A maioria dos dados são obrigatórios. Assim, certifique-se de ter ao menos o CEP e o número do imóvel</p>
                <p>Ao informar o CEP, o sistema fará uma pesquisa no banco de endereços dos correios e retornará ou o restante do endereço ou uma mensagem informando que o CEP não foi localizado</p>
                <p>Em seguida, clicando no icone <img src="$btn_lapis" alt="Ver endereço"> à direita do endereço ou clicando sobre o próprio endereço você será enviado à página de visualização e edição do endereço, conforme pode ver abaixo:</p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_endereco_form" alt="Ver endereço"></p>
            </div>
        </div>
        <p class="text-center"><img src="$cadastro_006_endereco_view" alt="Novo endereço" style="width:100%;"></p>
        <p>Se desejar aproveitar alguns dados do endereço ara criar um parecido, poderá retornar ao registro de endereço clicando em <img src="$btn_lapis" alt="Editar endereço"> e em seguida clicando em <img src="$btn_duplicar" alt="Duplicar endereço">.</p>
HTML;
$cadastro_006_contato_form = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_contatos_form.jpg';
$cadastro_006_contato_view = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '006_contatos_view.jpg';
$cadastro_contatos = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Registrar e editar endereços</p>
                <p class="text-center"><img src="$cadastro_006_novo_contato" alt="Novo contato"></p>
            </div>
        </div>
        <p>Para inserir um contato do cadastro você poderá clicar no ícone <img src="$btn_mais" alt="Novo contato"> no menu lateral do registro do cliente conforme acima</p>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p>Você será direcionado para o formulário a seguir onde deverá informar os dados disponíveis. O campo "Contato" é obrigatório. Nele você colocará exatamento a forma de contato e não o nome ou o departamento. Ou seja, um número de telefone, um email, um site e etc.</p>
                <p>Poderá adicionar quantos meios de contato desejar</p>
                <p>Em seguida, clicando no icone <img src="$btn_lapis" alt="Ver contato"> à direita do contato ou clicando sobre o próprio contato você será enviado à página de visualização e edição do mesmo, conforme pode ver abaixo:</p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_contato_form" alt="Novo contato" style="width:100%;"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p class="text-center"><img src="$cadastro_006_contato_view" alt="Ver contato" style="width:100%;"></p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p></p>
                <p>Se desejar aproveitar nome e departamento e colocar mais formas de contato poderá retornar ao registro de contato clicando em <img src="$btn_lapis" alt="Editar contato"> e em seguida clicando em <img src="$btn_duplicar" alt="Duplicar contato">.</p>
            </div>
        </div>
        
HTML;
$prospec_007 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007.jpg';
$prospec_007_btn = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_btn_prospec.jpg';
$prospec_007_form = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_form.jpg';
$prospec_007_sel_cli = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_selec_cliente.jpg';
$prospec_007_sel_end = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_selec_endereco.jpg';
$prospec_007_view = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_view.jpg';
$prospec_007_index_painel = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '007_index_painel01.png';
$prospec = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Registrar e editar visitas a clientes</p>
                <p class="text-center"><img src="$prospec_007" alt="Prospecções" style="width:100%;"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <p>O LynkOs foi projetado para registrar também visitas a clientes. Acesse a tela acima clicando em <img src="$prospec_007_btn" alt="Ver prospecções" style="height:24px;"> no menu ou na página inicial do LynkOs no texto "Ver visitas" conforme a seguir:</p>
                <p>Os resultados nessa página só são vistos pelo gestor ou pelo pessoal do comercial. Um agente comercial só verá os seus próprios registros de visitas.</p>
                <p>Como pode observar na imagem acima o mapa indica de forma agrupada as visitas registradas conforme informado no grid de consultas. É possível filtrar os resultados para mostrar apenas alguns dados. O grid mostra no máximo 50 registros e o mapa segue essa mesma lógica.</p>
                <p>Para mostrar todas as marcações presentes no mapa em detalhe, role o mouse para cima ou para baixo ou pince na tela caso use dispositivo com tela de toque para aumentar ou diminuir o zoom.</p>
            </div>
            <div class="col-xs-12 col-md-4">
                <img src="$prospec_007_index_painel" alt="Ver prospecções" style="width:100%;">
            </div>
        </div>
HTML;
$prospec_registrar = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Registrar visitas a clientes</p>
            </div>
        </div>
        <p>Registrar o contato com os clientes serve para o fim de monitorar entre outras coisas quais clientes precisam de mais atenção. Atravéz dessa métrica a gestão comercial poderá ajudar os agentes externos a melhorar seus resultados e por sua vez o resultado da empresa.</p>
        <p>Para registrar um contato com o cliente, na tela acima pressione o botão <img src="$btn_mais_vd" alt="Nova visita"> ou na tela de detalhes do cadastro na coluna da esquerda e ao lado do endereço clique em <img src="$btn_map_marker" alt="Nova visita"> conforme mostrado abaixo:</p>
        <p class="text-center"><img src="$cadastro_006_novo_endereco" alt="Ver prospecções"></p>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p>A seguir, no formulário de registro, dê o máximo de informações que dispuser e atente para alguns detalhes:</p>
                <p class="text-center"><img src="$prospec_007_sel_cli" alt="Selecionar cliente" style="width:100%;"></p>
                <p>Para selecionar o cliente, caso já não tenha sido selecionado, comece digitando o nome dele. Quando tiver digitado ao menos três caracteres, o sistema irá buscar em ordem alfabética as cinco primeiras ocorrências para o que estiver sendo digitado.</p>
                <p class="text-center"><img src="$prospec_007_sel_end" alt="Selecionar endereço" style="width:100%;"></p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p><img src="$prospec_007_form" alt="Nova prospecção" style="width:100%;"></p>
            </div>
        </div>
        <p>Após selecionar o cliente a caixa de endereços será populada com os endereços disponíveis no cadastro deste cliente. Selecione um deles.</p>
        <p>Caso o endereço não apareça na lista de endereços após selecionar o cliente, clique no "X" circulado em vermelho acima e torne a localizar o nome do cliente e a lista de endereços será novamente populada com os endereços registrados.</p>
        <p>Ao escrever suas observações, esteja a vontade para usar o editor de texto e formatar a mensagem realçando o que for mair importante. Faça como faria no seu editor de texto preferido.</p>        
HTML;
$prospec_editar = <<< HTML
        <div class="jumbotron">
            <div class="container">
                <p>Editar visitas a clientes</p>
                <p class="text-center"><img src="$prospec_007_view" alt="Prospecções" style="width:100%;"></p>
            </div>
        </div>
        <p>Para ver os detalhes de um registro clique em <img src="$btn_ver" alt="Ver prospecções">. Para editar clique em <img src="$btn_lapis" alt="Editar prospecções"> ou em <img src="$btn_ver" alt="Ver prospecções"> e depois de mostrada a tela de detalhes do registro clique em <img src="$btn_lapis" alt="Editar prospecções">. Veja em detalhes na imagem acima.</p>
HTML;
$prospec_duplicar = <<< HTML
        <p>Se desejar poderá retornar ao registro de visita clicando em <img src="$btn_lapis" alt="Editar visita"> no grid de consultas e em seguida clicando em <img src="$btn_duplicar" alt="Duplicar visita"> poderá duplicar o registro para aproveitar a maior parte dos dados.</p>
HTML;

$contato_020 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '020_contato.jpg';
$_020 = Url::home(true) . Yii::getAlias('@imgs_apres_url') . DIRECTORY_SEPARATOR . '020.jpg';
$contato = <<< HTML
         <div class="jumbotron">
            <div class="container">
                <p>Contato e suporte</p>
                <p><img src="$contato_020" alt="Contato e suporte" style="width:100%;max-width:900px;"></p>
            </div>
        </div>
        <p>Para enviar uma mensagem, clique no envelope na barra superior da aplicação conforme demostrado abaixo:</p>
        <p><img src="$_020" alt="Contato e suporte" style="max-width:100%"></p>
        <p>Se você já é um usuário e está logado, o sistema já irá preencher o formulário com teu nome e email.</p>
        <p>Queremos saber como está sendo sua experiência e também estamos a disposição para dar maiores informações.</p>
        <p>Queremos que sua experiência conosco seja a melhor até quando estiver com dúvidas.</p>
HTML;

$items = [
    [
        'url' => '#a-1',
        'label' => 'Conteúdo',
        'header' => 'O ' . Yii::$app->params['nameFull'],
// 'subheader' => 'Subheader 1',
        'icon' => 'play-circle',
// 'content' => $apresentacao . $logica . $segurança,
        'items' => [
            ['url' => '#a-1-1', 'label' => 'Apresentação', 'content' => $apresentacao],
            ['url' => '#a-1-2', 'label' => 'Lógica', 'content' => $logica],
            ['url' => '#a-1-3', 'label' => 'Segurança', 'content' => $segurança],
            ['url' => '#a-1-4', 'label' => 'Importância', 'content' => $importancia],
        ],
    ],
    [
        'url' => '#a-2',
        'label' => 'Início e Geral',
//        'header' => 'Operações',
// 'subheader' => 'Subheader 1',
        'icon' => 'play-circle',
// 'content' => $apresentacao . $logica . $segurança,
        'items' => [
            ['url' => '#a-2-1', 'label' => 'Responsivo', 'content' => $linkos],
            ['url' => '#a-2-2', 'label' => 'Acesso', 'content' => $login],
            ['url' => '#a-2-3', 'label' => 'Registre-se', 'content' => $registro],
            ['url' => '#a-2-4', 'label' => 'Boas vindas', 'content' => $boas_vindas],
            ['url' => '#a-2-5', 'label' => 'Início', 'content' => $inicio],
            ['url' => '#a-2-6', 'label' => 'Equipe existente', 'content' => $inicio_usuario],
            ['url' => '#a-2-7', 'label' => 'Nova equipe', 'content' => $inicio_gestor],
            ['url' => '#a-2-8', 'label' => 'Registrar empresa', 'content' => $empresa],
            ['url' => '#a-2-9', 'label' => 'Perfil de usuário', 'content' => $perfil],
            ['url' => '#a-2-10', 'label' => 'Visão geral', 'content' => $botoes],
        ],
    ],
    [
        'url' => '#a-3',
        'label' => 'Cadastro',
//        'header' => 'Operações',
// 'subheader' => 'Subheader 1',
        'icon' => 'play-circle',
// 'content' => $apresentacao . $logica . $segurança,
        'items' => [
            ['url' => '#a-3-1', 'label' => 'Consultas', 'content' => $cadastro_cons],
            ['url' => '#a-3-2', 'label' => 'Cadastrar', 'content' => $cadastro_cadas],
            ['url' => '#a-3-3', 'label' => 'Prospecto ou não?', 'content' => $cadastro_prosp],
            ['url' => '#a-3-4', 'label' => 'Regras de validação', 'content' => $cadastro_regras],
            ['url' => '#a-3-5', 'label' => 'Repetido?', 'content' => $cadastro_repetido],
            ['url' => '#a-3-6', 'label' => 'Ver e editar', 'content' => $cadastro_editar],
            ['url' => '#a-3-7', 'label' => 'Endereços', 'content' => $cadastro_endereco],
            ['url' => '#a-3-8', 'label' => 'Contatos', 'content' => $cadastro_contatos],
        ],
    ],
    [
        'url' => '#a-4',
        'label' => 'Contato com o cliente',
//        'header' => 'Operações',
// 'subheader' => 'Subheader 1',
        'icon' => 'play-circle',
// 'content' => $apresentacao . $logica . $segurança,
        'items' => [
            ['url' => '#a-4-1', 'label' => 'Consultas', 'content' => $prospec],
            ['url' => '#a-4-2', 'label' => 'Registrar', 'content' => $prospec_registrar],
            ['url' => '#a-4-3', 'label' => 'Editar', 'content' => $prospec_editar],
            ['url' => '#a-4-4', 'label' => 'Duplicar', 'content' => $prospec_duplicar],
        ],
    ],

];
?>

<div class="row form-bg form-transparence">
    <div class="col-xs-12 col-md-12">
        <?php
        if (Yii::$app->mobileDetect->isMobile()) {
            Yii::$app->session->setFlash('info', "Para melhor visualização acesse essa apresentação num computador de mesa.");
            Yii::$app->session->setFlash('success', "Vire a tela para ver melhor");
            echo yiister\gentelella\widgets\FlashAlert::widget(['showHeader' => false]);
        }
        ?>
        <div class="row">
            <?php if (!Yii::$app->mobileDetect->isMobile()) { ?>
                <div class="col-xs-2 col-md-2">
                    <?=
                    Affix::widget([
                        'items' => $items,
                        'type' => 'menu'
                    ]);
                    ?>
                </div>
            <?php } ?>
            <div class="col-xs-12 col-md-10">
                <?php
                echo Affix::widget([
                    'items' => $items,
                    'type' => 'body'
                ]);
                ?>                
            </div>
        </div>
    </div>
</div>

<?php
//$js = <<< JS
//JS;
//$this->registerJs($js);
?>