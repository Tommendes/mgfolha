<?php

use yii\db\Migration;

/**
 * Class m180828_131231_fin_basefixa
 */
class m180828_131231_fin_basefixa extends Migration {

    const TABELA = 'fin_basefixa';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable('fin_eventosbasefixa');
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
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'id_base_fixa' => $this->string(4)->notNull()->comment('Codigo base fixa'),
            'nome_base_fixa' => $this->string(255)->notNull()->comment('Nome base fixa'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-id_base_fixa` '
                . '(dominio, id_base_fixa)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_131231_fin_basefixa cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_131231_fin_basefixa cannot be reverted.\n";

      return false;
      }
     */
}
