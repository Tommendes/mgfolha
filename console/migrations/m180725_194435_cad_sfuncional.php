<?php

use yii\db\Migration;

/**
 * Class m180725_194435_cad_sfuncional
 */
class m180725_194435_cad_sfuncional extends Migration {

    const TABELA = 'cad_sfuncional';

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
            'ano' => $this->string(4)->notNull()->comment('Ano'),
            'mes' => $this->string(2)->notNull()->comment('Mês'),
            'complementar' => $this->string(3)->notNull()->comment('complementar'),
            'id_cad_servidores' => $this->integer(11)->notNull()->comment('ID do servidor'),
            'id_local_trabalho' => $this->integer(11)->comment('Local de trabalho'),
            'id_cad_principal' => $this->integer(11)->comment('Vinculo principal'),
            'id_escolaridade' => $this->string(2)->comment('Escolaridade'),
            'escolaridaderais' => $this->string(2)->comment('Escolaridade RAIS'),
            'rais' => $this->tinyInteger()->comment('Declara RAIS'),
            'dirf' => $this->tinyInteger()->comment('Declara DIRF'),
            'sefip' => $this->tinyInteger()->comment('Declara SEFIP'),
            'sicap' => $this->tinyInteger()->comment('Declara SICAP'),
            'insalubridade' => $this->tinyInteger()->comment('Insalubridade'),
            'decimo' => $this->tinyInteger()->comment('Recebe décimo'),
            'id_vinculo' => $this->string(2)->comment('Vínculo'),
            'id_cat_sefip' => $this->integer(11)->comment('Categoria SEFIP'),
            'ocorrencia' => $this->string(2)->comment('Ocorrência'),
            'carga_horaria' => $this->decimal(15, 2)->comment('Carga horária'),
            'molestia' => $this->string(2)->comment('Moléstia'),
            'd_laudomolestia' => $this->string(10)->comment('Moléstia data'),
            'manad_tiponomeacao' => $this->string(20)->comment('Tipo nomeação'),
            'manad_numeronomeacao' => $this->string(255)->comment('Número nomeação'),
            'd_admissao' => $this->string(10)->comment('Admissão data'),
            'd_enio_inicio' => $this->string(10)->comment(''),
            'd_enio_fim' => $this->string(10)->comment(''),
            'd_beneficio' => $this->string(10)->comment(''),
            'n_valorbaseinss' => $this->decimal(15, 2)->comment('Valor base INSS'),
            'ponto' => $this->integer(11)->comment('Marcação de ponto eletrônico'),
            'd_tempo' => $this->string(10),
            'd_tempofim' => $this->string(10),
                ], $tableOptions);

        $this->execute('UPDATE ' . $this::TABELA . ' SET ' . $this::TABELA . '.id_vinculo = NULL WHERE ' . $this::TABELA . '.id_vinculo NOT BETWEEN 1 AND 7');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dominio_id_cad_servidores_ano_mes_complementar` '
                . '(dominio, id_cad_servidores, ano, mes, complementar)');

        // $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_local_trabalho-cad_localtrabalho-id` '
        //         . 'FOREIGN KEY (id_local_trabalho) REFERENCES cad_localtrabalho(id) '
        //         . 'ON UPDATE CASCADE ON DELETE NO ACTION');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_cad_servidores-cad_servidores-id` '
                . 'FOREIGN KEY (id_cad_servidores) REFERENCES cad_servidores(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180725_194435_cad_sfuncional cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_194435_cad_sfuncional cannot be reverted.\n";

      return false;
      }
     */
}
