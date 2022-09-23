<?php

use yii\db\Migration;

class m130524_201442_init extends Migration {

    const TABELA = '{{%user}}';

    public function safeUp() {
        return true;
    }

//    public function safeUp() {
//        $tableOptions = null;
//        if ($this->db->driverName === 'mysql') {
//            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
//            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  ENGINE=InnoDB';
//        }
//
//        $this->dropTable('user_auth');
//        $this->dropTable('user_opcoes');
//        $this->dropTable($this::TABELA);
//
//        $this->createTable($this::TABELA, [
//            'id' => $this->primaryKey()->comment('ID do registro'),
//            'username' => $this->string()->notNull()->comment('Nome de usuário'),
//            'hash' => $this->string()->notNull()->unique()->comment('Identificador de usuário'),
//            'auth_key' => $this->string(32)->notNull(),
//            'password_hash' => $this->string()->notNull(),
//            'password_reset_token' => $this->string()->unique(),
//            'github' => $this->string()->unique(),
//            'email' => $this->string()->notNull()->comment('Email usuário'),
//            'tel_contato' => $this->string()->comment('Telefone usuário'),
//            'slug' => $this->string()->notNull()->unique()->comment(''),
//            'status' => $this->tinyInteger()->notNull()->defaultValue(20)->comment(''),
//            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
//            'evento' => $this->integer(11)->notNull()->comment('Evento do registro'),
//            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
//            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
//            'administrador' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Administrador'),
//            'gestor' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestor'),
//            'usuarios' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestão de usuários'),
//            'cadastros' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestão de cadastros'),
//            'folha' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestão de folha'),
//            'financeiro' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestão de financeiro'),
//            'parametros' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Gestão de parâmetros'),
//                ], $tableOptions);
//
//        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `username_email_unique` '
//                . '(username, email)');
//    }

    public function safeDown() {
        $this->dropTable($this::TABELA);
    }

}
