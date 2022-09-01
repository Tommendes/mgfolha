<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CpfValidator;

/**
 * This is the model class for table "cad_sdependentes".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_cad_servidores ID do servidor
 * @property int $matricula Matricula dependente
 * @property string $nome Nome
 * @property int $tipo Tipo
 * @property int $permanente Permanente
 * @property string|null $nascimento_d Nascimento data
 * @property string|null $inss_prz Prazo INSS
 * @property string|null $irrf_prz Prazo IRRF
 * @property string|null $rpps_prz Prazo RPPS
 * @property string|null $rg RG
 * @property string|null $rg_emissor RG emissor
 * @property string|null $rg_uf RG UF
 * @property string|null $rg_d RG emissão
 * @property string|null $certidao Certidão
 * @property string|null $livro Livro
 * @property string|null $folha Folha
 * @property string|null $certidao_d Emissão
 * @property int|null $carteira_vacinacao Cartão de vacinação
 * @property int|null $historico_escolar Histórico escolar
 * @property int|null $plano_saude Plano de saúde
 * @property string|null $cpf CPF
 * @property string|null $relacao_dependecia Relação de dependência
 * @property int|null $pensionista Pensionista
 * @property int|null $irpf Dependente IRRF
 *
 * @property CadServidores $cadServidores
 */
class CadSdependentes extends \yii\db\ActiveRecord {

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
        return 'cad_sdependentes';
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
            'matricula', 'nome', 'tipo', 'permanente', 'carteira_vacinacao',
            'historico_escolar', 'plano_saude', 'pensionista',
            'nascimento_d', 'inss_prz', 'irrf_prz', 'rpps_prz',], 'required'],
            ['cpf', 'required', 'when' => function ($model) {
                    return $model->irpf == 1;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#cadsdependentes-irpf').val() == 1;
                }"
            ],
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores',
            'matricula', 'tipo', 'permanente', 'carteira_vacinacao',
            'historico_escolar', 'plano_saude', 'pensionista', 'irpf'], 'integer'],
            [['slug', 'dominio', 'nome'], 'string', 'max' => 255],
            [['nascimento_d', 'inss_prz', 'irrf_prz', 'rpps_prz', 'rg_d', 'certidao_d'], 'string', 'max' => 10],
            [['rg', 'rg_emissor'], 'string', 'max' => 20],
            [['rg_uf'], 'string', 'max' => 2],
            [['certidao'], 'string', 'max' => 100],
            [['livro', 'folha', 'relacao_dependecia'], 'string', 'max' => 8],
            [['cpf'], 'string', 'max' => 14],
            [['cpf'], CpfValidator::className()],
            [['slug'], 'unique'],
            [['id_cad_servidores', 'matricula', 'dominio'], 'unique', 'targetAttribute' => ['id_cad_servidores', 'matricula', 'dominio']],
            [['id_cad_servidores', 'cpf', 'dominio'], 'unique', 'targetAttribute' => ['id_cad_servidores', 'cpf', 'dominio']],
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
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'id_cad_servidores' => Yii::t('yii', 'ID do servidor'),
            'matricula' => Yii::t('yii', 'Matricula dependente'),
            'nome' => Yii::t('yii', 'Nome'),
            'tipo' => Yii::t('yii', 'Tipo'),
            'permanente' => Yii::t('yii', 'Inválido'),
            'nascimento_d' => Yii::t('yii', 'Nascimento data'),
            'inss_prz' => Yii::t('yii', 'Prazo INSS'),
            'irrf_prz' => Yii::t('yii', 'Prazo IRRF'),
            'rpps_prz' => Yii::t('yii', 'Prazo RPPS'),
            'rg' => Yii::t('yii', 'RG'),
            'rg_emissor' => Yii::t('yii', 'RG emissor'),
            'rg_uf' => Yii::t('yii', 'RG UF'),
            'rg_d' => Yii::t('yii', 'RG emissão'),
            'certidao' => Yii::t('yii', 'Certidão Nascimento'),
            'livro' => Yii::t('yii', 'Livro'),
            'folha' => Yii::t('yii', 'Folha'),
            'certidao_d' => Yii::t('yii', 'Certidão data'),
            'carteira_vacinacao' => Yii::t('yii', 'Cartão de vacinação'),
            'historico_escolar' => Yii::t('yii', 'Histórico escolar'),
            'plano_saude' => Yii::t('yii', 'Plano de saúde'),
            'cpf' => Yii::t('yii', 'CPF'),
            'relacao_dependecia' => Yii::t('yii', 'Relação de dependência'),
            'pensionista' => Yii::t('yii', 'Pensionista'),
            'irpf' => Yii::t('yii', 'Dependente do IRRF'),
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
            $this->cpf = str_replace('.', '', str_replace('-', '', $this->cpf));
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
        $this->matricula = str_pad($this->matricula, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($id_cad_servidores) {
        return CadServidores::findOne($id_cad_servidores);
    }

}
