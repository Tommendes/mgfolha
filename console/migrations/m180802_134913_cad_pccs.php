<?php

use yii\db\Migration;

/**
 * Class m180802_134913_cad_pccs
 */
class m180802_134913_cad_pccs extends Migration {

    const TABELA = 'cad_pccs';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable("fin_referencias");
        $this->dropTable("cad_classes");
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
            'id_pccs' => $this->integer(11)->notNull()->comment('ID PCCS'),
            'nome_pccs' => $this->string(255)->notNull()->comment('Nome'),
            'nivel_pccs' => $this->string(255)->comment('Nível'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '-dom-id_pccs` '
                . '(dominio, id_pccs)');
        
        $this->execute('ALTER TABLE ' . $this::TABELA . ' DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `id_pccs`)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_134913_cad_pccs cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_134913_cad_pccs cannot be reverted.\n";

      return false;
      }
     */
}
