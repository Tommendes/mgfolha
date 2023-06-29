<?php

use yii\db\Migration;

/**
 * Class m180802_135304_cad_smovimentacao
 */
class m180802_135304_cad_smovimentacao extends Migration {

    const TABELA = 'cad_smovimentacao';

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
            'id_cad_servidores' => $this->integer(11)->notNull()->comment('ID do servidor'),
            'codigo_afastamento' => $this->string(2)->notNull()->comment('Código do afastamento'),
            'd_afastamento' => $this->string(10)->notNull()->comment('Data do afastamento'),
            'codigo_retorno' => $this->string(2)->comment('Código do retorno'),
            'd_retorno' => $this->string(10)->comment('Data do retorno'),
            'motivo_desligamentorais' => $this->string(2)->comment('Motivo do desligamento'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '-dom-servidor-cod_afast-d_afastamento` '
                . '(dominio, id_cad_servidores, codigo_afastamento, d_afastamento)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_135304_cad_smovimentacao cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_135304_cad_smovimentacao cannot be reverted.\n";

      return false;
      }
     */
}
