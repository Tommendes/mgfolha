<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\controllers\SisParamsController;
use frontend\controllers\UserOptionsController;
use common\controllers\AppController;
use yii\helpers\Json;

/**
 * This is the model class for table "user".
 *
 * @property int $id ID do registro
 * @property string $username Nome de usuário
 * @property string $hash Identificador de usuário
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $github
 * @property string $url_foto
 * @property string $email Email usuário
 * @property string $tel_contato Telefone usuário
 * @property string $slug
 * @property int $status
 * @property string $base_servico Diretório do serviço
 * @property string $cliente Cliente
 * @property string $dominio Dominio
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $administrador Administrador
 * @property int $gestor Gestor
 * @property int $usuarios Gestão de usuários 
 * @property int $cadastros Gestão de cadastros
 * @property int $folha Gestão de folha
 * @property int $financeiro Gestão de financeiro
 * @property int $parametros Gestão de parâmetros
 * @property string $per_mes Mês de operação da folha
 * @property string $per_ano Ano de operação da folha
 * @property string $per_parcela Parcela de operação da folha
 * @property string $tp_usuario Tipo de usuário
 * @property string $l_url Última url
 * @property int $id_cad_servidores Id de servidor
 *
 * @property UserAuth[] $userAuths
 * @property UserOptions[] $UserOptions
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;

    /**
     * Este tipo de usuário criou seu perfil direto na pagina de signup
     */
    const STATUS_NEW_USER = 20;

    /**
     * Este tipo de usuário não tem domínio
     */
    const STATUS_NEW_USER_UNREGISTERED = 21;

    /**
     * Este tipo de usuário vem com login de rede social portanto não precisa de dupla confirmação
     */
    const STATUS_NEW_USER_RS = 30;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Retorna as variáveis do usuário
     * @param type $id
     * @return type
     */
    public static function getUsuariosOpc($id)
    {
        return UserOptionsController::findModel($id);
    }

    /**
     * Retorna um array de domínios
     * @param type $array
     * @return string
     */
    public static function getDominiosDoOrgao($array = true)
    {
        $dominios = Orgao::find()
            ->select('dominio')
            ->orderBy('dominio')
            ->all();
        foreach ($dominios as $dominio) {
            $result[] = ['chave' => $dominio['dominio'], 'valor' => \common\controllers\AppController::str_capitalizar($dominio['dominio']), 'reduzido' => strtoupper(substr($dominio['dominio'], 0, 1))];
        }
        return $array ? $result : $dominios;
    }

    /**
     * Retorna um label para a alçada informada
     * @param type $value
     * @return string
     */
    public static function getUsuariosAlcadas($value = null)
    {
        $result = '';
        switch ($value) {
            case 0:
                $result = '0-Acesso negado';
                break;
            case 1:
                $result = '1-Pode exibir';
                break;
            case 2:
                $result = '2-Pode criar';
                break;
            case 3:
                $result = '3-Pode editar';
                break;
            case 4:
                $result = '4-Pode excluir/cancelar';
                break;
        }
        return $result;
    }

    /**
     * Retorna um label para a alçada informada
     * @param type $value
     * @return string
     */
    public static function getUsuariosStatus($value = null)
    {
        $result = '';
        switch ($value) {
            case self::STATUS_INATIVO:
                $result = 'Inativo';
                break;
            case self::STATUS_ATIVO:
                $result = 'Ativo';
                break;
            case self::STATUS_NEW_USER:
                $result = 'Novo usuário';
                break;
            case self::STATUS_NEW_USER_UNREGISTERED:
                $result = 'Sem domínio';
                break;
            case self::STATUS_NEW_USER_RS:
                $result = 'Novo Usuário Rede Social';
                break;
            case self::STATUS_CANCELADO:
                $result = 'Cancelado';
                break;
        }
        return $result;
    }

    /**
     * Retorna Sim ou não a opção informada
     * @param type $value
     * @return string
     */
    public static function getUsuariosSN($value = null)
    {
        $result = '';
        switch ($value) {
            case 0:
                $result = 'Não';
                break;
            case 1:
                $result = 'Sim';
                break;
        }
        return $result;
    }

    /**
     * Retorna Sim ou não a opção informada
     * @param type $value
     * @return string
     */
    public static function getTpUsuario($value = null)
    {
        $result = '';
        switch ($value) {
            case 0:
                $result = 'Servidor';
                break;
            case 1:
                $result = 'Usuário';
                break;
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                self::STATUS_INATIVO,
                self::STATUS_ATIVO,
                self::STATUS_NEW_USER,
                self::STATUS_NEW_USER_UNREGISTERED,
                self::STATUS_NEW_USER_RS,
                self::STATUS_CANCELADO,
            ]],
            // verifica se "username" começa com uma letra e contém somente caracteres
            //            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            // verifica se "username" é uma string cujo tamanho está entre 4 e 24
            ['username', 'string', 'length' => [4, 24]],
            // trima os espaços em branco ao redor de "username" e "email"
            [['username', 'email',], 'trim'],
            [['username', 'hash', 'auth_key', 'password_hash', 'email', 'slug', 'base_servico',  'cliente', 'dominio', 'evento', 'created_at', 'updated_at', 'tp_usuario'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'administrador', 'gestor', 'usuarios', 'cadastros', 'folha', 'financeiro', 'parametros', 'tp_usuario', 'id_cad_servidores'], 'integer'],
            [['username', 'hash', 'password_hash', 'password_reset_token', 'github', 'url_foto', 'email', 'tel_contato', 'slug', 'base_servico',  'cliente', 'dominio'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['per_ano'], 'string', 'max' => 4],
            [['per_mes'], 'string', 'max' => 2],
            [['per_parcela'], 'string', 'max' => 3],
            [['slug'], 'unique'],
            [['username', 'email', 'dominio',], 'unique', 'targetAttribute' => ['username', 'email', 'dominio']],
            [['hash', 'username', 'dominio',], 'unique', 'targetAttribute' => ['hash', 'username', 'dominio']],
            [['password_reset_token'], 'unique'],
            [['github'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'username' => Yii::t('yii', 'Username'),
            'hash' => Yii::t('yii', 'Identificador de usuário'),
            'auth_key' => Yii::t('yii', 'Auth Key'),
            'password_hash' => Yii::t('yii', 'Password Hash'),
            'password_reset_token' => Yii::t('yii', 'Password Reset Token'),
            'github' => Yii::t('yii', 'Github'),
            'url_foto' => Yii::t('yii', 'Foto'),
            'email' => Yii::t('yii', 'Email'),
            'tel_contato' => Yii::t('yii', 'Telefone'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'base_servico' => Yii::t('yii', 'Serviço ativo'),
            'cliente' => Yii::t('yii', 'Cliente'),
            'dominio' => Yii::t('yii', 'Dominio'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'administrador' => Yii::t('yii', 'Administrador'),
            'gestor' => Yii::t('yii', 'Gestor'),
            'usuarios' => Yii::t('yii', 'Gestão de usuários'),
            'cadastros' => Yii::t('yii', 'Gestão de cadastros'),
            'folha' => Yii::t('yii', 'Gestão de folha'),
            'financeiro' => Yii::t('yii', 'Gestão de financeiro'),
            'parametros' => Yii::t('yii', 'Gestão de parâmetros'),
            'per_mes' => Yii::t('yii', 'Mês de operação da folha'),
            'per_ano' => Yii::t('yii', 'Ano de operação da folha'),
            'per_parcela' => Yii::t('yii', 'Parcela de operação da folha'),
            'l_url' => Yii::t('yii', 'Última Url acessada'),
            'tp_usuario' => Yii::t('yii', 'Usuário ou servidor?'),
            'id_cad_servidores' => Yii::t('yii', 'Id de servidor'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuths()
    {
        return $this->hasMany(UserAuth::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOptions()
    {
        return $this->hasMany(UserOptions::className(), ['id_user' => 'id']);
    }

    /**
     * Cria um novo usuário
     * 
     * @param type $username
     * @param type $email
     * @param type $password
     * @param type $matricula
     * @param type $client
     * @return type
     */
    public static function newUser($cpf, $matricula, $email, $password, $client = 'newUser', $username = null)
    {
        if (is_nan($cpf) || is_null($cpf) || !is_numeric($cpf)) {
            return "CPF \"$cpf\" é inválido!";
        }
        if (is_nan($matricula) || is_null($matricula) || !is_numeric($matricula)) {
            return "Matricula \"$matricula\" é inválido!";
        }
        $matricula = (int) substr($matricula, 0, 4);
        $id = "1$cpf$matricula";
        $token = base_convert($id, 12, 36);
        $servidor = \frontend\controllers\SiteController::actionServidorByToken($token);
        $cpfD = substr(base_convert($token, 36, 12), 1, 11);
        $matriculaD = substr(base_convert($token, 36, 12), 12);
        if ($servidor) {
            $servs = count($servidor);
            for ($i = 0; $i < $servs; $i++) {
                if (isset($servidor[$i]['servidor']) && isset($servidor[$i]['servidor']) != false && !empty($servidor[$i]['servidor']) & !is_null($servidor[$i]['servidor'])) {
                    $nomes = AppController::removerPreposicoesArtigos($servidor[$i]['servidor']['nome'], " ", true);
                    //            if (is_array($nomes)) {
                    for ($y = 0; $y < 2; $y++) {
                        $username .= AppController::str_capitalizar($nomes[$y]) . " ";
                    }
                    $username = trim($username);
                    // return Json::encode($servidor[$i]);
                    $cliente = $servidor[$i]['cliente'];
                    $dominio = $servidor[$i]['servidor']['dominio'];
                    $id_cad_servidores = $servidor[$i]['servidor']['id'];
                }
            }
            //            }
        } else {
            return "Servidor não localizado com os dados informados! por favor tente novamente. $cpfD $matriculaD: " . Json::encode($servidor);
        }

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);

        $user->status = $user::STATUS_ATIVO;
        $user->created_at = time();
        $user->updated_at = time();
        //        $user->generateDominio();
        $user->dominio = $dominio;
        $user->slug = sha1($user::tableName() . time());
        $user->generateAuthKey();
        $user->generateHash();

        $user->administrador = 0;
        $user->gestor = 0;
        $user->usuarios = 0;
        $user->cadastros = 0;
        $user->folha = 0;
        $user->financeiro = 0;
        $user->parametros = 0;
        $user->per_mes = date('m');
        $user->per_ano = date('Y');
        $user->per_parcela = '000';
        $user->tp_usuario = '0';
        $user->base_servico = 'pagamentos'; // recuperar esse dado do token
        $user->cliente = $cliente; // recuperar esse dado do token
        $user->id_cad_servidores = $id_cad_servidores; // recuperar esse dado do token

        $user->evento = 0; //SisEventsController::registrarEvento('Usuário inserido com sucesso.', $client, $username, 'user');

        return $user->save() ? $user : \common\controllers\AppController::limpa_json(\yii\helpers\Json::encode($user->getErrors()));
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
            //                        ->join('join', 'orgao', 'orgao.dominio = user.dominio and orgao.status = ' . Empresa::STATUS_ATIVO)
            ->where(['user.id' => $id])
            ->andFilterWhere(['between', 'user.status', self::STATUS_ATIVO, self::STATUS_NEW_USER_RS])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->andFilterWhere([
                'or',
                ['user.username' => $username],
                ['user.email' => $username]
            ])
            ->andFilterWhere(['between', 'user.status', self::STATUS_ATIVO, self::STATUS_NEW_USER_RS])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $r
     * @return static|null
     */
    public static function findByAuth_key($r)
    {
        return static::findOne(['auth_key' => $r]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()
            ->where([
                'password_reset_token' => $token,
            ])
            ->andWhere([
                'or',
                'user.status=' . User::STATUS_ATIVO,
                'user.status=' . User::STATUS_NEW_USER,
                'user.status=' . User::STATUS_NEW_USER_UNREGISTERED,
                'user.status=' . User::STATUS_NEW_USER_RS,
            ])
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new domain user
     */
    public function generateDominio()
    {
        $dominio = Yii::$app->security->generateRandomString(13);
        $this->dominio = $dominio;
        return $dominio;
    }

    /**
     * Generates new domain user
     */
    public function generateHash()
    {
        $this->hash = strrev(substr(Yii::$app->security->generateRandomString(12) . '_' . time(), 7, 12));
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function beforeSave($insert)
    {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->slug = strtolower(sha1($this->tableName() . time()));
            $this->dominio = is_null($this->dominio) ? $this->generateDominio() : $this->dominio;
        } else {
        }

        if (empty($this->per_mes)) {
            $this->per_mes = date('m');
        }
        if (empty($this->per_ano)) {
            $this->per_ano = date('Y');
        }
        if (empty($this->per_parcela)) {
            $this->per_parcela = 0;
        }

        $this->updated_at = time();
        $this->email = strtolower($this->email);

        return $retorno;
    }

    public function afterFind()
    {
        $this->dominio = strtolower($this->dominio);
        date_default_timezone_set(SisParamsController::getTimeZone());
    }
}
