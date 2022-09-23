<?php

use yii\db\Migration;

/**
 * Class m180727_141450_user_opcoes
 */
class m130524_201443_user_auth extends Migration {

    const TABELA = 'user_auth';

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
//        $this->dropTable($this::TABELA);
//
//        $this->createTable($this::TABELA, [
//            'id' => $this->primaryKey()->comment('ID do registro'),
//            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
//            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
//            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
//            'id_user' => $this->integer(11)->notNull()->comment('ID do usuário'),
//            'email' => $this->string()->comment('Email'),
//            'user' => $this->string()->comment('Usuário'),
//            'source' => $this->string()->comment('Usuário'),
//            'source_id' => $this->string()->comment('Usuário'),
//            'cover' => $this->string()->comment('Usuário'),
//            'first_name' => $this->string()->comment('Usuário'),
//            'last_name' => $this->string()->comment('Usuário'),
//            'age_range' => $this->string()->comment('Usuário'),
//            'link' => $this->string()->comment('Usuário'),
//            'gender' => $this->string()->comment('Usuário'),
//            'locale' => $this->string()->comment('Usuário'),
//            'picture' => $this->string()->comment('Usuário'),
//            'timezone' => $this->string()->comment('Usuário'),
//            'updated_time' => $this->string()->comment('Usuário'),
//            'verified' => $this->string()->comment('Usuário'),
//            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
//                ], $tableOptions);
//
//        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_user-user-id` '
//                . 'FOREIGN KEY (id_user) REFERENCES user(id) '
//                . 'ON UPDATE CASCADE ON DELETE CASCADE');
//    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180727_141450_user_opcoes cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180727_141450_user_opcoes cannot be reverted.\n";

      return false;
      }
     */
}
