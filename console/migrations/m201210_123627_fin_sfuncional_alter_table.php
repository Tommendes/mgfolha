<?php

use yii\db\Migration;

/**
 * Class m201210_123627_fin_sfuncional_alter_table
 */
class m201210_123627_fin_sfuncional_alter_table extends Migration
{

    const TABELA_SFUNCIONAL = 'fin_sfuncional';
    // ALTER TABLE `wwmga_tapera_previdencia`.`fin_rubricas` ADD CONSTRAINT `fk-fin_rubricas-id_con_contratos-con_contratos-id` FOREIGN KEY (`id_con_contratos`) REFERENCES `wwmga_tapera_previdencia`.`con_contratos`(`id`) ON UPDATE CASCADE ON DELETE NO ACTION; 

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->getTableExist($this::TABELA_SFUNCIONAL)) {
            echo "*** Alterações em " . $this::TABELA_SFUNCIONAL . " ***";
            $this->execute('ALTER TABLE ' . $this::TABELA_SFUNCIONAL
                . ' CHANGE parcela complementar VARCHAR(3) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT "parcela", '
                . 'ADD COLUMN id_local_trabalho INT(11) DEFAULT NULL COMMENT "Local de trabalho", '
                . 'ADD COLUMN id_cad_principal INT(11) DEFAULT NULL COMMENT "Vinculo principal", '
                . 'ADD COLUMN id_escolaridade VARCHAR(2) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Escolaridade", '
                . 'ADD COLUMN escolaridaderais VARCHAR(2) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Escolaridade RAIS", '
                . 'ADD COLUMN rais TINYINT(3) DEFAULT NULL COMMENT "Declara RAIS", '
                . 'ADD COLUMN dirf TINYINT(3) DEFAULT NULL COMMENT "Declara DIRF", '
                . 'ADD COLUMN sefip TINYINT(3) DEFAULT NULL COMMENT "Declara SEFIP", '
                . 'ADD COLUMN sicap TINYINT(3) DEFAULT NULL COMMENT "Declara SICAP", '
                . 'ADD COLUMN insalubridade TINYINT(3) DEFAULT NULL COMMENT "Insalubridade", '
                . 'ADD COLUMN decimo TINYINT(3) DEFAULT NULL COMMENT "Recebe décimo", '
                . 'ADD COLUMN id_vinculo VARCHAR(2) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Vínculo", '
                . 'ADD COLUMN id_cat_sefip INT(11) DEFAULT NULL COMMENT "Categoria SEFIP", '
                . 'ADD COLUMN ocorrencia VARCHAR(2) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Ocorrência", '
                . 'ADD COLUMN carga_horaria DECIMAL(15, 2) DEFAULT NULL COMMENT "Carga horária", '
                . 'ADD COLUMN molestia VARCHAR(2) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Moléstia", '
                . 'ADD COLUMN d_laudomolestia VARCHAR(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Moléstia data", '
                . 'ADD COLUMN manad_tiponomeacao VARCHAR(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Tipo nomeação", '
                . 'ADD COLUMN manad_numeronomeacao VARCHAR(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Número nomeação", '
                . 'ADD COLUMN d_admissao VARCHAR(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT "Admissão data", '
                . 'ADD COLUMN d_enio_inicio VARCHAR(10) COLLATE utf8mb4_general_ci DEFAULT NULL after enio, '
                . 'ADD COLUMN d_enio_fim VARCHAR(10) COLLATE utf8mb4_general_ci DEFAULT NULL after d_enio_inicio, '
                . 'ADD COLUMN d_tempofim VARCHAR(10) COLLATE utf8mb4_general_ci DEFAULT NULL, '
                . 'ADD COLUMN n_valorbaseinss DECIMAL(15,2) DEFAULT NULL COMMENT "Valor base INSS"');
            echo "*** Importação dos dados em cad_sfuncional para " . $this::TABELA_SFUNCIONAL . " ***";
            // Este update foi colocado aqui como estratégia para inserir um id_local_trabalho antes
            // de trazer os dados de cad_sfuncional para fin_sfuncional
            $this->execute('UPDATE cad_sfuncional SET id_local_trabalho = (SELECT id FROM cad_localtrabalho WHERE nome = "Local não informado") WHERE id_local_trabalho < 1');
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET id_local_trabalho = (SELECT cs.id_local_trabalho FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET id_cad_principal = (SELECT cs.id_cad_principal FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET id_escolaridade = (SELECT cs.id_escolaridade FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET escolaridaderais = (SELECT cs.escolaridaderais FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET rais = (SELECT cs.rais FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET dirf = (SELECT cs.dirf FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET sefip = (SELECT cs.sefip FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET sicap = (SELECT cs.sicap FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET insalubridade = (SELECT cs.insalubridade FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET decimo = (SELECT cs.decimo FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET id_vinculo = (SELECT cs.id_vinculo FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET id_cat_sefip = (SELECT cs.id_cat_sefip FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET ocorrencia = (SELECT cs.ocorrencia FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET carga_horaria = (SELECT cs.carga_horaria FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET molestia = (SELECT cs.molestia FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_laudomolestia = (SELECT cs.d_laudomolestia FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET manad_tiponomeacao = (SELECT cs.manad_tiponomeacao FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET manad_numeronomeacao = (SELECT cs.manad_numeronomeacao FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_admissao = (SELECT cs.d_admissao FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_tempo = (SELECT cs.d_tempo FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_tempofim = (SELECT cs.d_tempofim FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_beneficio = (SELECT cs.d_beneficio FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET n_valorbaseinss = (SELECT cs.n_valorbaseinss FROM cad_sfuncional cs WHERE cs.id_cad_servidores = ff.id_cad_servidores GROUP BY cs.id_cad_servidores)");
            $this->execute("UPDATE " . $this::TABELA_SFUNCIONAL . " ff SET d_admissao = (SELECT cs.d_admissao FROM cad_servidores cs WHERE cs.id = ff.id_cad_servidores GROUP BY cs.id)");
            $this->execute("DROP TABLE cad_sfuncional");
        } else {
            echo "\n";
            echo $this::TABELA_SFUNCIONAL . " ainda não foi criada\n";
            echo "Operação abortada...\n";
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180725_205204_cad_sferias cannot be reverted.\n";

        return false;
    }

    /**
     * Executa o update na tabela para inserir dados em campos vazios
     */
    public function getTableExist($table)
    {
        try {
            $q = Yii::$app->db->createCommand(
                "SELECT COUNT(*) AS quant FROM information_schema.tables "
                    . "WHERE table_name = '$table' AND TABLE_SCHEMA = 'mgfolha_import'"
            )
                ->queryOne();
            $q = $q['quant'];
        } catch (Exception $ex) {
            echo $ex;
            exit;
        }
        return $q > 0;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_205204_cad_sferias cannot be reverted.\n";

      return false;
      }
     */
}
