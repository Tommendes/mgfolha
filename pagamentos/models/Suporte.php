<?php

namespace pagamentos\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "sis_suporte".
 *
 * @property integer $id
 * @property string $slug
 * @property string $dominio
 * @property string $grupo
 * @property string $slug
 * @property string $descricao
 * @property string $texto
 * @property integer $status
 * @property integer $evento
 * @property integer $created_at
 * @property integer $updated_at 
 */
class Suporte extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_CANCELADO = 99;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sis_suporte';
    }

    public function behaviors() {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'slug',
            ],
        ];
    }

    protected function findModelBySlug($slug) {
        if (($model = $this->findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['slug'], 'required'],
//            [['dominio', 'grupo', 'slug', 'descricao', 'status', 
//                'evento', 'created_at', 'updated_at'], 'required'],
            [['texto'], 'string'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['dominio'], 'string', 'max' => 30],
            [['grupo', 'slug', 'descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'Registro'),
            'dominio' => Yii::t('yii', 'Domínio'),
            'grupo' => Yii::t('yii', 'Grupo'),
            'slug' => Yii::t('yii', 'slug'),
            'descricao' => Yii::t('yii', 'Descrição'),
            'texto' => Yii::t('yii', 'Texto do suporte'),
            'status' => Yii::t('yii', 'Status'),
            'evento' => Yii::t('yii', 'Último evento relacionado'),
            'created_at' => Yii::t('yii', 'Criação do registro'),
            'updated_at' => Yii::t('yii', 'Última atualização'),
        ];
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->updated_at = time();
            $this->slug = (strlen($this->slug) == 0 || is_null($this->slug)) ? strtolower(sha1($this->tableName() . time())) : $this->slug;
        } else {
            $this->updated_at = time();
        }
        $this->slug = strtolower(str_replace(' ', '_', $this->slug));
        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
//        $this->created_at = Yii::$app->formatter->asDatetime($this->created_at, 'php:d-m-Y H:i:s');
//        $this->updated_at = Yii::$app->formatter->asDatetime($this->updated_at, 'php:d-m-Y H:i:s');
    }

    /**
     * Retorna o domímio e razão social do domínio
     * @param type $id
     * @return type
     */
    public function getDominio($id = null) {
        if ($id == null) {
            $dominio = Empresa::find()
                    ->select([
                        'id' => 'id',
                        'dominio' => 'concat(dominio,"-",razaosocial)'
                    ])
                    ->groupBy('dominio')
                    ->all();
            $dominio[] = ['id' => 0, 'dominio' => Yii::$app->name];
        } else {
            if ($id > 0) {
                $dominio = Empresa::find()
                                ->select([
                                    'dominio' => 'concat(dominio,"-",razaosocial)'
                                ])
                                ->where(['id' => $id])
                                ->one()->dominio;
            } else {
                $dominio = Yii::$app->name;
            }
        }
        return $dominio;
    }

    /**
     * Retorna o domímio e razão social do domínio
     * @param type $id
     * @return type
     */
    public function getGrupo($id = null) {
        if ($id == null) {
            $grupo = SisParams::find()
                    ->select([
                        'id' => 'id',
                        'label' => 'label'
                    ])
                    ->where([
                        'dominio' => 'lynkos',
                        'grupo' => 'suporte',
                    ])
                    ->groupBy('label')
                    ->all();
        } else {
            $grupo = SisParams::find()
                            ->where(['id' => $id])
                            ->one()->label;
        } return $grupo;
    }

    /**
     * Retorna o status do registro
     * @param type $id
     * @return type
     */
//    public function getStatus($id = null) {
//        if ($id == null) {
//            $status = SisParams::find()
//                    ->select([
//                        'id' => 'id',
//                        'label' => 'label'
//                    ])
//                    ->where([
//                        'dominio' => 'lynkos',
//                        'grupo' => 'sup_status'
//                    ])
//                    ->groupBy('label')
//                    ->all();
//        } else {
//            $status = SisParams::find()
//                            ->where([
//                                'id' => $id
//                            ])
//                            ->one()->label;
//        }
//        return $status;
//    }
}
