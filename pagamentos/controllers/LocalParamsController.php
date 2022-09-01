<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\LocalParams;
use pagamentos\models\LocalParamsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;

/**
 * LocalParamsController implements the CRUD actions for LocalParams model.
 */
class LocalParamsController extends Controller {

    public $administrador = 0;
    public $gestor = 0;
    public $params = 0;

    const CLASS_VERB_NAME = 'Parâmetro';
    const CLASS_VERB_NAME_PL = 'Parâmetros';
    const GET_PARAMS_BY_GROUP_TEXT = 0;
    const GET_PARAMS_BY_GROUP_MODEL = 1;
    const GET_PARAMS_BY_GROUP_JSON = 2;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
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
                        'actions' => ['z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all LocalParams models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LocalParamsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocalParams model.
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
            return $this->redirect(['index']);
            //return $this->redirect(Url::home(true) . str_replace('_', '-', $model->tableName()) . '/' . $model->slug);
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
     * Creates a new LocalParams model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new LocalParams();

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
     * Updates an existing LocalParams model.
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
     * Deletes an existing LocalParams model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = LocalParams::STATUS_CANCELADO;
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
     * Duplicates an existing LocalParams model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id

     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = LocalParams::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = LocalParams::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
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
     * Finds the LocalParams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocalParams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = LocalParams::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = LocalParams::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }
//        if (($model = LocalParams::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Retorna o Time Zone do cliente
     * @return type
     */
    public static function getTimeZone() {
        $tz = null;
        return \common\models\Listas::getTZ($tz);
    }

    /**
     * Retorna os parametros ativos a partir de um grupo e ordena por parâmetro
     * @param type $r grupo
     * @param type $t tipo do retorno ( 1 = model, 2 = json )
     * @param type $c valor do retorno ( nome da coluna )
     * @return type
     */
    public static function getParamsByGroup($r, $t = self::GET_PARAMS_BY_GROUP_MODEL, $c = null) {
        $params = LocalParams::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'status' => LocalParams::STATUS_ATIVO,
                            'grupo' => $r,
                        ])->orderBy('parametro');
        if (isset($c) && !empty($c)) {
            $params = $params->one()->$c;
        } else {
            $params = $params->asArray()->all();
        }
        switch ($t) {
            case self::GET_PARAMS_BY_GROUP_TEXT : return $params;
                break;
            case self::GET_PARAMS_BY_GROUP_MODEL : return $params;
                break;
            case self::GET_PARAMS_BY_GROUP_JSON : return Json::encode($params);
                break;
        }
    }

    /**
     * Retorna o parametro único de um grupo
     * @param type $r grupo
     * @return type
     */
    public static function getParamByGroup($r, $dominio = null) {
        $params = LocalParams::find()
                        ->where([
                            'status' => LocalParams::STATUS_ATIVO,
                            'grupo' => $r,
                        ])
                        ->andWhere($dominio == null ? '1=1' : [
                            'dominio' => $dominio,
                        ])->one()->parametro;
        return $params;
    }

    /**
     * Seta o valor do parametro único de um grupo
     * @param type $r grupo
     * @param type $v valor
     * @return type
     */
    public function actionZ($r, $v) {
        $model_first = LocalParams::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'status' => LocalParams::STATUS_ATIVO,
                            'grupo' => $r,
                        ])->asArray()->one();
        $model = LocalParams::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'status' => LocalParams::STATUS_ATIVO,
                            'grupo' => $r,
                        ])->one();
        $model->parametro = $v;
        if ($model->save()) {
            $model_last = LocalParams::find()
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                                'status' => LocalParams::STATUS_ATIVO,
                                'grupo' => $r,
                            ])->asArray()->one();
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            return true;
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $model->getErrors();
        }
    }

    /**
     * gera uma lista a ser recuperada a partir de um grupo de parâmetros
     * @param type $grupo
     */
    public function actionLists($grupo, $default) {
        $echo = null;
        $posts = LocalParams::find()
                        ->where(['grupo' => $grupo,])
                        ->andWhere(['or',
                            ['dominio' => Yii::$app->user->identity->dominio],
                            ['dominio' => Yii::$app->id],
                        ])
                        ->orderBy('label')
                        ->groupBy('label')->all();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($default == $post->label) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $echo .= "<option $selected value='" . $post->label . "'>" . $post->label . "</option>";
            }
            echo $echo;
        } else {
            echo "<option>-</option>";
        }
    }

    /**
     * Retorna o label do parâmetro
     * @param type $parametro
     * @param type $grupo 
     * @return type
     */
    public static function getParamsLabel($parametro = null, $grupo = null, $dominio = null, $id = null) {
        if (!is_null($id)) {
            $label = LocalParams::findOne($id);
        } else {
            $label = LocalParams::find()
                    ->where([
                        'parametro' => $parametro,
                        'grupo' => $grupo,
                        'dominio' => !is_null($dominio) ? $dominio : Yii::$app->id
                    ])
                    ->one();
        }
        if (!is_null($label)) {
            $label = $label->label;
        } else {
            $label = 'Sem Label IOE: ' . $parametro;
        }
        return $label;
    }

    /**
     * Retorna um array contendo os itens de um grupo
     * @param type $grupo
     * @return type
     */
    public static function getParamsGrupo($grupo) {
        return ArrayHelper::map(LocalParams::findAll(['grupo' => $grupo]), 'id', 'label');
    }

}
