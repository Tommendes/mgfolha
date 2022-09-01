<?php

namespace frontend\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "msgs".
 *
 * @property int $id
 * @property string $slug
 * @property int $status
 * @property string $dominio
 * @property string $caption
 * @property string $caption_color_id
 * @property string $body
 * @property string $link
 * @property int $id_desk_user
 * @property int $evento
 * @property int $created_at
 * @property int $updated_at
 *
 * @property MsgsDestin[] $msgsDestins
 */
class Msgs extends \yii\db\ActiveRecord {

    /**
     * Inativo
     * Refere-se ao estado geral do registro
     */
    const STATUS_INATIVO = 0;

    /**
     * Ativo
     * Refere-se ao estado geral do registro
     */
    const STATUS_ATIVO = 10;

    /**
     * Lida
     * Refere-se ao estado geral do registro
     */
    const STATUS_LIDA = 90;

    /**
     * Cancelado
     * Refere-se ao estado geral do registro
     */
    const STATUS_CANCELADO = 99;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'msgs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                    self::STATUS_INATIVO,
                    self::STATUS_ATIVO,
                    self::STATUS_LIDA,
                    self::STATUS_CANCELADO
                ]
            ],
            [['slug', 'dominio', 'caption', 'body', 'evento', 'created_at', 'updated_at'], 'required'],
            [['status', 'evento', 'id_desk_user', 'id_pos', 'created_at', 'updated_at'], 'integer'],
            [['body'], 'string'],
            [['dominio'], 'string', 'max' => 32],
            [['caption', 'caption_color_id', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'id_pos' => Yii::t('yii', 'Posição no desk'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Dominio'),
            'caption' => Yii::t('yii', 'Título'),
            'caption_color_id' => Yii::t('yii', 'Cor da barra de título'),
            'body' => Yii::t('yii', 'Corpo'),
            'link' => Yii::t('yii', 'Link'),
            'evento' => Yii::t('yii', 'Evento'),
            'id_desk_user' => Yii::t('yii', 'Usuário Desk'),
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsgsDestins() {
        return $this->hasMany(MsgsDestin::className(), ['id_msgs' => 'id']);
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->slug = empty($this->slug) ? sha1(Yii::$app->security->generateRandomString()) : $this->slug;
        }
        $this->dominio = empty($this->dominio) ? Yii::$app->user->identity->dominio : $this->dominio;
        $this->updated_at = time();
        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

    /**
     * Retorna o domímio e razão social do domínio
     * @param type $id
     * @return type
     */
    public function getDominio($id) {
        if ($id > 0) {
            $dominio = Empresa::find()
                    ->select([
                        'dominio' => 'concat(dominio,"-",razaosocial)'
                    ])
                    ->where(['id' => $id])
                    ->one();
        } else {
            $dominio = Yii::$app->name;
        }
        return $dominio;
    }

    /**
     * Retorna os usuários desktop
     * @param type $id
     * @return type
     */
    public function getDeskUser($id) {
        if (($desk_user = DeskUsers::find()
                        ->where(['id' => $id])
                        ->asArray()->one()) == null) {
            $desk_user['dll_nome'] = '';
            $desk_user['cli_nome_comput'] = '';
            $desk_user['cli_nome_user'] = '';
        }
        return $desk_user['dll_nome'] . ' -> ' .
                $desk_user['cli_nome_comput'] . ' -> ' .
                $desk_user['cli_nome_user'];
    }

    /**
     * Retorna as posições desktop
     * @param type $id_pos
     * @return string
     */
    public function getDeskPos($id_pos) {
        switch ($id_pos) {
            case 0 : $pos = 'Superior direito';
                break;
            case 1 : $pos = 'Inferior direito';
                break;
        }
        return $pos;
    }

    /**
     * Retorna os domíios registrados no BD
     * @return type
     */
    public function getDominios() {
        $dominio = Empresa::find()
                ->select([
                    'id' => 'dominio',
                    'dominio' => 'concat(dominio,"-",razaosocial)'
                ])
                ->groupBy('dominio')
                ->all();
        $dominio[] = ['id' => '_app', 'dominio' => Yii::$app->name];
        return $dominio;
    }

}
