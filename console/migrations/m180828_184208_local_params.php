<?php

use yii\db\Migration;

/**
 * Class m180828_184208_local_params
 */
class m180828_184208_local_params extends Migration {

    const TABELA = 'local_params';

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
            'grupo' => $this->string(255)->comment('Grupo do parâmetro'),
            'parametro' => $this->text()->comment('Parâmetro'),
            'label' => $this->text()->comment('Label')
        ]);


        $columnsIdle = [
            'id' => null, 
            'slug' => strtolower(sha1($this::TABELA . time())),
            'status' => 10, 'dominio' => 'mgfolha', 'evento' => 1,
            'created_at' => time(), 'updated_at' => time(),
            'grupo' => 'idle', 'parametro' => '10',
            'label' => 'Tempo de ociosidade em minutos antes de sair do app'
        ];
        echo '*** Tabela "' . $this::TABELA . '" ***';
        do {
            echo "\nSelecione o domínio do cliente: Ativos(a)|Previdencia(p)|Câmara(c)";
            $handle = fopen("php://stdin", "r");
            $line = trim(fgets($handle));
            $dominio = $this->getDominio($line);
        } while (!in_array($line, ['a', 'p', 'c']));
        $columnsTgf = [
            'id' => null, 
            'slug' => strtolower(sha1($this::TABELA . time())),
            'status' => 10, 'dominio' => $dominio, 'evento' => 1,
            'created_at' => time(), 'updated_at' => time(),
            'grupo' => 'tgf', 'parametro' => '35',
            'label' => 'Tempo de geração da folha'
        ];
        $this->insert($this::TABELA, $columnsIdle);
        $this->insert($this::TABELA, $columnsTgf);
    }

    public function getDominio($r) {
        switch ($r) {
            case 'a': return 'ativos';
            case 'i': return 'previdencia';
            case 'c': return 'camara';
            default : return "Vazio?: '$r'";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_184208_local_params cannot be reverted.\n";
        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_184208_local_params cannot be reverted.\n";

      return false;
      }
     */
}
