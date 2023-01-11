<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Url;
use common\models\UserSearchNewUser;
use common\models\UploadForm;
use yii\web\UploadedFile;
use common\controllers\ImgController;
use pagamentos\controllers\FinParametrosController;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    public $gestor = 0;
    public $user = 0;

    const CLASS_VERB_NAME = 'Usuário';
    const CLASS_VERB_NAME_PL = 'Usuários';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity->usuarios;
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
                        'actions' => ['boas-vindas', 'p', 'view', 'photo', 'upload'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->user >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl', 'lists'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->user >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->user >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->user >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        if ($this->user >= 3 || $this->gestor >= 1) {
            $searchModel = new UserSearch();
            $searchModelNewUser = new UserSearchNewUser();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProviderNewUser = $searchModelNewUser->search(Yii::$app->request->queryParams);

            return $this->render('@frontend/views/user/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'searchModelNewUser' => $searchModelNewUser,
                        'dataProviderNewUser' => $dataProviderNewUser,
            ]);
        } else {
            return $this->actionView(Yii::$app->user->identity->id);
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $this->EmailUpdPerfil($model);
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema 
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'user/' . $model->slug);
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@frontend/views/user/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@frontend/views/user/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' show a confirmation message.
     * @param integer $id
     * @return mixed   
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionP($id, $controller) {
        $controller = strpos($controller, '%2F') > 0 ? str_ireplace('%2F', '/', $controller) : $controller;
        $base_servico = Yii::$app->user->identity->base_servico;
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
//            $model->cliente = $this->getDominio($model->cliente, $model->dominio);
            if ($model->save()) {
                $model->base_servico = $this->getBaseServico($model->cliente, $model->base_servico);
                $model->dominio = $this->getDominio($model->cliente, $model->dominio);
                $model->per_mes = FinParametrosController::getMes($model->per_ano, $model->dominio, $model->per_mes);
                $model->per_parcela = FinParametrosController::getParcela($model->per_ano, $model->per_mes, $model->dominio, $model->per_parcela);
                $model_last = $this->findModel($id, true);
                if ($model_first['dominio'] != $model_last['dominio']) {
                    $controller = '/' . explode("/", $controller)[1] . (isset(explode("/", $controller)[2]) ? '/' . explode("/", $controller)[2] : '');
                }
                if ($model_first['cliente'] != $model_last['cliente']) {
                    $controller = '';
                }
                $base_servico = strtolower($model->base_servico);
                $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
                $model->save();
                $data = Yii::t('yii', 'Perfil ajustado com sucesso.');
                Yii::$app->session->setFlash('success', $data);
            } else {
                $controller = $this->goHome();
                $data = Yii::t('yii', 'Não foi possível ajustar o período ou não existia movimento no período ajustado. Tente outro período.<br>Erro: ' . \common\controllers\AppController::limpa_json($model->getErrors()));
                Yii::$app->session->setFlash('error', $data);
            }
        }
        return $this->redirect((strlen($base_servico) > 0 ? Url::to(['/../' . $base_servico . '/' . $controller], true) : $controller));
    }

    /**
     * Retorna o domínio do cliente a ser utilizado
     * Em caso de mudança de cliente, pode ser o mesmo domínio que o cliente anterior
     * ou pode ser outro caso o cliente para onde o usuário apontará não contenha 
     * os mesmo domínios que ele
     */
    public function getBaseServico($cliente, $servicoAtual) {
        $servicos = $this->getBaseServicos($cliente);
        $servico = strtolower($servicoAtual);
        // Caso o cliente selecionado tenha o mesmo domínio então mantém a 
        // seleção de domínio anterior
        if (is_array($servicos) && in_array($servico, $servicos)) {
            $retorno = $servico;
        }
        // Do contrário, seleciona o primeiro domínio da lista
        else {
            $retorno = $servicos[0];
        }
        return $retorno;
    }

    /**
     * Retorna os domínios do cliente
     */
    public function getBaseServicos($cliente) {
        $dadosDosClientes = (Yii::$app->clientes->clientes);
        foreach ($dadosDosClientes[$cliente]['servicos'] as $key => $value) {
            $servicos[] = $value;
        }
        return $servicos;
    }

    /**
     * Retorna o domínio do cliente a ser utilizado
     * Em caso de mudança de cliente, pode ser o mesmo domínio que o cliente anterior
     * ou pode ser outro caso o cliente para onde o usuário apontará não contenha 
     * os mesmo domínios que ele
     */
    public function getDominio($cliente, $dominioAtual) {
        $dominios = $this->getDominios($cliente);
        $dominio = strtolower($dominioAtual);
        // Caso o cliente selecionado tenha o mesmo domínio então mantém a 
        // seleção de domínio anterior
        if (is_array($dominios) && in_array($dominio, $dominios)) {
            $retorno = $dominio;
        }
        // Do contrário, seleciona o primeiro domínio da lista
        else {
            $retorno = $dominios[0];
        }
        return $retorno;
    }

    /**
     * Retorna os domínios do cliente
     */
    public function getDominios($cliente) {
        $dadosDosClientes = (Yii::$app->clientes->clientes);
        foreach ($dadosDosClientes[$cliente]['dominios'] as $key => $value) {
            $dominios[] = $value;
        }
        return $dominios;
    }

    /**
     * Envia email confirmando a alteração do perfil do usuário 
     * Informa o usuário e o gestor utilizando o email em
     * e para o email em Yii::$app->params['adminEmail']
     * @param type $model
     */
    static function EmailUpdPerfil($model) {
//        Envia email para o usuário
        Yii::$app
                ->mailer
                ->compose(
                        ['html' => 'updUserPerfil-html', 'text' => 'updUserPerfil-text'], [
                    'model' => $model,
                    'gestor' => false,
                        ]
                )
                ->setFrom([Yii::$app->params['noreplyEmail'] => 'Robô ' . Yii::$app->name])
                ->setTo([$model->email => $model->username,])
                ->setSubject(Yii::t('yii', 'Alteração de perfil') . Yii::t('yii', ' - Message express from ') . Yii::$app->name)
                ->send();
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
            
            $source_file = strtolower(Yii::getAlias("@common_uploads_root/imagens/usuarios/$id.png"));
            $dst_dir = strtolower(Yii::getAlias("@common_uploads_root/imagens/usuarios/$id"));
            if (base64_to_jpeg($imagem, $source_file)) {
                $sizes = ['64', '312'];
                foreach ($sizes as $size) {
                    ImgController::resize_crop_image($size, $size, $source_file, $dst_dir . "_$size.png", 100);
                }
                $model_cad->url_foto = $model_cad->slug . '_312.png';
                unlink($source_file);
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

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@frontend/views/user/photo', [
                        'model' => $model,
                        'modal' => true,
            ]);
        } else {
            return $this->render('@frontend/views/user/photo', [
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
            $source_file = strtolower(Yii::getAlias("@common_uploads_root/imagens/usuarios/"));
            $sizes = ['64', '312'];
            if ($model->upload($source_file, $cad->slug)) {
                foreach ($model->imageFiles as $file) {
                    $source_file .= $cad->slug;
                    foreach ($sizes as $size) {
                        ImgController::resize_crop_image($size, $size, $source_file . '.' . $file->extension, $source_file . "_$size.$file->extension", 100);
                        $cad->url_foto = $cad->slug . "_$size.$file->extension";
                        $cad->save();
                    }
                }
                unlink($source_file . ".$file->extension");
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
            return $this->renderAjax('@frontend/views/user/upload', [
                        'model' => $model,
                        'modal' => $modal,
                        'imagem' => $cad->url_foto
            ]);
        } else {
            return $this->render('@frontend/views/user/upload', [
                        'model' => $model,
                        'modal' => $modal,
                        'imagem' => $cad->url_foto
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
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
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        if (Yii::$app->user->identity->administrador >= 1) {
            $model_first = $this->findModel($id, true);
            $model = $this->findModel($id);
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                $this->EmailUpdPerfil($model);
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
        } else {
            return $this->actionView($id);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = User::STATUS_CANCELADO;
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
     * Duplicates an existing User model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = User::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = User::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->username = 'Nome de usuário' . time();
        $model->email = 'email_' . time() . '@do.usuario';
        $model->hash = strrev(substr(Yii::$app->security->generateRandomString() . '_' . time()
                        , 7, 12));
        $model->password_hash = Yii::$app->security->generatePasswordHash('lynkos123');
        $model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = User::find()
                            ->andWhere(['and',
                                /* Foi removida a localização por dominio por causa da 
                                 * alteração no actionP que pode ser por dominio e 
                                 * isso causava erro no retorno */
//                                ['dominio' => Yii::$app->user->identity->dominio],
                                ['status' => User::STATUS_ATIVO],
                            ])
                            ->orWhere(['or',
                                ['status' => User::STATUS_NEW_USER],
                                ['status' => User::STATUS_NEW_USER_UNREGISTERED],
                                ['status' => User::STATUS_NEW_USER_RS],
                            ])
                            ->andWhere([is_numeric($id) ? 'id' : 'slug' => $id])
                            ->andWhere(
                                    (Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 1 ||
                                    Yii::$app->user->identity->id == $id || Yii::$app->user->identity->slug == $id) ?
                                    ['>', 'id', '0'] : ['id' => '-1']
                            )
                            ->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = User::find()
                    ->andWhere(['and',
                        /* Foi removida a localização por dominio por causa da 
                         * alteração no actionP que pode ser por dominio e 
                         * isso causava erro no retorno */
//                        ['dominio' => Yii::$app->user->identity->dominio],
                        ['status' => User::STATUS_ATIVO],
                    ])
                    ->orWhere(['or',
                        ['status' => User::STATUS_NEW_USER],
                        ['status' => User::STATUS_NEW_USER_UNREGISTERED],
                        ['status' => User::STATUS_NEW_USER_RS],
                    ])
                    ->andWhere([is_numeric($id) ? 'id' : 'slug' => $id])
                    ->andWhere(
                            (Yii::$app->user->identity->gestor == 1 || Yii::$app->user->identity->usuarios >= 1 ||
                            Yii::$app->user->identity->id == $id || Yii::$app->user->identity->slug == $id) ?
                            ['>', 'id', '0'] : ['id' => '-1']
                    )
                    ->one()) !== null) {
                return $model;
            }
        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Edita as permissões do usuário que registra a empresa para usuário master
     * @param type $usuario
     * @param type $dominio
     */
    public static function updateUserDominio($master, $usuario, $dominio) {
        $usuario->dominio = $dominio;
        $usuario->administrador = 0;
        if ($master) {
            $usuario->gestor = 1;
            $usuario->usuarios = 4;
            $usuario->cadastros = 4;
            $usuario->folha = 4;
            $usuario->financeiro = 4;
            $usuario->parametros = 4;
            $usuario->status = User::STATUS_ACTIVE;
        } else {
            $usuario->gestor = 0;
            $usuario->usuarios = 0;
            $usuario->cadastros = 0;
            $usuario->folha = 0;
            $usuario->financeiro = 0;
            $usuario->parametros = 0;
            $usuario->status = User::STATUS_ACTIVE;
        }
        $usuario->per_mes = date('m');
        $usuario->per_ano = date('Y');
        $usuario->per_parcela = 0;

        $usuario->save();
    }

    /**
     * Página de boas vindas ao novo usuário
     * @param type $r
     * @return type
     */
    public function actionBoasVindas($r) {
        $model = User::findByAuth_key($r);
        if (!\Yii::$app->user->isGuest && !$model->status === User::STATUS_NEW_USER) {
            return $this->goHome();
        }
        if (($model !== null) &&
                ($model->load(Yii::$app->request->post())) &&
                ($model->save())) {

            $model->evento = SisEventsController::registrarEvento("Usuário registrado com sucesso! " . SisEventsController::evt($model), 'actionBoasVindas', Yii::$app->user->identity->id, $model->tableName(), $model->id);

            UserController::updateUserDominio(false, $model, $model->dominio);

            $model->status = User::STATUS_NEW_USER_UNREGISTERED;

            $model->save();
            if (trim($model->verif) === strrev(substr($model->auth_key, 5, 8))) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Olá {username} Seja muito bem vindo!', ['username' => $model->username]));
                $this->EmailBoasVindas($model);
            } else {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Código de verificação inválido. Favor verificar.'));
            }
            return $this->goHome();
        } else if ($model !== null) {
            return $this->render('welcome', [
                        'model' => $model,
            ]);
        } else {
            return $this->goHome();
        }
    }

    /**
     * Envia email para o novo usuário e para o email em Yii::$app->params['adminEmail']
     * @param type $model
     */
    static function EmailBoasVindas($model) {
//        Envia email para o novo usuário
        Yii::$app
                ->mailer
                ->compose(
                        ['html' => 'newUser-html', 'text' => 'newUser-text'], ['model' => $model]
                )
                ->setFrom([Yii::$app->params['noreplyEmail'] => 'Robô ' . Yii::$app->name])
                ->setTo([$model->email => $model->username,])
                ->setSubject(Yii::t('yii', 'Confirmação de cadastro') . Yii::t('yii', ' - Message express from ') . Yii::$app->name)
                ->send();
//        Envia email para o(s) adm(s)
        // Yii::$app
        //         ->mailer
        //         ->compose(
        //                 ['html' => 'newUserToAdm-html', 'text' => 'newUserToAdm-text'], ['model' => $model]
        //         )
        //         ->setFrom([Yii::$app->params['noreplyEmail'] => 'Robô ' . Yii::$app->name])
        //         ->setTo([Yii::$app->params['adminEmail'] => 'Administrador',])
        //         ->setSubject(Yii::t('yii', 'New user') . Yii::t('yii', ' - Message express from ') . Yii::$app->name)
        //         ->send();
    }

    /**
     * gera uma lista a ser recuperada a partir de um id
     * @param type $id
     */
    public function actionLists($id) {
        $echo = null;
        $posts = UserDomains::find()
                ->select([
                    'id' => 'id',
                    'dominio' => 'dominio'
                ])
                ->where(['base_servico' => $id])
                ->groupBy('base_servico, dominio')
                ->orderBy('base_servico, dominio')
                ->all();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $echo .= "<option value='" . $post->dominio . "'>" . $post->dominio . "</option>";
            }
            echo $echo;
        } else {
            echo "<option>-</option>";
        }
    }

}
