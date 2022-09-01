<?php

/**
 * Messages override for yii translation category 
 */
use yii\helpers\ArrayHelper;

// We merge the upstream translations with our local array of strings 
return ArrayHelper::merge(require(Yii::getAlias("@vendor/yiisoft/yii2/messages/pt-BR/yii.php")), [
            // Custom messages 
            'Successfully uploaded file' => 'Arquivo enviado com sucesso',
            'Successfully created record' => 'Registro criado com sucesso',
            'Successfully canceled record' => 'Registro cancelado com sucesso',
            'Successfully deleted record' => 'Registro excluído com sucesso',
            'Attempt to delete unsuccessful. Error: {error}' => 'Tentativa de exclusão de registro mau sucedida. Erro: {error}',
            'Create record {modelClass}' => 'Novo {modelClass}',
            'Search' => 'Procurar',
            'Save' => 'Salvar',
            'Validate' => 'Validar',
            'Reset Grid' => 'Redefinir o grid',
            'Create {modelClass} {add}' => 'Novo {modelClass} {add}',
            'Create {modelClass}' => 'Novo {modelClass}',
            'See {modelClass}' => 'Ver {modelClass}',
            'Updating {modelClass}' => 'Editar {modelClass}',
            'Updating record {modelClass}' => 'Editar registro {modelClass}',
            'Sorry, we did not find the data you entered. Try again. Check the username and password entered.' => 'Desculpe, não encontramos os dados inseridos. Verifique o nome de usuário e a senha digitados e tente novamente',
            'If you think this is a bug, contact your system administrator.' => 'Se você acha que isso é um bug, entre em contato com o administrador do sistema',
            'You have been disconnected due to idle system time. Please sign in again.' => 'Você foi desconectado devido ao tempo de inatividade do sistema. Por favor, entre novamente.',
            'Successfully updated record.' => 'Registro editado com sucesso',
            'Check your email for further instructions.' => 'Verifique seu e-mail para mais instruções.',
            'Already a member?' => 'Já é usuário?',
            'Create Account' => 'Se registrar',
            'New to site?' => 'Novo por aqui?',
            'If you forgot your password you can reset it' => 'Se você esqueceu sua senha então pode resetar',
            'Please choose your new password:' => 'Por favor digite sua nova senha:',
            'Please fill out your email. A link to reset password will be sent there.' => 'Por favor informe seu email. Um link será enviado para seu email de cadastro.',
            'Send' => 'Enviar',
            'Request password reset' => 'Resetar sua senha',
            'Contact' => 'Contato',
            'New {modelClass}' => 'Novo {modelClass}',
            'created_at' => 'Registro em',
            'updated_at' => 'Atualização em',
            'Are you sure you want to exclude this item?' => 'Confirma a exclusão deste item?',
            'Acesso informacao' => 'Acesso à informação',
            'Reclamacao' => 'Reclamação',
            'Solicitacao' => 'Solicitação',
            'Sugestao' => 'Sugestão',
            'File (s) uploaded successfully' => 'Arquivo(s) enviado(s) com sucesso',
            'Unsuccessfully upload. Error in some upload(s). Errors: {error}' => 'Arquivo não enviado. Erro: {}',
            'Submit' => 'Enviar',
            'Thank you for contacting us. We will respond to you as soon as possible.' => 'Obrigado por nos contatar. Nós responderemos a você o mais breve possível.',
            'There was an error sending your message.' => 'Houve um erro ao enviar a sua mensagem.',
            'This email address has already been taken.' => 'Este email já foi registrado',
            'Follow the link below to reset your password:' => 'Acesse o link a seguir para resetar sua senha:',
            'New password saved.' => 'Nova senha salva',
            'The requested page does not exist.' => 'Esta página não existe. Por favor, verifique o endereço digitado e tente novamente.',
            'Log in' => 'Acessar',
            'Send to {destinner}' => 'Enviar para {destinner}',
            'Are you sure you want to duplicate this item?' => 'Confirma a duplicação deste item?',
            'Are you sure you want to replicate this item to all users?' => 'Confirma a replicação deste item para todos os clientes?',
            'Createa {modelClass}' => 'Nova {modelClass}',
            'Uploading file' => 'Enviar arquivo',
            'Are you sure you want to send this item?' => 'Confirma o envio desse item?',
            'The above error occurred while the Web server was processing your request.' => 'O erro acima ocorreu enquanto o web service tentava processar sua solicitação.',
            'Print' => 'Imprimir',
            'Filter as you type ...' => 'Filtre enquanto você digita ...',
        ]);
