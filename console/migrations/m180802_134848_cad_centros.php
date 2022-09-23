<?php

use yii\db\Migration;

/**
 * Class m180802_134848_cad_centros
 */
class m180802_134848_cad_centros extends Migration {

    const TABELA = 'cad_centros';

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
            'cod_centro' => $this->string(4)->notNull()->comment('Código centro'),
            'nome_centro' => $this->string(255)->notNull()->comment('Nome centro de custo'),
            'cnpj_ua' => $this->string(14)->notNull()->comment('CNPJ U.A.'),
            'cod_ua' => $this->string(4)->notNull()->comment('Código U.A.'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dominio_cod-centro` '
                . '(dominio, cod_centro)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_134848_cad_centros cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_134848_cad_centros cannot be reverted.\n";

      return false;
      }
     */
}
