<?php

use yii\db\Migration;

/**
 * Class m180802_133717_cad_bconvenios
 */
class m180802_133717_cad_bconvenios extends Migration {

    const TABELA = 'cad_bconvenios';

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
            'id_cad_bancos' => $this->integer(11)->notNull()->comment('Banco conveniado'),
            'id_orgao_ua' => $this->integer(11)->notNull()->comment('Unidade autônoma'),
            'convenio' => $this->string(255)->notNull()->comment('Convênio'),
            'cod_compromisso' => $this->string(4)->notNull()->comment('Código do compromisso'),
            'agencia' => $this->string(15)->notNull()->comment('Agência'),
            'a_digito' => $this->string(2)->comment('Ag Dígito'),
            'conta' => $this->string(15)->notNull()->comment('Conta'),
            'c_digito' => $this->string(2)->comment('C Dígito'),
            'convenio_nr' => $this->string(255)->notNull()->comment('Convênio'),
            'tipo_servico' => $this->integer(11)->comment('Tipo serviço'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-conv-cad_bcos-orgao_ua` '
                . '(dominio, convenio_nr, id_cad_bancos, id_orgao_ua, agencia, c_digito)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180802_133717_cad_bconvenios cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180802_133717_cad_bconvenios cannot be reverted.\n";

      return false;
      }
     */
}
