<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Modelo de classe para geração|edição da "folha" de pagamento
 */
class Holerite extends Model {
 
    public $id_cad_servidor; 
    public $cliente;
    public $dominio;
    public $ano;
    public $mes;
    public $parcela;
    public $token;
    public $reCaptcha;

    public static function tableName() {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
//            [['token',], 'required'],
            [['cliente', 'token', 'dominio'], 'string'],
            [['id_cad_servidor',], 'integer'],
            [['ano',], 'string', 'max' => 4],
            [['mes',], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(), 'uncheckedMessage' => 'Por favor, confirme que você não é um robô.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'token' => Yii::t('yii', 'Token de validação'),
        ];
    }

    /**
     * @inheritdoc
     */
//    public function findByToken($token) {
//        try {
//            $model = Yii::$app->db->createCommand("CALL mgfolha_folha.getTokenValidacao('$token')")
//                    ->queryOne();
////            foreach($model as $model) {
////                $this->ano = ($model['ano']);
////                $this->mes = ($model['mes']);
////                $this->parcela = ($model['parcela']);
////                $this->id_cad_servidor = ($model['id_cad_servidor']);
////                $this->cliente = ($model['cliente']);
////            }
//        } catch (\Exception $e) {
//            $data = ['mess' => Yii::t('yii', 'Tentativa sem sucesso. Erro(s): {error}', [
//                    'error' => 'Exception: ' . $e->getMessage()]), 'class' => 'warning'];
//            throw $e;
//        } catch (\Throwable $e) {
//            $data = ['mess' => Yii::t('yii', 'Tentativa sem sucesso. Erro(s): {error}', [
//                    'error' => 'Throwable: ' . $e->getMessage()]), 'class' => 'warning'];
//            throw $e;
//        }
//        return \yii\helpers\Json::encode($model);
//    }
//    
//    public function __toString() {
//        return $this->token . $this->ano . $this->mes . $this->parcela . $this->id_cad_servidor . $this->cliente;
//    }

}
