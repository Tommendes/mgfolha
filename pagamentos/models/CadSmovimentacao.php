<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_smovimentacao".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_cad_servidores ID do servidor
 * @property string $codigo_afastamento Código do afastamento
 * @property string $d_afastamento Data do afastamento
 * @property string $codigo_retorno Código do retorno
 * @property string $d_retorno Data do retorno
 * @property string $motivo_desligamentorais Motivo do desligamento
 */
class CadSmovimentacao extends \yii\db\ActiveRecord {

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
        return 'cad_smovimentacao';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_cad_servidores',
            'codigo_afastamento', 'd_afastamento',], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores'], 'integer'],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['codigo_afastamento', 'codigo_retorno', 'motivo_desligamentorais'], 'string', 'max' => 2],
            [['d_afastamento', 'd_retorno'], 'string', 'max' => 10],
            [['d_retorno'], 'required', 'when' => function ($model) {
                    return $model->codigo_retorno != null;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#cadsmovimentacao-codigo_retorno').val().length > 0;
                }"
            ],
            [['motivo_desligamentorais'], 'required', 'when' => function ($model) {
                    return in_array($model->codigo_afastamento, ['H', 'I1', 'I2', 'I3', 'I4', 'J', 'K', 'L', 'S2', 'S3', 'U1', 'U3']);
                },
                'whenClient' => "function (attribute, value) {
                    return $.inArray($('#cadsmovimentacao-codigo_afastamento').val(), ['H', 'I1', 'I2', 'I3', 'I4', 'J', 'K', 'L', 'S2', 'S3', 'U1', 'U3']);
                }"
            ],
            [['slug'], 'unique'],
            [['dominio', 'id_cad_servidores', 'codigo_afastamento', 'd_afastamento'], 'unique', 'targetAttribute' => ['dominio', 'id_cad_servidores', 'codigo_afastamento', 'd_afastamento']],
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
            'dominio' => Yii::t('yii', 'Domínio do cliente'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'id_cad_servidores' => Yii::t('yii', 'ID do servidor'),
            'codigo_afastamento' => Yii::t('yii', 'Código do afastamento'),
            'd_afastamento' => Yii::t('yii', 'Afastamento'),
            'codigo_retorno' => Yii::t('yii', 'Código do retorno'),
            'd_retorno' => Yii::t('yii', 'Retorno'),
            'motivo_desligamentorais' => Yii::t('yii', 'Motivo do desligamento'),
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

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne(is_null($id_cad_servidores) ? $this->id_cad_servidores : $id_cad_servidores);
    }

}
