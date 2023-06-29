<?php

use yii\db\Migration;

/**
 * Class m180802_134823_cad_cargos
 */
class m180802_134823_cad_cargos extends Migration {

    const TABELA = 'cad_cargos';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable($this::TABELA);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  ENGINE=InnoDB';
        }

        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
            'created_at' => $this->string(255)->notNull()->comment('Registro em'),
            'updated_at' => $this->string(255)->notNull()->comment('Atualização em'),
            'id_cargo' => $this->string(4)->notNull()->comment('Código'),
            'nome' => $this->string(100)->notNull()->comment('Nome'),
            'cbo' => $this->string(7)->comment('C.B.O.'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '-dom-id_cargo` '
                . '(dominio, id_cargo)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_134823_cad_cargos cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_134823_cad_cargos cannot be reverted.\n";

      return false;
      }
     */
}
