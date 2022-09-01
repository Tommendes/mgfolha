<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CnpjValidator;

/**
 * This is the model class for table "orgao".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Evento do registro
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $orgao Nome do cliente
 * @property string $cnpj CNPJ do cliente
 * @property string $url_logo Url da logo do cliente
 * @property string $cep Cep
 * @property string $logradouro Logradouro
 * @property string $numero Número
 * @property string $complemento Complemento
 * @property string $bairro Bairro
 * @property string $cidade Cidade
 * @property string $uf Estado
 * @property string $email Email
 * @property string $telefone Telefone
 * @property string $codigo_fpas Código FPAS
 * @property string $codigo_gps Código GPS
 * @property string $codigo_cnae Código CNAE
 * @property string $codigo_ibge Código IBGE
 * @property string $codigo_fgts Código FGTS
 * @property string $mes_descsindical Mês desconto sindical
 * @property string $cpf_responsavel_dirf CPF Resp Dirf
 * @property string $nome_responsavel_dirf Nome Resp Dirf
 *
 * @property OrgaoResp[] $orgaoResps
 * @property OrgaoUa[] $orgaoUas
 */
class Orgao extends \yii\db\ActiveRecord {

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
        return 'orgao';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'orgao', 'cnpj', 'cep',
            'logradouro', 'numero', 'cidade'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'orgao', 'url_logo', 'logradouro', 'bairro', 'cidade',
            'codigo_fpas', 'codigo_gps', 'codigo_cnae', 'cpf_responsavel_dirf',
            'nome_responsavel_dirf'], 'string', 'max' => 255],
            [['cnpj', 'telefone'], 'string', 'max' => 18],
            [['cnpj'], CnpjValidator::className()],
            [['cpf_responsavel_dirf'], 'string', 'max' => 14],
            [['cep'], 'string', 'max' => 8],
            [['email'], 'email'],
            [['numero', 'cnpj', 'codigo_ibge', 'codigo_fgts', 'codigo_fpas', 'codigo_gps',
            'codigo_cnae', 'cpf_responsavel_dirf'], 'number'],
            [['complemento'], 'string', 'max' => 40],
            [['uf', 'mes_descsindical'], 'string', 'max' => 2],
            [['cnpj', 'slug', 'dominio'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Domínio do orgão'),
            'evento' => Yii::t('yii', 'Evento do registro'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'orgao' => Yii::t('yii', 'Nome do orgão'),
            'cnpj' => Yii::t('yii', 'CNPJ do orgão'),
            'url_logo' => Yii::t('yii', 'Logo do orgão'),
            'cep' => Yii::t('yii', 'Cep'),
            'logradouro' => Yii::t('yii', 'Logradouro'),
            'numero' => Yii::t('yii', 'Número'),
            'complemento' => Yii::t('yii', 'Complemento'),
            'bairro' => Yii::t('yii', 'Bairro'),
            'cidade' => Yii::t('yii', 'Cidade'),
            'uf' => Yii::t('yii', 'Estado'),
            'email' => Yii::t('yii', 'Email'),
            'telefone' => Yii::t('yii', 'Telefone'),
            'codigo_fpas' => Yii::t('yii', 'Código FPAS'),
            'codigo_gps' => Yii::t('yii', 'Código GPS'),
            'codigo_cnae' => Yii::t('yii', 'Código CNAE'),
            'codigo_ibge' => Yii::t('yii', 'Código IBGE'),
            'codigo_fgts' => Yii::t('yii', 'Código FGTS'),
            'mes_descsindical' => Yii::t('yii', 'Mês desconto sindical'),
            'cpf_responsavel_dirf' => Yii::t('yii', 'CPF Resp Dirf'),
            'nome_responsavel_dirf' => Yii::t('yii', 'Nome Resp Dirf'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgaoResps() {
        return $this->hasMany(OrgaoResp::className(), ['id_orgao' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgaoUas() {
        return $this->hasMany(OrgaoUa::className(), ['id_orgao' => 'id']);
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
                $this->slug = strtolower(sha1($this->tableName() . time()));
                $this->dominio = Yii::$app->user->identity->dominio;
            } else {
                if (!isset(Yii::$app->user->identity->dominio)) {
                    $this->dominio = Yii::$app->user->identity->dominio;
                }
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

    /**
     * Retorna um label para o status informado
     * @param type $value
     * @return string
     */
    public static function getStatusLabel($value = null) {
        $result = '';
        switch ($value) {
            case self::STATUS_INATIVO: $result = 'Inativo';
                break;
            case self::STATUS_ATIVO: $result = 'Ativo';
                break;
            case self::STATUS_CANCELADO: $result = 'Cancelado';
                break;
        }
        return $result;
    }

}
