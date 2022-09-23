<?php

use yii\db\Migration;

/**
 * Class m180828_184203_fin_faixas
 */
class m180828_184203_fin_faixas extends Migration {

    const TABELA = 'fin_faixas';

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

        // Cria a tabela
        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'tipo' => $this->tinyInteger(1)->notNull()->comment('Tipo da faixa'),
            'data' => $this->string(10)->notNull()->comment('Data entra em vigor'),
            'faixa' => $this->string(255)->notNull()->comment('Faixa'),
            'v_final1' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor final da faixa 1'),
            'v_faixa1' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor da faixa 1'),
            'v_deduzir1' => $this->decimal(11, 2)->comment('Deduzir da faixa 1')->defaultValue(0),
            'v_final2' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor final da faixa 2'),
            'v_faixa2' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor da faixa 2'),
            'v_deduzir2' => $this->decimal(11, 2)->comment('Deduzir da faixa 2')->defaultValue(0),
            'v_final3' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor final da faixa 3'),
            'v_faixa3' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor da faixa 3'),
            'v_deduzir3' => $this->decimal(11, 2)->comment('Deduzir da faixa 3')->defaultValue(0),
            'v_final4' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor final da faixa 4'),
            'v_faixa4' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor da faixa 4'),
            'v_deduzir4' => $this->decimal(11, 2)->comment('Deduzir da faixa 4')->defaultValue(0),
            'v_final5' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor final da faixa 5'),
            'v_faixa5' => $this->decimal(11, 2)->defaultValue(0)->comment('Valor da faixa 5'),
            'v_deduzir5' => $this->decimal(11, 2)->comment('Deduzir da faixa 5')->defaultValue(0),
            'deduzir_dependente' => $this->decimal(11, 2)->comment('Deduzir por dependente')->defaultValue(0),
            'salario_vigente' => $this->decimal(11, 2)->comment('Alíquota'),
            'inss_teto' => $this->decimal(11, 2)->comment('INSS teto')->defaultValue(0),
            'inss_patronal' => $this->decimal(11, 2)->comment('INSS patronal')->defaultValue(0),
            'inss_rat' => $this->decimal(11, 2)->comment('INSS Rat')->defaultValue(0),
            'inss_fap' => $this->decimal(11, 2)->comment('INSS Fap')->defaultValue(0),
            'rpps_patronal' => $this->decimal(11, 2)->comment('rpps patronal')->defaultValue(0),
                ], $tableOptions);

        // Cria os indices da tabela
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-tipo-faixa-data` '
                . '(dominio, tipo, faixa, data)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_184203_fin_faixas cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_184203_fin_faixas cannot be reverted.\n";

      return false;
      }
     */
}
