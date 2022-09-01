<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;
use pagamentos\controllers\FinParametrosController;

/**
 * This is the model class for table "local_params".
 *
 * @property int $id
 * @property string $slug Id de chamada do registro
 * @property int $status Status
 * @property string $dominio Domínio do cliente
 * @property string $grupo Grupo do parâmetro
 * @property string $parametro Parâmetro
 * @property string $label Label do parametro
 * @property string $data_registro Data do registro
 * @property int $evento ID do evento relacionado
 * @property int $created_at Data de registro
 * @property int $updated_at Última da atualização
 */
class LocalParams extends \yii\db\ActiveRecord {

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
        return 'local_params';
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
            [['slug', 'created_at', 'updated_at'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['parametro', 'label'], 'string'],
            [['data_registro'], 'safe'],
            [['slug'], 'string', 'max' => 255],
            [['dominio'], 'string', 'max' => 40],
            [['grupo'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Dominio'),
            'grupo' => Yii::t('yii', 'Grupo'),
            'parametro' => Yii::t('yii', 'Parametro'),
            'label' => Yii::t('yii', 'Label'),
            'data_registro' => Yii::t('yii', 'Data Registro'),
            'evento' => Yii::t('yii', 'Evento'),
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
        if (FinParametrosController::getS()) {
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
