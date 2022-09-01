<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "sis_reviews".
 *
 * @property int $id ID do registro
 * @property string $slug Id de chamada do registro
 * @property string $dominio Domínio do cliente
 * @property int $versao Versão
 * @property int $lancamento Lancamento
 * @property int $revisao Revisao
 * @property string $titulo Título
 * @property string $descricao Revisão
 * @property int $status Status da revisão
 * @property int $evento
 * @property int $created_at Data de registro
 * @property int $updated_at Última da atualização
 */
class SisReviews extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'sis_reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['slug', 'dominio', 'titulo', 'evento', 'created_at', 'updated_at'], 'required'],
            [['versao', 'lancamento', 'revisao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['descricao'], 'string'],
            [['slug'], 'string', 'max' => 255],
            [['dominio'], 'string', 'max' => 40],
            [['titulo'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'slug' => Yii::t('yii', 'Id de chamada do registro'),
            'dominio' => Yii::t('yii', 'Domínio do cliente'),
            'versao' => Yii::t('yii', 'Versão'),
            'lancamento' => Yii::t('yii', 'Lancamento'),
            'revisao' => Yii::t('yii', 'Revisao'),
            'titulo' => Yii::t('yii', 'Título'),
            'descricao' => Yii::t('yii', 'Revisão'),
            'status' => Yii::t('yii', 'Status da revisão'),
            'evento' => Yii::t('yii', 'Evento'),
            'created_at' => Yii::t('yii', 'Data de registro'),
            'updated_at' => Yii::t('yii', 'Última da atualização'),
        ];
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->slug = strtolower(sha1($this->tableName() . time()));
//            $this->dominio = $this->generateDominio();
        } else {
            if (!isset(Yii::$app->user->identity->dominio)) {
                $this->dominio = Yii::$app->user->identity->dominio;
            }
        }
        $this->updated_at = time();

        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
