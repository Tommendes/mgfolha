<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "user_domains".
 *
 * @property int $id Registro
 * @property string $slug Nome
 * @property int $status Status do dominio
 * @property int $evento Evento do sistema
 * @property int $created_at Criação
 * @property int $updated_at ltima edição
 * @property string $base_servico Diretório do serviço
 * @property string $municipio Município
 * @property string $cliente Cliente
 * @property string $dominio Dominio
 */
class UserDomains extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_domains';
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
            [['slug', 'created_at', 'updated_at', 'status', 'evento'], 'required'],
            [['created_at', 'updated_at', 'status', 'evento'], 'integer'],
            [['slug', 'base_servico', 'municipio', 'cliente', 'dominio'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['base_servico', 'cliente', 'dominio'], 'unique', 'targetAttribute' => ['base_servico', 'cliente', 'dominio']],
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
            'evento' => Yii::t('yii', 'Evento do sistema'),
            'created_at' => Yii::t('yii', 'Registro'),
            'updated_at' => Yii::t('yii', 'Edição em'),
            'municipio' => Yii::t('yii', 'Municipio'),
            'base_servico' => Yii::t('yii', 'Serviço'),
            'cliente' => Yii::t('yii', 'Cliente'),
            'dominio' => Yii::t('yii', 'Dominio'),
        ];
    }

    /**
     * Operações executadas antes de salvar
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        $retorno = true;
        if (\frontend\controllers\FinParametrosController::getS()) {
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
