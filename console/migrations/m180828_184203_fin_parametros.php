<?php

use yii\db\Migration;

/**
 * Class m180828_184203_fin_parametros
 */
class m180828_184203_fin_parametros extends Migration {

    const TABELA = 'fin_parametros';

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
            'ano' => $this->string(4)->notNull()->comment('Ano'),
            'mes' => $this->string(2)->notNull()->comment('Mês'),
            'complementar' => $this->string(3)->notNull()->comment('complementar'),
            'ano_informacao' => $this->string(4)->notNull()->comment('Ano informação'),
            'mes_informacao' => $this->string(2)->notNull()->comment('Mês informação'),
            'complementar_informacao' => $this->string(3)->notNull()->comment('complementar informação'),
            'descricao' => $this->string()->notNull()->comment('Descrição'),
            'situacao' => $this->integer(1)->notNull()->comment('Situação'),
            'd_situacao' => $this->string(10)->comment('Data situação'),
            'mensagem' => $this->string(255)->notNull()->comment('Mensagem'),
            'mensagem_aniversario' => $this->string(255)->notNull()->comment('Mensagem aniversário'),
            'manad_tipofolha' => $this->tinyInteger(1)->notNull()->comment('Manad tipo folha'),
            'tgf' => $this->integer(11)->comment('Tempo de geração da folha em segundos'),
                ], $tableOptions); 
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-mes-ano-complementar` '
                . '(dominio, mes, ano, complementar)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_184203_fin_parametros cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_184203_fin_parametros cannot be reverted.\n";

      return false;
      }
     */
}
