<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_eventospercentual".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_fin_eventos Codigo evento
 * @property string $id_eventos_percentual Código percentual
 * @property string $data Data
 * @property string $percentual Percentual
 *
 * @property FinEventos $FinEventos
 */
class FinEventospercentual extends \yii\db\ActiveRecord {

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
        return 'fin_eventospercentual';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_fin_eventos', 'id_eventos_percentual', 'data', 'percentual'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_fin_eventos'], 'integer'],
            [['percentual'], 'number', 'min' => 0.01, 'max' => 100.00],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['id_eventos_percentual'], 'string', 'max' => 4],
            [['data'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['dominio', 'id_fin_eventos', 'id_eventos_percentual'], 'unique', 'targetAttribute' => ['dominio', 'id_fin_eventos', 'id_eventos_percentual']],
            [['id_fin_eventos'], 'exist', 'skipOnError' => true, 'targetClass' => FinEventos::className(), 'targetAttribute' => ['id_fin_eventos' => 'id']],
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
            'id_fin_eventos' => Yii::t('yii', 'Codigo evento'),
            'id_eventos_percentual' => Yii::t('yii', 'Código'),
            'data' => Yii::t('yii', 'Data'),
            'percentual' => Yii::t('yii', 'Percentual'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventos() {
        return $this->hasOne(FinEventos::className(), ['id' => 'id_fin_eventos']);
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
            $this->percentual = str_replace(',', '.', $this->percentual);
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

    public function getFinEvento() {
        return FinEventos::findOne($this->id_fin_eventos);
    }

}
