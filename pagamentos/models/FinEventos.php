<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_eventos". 
 *
 * @property int $id ID do registro 
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $ev_root Evento calculado pelo sistema
 * @property string $id_evento Codigo evento
 * @property string $evento_nome Evento
 * @property string $tipo Tipo
 * @property int $consignado Consignado
 * @property int $consignavel Consignável
 * @property int $deduzconsig Deduz da consignação
 * @property int $automatico Automático
 * @property string $vinculacao_dirf DIRF
 * @property int $i_prioridade Prioridade
 * @property int $fixo Fixo
 * @property int $sefip SEFIP
 * @property int $rais RAIS
 *
 * @property FinBasefixaeventos[] $finBasefixaeventos
 * @property FinEventosbase[] $FinEventosbases
 * @property FinEventosbase[] $FinEventosbases0
 * @property FinEventosdesconto[] $FinEventosdescontos
 * @property FinEventosdesconto[] $FinEventosdescontos0
 * @property FinEventospercentual[] $FinEventospercentuals
 * @property FinRubricas[] $finRubricas
 */
class FinEventos extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;
//    Tipos de evento
    const EVENTO_CREDITO = 0;
    const EVENTO_DEBITO = 1;

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
        return 'fin_eventos';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_evento', 'evento_nome',
            'tipo', 'consignado', 'consignavel', 'deduzconsig', 'automatico',
            'i_prioridade', 'fixo', 'sefip', 'rais', 'ev_root'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'consignado',
            'consignavel', 'deduzconsig', 'automatico',
            'fixo', 'sefip', 'rais', 'ev_root'], 'integer'],
            [['i_prioridade'], 'number', 'min' => 1, 'max' => 10],
            [['slug', 'dominio', 'evento_nome'], 'string', 'max' => 255],
            [['id_evento'], 'string', 'max' => 3],
            [['tipo'], 'string', 'max' => 1],
            [['vinculacao_dirf'], 'string', 'max' => 5],
            [['slug'], 'unique'],
            [['dominio', 'id_evento'], 'unique', 'targetAttribute' => ['dominio', 'id_evento']],
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
            'id_evento' => Yii::t('yii', 'Rúbrica'),
            'evento_nome' => Yii::t('yii', 'Nome'),
            'tipo' => Yii::t('yii', 'Tipo'),
            'consignado' => Yii::t('yii', 'Consignado'),
            'consignavel' => Yii::t('yii', 'Consignável'),
            'deduzconsig' => Yii::t('yii', 'Deduz em consignação'),
            'automatico' => Yii::t('yii', 'Automático'),
            'vinculacao_dirf' => Yii::t('yii', 'DIRF'),
            'i_prioridade' => Yii::t('yii', 'Prioridade'),
            'fixo' => Yii::t('yii', 'Fixo'),
            'sefip' => Yii::t('yii', 'SEFIP'),
            'rais' => Yii::t('yii', 'RAIS'),
            'ev_root' => Yii::t('yii', 'Calculado pelo sistema'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinBasefixaeventos() {
        return $this->hasMany(FinBasefixaeventos::className(), ['id_eventos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventosbases() {
        return $this->hasMany(FinEventosbase::className(), ['id_fin_eventos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventosbases0() {
        return $this->hasMany(FinEventosbase::className(), ['id_fin_eventosbase' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventosdescontos() {
        return $this->hasMany(FinEventosdesconto::className(), ['id_eventos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventosdescontos0() {
        return $this->hasMany(FinEventosdesconto::className(), ['id_eventos_desconto' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventospercentuals() {
        return $this->hasMany(FinEventospercentual::className(), ['id_eventos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinRubricas() {
        return $this->hasMany(FinRubricas::className(), ['id_fin_eventos' => 'id']);
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
        $this->id_evento = str_pad((integer) $this->id_evento, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna a base fixa do evento
     */
    public function getBaseFixa() {
        return FinBasefixa::find()
                        ->select([
                            'id' => 'fin_basefixa.id',
                            'slug' => 'fin_basefixa.slug',
                            'nome_base_fixa' => 'fin_basefixa.nome_base_fixa',
                            'id_base_fixa' => 'fin_basefixa.id_base_fixa',
                            'fixa_refer_id' => 'fin_basefixarefer.id',
                            'fixa_refer_id_fixa' => 'fin_basefixarefer.id_fixa',
                            'fixa_refer_descricao' => 'fin_basefixarefer.descricao',
                            'fixa_refer_data' => 'fin_basefixarefer.data',
                            'fixa_refer_valor' => 'fin_basefixarefer.valor',
                        ])
                        ->where([
                            'fin_basefixa.dominio' => Yii::$app->user->identity->dominio,
                            'fin_basefixaeventos.id_fin_eventos' => $this->id,
                        ])
                        ->join('join', 'fin_basefixarefer', 'fin_basefixarefer.id_fin_base_fixa = fin_basefixa.id')
                        ->join('join', 'fin_basefixaeventos', 'fin_basefixaeventos.id_fin_base_fixa = fin_basefixa.id and '
                                . 'fin_basefixarefer.id_fin_base_fixa = fin_basefixa.id')
                        ->orderBy(['data' => SORT_DESC])
                        ->limit(1)
                        ->one();
    }

    /**
     * Retorna os valores em BD.getUseOfIdEvento
     * @param type $id_fin_eventos
     * @param type $v_ano
     * @param type $v_mes
     * @param type $v_parcela
     * @return type
     */
    public function getUseOfIdEvento() {
        return Yii::$app->db->createCommand(
                        "select " . $this->getDb()->dbname . ".getUseOfIdEvento('$this->dominio', '$this->id') AS quant"
                )->queryOne();
    }

}
