<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CnpjValidator;

/**
 * This is the model class for table "cad_centros".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $cod_centro Código centro
 * @property string $nome_centro Nome centro de custo
 * @property string $cnpj_ua CNPJ U.A.
 * @property string $cod_ua Código U.A.
 */
class CadCentros extends \yii\db\ActiveRecord {

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
        return 'cad_centros';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'cod_centro', 'nome_centro', 'cnpj_ua', 'cod_ua'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'nome_centro'], 'string', 'max' => 255],
            [['cod_centro', 'cod_ua'], 'string', 'max' => 4],
            [['cnpj_ua'], 'string', 'max' => 14],
            [['cnpj_ua'], CnpjValidator::className()],
            [['slug'], 'unique'],
            [['dominio', 'cod_centro'], 'unique', 'targetAttribute' => ['dominio', 'cod_centro']],
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
            'cod_centro' => 'Código centro',
            'nome_centro' => 'Nome centro',
            'cnpj_ua' => 'Cnpj Ua',
            'cod_ua' => 'Código Ua',
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
