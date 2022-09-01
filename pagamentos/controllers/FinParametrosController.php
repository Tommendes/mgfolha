<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\FinParametros;
use pagamentos\models\FinParametrosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\helpers\Html;
use yii\web\Response;
use yii\helpers\Json;

/**
 * FinParametrosController implements the CRUD actions for FinParametros model.
 */
class FinParametrosController extends Controller {

    public $gestor = 0;
    public $finparametros = 0;

    const CLASS_VERB_NAME = 'Parametro financeiro';
    const CLASS_VERB_NAME_PL = 'Parametros financeiros';
    const CLASS_VERB_NAME_CPL = 'Parametro financeiro complementar';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->finparametros = Yii::$app->user->identity->financeiro;
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
                        'actions' => ['s-c', 'lists'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['index', 'view', 's',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->finparametros >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finparametros >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u', 'z',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finparametros >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finparametros >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all FinParametros models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FinParametrosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinParametros model.
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
     * Creates a new FinParametros model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new FinParametros();

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
     * Updates an existing FinParametros model.
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
     * Deletes an existing FinParametros model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = FinParametros::STATUS_CANCELADO;
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
     * Duplicates an existing FinParametros model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl() {
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $dominio = Yii::$app->user->identity->dominio;
        $model = FinParametros::find()->where([
                    'ano' => $ano,
                    'mes' => $mes,
                    'parcela' => Yii::$app->user->identity->per_parcela,
                    'dominio' => $dominio,
                ])->one();
        $id_n = FinParametros::find()->max('id') + 1;
        $parcela_n = FinParametros::find()->where([
                    'ano' => $ano,
                    'mes' => $mes,
                    'dominio' => $dominio,
                ])->max('cast(parcela as unsigned)') + 1;
        $model->id = $id_n;
        $model->parcela = str_pad($parcela_n, 3, 0, STR_PAD_LEFT);
        $model->parcela_informacao = Yii::$app->user->identity->per_parcela;
        $model->descricao = $mes == 13 ? ((intval($model->parcela) > 0) ? 'Parcela ' . $model->parcela . ' do ' : '') . '13º de ' . date('Y') : \common\models\Listas::getMes($model->mes) . ' de ' . date('Y');
        $model->status = FinParametros::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento(Yii::t('yii', 'Create {modelClass} to {add}', [
                                'modelClass' => strtolower(Html::encode(FinParametrosController::CLASS_VERB_NAME_CPL)),
                                'add' => $ano . '/' . $mes,
                            ]) . " criado com sucesso! Dados do registro criado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Parâmetro criado com sucesso.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Não foi possível criar o parâmetro. Erro: "' . $parcela_n . '" ') . Json::encode($model->getErrors()));
            return $this->redirect(['index']);
        }
    }

    /**
     * Self create a FinParametros model.
     * @return mixed
     */
    public static function actionSC($decimo = false) {
        $par = true;
        if ($decimo) {
            $now_per = date('Y');
            $mes = str_pad(date('m'), 3, '0', STR_PAD_LEFT);
            if (($model = self::getFinParametro13()
                            ->andWhere(
                                    "CAST(parcela as unsigned) = CAST('$mes' as unsigned)"
                            )
                            ->orderBy('ano DESC, mes DESC')
                            ->limit('1')->one()) != null) {
                $last_per = date('Y', strtotime($model->ano . '/' . ($model->mes > 12 ? 12 : $model->mes) . '/1'));
            } else if (($model = self::getFinParametro13()
                            ->andWhere(
                                    "CAST(parcela as unsigned) <= CAST('$mes' as unsigned)"
                            )
                            ->orderBy('ano DESC, mes DESC')
                            ->limit('1')->one()) != null) {
//                $last_per = date('Y', strtotime($model->ano . '/' . ($model->mes > 12 ? 12 : $model->mes) . '/1'));
                $last_per = date_format(date_sub(date_create(date('Y-m-t')), date_interval_create_from_date_string("1 year")), "Y");
            } else {
                $par = false;
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Erro na criação de um parâmetro financeiro de 13º.'));
            }
        } else {
            $now_per = date('Y-m-t');
            if (($model = FinParametros::find()
                            ->where([
                                'parcela' => '000'
                            ])
                            ->andWhere(['<=', 'mes', '12'])
                            ->andWhere(isset(Yii::$app->user->identity->dominio) && !empty(
                                            Yii::$app->user->identity->dominio) ? [
                                'dominio' => Yii::$app->user->identity->dominio,
                                    ] : '1=1')
                            ->orderBy('ano DESC, mes DESC')
                            ->limit('1')->one()) != null) {
                $last_per = date('Y-m-t', strtotime($model->ano . '/' . ($model->mes > 12 ? 12 : $model->mes) . '/1'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Erro na criação de um parâmetro financeiro.'));
                $par = false;
            }
        }

        if ($par && $now_per > $last_per) {
            $id_n = FinParametros::find()->max('id') + 1;
            $model->id = $id_n;
            $model->slug = strtolower(sha1(uniqid(rand($decimo, time()), true)));
            $model->status = FinParametros::STATUS_ATIVO;
            $model->created_at = $model->updated_at = time();
            $model->ano_informacao = $decimo ? date('Y') : $model->ano;
            $model->mes_informacao = $decimo ? date_format(date_sub(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 month")), "m") : $model->mes;
            $model->parcela_informacao = $model->parcela;
            $model->ano = date('Y');
            $model->mes = $decimo ? '13' : date('m');
            $model->parcela = $decimo ? str_pad(date('m'), 3, '0', STR_PAD_LEFT) : '000';
            $model->descricao = $decimo ? '13º de ' . date('Y') : \common\models\Listas::getMes($model->mes) . ' de ' . date('Y');
            $model->situacao = FinParametros::SITUACAO_ABERTA;
            $model->d_situacao = date('d-m-Y');
//            mensagem, mensagem_aniversario e manad_tipofolha serão apenas duplicados
            $model->isNewRecord = true;
            if ($model->save()) {
                $resMess = (self::CLASS_VERB_NAME . " $model->ano|$model->mes|$model->parcela criado com sucesso!");
                $model->evento = SisEventsController::registrarEvento($resMess
                                . SisEventsController::evt($model), 'actionSC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
                $model->save();
                Yii::$app->session->setFlash($decimo ? 'info' : 'success', Yii::t('yii', $resMess));
            } else {
                Yii::$app->session->setFlash($decimo ? 'danger' : 'warning', Yii::t('yii', 'Attempt to create unsuccessful. Error: ') . Json::encode($model->getErrors()));
            }
        }
//        return Json::encode([/*'$last_per' => $last_per, '$now_per' => $now_per, */$model]);
        return true;
    }

    /**
     * Inicialização do model
     * @return type FinParametros::find()
     *      ->where(['mes' => '13',])
     *      ->andWhere(isset(Yii::$app->user->identity->dominio) && !empty(Yii::$app->user->identity->dominio) ? ['dominio' => Yii::$app->user->identity->dominio,] : '1=1')
     */
    public static function getFinParametro13() {
        return FinParametros::find()
                        ->where([
                            'mes' => '13',
                        ])
                        ->andWhere(isset(Yii::$app->user->identity->dominio) && !empty(
                                        Yii::$app->user->identity->dominio) ? [
                            'dominio' => Yii::$app->user->identity->dominio,
                                ] : '1=1');
    }

    /**
     * Informa a situação da folha atual: 0=fechada(não editável); 1=aberta(editável)
     * @return type
     */
    public static function getS() {
        return ($return = FinParametros::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes,
                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->one()) != null ? $return->situacao : 0;
    }

    /**
     * Retorna o parâmetro atual
     * @return type
     */
    public static function getParametroAtual() {
        return FinParametros::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes,
                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->one();
    }

    /**
     * Gera uma lista a ser recuperada a partir de um parametro $p
     * O retorno é uma cadeia de opções embarcada na tag html <option> 
     * com o valor o solicitado no parametro $r
     * @param type String
     */
    public function actionLists($id, $p, $r, $p2 = null, $pv2 = null) {
        $echo = null;
        $posts = FinParametros::find()
                ->select([
                    $r => $r,
                ])
                ->where([$p => $id])
                ->andWhere((!empty($p2) && !empty($pv2)) ? "$p2 = '$pv2'" : '1=1')
                ->groupBy($r)
                ->orderBy([$r => SORT_ASC])
                ->all();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $echo .= "<option value='" . $post->$r . "'>" . $post->$r . "</option>";
            }
            echo $echo;
        } else {
            echo "<option>-</option>";
        }
    }

    /**
     * Finds the FinParametros model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinParametros the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = FinParametros::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = FinParametros::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }
//        if (($model = FinParametros::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Seta o tgf(Tempo de geração da folha)
     * @param type $sds
     * @return boolean
     */
    public static function actionZ($sds) {
        $model = FinParametros::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes,
                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->one();
        $model_first = FinParametros::find()
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes,
                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->asArray()->one();
        $model->tgf = $sds;
        if ($model->save()) {
            $model_last = FinParametros::find()
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                                'ano' => Yii::$app->user->identity->per_ano,
                                'mes' => Yii::$app->user->identity->per_mes,
                                'parcela' => Yii::$app->user->identity->per_parcela,
                            ])->asArray()->one();
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            $data = 'TGF informado: ' + $model->tgf;
        } else {
            $data = AppController::limpa_json($model->getErrors());
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Retorna o maior tempo de geração de folha de pagamento
     * @return type
     */
    public static function getTgf() {
        $model = FinParametros::find()
                        ->select('max(tgf) as tgf')
                        ->where([
                            'dominio' => Yii::$app->user->identity->dominio,
//                            'ano' => Yii::$app->user->identity->per_ano,
//                            'mes' => Yii::$app->user->identity->per_mes,
//                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->one();
        return $model->tgf;
    }

    /**
     * Esta função atende ap UserController::actionP
     * Retorna a parcela do cliente a ser utilizado
     * Em caso de mudança de cliente, mês ou ano, pode ser a mesma parcela que a anterior
     * ou pode ser outra caso o cliente para onde o usuário apontará não contenha 
     * os mesmo domínios que ele 
     */
    public static function getMes($ano, $dominio, $mesAtual) {
        $retorno = $mesAtual;
        $meses = self::getMeses($ano, $dominio, $mesAtual);
        // Caso o cliente selecionado tenha o mesmo domínio então mantém a 
        // seleção de domínio anterior
//        Yii::$app->session->setFlash('warning', "Mes: $mesAtual -> " . Json::encode($meses));
        if (in_array($mesAtual, $meses)) {
            $retorno = $mesAtual;
        }
        // Do contrário, seleciona o primeiro domínio da lista
        else {
            $retorno = $meses[0];
        }
        return $retorno;
    }

    /**
     * Retorna as parcelas do ano/mes/dominio informados
     * @param type $ano
     * @param type $dominio
     * @return type
     */
    public static function getMeses($ano, $dominio, $mesAtual) {
        $meses = [$mesAtual];
        $return = FinParametros::find()
                ->select('mes')
                ->where([
                    'dominio' => $dominio,
                    'ano' => $ano,
                ])
                ->groupBy('mes')->orderBy('mes')
                ->all();
        foreach ($return as $value) {
            $meses[] = $value['mes'];
        }
        return $meses;
    }

    /**
     * Esta função atende ap UserController::actionP
     * Retorna a parcela do cliente a ser utilizado
     * Em caso de mudança de cliente, mês ou ano, pode ser a mesma parcela que a anterior
     * ou pode ser outra caso o cliente para onde o usuário apontará não contenha 
     * os mesmo domínios que ele 
     */
    public static function getParcela($ano, $mes, $dominio, $parcelaAtual) {
        $retorno = $parcelaAtual;
        $parcelas = self::getParcelas($ano, $mes, $dominio, $parcelaAtual);
        // Caso o cliente selecionado tenha o mesmo domínio então mantém a 
        // seleção de domínio anterior
//        Yii::$app->session->setFlash('dander', "Parcela: $parcelaAtual -> " . Json::encode($parcelas));
        if (in_array($parcelaAtual, $parcelas)) {
            $retorno = $parcelaAtual;
        }
        // Do contrário, seleciona o primeiro domínio da lista
        else {
            $retorno = $parcelas[0];
        }
        return $retorno;
    }

    /**
     * Retorna as parcelas do ano/mes/dominio informados
     * @param type $ano
     * @param type $mes
     * @param type $dominio
     * @return type
     */
    public static function getParcelas($ano, $mes, $dominio, $parcelaAtual) {
        $parcelas = [$parcelaAtual];
        $return = FinParametros::find()
                        ->select('parcela')
                        ->where([
                            'dominio' => $dominio,
                            'ano' => $ano,
                            'mes' => $mes,
                        ])
                        ->groupBy('parcela')->orderBy('parcela')
                        ->asArray()->all();
        foreach ($return as $value) {
            $parcelas[] = $value['parcela'];
        }
        return $parcelas;
    }

}
