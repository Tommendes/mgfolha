<?php

use yii\db\Migration;

/**
 * Class m180802_134914_cad_classes
 */
class m180802_134914_cad_classes extends Migration {

    const TABELA = 'cad_classes';

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
            'id_pccs' => $this->integer(11)->notNull()->comment('ID PCCS'),
            'id_classe' => $this->integer(11)->notNull()->comment('Classe'),
            'nome_classe' => $this->string(255)->notNull()->comment('Nome'),
            'i_ano_inicial' => $this->integer(3)->notNull()->comment('Ano inicial'),
            'i_ano_final' => $this->integer(3)->notNull()->comment('Ano final'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '-dom-id_pccs-id_classe` '
                . '(dominio, id_pccs, id_classe)');

//        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_pccs-cad_pccs-id_pccs` '
//                . 'FOREIGN KEY (id_pccs) REFERENCES cad_pccs(id_pccs) ON UPDATE CASCADE ON DELETE CASCADE');
//        
        $this->execute('ALTER TABLE ' . $this::TABELA . ' DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `id_pccs`, `id_classe`)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_134914_cad_classes cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_134914_cad_classes cannot be reverted.\n";

      return false;
      }
     */
}
