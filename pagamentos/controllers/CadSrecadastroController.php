<?php

namespace pagamentos\controllers;

use Exception;
use JasperServerIntegration;
use Yii;
use pagamentos\models\CadSrecadastro;
use pagamentos\models\CadSrecadastroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use pagamentos\models\CadServidores;

/**
 * CadSrecadastroController implements the CRUD actions for CadSrecadastro model.
 */
class CadSrecadastroController extends Controller {

    public $gestor = 0;
    public $cadastros = 0;

    const CLASS_VERB_NAME = 'Recadastro do servidor';
    const CLASS_VERB_NAME_PL = 'Recadastros do servidor';

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
                        'actions' => ['print'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->cadastros >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u', 'p'],
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
     * Lists all CadSrecadastro models.
     * @return mixed
     */
    public function actionIndex($id_cad_servidores = null, $modal = false) {
        if (!is_null($id_cad_servidores)):
            $searchModel = new CadSrecadastroSearch();
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
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para acessar o recadastro, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Displays a single CadSrecadastro model.
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
     * Creates a new CadSrecadastro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($id_cad_servidores = null, $modal = false, $mv = null) {
        if (!is_null($id_cad_servidores) &&
                (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null)):
            $model = new CadSrecadastro();
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
     * Creates a new CadSrecadastro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionP($id = null, $modal = false, $mv = null) {
        if (!is_null($id) &&
                (($cadServidor = CadServidores::findOne($id)) != null)):
            $model = new CadSrecadastro();
            $id_n = CadSrecadastro::find()->max('id') + 1;
            $model->id = $id_n;
            $model->slug = strtolower(sha1($model->tableName() . time()));
            $model->status = CadSrecadastro::STATUS_ATIVO;
            $model->dominio = Yii::$app->user->identity->dominio;
            $model->evento = SisEventsController::registrarEvento("Registro de servidor confirmado com sucesso! "
                            . "Dados do registro confirmado: "
                            . SisEventsController::evt($model), 'actionP', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->created_at = $model->updated_at = time();
            $model->id_cad_servidores = $cadServidor->id;
            $model->id_user_recadastro = Yii::$app->user->identity->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'O registro do servidor foi confirmado e a ficha cadastral será gerada para impressão.'));
                return $this->printFichaRecadastro($model->id_cad_servidores);
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Não foi possível criar o registro de recadastro do servidor. Erro(s): ') . AppController::limpa_json(Json::encode($model->getErrors())));
            }
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para registrar um recadastro do servidor, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Imprime um recurso
     * @param type $id
     * @param type $id_f
     * @param type $format
     * @param type $fileName
     * @return type
     */
    public function actionPrint($id = null, $id_f = null, $format = 'pdf', $fileName = 'fichaRecadastro')
    {
        if ($id_f == null) {
            $id_f = $id;
        }
        switch ($fileName) {
            case 'fichaRecadastro':
                return $this->printFichaRecadastro($id, $format, $fileName);
                break;
        }
    }

    /**
     * Retorna a ficha financeira solicitada para impressão
     */
    public function printFichaRecadastro($id = null, $format = 'pdf', $fileName = 'fichaRecadastro')
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        $db = Yii::$app->$db->dbname;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $dominio = Yii::$app->user->identity->dominio;
        $controls = array(
            'ano' => $ano,
            'mes' => $mes,
            'parcela' => $parcela,
            'dominio' => $dominio,
            'id_cad_servidor' => $id,
            'id_usuario' => Yii::$app->user->identity->id,
            'tableSchema' => $db,
            'username' => str_replace(' ', '%20', Yii::$app->user->identity->username),
        );
        require_once(Yii::getAlias("@common/components/jasperserverintegration.php"));
        // Cria o objeto do JasperServerIntegration
        $jsi = new JasperServerIntegration(
            'http://ns1.mgcash.app.br:8080/jasperserver',     // URL do JasperServer
            "reports/MGFolha/cad_servidores/$fileName",     // Caminho do relatório no JasperServer sem a primeira barra
            $format,                                        // Tipo da exportação do relatório
            'caixa',                                  // Usuário com acesso ao relatório
            'C@1xa',                                   // Senha do usuário
            $controls                                       // Array com os parâmetros (opcional)
        );

        // Executa o relatório
        try {
            $data = $jsi->execute();
            SisEventsController::registrarEvento("Ficha de recadastro impressa com sucesso!", 'printFichaRecadastro', Yii::$app->user->identity->id, 'cad_servidores', $id);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (Exception $e) {
            $eMsg = $e->getMessage();
            $eCode = $e->getCode();
            print("Erro - $eCode: $eMsg.");
        }
    }

    /**
     * Updates an existing CadSrecadastro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
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
     * Deletes an existing CadSrecadastro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = CadSrecadastro::STATUS_CANCELADO;
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
        return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
//
//        if (!Yii::$app->request->isAjax) {
//            Yii::$app->session->setFlash($data['class'], $data['mess']);
//            return $this->redirect(['index']);
//        } else {
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return $data;
//        }
    }

    /**
     * Duplicates an existing CadSrecadastro model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = CadSrecadastro::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = CadSrecadastro::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
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
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne($id_cad_servidores);
    }

    /**
     * Finds the CadSrecadastro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CadSrecadastro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = CadSrecadastro::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = CadSrecadastro::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }
//        if (($model = CadSrecadastro::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
