<?php

use yii\db\Migration;

/**
 * Class m180727_141450_user_opcoes
 */
class m130524_201443_user_opcoes extends Migration {

    const TABELA = 'user_opcoes';

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
//            'id_user' => $this->integer(11)->notNull()->comment('ID do usuário'),
//            'geo_lt' => $this->string()->comment('Geo Latitude atual'),
//            'geo_ln' => $this->string()->comment('Geo Longitude atual'),
//            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
//            'created_at' => $this->string(255)->notNull()->comment('Registro em'),
//            'updated_at' => $this->string(255)->notNull()->comment('Atualização em'),
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
