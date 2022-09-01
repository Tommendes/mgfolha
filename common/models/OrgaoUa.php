<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CnpjValidator;

/**
 * This is the model class for table "orgao_ua".
 *
 * @property int $id ID do registro
 * @property int $id_orgao
 * @property int $status
 * @property int $evento Evento do registro
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $codigo Código
 * @property string $cnpj CNPJ
 * @property string $nome Nome
 *
 * @property Orgao $orgao
 */
class OrgaoUa extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * Setar o DB baseado na URL
     * @return type
     */
    public static function getDb() {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        return Yii::$app->$db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'orgao_ua';
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
            [['id_orgao', 'evento', 'created_at', 'updated_at', 'codigo', 'cnpj', 'nome'], 'required'],
            [['id_orgao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['codigo'], 'string', 'max' => 10],
            [['cnpj'], 'string', 'max' => 18],
            [['cnpj'], CnpjValidator::className()],
            [['nome'], 'string', 'max' => 255],
            [['id_orgao', 'codigo', 'cnpj'], 'unique', 'targetAttribute' => ['id_orgao', 'codigo', 'cnpj']],
            [['id_orgao'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['id_orgao' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'id_orgao' => Yii::t('yii', 'Id Orgao'),
            'status' => Yii::t('yii', 'Status'),
            'evento' => Yii::t('yii', 'Evento do registro'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'codigo' => Yii::t('yii', 'Código'),
            'cnpj' => Yii::t('yii', 'CNPJ'),
            'nome' => Yii::t('yii', 'Nome'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgao() {
        return $this->hasOne(Orgao::className(), ['id' => 'id_orgao']);
    }

    /**
     * Operações executadas antes de salvar
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        $retorno = true;
        if (\pagamentos\controllers\FinParametrosController::getS()) {
            if ($insert) {
                $this->created_at = time();
            } else {
                
            }
            $this->updated_at = time();
        } else {
            $retorno = false;
            Yii::$app->session->setFlash('error', Yii::t('yii', Yii::$app->params['mmf2']));
            $this->addError('id', Yii::t('yii', Yii::$app->params['mmf2']));
        }

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
