<?php

use yii\db\Migration;

/**
 * Class m180828_184209_sis_events
 */
class m180828_184209_sis_events extends Migration
{

    const TABELA = 'sis_events';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->dropTable($this::TABELA);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  ENGINE=InnoDB';
        }

        // $this->createTable($this::TABELA, [
        //     'id' => $this->primaryKey()->comment('ID do registro'),
        //     'slug' => $this->string()->notNull()->unique(),
        //     'status' => $this->tinyInteger()->notNull()->defaultValue(10),
        //     'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
        //     'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
        //     'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
        //     'evento' => $this->text()->comment('Evento do registro'),
        //     'classevento' => $this->string(50)->comment('Classe'),
        //     'tabela_bd' => $this->string(50)->comment('Tabela do BD'),
        //     'id_registro' => $this->integer(11)->comment('Id do Registro'),
        //     'ip' => $this->string(50)->comment('Tabela do BD'),
        //     'geo_lt' => $this->string(50)->comment('Geo Latitude'),
        //     'geo_ln' => $this->string(50)->comment('Geo Longitude'),
        //     'id_user' => $this->integer(11)->notNull()->comment('Id do Registro'),
        //     'username' => $this->string(255)->comment('Username'),
        // ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180828_184209_sis_events cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_184209_sis_events cannot be reverted.\n";

      return false;
      }
     */
}
