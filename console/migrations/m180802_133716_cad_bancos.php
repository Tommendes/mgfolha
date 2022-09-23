<?php

use yii\db\Migration;

/**
 * Class m180802_133716_cad_bancos
 */
class m180802_133716_cad_bancos extends Migration {

    const TABELA = 'cad_bancos';

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
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'febraban' => $this->string(5)->notNull()->comment('Código febraban'),
            'nome' => $this->string(255)->notNull()->comment('Nome do banco'),
            'nomeAbrev' => $this->string(10)->comment('Apelido do banco'),
            'url_logo' => $this->string(255)->comment('Url da logo do banco'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dominio_febraban` '
                . '(dominio, febraban)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_133716_cad_bancos cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_133716_cad_bancos cannot be reverted.\n";

      return false;
      }
     */
}
