<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_parametros".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $ano Ano
 * @property string $mes Mês
 * @property string $parcela Parcela
 * @property string $ano_informacao Ano informação
 * @property string $mes_informacao Mês informação
 * @property string $parcela_informacao Parcela informação
 * @property string $descricao Descrição
 * @property int $situacao Situação
 * @property string $d_situacao Data situação
 * @property string $mensagem Mensagem
 * @property string $mensagem_aniversario Mensagem aniversário
 * @property int $manad_tipofolha Manad tipo folha
 */
class FinParametros extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;
    const SITUACAO_FECHADA = 0;
    const SITUACAO_ABERTA = 1;

    public $mesAbrev;

    /**
     * Variável utilizada em actionSC em FinParametrosController
     * ARmazena o resultado da query que identifica a virada do mês
     * e determina a criação de um novo parâmetro de folha
     * @var type 
     */
    public $sc;

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
        return self::getDb()->dbname . '.fin_parametros';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'ano', 'mes', 'parcela', 'ano_informacao', 'mes_informacao', 'parcela_informacao', 'descricao', 'situacao', 'd_situacao', 'mensagem', 'mensagem_aniversario', 'manad_tipofolha'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'situacao', 'manad_tipofolha'], 'integer'],
            [['slug', 'dominio', 'descricao', 'mensagem', 'mensagem_aniversario'], 'string', 'max' => 255],
            [['ano', 'ano_informacao'], 'string', 'max' => 4],
            [['mes', 'mes_informacao'], 'string', 'max' => 2],
            [['parcela', 'parcela_informacao'], 'string', 'max' => 3],
            [['d_situacao'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['dominio', 'mes', 'ano', 'parcela'], 'unique', 'targetAttribute' => ['dominio', 'mes', 'ano', 'parcela']],
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
            'ano' => Yii::t('yii', 'Ano'),
            'mes' => Yii::t('yii', 'Mês'),
            'parcela' => Yii::t('yii', 'Parcela'),
            'ano_informacao' => Yii::t('yii', 'Ano informação'),
            'mes_informacao' => Yii::t('yii', 'Mês informação'),
            'parcela_informacao' => Yii::t('yii', 'Parcela informação'),
            'descricao' => Yii::t('yii', 'Descrição'),
            'situacao' => Yii::t('yii', 'Situação'),
            'd_situacao' => Yii::t('yii', 'Data situação'),
            'mensagem' => Yii::t('yii', 'Mensagem'),
            'mensagem_aniversario' => Yii::t('yii', 'Mensagem aniversário'),
            'manad_tipofolha' => Yii::t('yii', 'Manad tipo folha'),
        ];
    }

    /**
     * Operações executadas antes de salvar 
     * @param type $insert
     * @return boolean  
     */
    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->slug = strtolower(sha1(uniqid(rand(0, time()), true)));
            $this->dominio = Yii::$app->user->identity->dominio;
        } else {
            if (\pagamentos\controllers\FinParametrosController::getS()) {
                if (!isset(Yii::$app->user->identity->dominio)) {
                    $this->dominio = Yii::$app->user->identity->dominio;
                }
            } else {
                $retorno = false;
                Yii::$app->session->setFlash('error', Yii::t('yii', Yii::$app->params['mmf2']));
                $this->addError('id', Yii::t('yii', Yii::$app->params['mmf2']));
            }
        }
        $this->updated_at = time();

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

    /**
     * Retorna o parâmetro atual no model
     * @return type
     */
    public function getParametroAtual() {
        return Yii::$app->user->identity->per_ano . '|' . Yii::$app->user->identity->per_mes . '|' . Yii::$app->user->identity->per_parcela;
    }

}
