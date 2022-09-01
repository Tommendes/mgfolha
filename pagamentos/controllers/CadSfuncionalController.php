<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\CadSfuncional;
use pagamentos\models\CadSfuncionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->cadsfuncional >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 's-c', 'dpl', 'z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadsfuncional >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u', 'a-c'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadsfuncional >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadsfuncional >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CadSfuncional models.
     * @return mixed
     */
    public function actionIndex($ics = null, $modal = false) {
        if (!is_null($ics) &&
                (($cadServidor = CadServidores::findOne($ics)) != null)):
            $searchModel = new CadSfuncionalSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $cadServidor->id);

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $ics,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $ics,
                            'modal' => $modal,
                ]);
            }
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para acessar um registro de funcional, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Displays a single CadSfuncional model.
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
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir. Erros: ' . AppController::limpa_json($model->getErrors())));
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
     * Creates a new CadSfuncional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($ics = null, $modal = false, $mv = null) {
        if (!is_null($ics) &&
                (($cadServidor = CadServidores::findOne($ics)) != null)):
            $model = new CadSfuncional();
            $model->id_cad_servidores = $cadServidor->id;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
                $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
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
     * Updates an existing CadSfuncional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        $model_first = $this->findModel($id, true, true);
        $model = $this->findModel($id, false, true);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            $data = Yii::t('yii', 'Successfully updated record.');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        }
    }

    /**
     * Deletes an existing CadSfuncional model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = CadSfuncional::STATUS_CANCELADO;
        $model->dominio = $model->dominio . '_XDEL';
//          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->save(false)) {
            $data = ['mess' => Yii::t('yii', 'Successfully canceled record.'), 'class' => 'success', 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Attempt to cancel unsuccessful. Error: {error}', [
                    'error' => Json::encode($model->getErrors())
                ]), 'class' => 'warning'];
        }

        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash($data['class'], $data['mess']);
            return $this->redirect(['index']);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
    }

    /**
     * Duplicates an existing CadSfuncional model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = CadSfuncional::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = CadSfuncional::STATUS_ATIVO;
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->username, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Cria um novo model CadSfuncional inserindo os dados tendo como referência o mês de informação
     * @param type $id Informar o id_cad_servidor original que serviu para a duplicação do registro
     * @param type $id_novo Informar o id_cad_servidor novo
     * @return string
     */
    public function actionSC($id, $id_novo, $decimo = false) {
        // Recebe o parâmetro
        $finParametro = FinParametrosController::getParametroAtual();
        // Recupera o último registro CadSFuncional do servidor utilizando o 
        // periodo de informação da folha atual
        $cad_sfuncional = CadSfuncional::find()
                        ->join('join', 'cad_servidores', 'cad_servidores.id = cad_sfuncional.id_cad_servidores')
                        ->where([
                            is_numeric($id) ? 'cad_servidores.id' : 'cad_servidores.slug' => $id,
                            'cad_sfuncional.dominio' => Yii::$app->user->identity->dominio,
                            'cad_sfuncional.ano' => $finParametro->ano_informacao,
                            'cad_sfuncional.mes' => $finParametro->mes_informacao,
                            'cad_sfuncional.parcela' => $finParametro->parcela_informacao,
                        ])->one();
        // Caso não haja um registro baseado no periodo de informação da folha atual
        if ($cad_sfuncional == null) {
            // Recupera o último registro CadSFuncional do servidor em qualquer período
            $cad_sfuncional = CadSfuncional::find()
                    ->join('join', 'cad_servidores', 'cad_servidores.id = cad_sfuncional.id_cad_servidores')
                    ->where([
                        is_numeric($id) ? 'cad_servidores.id' : 'cad_servidores.slug' => $id,
                        'cad_sfuncional.dominio' => Yii::$app->user->identity->dominio,
                    ])
                    ->orderBy('ano, mes, parcela')
                    ->limit(1)
                    ->one();
        }
        if ($cad_sfuncional == null) {
            // Cria registro novo em CadSfuncional
            $cad_sfuncional = new CadSfuncional();
        } else {
            // Cria registro novo em CadSfuncional baseado no registro localizado
            $cad_sfuncional->isNewRecord = true;
        }
        $id_n = CadSfuncional::find()->max('id') + 1;
        $cad_sfuncional->id = $id_n;
        $cad_sfuncional->status = CadSfuncional::STATUS_ATIVO;
        $cad_sfuncional->id_cad_servidores = $id_novo;
        $cad_sfuncional->slug = strtolower(sha1(Yii::$app->security->generateRandomString()));
        $cad_sfuncional->created_at = $cad_sfuncional->updated_at = time();
        $cad_sfuncional->ano = Yii::$app->user->identity->per_ano;
        $cad_sfuncional->mes = !$decimo ? Yii::$app->user->identity->per_mes : '13';
        $cad_sfuncional->parcela = Yii::$app->user->identity->per_parcela;
        if ($cad_sfuncional->save()) {
            $cad_sfuncional->evento = SisEventsController::registrarEvento("Registro funcional criado com sucesso! "
                            . "Dados do registro criado: "
                            . SisEventsController::evt($cad_sfuncional), 'getSC', Yii::$app->user->identity->id, $cad_sfuncional->tableName(), $cad_sfuncional->id);
            $cad_sfuncional->save();
            $data = ['mess' => Yii::t('yii', 'Registro funcional criado com sucesso!'), 'class' => Yii::$app->security->generateRandomString(5), 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Tentativa de criação do registro funcional mau sucedida. Erro: {error}', [
                    'error' => Json::encode($cad_sfuncional->getErrors())
                ]), 'class' => 'warning'];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($ics = null) {
        return CadServidores::findOne($ics);
    }

    /**
     * Altera campo
     * Altera os dados de um devido campo da tabela
     * @param type $id
     * @param type $campo
     * @param type $valor
     * @return string
     */
    public function actionAC($id, $campo, $valor) {
        $model_first = $this->findModel($id, true, false);
        if (!is_null($campo) && !is_null($valor)) {
//            $post = Yii::$app->request->post();
            $model = $this->findModel($id, false, false);
            $model->$campo = $valor;
//            if ($model->load($post) && $model->save()) {
            if ($model->save(false)) {
//                registra evento no log do sistema
                $model_last = $this->findModel($id, true, false);
                $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
                $model->save(false);
                $data = ['mess' => Yii::t('yii', 'Successfully updated record.'), 'class' => 'success', 'reloadPage' => true];
            } else {
                $data = ['mess' => Yii::t('yii', 'Attempt to update unsuccessful. Error: {error}', [
                        'error' => Json::encode($model->getErrors())
                    ]) . '.<br><strong>Antes de prosseguir, por favor reveja o(s) erro(s)</strong>', 'class' => 'warning'];
            }

            if (!Yii::$app->request->isAjax) {
                Yii::$app->session->setFlash($data['class'], $data['mess']);
                return $this->actionView($id);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            }
        } else {
            return $this->goBack();
        }
    }

    /**
     * Retorna os dados de um devido campo da tabela
     * @param type $id
     * @param type $c Coluna da tabela
     * @return string
     */
    public function actionZ($id, $c) {
//        $model = $this->findModel($id, true, true);
//        if (!is_null($model)) {
//            $data = $model[$c];
//        } else {
//            $data = null;
//        }
//        Yii::$app->response->format = Response::FORMAT_JSON;
        echo 'Tom Mendes';
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
