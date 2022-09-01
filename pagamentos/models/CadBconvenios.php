<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_bconvenios".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_cad_bancos Banco conveniado
 * @property int $id_orgao_ua Unidade autônoma
 * @property string $convenio Convênio
 * @property string $cod_compromisso Código do compromisso
 * @property string $agencia Agência
 * @property string $a_digito Ag Dígito
 * @property string $conta Conta
 * @property string $c_digito C Dígito
 * @property string $convenio_nr Convênio
 * @property int $tipo_servico Tipo serviço
 */
class CadBconvenios extends \yii\db\ActiveRecord {

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
        return 'cad_bconvenios';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_cad_bancos', 'id_orgao_ua', 'convenio', 'cod_compromisso', 'agencia', 'conta', 'convenio_nr'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_bancos', 'id_orgao_ua', 'tipo_servico'], 'integer'],
            [['slug', 'dominio', 'convenio', 'convenio_nr'], 'string', 'max' => 255],
            [['cod_compromisso'], 'string', 'max' => 4],
            [['agencia', 'conta'], 'string', 'max' => 15],
            [['a_digito', 'c_digito'], 'string', 'max' => 2],
            [['slug'], 'unique'],
            [['dominio', 'convenio_nr', 'convenio', 'id_cad_bancos', 'id_orgao_ua'], 'unique', 'targetAttribute' => ['dominio', 'convenio_nr', 'convenio', 'id_cad_bancos', 'id_orgao_ua']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'status' => 'Status',
            'dominio' => 'Dominio',
            'evento' => 'Evento',
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
            'id_cad_bancos' => 'Banco',
            'id_orgao_ua' => 'Unidade autônoma',
            'convenio' => 'Convenio',
            'cod_compromisso' => 'Código Compromisso',
            'agencia' => 'Agencia',
            'a_digito' => 'Digito',
            'conta' => 'Conta',
            'c_digito' => 'Digito',
            'convenio_nr' => 'Convenio Nr',
            'tipo_servico' => 'Tipo Servico',
        ];
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

}
