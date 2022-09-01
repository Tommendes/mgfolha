<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "desk_users".
 *
 * @property int $id
 * @property int $status
 * @property int $status_desk
 * @property string $dll_cod
 * @property string $dll_cnpj
 * @property string $dll_nome
 * @property string $cli_cnpj
 * @property string $cli_nome
 * @property string $cli_nome_comput
 * @property string $cli_nome_user
 * @property string $versao_desk
 * @property int $evento
 * @property int $created_at
 * @property int $updated_at
 */
class DeskUsers extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'desk_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                    self::STATUS_INATIVO,
                    self::STATUS_ATIVO,
                    self::STATUS_CANCELADO,
                ]
            ],
            [['status', 'status_desk', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['dll_cod', 'dll_cnpj', 'dll_nome', 'cli_cnpj', 'cli_nome', 'cli_nome_comput', 'cli_nome_user', 'versao_desk'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'status_desk' => 'Status Desk',
            'dll_cod' => 'Dll Cod',
            'dll_cnpj' => 'Dll Cnpj',
            'dll_nome' => 'Dll Nome',
            'cli_cnpj' => 'Cli Cnpj',
            'cli_nome' => 'Cli Nome',
            'cli_nome_comput' => 'Cli Nome Comput',
            'cli_nome_user' => 'Cli Nome User',
            'versao_desk' => 'Versao Desk',
            'evento' => 'Evento',
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
        ];
    }

    /**
     * Operações executadas antes de salvar
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
        } else {
            
        }
        $this->updated_at = time();

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
