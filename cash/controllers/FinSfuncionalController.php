<?php

namespace cash\controllers;

use Yii;
use pagamentos\models\FinSfuncional;
use pagamentos\models\FinSfuncionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use pagamentos\models\CadServidores;

/**
 * FinSfuncionalController implements the CRUD actions for FinSfuncional model.
 */
class FinSfuncionalController extends Controller {

    public $gestor = 0;
    public $finsfuncional = 0;

    const CLASS_VERB_NAME = 'Financeiro do servidor';
    const CLASS_VERB_NAME_PL = 'Financeiro dos servidores';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->finsfuncional = Yii::$app->user->identity->financeiro;
            $this->gestor = Yii::$app->user->identity->gestor;
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->finsfuncional >= 1;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single FinSfuncional model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if (!$model == null) {
            if ($model->load($post) && $model->save()) {
                $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
                $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
                $model->save();
                $ret = FolhaController::setAlterFolhaServidor(FolhaController::OPERACOES_DA_GERACAO_INI_INDIVIDUAL, $model->id_cad_servidores);
                Yii::$app->session->setFlash('kv-detail-success', Yii::t('yii', 'Successfully updated record.'));
                Yii::$app->session->setFlash('kv-detail-info', Yii::t('yii', $ret));
                if ($modal) {
                    return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
                } else {
                    $matricula = $model->getCadServidor()->matricula;
                    return $this->redirect(["fin-sfuncional/$matricula?modal=$modal&mv=$mv"]);
                }
            } else {
                if (!$model->load($post)) {
                    $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                    SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
                }
                if ($model->load($post) && !$model->save()) {
                    Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('@pagamentos/views/fin-sfuncional/view', [
                                'model' => $model,
                                'modal' => $modal,
                    ]);
                } else {
                    return $this->render('@pagamentos/views/fin-sfuncional/view', [
                                'model' => $model,
                                'modal' => $modal,
                    ]);
                }
            }
        } else {
            return $this->redirect(['/cad-servidores/']);
        }
    }

    /**
     * Finds the FinSfuncional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinSfuncional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false, $sfm = true) {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()
                ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
                ->where([
                    ((is_numeric($id) || $sfm) ? $cad . '.matricula' : $loc . '.slug') => $id,
                    $loc . '.dominio' => Yii::$app->user->identity->dominio,
                    $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
                ])
                ->andWhere([
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
        ]);
        if ($array) {
            if (($model = $model->asArray()->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro financeiro para o servidor'));
                return null;
            }
        } else {
            if (($model = $model->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro financeiro para o servidor'));
                return null;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Retorna os valores em BD.get_tempo_carteira
     * @param type $id
     * @param type $v_ano
     * @param type $v_mes
     * @param type $v_parcela
     * @return type
     */
    public static function getCarteira($id, $v_ano = null, $v_mes = null, $v_parcela = null) {
        $v_ano = $v_ano ?? Yii::$app->user->identity->per_ano;
        $v_mes = $v_mes ?? Yii::$app->user->identity->per_mes;
        $v_parcela = $v_parcela ?? Yii::$app->user->identity->per_parcela;
        $referencias = FinReferenciasController::getVctoBasico($id, $v_ano, $v_mes, $v_parcela);
        return $referencias;
    }

    /**
     * Retorna um array contendo os tipos de ênios
     */
    public static function getListaEnios() {
        $model = [
            1 => FinSfuncional::ENIO_1,
            3 => FinSfuncional::ENIO_3,
            5 => FinSfuncional::ENIO_5,
            10 => FinSfuncional::ENIO_10,
        ];
        return $model;
    }

    /**
     * Retorna quantos enios o servidor tem
     * @param type $enio
     * @param type $id_cad_servidores
     * @return string
     */
    public static function getEnios($enio, $id_cad_servidores) {
        $carteira = self::getCarteira($id_cad_servidores);
        switch ($enio) {
            case 1: $return = $carteira['v_anos'];
                break;
            case 3: $return = intdiv($carteira['v_anos'], 3);
                break;
            case 5: $return = intdiv($carteira['v_anos'], 5);
                break;
            case 10: $return = intdiv($carteira['v_anos'], 10);
                break;
            default : $return = 'Sem ênios';
        }
        return $return;
    }

    /**
     * Retorna o label do ênio
     */
    public static function getEnioLabel($tipo) {
        switch ($tipo) {
            case 1: $return = FinSfuncional::ENIO_1;
                break;
            case 3: $return = FinSfuncional::ENIO_3;
                break;
            case 5: $return = FinSfuncional::ENIO_5;
                break;
            case 10: $return = FinSfuncional::ENIO_10;
                break;
            default : $return = 'Sem ênios';
        }
        return $return;
    }

}
