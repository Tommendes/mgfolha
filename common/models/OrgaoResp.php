<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CpfValidator;

/**
 * This is the model class for table "orgao_resp".
 *
 * @property int $id ID do registro
 * @property int $id_orgao Orgão
 * @property int $status
 * @property int $evento Evento do registro
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $cpf_gestor Cpf do gestor
 * @property string $nome_gestor Nome do gestor
 * @property string $d_nascimento Nascimento do gestor
 * @property string $cep Cep
 * @property string $logradouro Logradouro
 * @property string $numero Número
 * @property string $complemento Complemento
 * @property string $bairro Bairro
 * @property string $cidade Cidade
 * @property string $uf Estado
 * @property string $email Email
 * @property string $telefone Telefone
 *
 * @property Orgao $orgao
 */
class OrgaoResp extends \yii\db\ActiveRecord {

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
        return 'orgao_resp';
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
            [['id_orgao', 'evento', 'created_at', 'updated_at', 'cpf_gestor', 'nome_gestor',
            'cep', 'logradouro', 'numero', 'bairro', 'cidade'], 'required'],
            [['id_orgao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['cpf_gestor'], 'string', 'max' => 11],
            [['cpf_gestor'], CpfValidator::className()],
            [['nome_gestor', 'logradouro', 'bairro', 'cidade', 'email'], 'string', 'max' => 255],
            [['d_nascimento', 'numero'], 'string', 'max' => 10],
            [['cep'], 'string', 'max' => 8],
            [['complemento'], 'string', 'max' => 40],
            [['uf'], 'string', 'max' => 2],
            [['telefone'], 'string', 'max' => 14],
            [['id_orgao', 'cpf_gestor'], 'unique', 'targetAttribute' => ['id_orgao', 'cpf_gestor']],
            [['id_orgao'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['id_orgao' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'id_orgao' => Yii::t('yii', 'Orgão'),
            'status' => Yii::t('yii', 'Status'),
            'evento' => Yii::t('yii', 'Evento do registro'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'cpf_gestor' => Yii::t('yii', 'Cpf do gestor'),
            'nome_gestor' => Yii::t('yii', 'Nome do gestor'),
            'd_nascimento' => Yii::t('yii', 'Nascimento do gestor'),
            'cep' => Yii::t('yii', 'Cep'),
            'logradouro' => Yii::t('yii', 'Logradouro'),
            'numero' => Yii::t('yii', 'Número'),
            'complemento' => Yii::t('yii', 'Complemento'),
            'bairro' => Yii::t('yii', 'Bairro'),
            'cidade' => Yii::t('yii', 'Cidade'),
            'uf' => Yii::t('yii', 'Estado'),
            'email' => Yii::t('yii', 'Email'),
            'telefone' => Yii::t('yii', 'Telefone'),
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
