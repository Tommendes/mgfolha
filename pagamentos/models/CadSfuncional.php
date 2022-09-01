<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_sfuncional".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_cad_servidores ID do servidor
 * @property string $ano Ano
 * @property string $mes Mês
 * @property string $parcela Parcela
 * @property int $id_local_trabalho Local de trabalho
 * @property int $id_cad_principal Vinculo principal
 * @property string $id_escolaridade Escolaridade
 * @property string $escolaridaderais Escolaridade RAIS
 * @property int $rais Declara RAIS
 * @property int $dirf Declara DIRF
 * @property int $sefip Declara SEFIP
 * @property int $sicap Declara SICAP
 * @property int $insalubridade Insalubridade
 * @property int $decimo Recebe décimo 
 * @property string $id_vinculo Vínculo
 * @property int $id_cat_sefip Categoria SEFIP
 * @property string $ocorrencia Ocorrência
 * @property string $carga_horaria Carga horária
 * @property string $molestia Moléstia
 * @property string $d_laudomolestia Moléstia data
 * @property string $manad_tiponomeacao Tipo nomeação
 * @property string $manad_numeronomeacao Número nomeação
 * @property string $d_tempo
 * @property string $d_tempofim
 * @property string $d_beneficio
 * @property string $n_valorbaseinss Valor base INSS
 *
 * @property CadServidores $cadServidores
 * @property CadLocaltrabalho $localTrabalho
 */
class CadSfuncional extends \yii\db\ActiveRecord {

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
        return 'cad_sfuncional';
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
            'ano', 'mes', 'parcela', 'id_local_trabalho', 'rais', 'dirf', 'sefip',
            'sicap', 'insalubridade', 'decimo',
            'carga_horaria',
//            'manad_tiponomeacao', 'id_cat_sefip', 'id_escolaridade', 'id_vinculo', 
//            'escolaridaderais',
                ], 'required'],
//            [['manad_numeronomeacao'], 'required', 'when' => function ($model) {
//                    return $model->manad_tiponomeacao >= 1;
//                },
//                'whenClient' => "function (attribute, value) {
//                    return $('#cadsfuncional-manad_tiponomeacao').val() >= 1;
//                }"
//            ],
            [['d_laudomolestia'], 'required', 'when' => function ($model) {
                    return $model->molestia >= 1;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#cadsfuncional-molestia').val() >= 1;
                }"
            ],
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores',
            'id_local_trabalho', 'id_cad_principal', 'rais', 'dirf', 'sefip',
            'sicap', 'insalubridade', 'decimo', 'id_cat_sefip'], 'integer'],
            [['carga_horaria', 'n_valorbaseinss'], 'number'],
            [['ano',], 'string', 'max' => 4],
            [['mes',], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['id_escolaridade', 'escolaridaderais', 'id_vinculo', 'ocorrencia', 'molestia'], 'string', 'max' => 2],
            [['d_laudomolestia', 'd_tempo', 'd_tempofim', 'd_beneficio'], 'string', 'max' => 10],
            [['manad_tiponomeacao', 'manad_numeronomeacao'], 'string', 'max' => 20],
//            [['id_cad_servidores'], 'unique'],
            [['dominio', 'id_cad_servidores', 'ano', 'mes', 'parcela'], 'unique', 'targetAttribute' => ['dominio', 'id_cad_servidores', 'ano', 'mes', 'parcela']],
            [['id_cad_servidores'], 'exist', 'skipOnError' => true, 'targetClass' => CadServidores::className(), 'targetAttribute' => ['id_cad_servidores' => 'id']],
            [['id_local_trabalho'], 'exist', 'skipOnError' => true, 'targetClass' => CadLocaltrabalho::className(), 'targetAttribute' => ['id_local_trabalho' => 'id']],
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
            'dominio' => Yii::t('yii', 'Dominio'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'id_cad_servidores' => Yii::t('yii', 'ID do servidor'),
            'ano' => Yii::t('yii', 'Ano'),
            'mes' => Yii::t('yii', 'Mês'),
            'parcela' => Yii::t('yii', 'Parcela'),
            'id_local_trabalho' => Yii::t('yii', 'Local de trabalho'),
            'id_cad_principal' => Yii::t('yii', 'Matrícula principal'),
            'id_escolaridade' => Yii::t('yii', 'Escolaridade'),
            'escolaridaderais' => Yii::t('yii', 'Escolaridade RAIS'),
            'rais' => Yii::t('yii', 'Declara RAIS'),
            'dirf' => Yii::t('yii', 'Declara DIRF'),
            'sefip' => Yii::t('yii', 'Declara SEFIP'),
            'sicap' => Yii::t('yii', 'Declara SICAP'),
            'insalubridade' => Yii::t('yii', 'Insalubridade'),
            'decimo' => Yii::t('yii', 'Recebe décimo'),
            'id_vinculo' => Yii::t('yii', 'Vínculo'),
            'id_cat_sefip' => Yii::t('yii', 'Categoria SEFIP'),
            'ocorrencia' => Yii::t('yii', 'Ocorrência'),
            'carga_horaria' => Yii::t('yii', 'Carga horária'),
            'molestia' => Yii::t('yii', 'Moléstia'),
            'd_laudomolestia' => Yii::t('yii', 'Moléstia data'),
            'manad_tiponomeacao' => Yii::t('yii', 'Tipo nomeação'),
            'manad_numeronomeacao' => Yii::t('yii', 'Número nomeação'),
            'd_tempo' => Yii::t('yii', 'Início PCC'),
            'd_tempofim' => Yii::t('yii', 'Limite PCC'),
            'd_beneficio' => Yii::t('yii', 'Saída para beneficio'),
            'n_valorbaseinss' => Yii::t('yii', 'Valor INSS outros entes'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadServidores() {
        return $this->hasOne(CadServidores::className(), ['id' => 'id_cad_servidores']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocalTrabalho() {
        return $this->hasOne(CadLocaltrabalho::className(), ['id' => 'id_local_trabalho']);
    }

    /*
     * Retorna a data de Admissão do servidor
     */

    public function getDAdmissao() {
        return $this->getCadServidor()->d_admissao;
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
                $this->slug = is_null($this->slug) ? strtolower(sha1($this->tableName() . time())) : ($this->slug);
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
