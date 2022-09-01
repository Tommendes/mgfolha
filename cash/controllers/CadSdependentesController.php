<?php

namespace cash\controllers;

use Yii;
use pagamentos\models\CadSdependentes;
use pagamentos\models\CadSdependentesSearch;
use pagamentos\models\CadServidores;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;

/**
 * CadSdependentesController implements the CRUD actions for CadSdependentes model.
 */
class CadSdependentesController extends Controller {

    public $gestor = 0;
    public $cadastros = 0;

    const CLASS_VERB_NAME = 'Dependente';
    const CLASS_VERB_NAME_PL = 'Dependentes';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->cadastros = Yii::$app->user->identity->cadastros;
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->cadastros >= 1;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CadSdependentes models.
     * @return mixed
     */
    public function actionIndex($id_cad_servidores = null, $modal = false) {
        if (!is_null($id_cad_servidores)):
            $searchModel = new CadSdependentesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id_cad_servidores);

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@pagamentos/views/cad-sdependentes/index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $id_cad_servidores,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@pagamentos/views/cad-sdependentes/index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $id_cad_servidores,
                            'modal' => $modal,
                ]);
            }
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para acessar o registro de um dependente, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Displays a single CadSdependentes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true, false);
        $model = $this->findModel($id, false, false);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true, false);
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
                return $this->renderAjax('@pagamentos/views/cad-sdependentes/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@pagamentos/views/cad-sdependentes/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Verifica a existência do cpf informado
     * @param type $q
     * @param type $id_cad_servidores
     * @param type $i
     * @return type
     */
    public function actionR($q, $id_cad_servidores = null, $i = null) {
        $dependentes = CadSdependentes::find()
//                ->join('join', 'cad_servidores', 'cad_servidores.id = cad_sdependentes.id_cad_servidores')
                ->where(['=', 'cad_sdependentes.cpf', str_replace('.', '', str_replace('-', '', $q))])
                ->andWhere([
                    'cad_sdependentes.dominio' => Yii::$app->user->identity->dominio,
//                    'id_cad_servidores' => $id_cad_servidores,
                ])
                ->andWhere(['!=', 'cad_sdependentes.id', $i])
                ->all();
        /*
         * Caso não hajam cadastros com este cpf_cnpj ou em caso de edição
         * se o id do cadastro existente for igual ao id do cadastro em edição
         * então retorna null, permitindo a utilização do documento no cadastro
         */
        if ($dependentes == null) {
            return Json::encode(['id' => 0, 'nome' => null]);
        } else {
            foreach ($dependentes as $dependente) {
                $result[] = [
                    'i' => $dependente->id,
                    'r' => $dependente->nome . ' (Servidor: ' . $dependente->getCadServidor()->nome . ' e matricula: ' . $dependente->getCadServidor()->matricula . ')',
                ];
            }
            return Json::encode(['id' => count($dependentes), 'result' => $result], JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Retorna o próximo número de matricula
     * @return type
     */
    public static function getMatricula($id_cad_servidores) {
        return CadSdependentes::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'id_cad_servidores' => $id_cad_servidores,
                        ])
                        ->max('matricula') + 1;
    }

    /**
     * Finds the CadSdependentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CadSdependentes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        $loc = CadSdependentes::tableName();
        $model = CadSdependentes::find()
                ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
                ->where([
            is_numeric($id) ? $loc . '.id' : $loc . '.slug' => $id,
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
        ]);
        if ($array) {
            if (($model = $model->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = $model->one()) !== null) {
                return $model;
            }
        }
//        if (($model = CadSdependentes::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

}
