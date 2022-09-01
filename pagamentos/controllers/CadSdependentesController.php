<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\CadSdependentes;
use pagamentos\models\CadSdependentesSearch;
use pagamentos\models\CadServidores;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use kartik\helpers\Html;
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
                    [
                        'actions' => ['c', 'r', 'dpl', 'photo', 'upload'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 4 || $this->gestor >= 1);
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
                return $this->renderAjax('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $id_cad_servidores,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('index', [
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
        $model_first = $this->findModel($id, true, true);
        $model = $this->findModel($id, false, true);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor($model->id_cad_servidores)->slug . '?mv=' . $mv);
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Creates a new CadSdependentes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($id_cad_servidores = null, $modal = false, $mv = null) {
        if (!is_null($id_cad_servidores) &&
                (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null)):
            $model = new CadSdependentes();
            $model->id_cad_servidores = $cadServidor->id;
            $model->matricula = self::getMatricula($model->id_cad_servidores);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
                $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor($model->id_cad_servidores)->slug . '?mv=' . $mv);
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para registrar um dependente, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Updates an existing CadSdependentes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        return $this->actionView($id, $modal);
//        $model_first = $this->findModel($id, true, true);
//        $model = $this->findModel($id, false, true);
//        $post = Yii::$app->request->post();
//        if ($model->load($post) && $model->save()) {
//            $model_last = $this->findModel($id, true, true);
////          registra evento no log do sistema
//            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
//            $model->save();
//            $data = Yii::t('yii', 'Successfully updated record.');
//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return $data;
//            } else {
//                Yii::$app->session->setFlash('success', $data);
//                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor($model->id_cad_servidores)->slug . '?mv=' . $mv);
//            }
//        }
//        if (Yii::$app->request->isAjax) {
//            return $this->renderAjax('_form', [
//                        'model' => $model,
//                        'modal' => $modal,
//            ]);
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//                        'modal' => $modal,
//            ]);
//        }
    }

    /**
     * Deletes an existing CadSdependentes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = CadSdependentes::STATUS_CANCELADO;
        $model->dominio = $model->dominio . '_XDEL';
//          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->save(true, ['status', 'dominio', 'evento'])) {
            $data = ['mess' => Yii::t('yii', 'Successfully canceled record.'), 'class' => 'success', 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Attempt to cancel unsuccessful. Error: {error}', [
                    'error' => Json::encode($model->getErrors())
                ]), 'class' => 'warning'];
        }

//if (!Yii::$app->request->isAjax) {
        Yii::$app->session->setFlash($data['class'], $data['mess']);
        return $this->redirect(['/cad-servidores/view', 'id' => $model->getCadServidor($model->id_cad_servidores)->slug]);
////        } else {
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return $data;
////        }
    }

    /**
     * Duplicates an existing CadSdependentes model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id, $id_cad_servidores = null) {
        $model = $this->findModel($id);
        if (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null):
            $model->id_cad_servidores = $id_cad_servidores;
            $model->matricula = self::getMatricula($id_cad_servidores);
            $id_n = CadSdependentes::find()->max('id') + 1;
            $model->id = $id_n;
            $model->status = CadSdependentes::STATUS_ATIVO;
            $model->slug = strtolower(sha1($model->tableName() . time()));
            $model->isNewRecord = true;
            if ($model->save()) {
                $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                                . "Dados do registro duplicado: "
                                . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id, $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
                return $this->redirect(['view', 'id' => $model->slug, 'modal' => 1, 'mv' => \kartik\detail\DetailView::MODE_VIEW]);
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
                return $this->redirect(['u', 'id' => $id]);
            }
        else:
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = ['mess' => Yii::t('yii', 'Attempt to cancel unsuccessful. Error: {error}', [
                    'error' => Json::encode($model->getErrors())
                ]), 'class' => 'warning'];
//            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor, atualize os dados do registro de dependente existente antes de prosseguir'));
            return $data; //$this->redirect(['view', 'id' => $model->slug]);
        endif;
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
