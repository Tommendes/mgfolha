<?php

namespace cash\controllers;

use Yii;
use pagamentos\models\CadSfuncional;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use pagamentos\models\CadServidores;

/**
 * CadSfuncionalController implements the CRUD actions for CadSfuncional model.
 */
class CadSfuncionalController extends Controller {

    public $gestor = 0;
    public $cadsfuncional = 0;

    const CLASS_VERB_NAME = 'Funcional do servidor';
    const CLASS_VERB_NAME_PL = 'Funcionais do servidor';
    const CLASS_VERB_NAME_PLL = 'Funcionais dos servidores';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->cadsfuncional = Yii::$app->user->identity->cadastros;
            $this->gestor = Yii::$app->user->identity->gestor;
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
//                    'a-c' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadsfuncional >= 2 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single CadSfuncional model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@pagamentos/views/cad-sfuncional/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@pagamentos/views/cad-sfuncional/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($ics = null) {
        return CadServidores::findOne($ics);
    }

    /**
     * 
     * Finds the CadSfuncional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param type $id
     * @param type $array
     * @param type $sfm acronym to search for matricula
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $array = false, $sfm = true) {
        $loc = CadSfuncional::tableName();
        $mes = Yii::$app->user->identity->per_mes > 12 ? 12 : Yii::$app->user->identity->per_mes;
        $ano = Yii::$app->user->identity->per_ano;
        $model = CadSfuncional::find()
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
        if (($model->one()) == null) {
            $model = CadSfuncional::find()
                    ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
                    ->where([
                        ((is_numeric($id) || $sfm) ? $cad . '.matricula' : $loc . '.slug') => $id,
                        $loc . '.dominio' => Yii::$app->user->identity->dominio,
                        $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
                    ])
                    ->andWhere("LAST_DAY(CONCAT(cad_sfuncional.ano, '/', cad_sfuncional.mes, '/01')) <= LAST_DAY('$ano/$mes/01')")
                    ->orderBy('ano desc, mes desc')
                    ->limit(1);
        }
        if ($array) {
            if (($model = $model->asArray()->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro funcional para o servidor'));
                return null;
            }
        } else {
            if (($model = $model->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro funcional para o servidor'));
                return null;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
