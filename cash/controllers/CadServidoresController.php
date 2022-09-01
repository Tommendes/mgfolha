<?php

namespace cash\controllers;

use Yii;
use pagamentos\models\CadServidores;
use pagamentos\models\CadServidoresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use common\models\UploadForm;
use yii\web\UploadedFile;
use kartik\detail\DetailView;
use kartik\helpers\Html;
use pagamentos\controllers\AppController;

/**
 * CadServidoresController implements the CRUD actions for CadServidores model.
 */
class CadServidoresController extends Controller {

    public $gestor = 0;
    public $cadastros = 0;

    const CLASS_VERB_NAME = 'Servidor';
    const CLASS_VERB_NAME_PL = 'Servidores';

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
                        'actions' => ['index', 'view', 'print'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->cadastros >= 1;
                        }
                    ],
//                    [
//                        'actions' => ['c', 'a-c', 'r', 'dpl', 'photo', 'upload'],
//                        'allow' => true,
//                        'matchCallback' => function () {
//                            return !Yii::$app->user->isGuest && ($this->cadastros >= 2 || $this->gestor >= 1);
//                        }
//                    ],
//                    [
//                        'actions' => ['u'],
//                        'allow' => true,
//                        'matchCallback' => function () {
//                            return !Yii::$app->user->isGuest && ($this->cadastros >= 3 || $this->gestor >= 1);
//                        }
//                    ],
//                    [
//                        'actions' => ['dlt'],
//                        'allow' => true,
//                        'matchCallback' => function () {
//                            return !Yii::$app->user->isGuest && ($this->cadastros >= 4 || $this->gestor >= 1);
//                        }
//                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CadServidores models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CadServidoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($dataProvider->getCount() == 1) {
            $newQuery = clone $dataProvider->query;
            $model = $newQuery->limit(1)->one();

            return $this->redirect(["/cad-servidores/$model->slug"]);
        } else {
            return $this->render('@pagamentos/views/cad-servidores/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionPrint($r, $dn = null) {
        $params = [
            'titulo' => 'Teste da chamada',
        ];
        $format = ['pdf', 'rtf'];
        return $this->getPrint($r, $params, $dn, $format);
    }

    /**
     * Print a resource
     * @param type $r
     * @param type $params
     * @param type $destin_name
     * @param type $format
     * @return type
     */
    public function getPrint($r, $params, $destin_name = null, $format = array()) {
        $dominio = strtolower(Yii::$app->user->identity->dominio);
        $destin_folder = strtolower(str_replace('Controller', '', \yii\helpers\StringHelper::basename($this->className())));
        $output = Yii::getAlias('@uploads_root') . DIRECTORY_SEPARATOR . $dominio . DIRECTORY_SEPARATOR . 'reports';
        if (!isset($destin_name) || empty($destin_name) || strlen(trim($destin_name)) < 0) {
            $destin_name = Yii::$app->security->generateRandomString(8);
            return AppController::setPrint($r, $destin_name, $destin_folder, $params, $format);
        } else {
            return Yii::$app->response->sendFile($output . DIRECTORY_SEPARATOR . $destin_folder . DIRECTORY_SEPARATOR . $destin_name . '.pdf'
                            , null
                            , ['inline' => true, 'mimeType' => 'application/pdf']);
        }
    }

    /**
     * Displays a single CadServidores model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null, $tp = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $view = '@pagamentos/views/cad-servidores/view';
        switch ($tp) {
            case 1: $view = '@pagamentos/views/cad-servidores/v_banco';
                break;
            case 2: $view = '@pagamentos/views/cad-servidores/v_endereco';
                break;
            case 3: $view = '@pagamentos/views/cad-servidores/v_documentos';
                break;
        }
        if ($tp > 0) {
            $mv = DetailView::MODE_EDIT;
        }
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . str_replace('_', '-', $model->tableName()) . '/' . $model->slug);
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir.<br>Erros: {erros}', ['erros' => AppController::limpa_json(Json::encode($model->getFirstErrors()))]));
                return $this->render('@pagamentos/views/cad-servidores/view', [
                            'model' => $model,
                            'modal' => 0,
                ]);
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax($view, [
                            'model' => $model,
                            'modal' => $modal,
                            'mv' => $mv,
                ]);
            } else {
                return $this->render($view, [
                            'model' => $model,
                            'modal' => $modal,
                            'mv' => $mv,
                ]);
            }
        }
    }

    /**
     * Creates a new CadServidores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false) {
        $model = new CadServidores();
        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->actionAC($model->id)) {
//          gera evento para ser gravado no log do sistema 
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();

            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@pagamentos/views/cad-servidores/_form', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        } else {
            return $this->render('@pagamentos/views/cad-servidores/create', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        }
    }

    /**
     * Cria um registro do servidor nas tabelas CadSFuncional e FinSFuncional
     * tendo como base o período atual do usuário
     * @param type $id Enviar o id do registro original
     * @param type $id_novo Enviar o id do registro duplicado
     * @return boolean
     */
    public function actionAC($id, $id_novo = null, $decimo = false) {
        $return = true;
        $model = $this->findModel($id);
        if ($id_novo == null) {
            $id_novo = $model->id;
        }
        // Verifica a existência e se não existir cria registro CadSfuncional 
        // duplicado do último existente
        if (($funcional = \pagamentos\models\CadSfuncional::find()->where([
                    'id_cad_servidores' => $id_novo,
                    'dominio' => $model->dominio,
                    'ano' => Yii::$app->user->identity->per_ano,
                    'mes' => $decimo ? '13' : Yii::$app->user->identity->per_mes,
                    'parcela' => Yii::$app->user->identity->per_parcela,
                ])->one()) == null) {
            $cadsf = CadSfuncionalController::actionSC($model->id, $id_novo, $decimo);
            if (!$cadsf['class'] == 'success') {
                $return = false;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', $cadsf['mess']));
            }
        }
        // Verifica a existência e se não existir cria registro FinSfuncional 
        // duplicado do último existente
        if (($financeiro = \pagamentos\models\FinSfuncional::find()->where([
                    'id_cad_servidores' => $id_novo,
                    'dominio' => $model->dominio,
                    'ano' => Yii::$app->user->identity->per_ano,
                    'mes' => $decimo ? '13' : Yii::$app->user->identity->per_mes,
                    'parcela' => Yii::$app->user->identity->per_parcela,
                ])->one()) == null) {
            $finsf = FinSfuncionalController::actionSC($model->id, $id_novo, $decimo);
            if (!$finsf['class'] == 'success') {
                $return = false;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', $finsf['mess']));
            }
        }
        return $return;
    }

    /**
     * Updates an existing CadServidores model.
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
                return $this->redirect(['view', 'id' => $model->slug]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@pagamentos/views/cad-servidores/_form', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        } else {
            return $this->render('@pagamentos/views/cad-servidores/update', [
                        'model' => $model,
                        'modal' => $modal,
            ]);
        }
    }

    /**
     * Deletes an existing CadServidores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = CadServidores::STATUS_CANCELADO;
        $model->dominio = $model->dominio . '_XDEL';
//          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->save()) {
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
     * Duplicates an existing CadServidores model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $idOriginal = $model->id;
        $id_n = CadServidores::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = CadServidores::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->matricula = self::getMatricula();
        $model->isNewRecord = true;
//        return $idOriginal . ' - ' . $model->id;
        if ($model->save() &&
                $this->actionAC($idOriginal, $model->id, false) && $this->actionAC($idOriginal, $model->id, true)
        ) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
        }
        return $this->redirect(['view', 'id' => $model->slug]);
    }

    public function actionPhoto($id) {
        $model = new \common\models\UploadForm();
        $cad_first = $this->findModel($id, true);
        $model_cad = $this->findModel($id);

        function base64_to_jpeg($base64_string, $output_file) {
            $return = true;
            $ifp = fopen($output_file, "wb");
            try {
                fwrite($ifp, base64_decode($base64_string));
                fclose($ifp);
            } catch (Exception $ex) {
                $return = false;
            }
            return $return;
        }

        if ($post = Yii::$app->request->post()) {
            $imagem = str_replace('data:image/png;base64,', '', $post['UploadForm']['base64']);
            if (base64_to_jpeg($imagem, Yii::getAlias('@uploads_root') . '/imagens/'
                            . Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/servidores/' . $id . '.png')) {

                $model_cad->url_foto = $model_cad->slug . '.png';
                if ($model_cad->save()) {
                    $cad_last = $this->findModel($id, true);
                    $model_cad->evento = SisEventsController::registrarUpdate($cad_first, $cad_last, Yii::$app->user->identity->id, $model_cad->tableName(), 'actionPhoto');
//                registra evento no log do sistema
                    $model_cad->save();
                    $data = Yii::t('yii', 'Successfully uploaded file');
                } else {
                    $data = Yii::t('yii', 'Erro: ') . Json::encode($model_cad);
                }
            } else {
                $data = Yii::t('yii', 'Erro no carregameento. Erro: {erro}', ['erro' => Json::encode($model->getErrors())]);
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $model_cad->slug]);
            }
        }

//        $post = Yii::$app->request->post();
//        if ($model->load($post)) {
//            $imagem = str_replace('data:image/png;base64,', '', $post['UploadForm']['base64']);
//            base64_to_jpeg($imagem, Yii::getAlias('@uploads_root') . '/' . Yii::$app->user->identity->dominio . '/imagens/' . $id . '.png');
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return 'Imagem salva com sucesso';
//        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@pagamentos/views/cad-servidores/photo', [
                        'model' => $model,
                        'modal' => true,
            ]);
        } else {
            return $this->render('@pagamentos/views/cad-servidores/photo', [
                        'model' => $model,
                        'modal' => true,
            ]);
        }
    }

    public function actionUpload($id, $modal = false) {
        $model = new UploadForm();
        $cad = $this->findModel($id);
        $cad_first = $this->findModel($id, true);
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload('/servidores/', $cad->slug)) {
                foreach ($model->imageFiles as $file) {
                    $cad->url_foto = $cad->slug . '.' . $file->extension;
                }
                $cad->save();
                $cad_last = $this->findModel($id, true);
                $cad->evento = SisEventsController::registrarUpdate($cad_first, $cad_last, Yii::$app->user->identity->id, $cad->tableName(), 'actionUpload');
//                registra evento no log do sistema
                $cad->save();
                $data = Yii::t('yii', 'Successfully uploaded file');
            } else {
                $data = Yii::t('yii', 'Erro no carregameento. Erro: {erro}', ['erro' => Json::encode($model->getErrors())]);
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $cad->slug]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@pagamentos/views/cad-servidores/upload', [
                        'model' => $model,
                        'modal' => $modal,
                        'imagem' => $cad->url_foto
            ]);
        } else {
            return $this->render('@pagamentos/views/cad-servidores/upload', [
                        'model' => $model,
                        'modal' => $modal,
                        'imagem' => $cad->url_foto
            ]);
        }
    }

    /**
     * Verifica a existência do cpf informado
     * @param type $q
     * @return type
     */
    public function actionR($q, $i = null) {
        $cadastros = CadServidores::find()
                ->where(['=', 'cpf', str_replace('.', '', str_replace('-', '', $q))])
                ->andWhere(['dominio' => Yii::$app->user->identity->dominio,])
                ->andWhere(['!=', 'id', $i])
                ->all();
        /*
         * Caso não hajam cadastros com este cpf_cnpj ou em caso de edição
         * se o id do cadastro existente for igual ao id do cadastro em edição
         * então retorna null, permitindo a utilização do documento no cadastro
         */
        if ($cadastros == null) {
            return Json::encode(['id' => 0, 'nome' => null]);
        } else {
            foreach ($cadastros as $cadastro) {
                $result[] = [
                    'i' => $cadastro->id,
//                    'cds' => [
//                        'n' => $cadastro->cadas_nome,
//                        's' => $cadastro->slug,
                    'r' => Html::a($cadastro->nome . ' (' . $cadastro->matricula . ')', ['/cad-servidores/' . $cadastro->slug], ['title' => 'Clique para editar registro', 'data-toggle' => 'tooltip', 'data-placement' => 'top',]) .
                    Html::a(' <span class="fa fa-clone"></span>', ['/cad-servidores/dpl', 'id' => $cadastro->slug], ['title' => 'Clique para duplicar registro', 'data-toggle' => 'tooltip', 'data-placement' => 'top',]),
//                    'r' => Html::a()'<a href=' . Url::home() . 'cadastros/' . $cadastro->slug . '>' . $cadastro->nome . '(' . $cadastro->cpf . ')</a>',
//                    ]
                ];
            }
            return Json::encode(['id' => count($cadastros), 'result' => $result], JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Retorna o próximo número de matricula
     * @return type
     */
    public static function getMatricula() {
        return CadServidores::find()
                        ->where(['dominio' => Yii::$app->user->identity->dominio])
                        ->max('matricula') + 1;
    }

    /**
     * Finds the CadServidores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CadServidores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = CadServidores::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = CadServidores::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }
//        if (($model = CadServidores::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * @param array $options
     * @return array
     */
    protected function parseProcessOptions(array $options) {
        $defaultOptions = [
            'format' => ['pdf'],
            'params' => [],
            'resources' => false,
            'locale' => false,
            'db_connection' => []
        ];

        return array_merge($defaultOptions, $options);
    }

}
