<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_scertidao".
 *
 * @property int $id ID do registro
 * @property int $status
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property string $slug
 * @property string $dominio Domínio do cliente
 * @property int $updated_at Atualização em
 * @property int $id_cad_servidores ID do servidor
 * @property int $tipo Tipo
 * @property string $certidao Certidão
 * @property string $emissao Emissão
 * @property string $cartorio Cartório
 * @property string $uf Uf
 * @property string $cidade Municipio
 * @property string $termo Termo
 * @property string $livro Livro
 * @property string $folha Folha
 *
 * @property CadServidores $cadServidores
 */
class CadScertidao extends \yii\db\ActiveRecord {

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
        return 'cad_scertidao';
    }

    public $res;

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
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores', 'tipo'], 'integer'],
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_cad_servidores'], 'required'],
            [['slug', 'dominio', 'certidao', 'cartorio', 'cidade', 'termo', 'livro', 'folha'], 'string', 'max' => 255],
            [['emissao'], 'string', 'max' => 10],
            [['uf'], 'string', 'max' => 2],
            [['id_cad_servidores', 'tipo'], 'unique', 'targetAttribute' => ['id_cad_servidores', 'tipo',],
//                'when' => function ($model) {
//                    Yii::$app->session->setFlash('danger', $this->res = Yii::t('yii', 'Não é possível haver '
//                                    . 'mais de uma certidão de nascimento.<br>'
//                                    . 'Se desejar, '
//                                    . Html::a('clique para ver as ' . strtolower(CadScertidaoController::CLASS_VERB_NAME_PL) . ' do servidor', ['/cad-scertidao/index', 'id_cad_servidores' => $this->id_cad_servidores], ['target' => '_empty'])
//                                    . '. Uma nova aba será aberta'));
//                    return $model->tipo == 0;
//                },
            ],
//            [['id_cad_servidores', 'tipo', 'emissao'], 'unique', 'targetAttribute' => ['id_cad_servidores', 'tipo', 'emissao'],
//                'when' => function ($model) {
//                    Yii::$app->session->setFlash('danger', $this->res = Yii::t('yii', 'Não é possível haver '
//                                    . 'mais de uma certidão de nascimento ou casamento '
//                                    . 'com a mesma data de emissão.<br>'
//                                    . 'Se desejar, '
//                                    . Html::a('clique para ver as ' . strtolower(CadScertidaoController::CLASS_VERB_NAME_PL) . ' do servidor', ['/cad-scertidao/index', 'id_cad_servidores' => $this->id_cad_servidores], ['target' => '_empty'])
//                                    . '. Uma nova aba será aberta'));
//                    return $model->tipo == 1;
//                }, 'whenClient' => "function (attribute, value) {
//                        return $('#cadscertidao-tipo').val() == '1';
//                    }"
//            ],
            [['id_cad_servidores'], 'exist', 'skipOnError' => true, 'targetClass' => CadServidores::className(), 'targetAttribute' => ['id_cad_servidores' => 'id']],
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
            'created_at' => Yii::t('yii', 'Registrado em'),
            'updated_at' => Yii::t('yii', 'Atualizado em'),
            'id_cad_servidores' => 'Id Cad Servidores',
            'tipo' => 'Tipo',
            'certidao' => 'Certidao',
            'emissao' => 'Emissao',
            'cartorio' => 'Cartorio',
            'uf' => 'Uf',
            'cidade' => 'Cidade',
            'termo' => 'Termo',
            'livro' => 'Livro',
            'folha' => 'Folha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadServidores() {
        return $this->hasOne(CadServidores::className(), ['id' => 'id_cad_servidores']);
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
