<?php

namespace frontend\controllers;

use Yii;
use common\models\SisEvents;
use common\models\SisEventsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use common\models\UserOptions;
use common\controllers\AppController;

/**
 * SisEventsController implements the CRUD actions for SisEvents model.
 */
class SisEventsController extends Controller {

    public $gestor = 0;
    public $administrador = 0;

    const CLASS_VERB_NAME = 'Evento do sistema';
    const CLASS_VERB_NAME_PL = 'Eventos do sistema';
    const DIAS_A_MANTER = 3650;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->gestor = Yii::$app->user->identity->gestor;
            $this->administrador = Yii::$app->user->identity->administrador;
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
                            return !Yii::$app->user->isGuest && $this->gestor >= 1;
                        }
                    ],
                    [
                        'actions' => ['c'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all SisEvents models.
     * @return mixed
     */
    public function actionIndex($modal = false) {
        $searchModel = new SisEventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@frontend/views/sis-events/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'modal' => $modal,
            ]);
        } else {
            return $this->render('@frontend/views/sis-events/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'modal' => $modal,
            ]);
        }
    }

    /**
     * Displays a single SisEvents model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = 0) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'sis-events' . $model->slug);
        } else {
            $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
            SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@frontend/views/sis-events/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@frontend/views/sis-events/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Ação para criar evento
     * @param type $evento
     * @param type $classevento
     * @param type $id_user
     * @param type $tabela_bd
     * @param type $id_registro
     * @param type $glt
     * @param type $gln
     * @return type
     */
    public function actionC($evento, $classevento, $id_user, $tabela_bd, $id_registro = null, $glt = null, $gln = null) {
//        Yii::$app->response->format = Response::FORMAT_JSON;
        return self::registrarEvento($evento, $classevento, $id_user, $tabela_bd, $id_registro, $glt, $gln);
    }

    /**
     * Finds the SisEvents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisEvents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SisEvents::findOne([
                    is_numeric($id) ? 'id' : 'slug' => $id,
                    'dominio' => Yii::$app->user->identity->dominio,
                ])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Reduzir a tabela sis_events
     * @return string
     */
    public static function actionZ() {
        $diasAManter = self::DIAS_A_MANTER;
        $sis_events = SisEvents::find()
                ->where('DATEDIFF(NOW(),FROM_UNIXTIME(created_at)) > ' . $diasAManter)
                ->all();
        if (count($sis_events) > 0) {
            $clear = Yii::$app->db->createCommand(
                    'DELETE FROM sis_events WHERE DATEDIFF(NOW(),FROM_UNIXTIME(created_at)) > ' . $diasAManter
            );

            if ($clear->execute()) {
                $evento = 'Tabela sis_events reduzida com sucesso. ' . count($sis_events) . ' eventos removidos';
                SisEventsController::registrarEvento($evento, 'actionZ', Yii::$app->user->identity->id, 'SisEvents');
                $data = $evento;
            } else {
                $data = 'Erro ao tentar reduzir a tabela eventos';
            }
        } else {
            $data = 'Não há eventos a limpar';
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Registra eventos do sistema
     * @param type $evento
     * @param type $classevento
     * @param type $id_user 
     * @param type $tabela_bd
     * @param type $id_registro
     * @param type $glt
     * @param type $gln
     * @return type 
     */
    public static function registrarEvento($evento, $classevento, $id_user, $tabela_bd = null, $id_registro = null, $glt = null, $gln = null) {
//        $return = 0;
        $UserOptions = UserOptions::findOne(['id_user' => isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0]);
        $eventos = new SisEvents();
        $eventos->id = $idEvento = SisEvents::find()->max('id') + 1;
        $eventos->dominio = $dominio = !Yii::$app->user->isGuest ? Yii::$app->user->identity->dominio : 'Visitante';
        $eventos->id_user = $id_user;
        $eventos->geo_lt = !empty($UserOptions->geo_lt) ? $UserOptions->geo_lt : $glt;
        $eventos->geo_ln = !empty($UserOptions->geo_ln) ? $UserOptions->geo_ln : $gln;
        $eventos->evento = $evento;
        $eventos->classevento = $classevento;
        $eventos->ip = $ip = AppController::get_client_ip();
        $eventos->slug = Yii::$app->security->generateRandomString();
        $eventos->tabela_bd = $tabela_bd;
        $eventos->id_registro = $id_registro;
        $eventos->created_at = $eventos->updated_at = time();
        if (!Yii::$app->user->isGuest) {
            $eventos->username = Yii::$app->user->identity->username;
        }
        $return = $eventos->save() ? $idEvento : Yii::$app->session->addFlash('error', Yii::t('yii', 'Unsuccessfully created System Event. Error: {error}', ['error' => Json::encode($eventos->getErrors())]));
        return $return;
    }

    /**
     * Registra eventos update do sistema
     * @param type $model_first
     * @param type $model_last
     * @param type $id_user
     * @return type
     */
    public static function registrarUpdate($model_first, $model_last, $id_user, $tabela_bd, $action = 'actionU') {
        $updateData = 'Dados editados (Antes -> Depois):';
        foreach ($model_first as $keyA => $valueA) {
            if ($model_last[$keyA] != $model_first[$keyA]) {
                $valueB = $model_last[$keyA];
                if ($keyA === 'updated_at') {
                    $valueA = date('d-m-Y H:i:s', $valueA);
                    $valueB = date('d-m-Y H:i:s', $model_last[$keyA]);
                }
                $updateData .= ' {' . $keyA . ': ' . $valueA . ' -> ' . $valueB . '}';
            }
        }
        return self::registrarEvento($updateData, $action, $id_user, $tabela_bd, $model_first['id']);
    }

    /**
     * Retorna apenas a diferença entre dois models
     * @param type $model_first
     * @param type $model_last
     * @return int
     */
//    public static function getUpdt($model_first, $model_last) {
//        return 'Dados antes da edição: ' . ;
//    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail($model) {
        if (!$model) {
            return false;
        }
        return Yii::$app
                        ->mailer
                        ->compose(
                                ['html' => 'welcomeUser-html', 'text' => 'welcomeUser-text'], ['model' => $model]
                        )
                        ->setFrom([Yii::$app->params['noreplyEmail'] => 'Robô ' . Yii::$app->name])
                        ->setTo([$model->email => $model->username])
                        ->setSubject(Yii::t('yii', 'Welcome') . Yii::t('yii', ' - Message express from ') . Yii::$app->name)
                        ->send();
    }

    /**
     * Sends an email with a error message.
     *
     * @return boolean whether the email was send
     */
    public static function sendEmailError($message, $evento, $url) {
        $administrador = User::findOne(['administrador' => '1']);
        return Yii::$app
                        ->mailer
                        ->compose(
                                ['html' => 'errorMessage-html', 'text' => 'errorMessage-text']
                                , [
                            'compose' => [
                                'erro' => $message,
                                'url' => $url,
                                'evento' => $evento,
                                'destinatario' => $administrador->username,
                            ],
                                ]
                        )
                        ->setFrom([Yii::$app->params['supportEmail'] => 'Robô ' . Yii::$app->name])
                        ->setTo($administrador->email)
                        ->setSubject(Yii::t('yii', 'Error report from ') . Yii::$app->name)
                        ->send();
    }

    public static function UrlAtual() {
        $url = Url::home(true) . $_SERVER['REQUEST_URI'];
        return $url;
    }

    /**
     * Retorna os nomes mais os valores das colunas da tabela do model
     * @param type $model
     * @return type
     */
    public static function evt($model) {
        $i = 0;
        $evento = '';
        $tableName = $model->getDb()->schema->getTableSchema($model->tableName());
        $colunas = $tableName->getColumnNames();
        foreach ($colunas as $coluna) {
            $evento .= $coluna . ':' . $model->$coluna . ' | ';
        }
        return $evento;
    }

    /**
     * Retorna o evento no formato: ' -> ' . $evento->getUsername() . ' -> ' . $evento->classevento
     * @param type $id
     * @return type
     */
    public static function findEvt($id) {
        $evento = SisEvents::findOne($id);
        $classeEvento = '';
        $username = '';
        if (!is_null($evento)) {
            $classeEvento = $evento->classevento;
            $username = $evento->getUsername();
        } else {
            $classeEvento = 'superior a ' . self::DIAS_A_MANTER . ' dias';
            $username = 'Obsoleto';
        }
        $dadosEvento = $username . ($username === 'Obsoleto' ? ' ou ' : '-> ') . SisEventsController::getEventoDescri($classeEvento);
        return ": $dadosEvento";
    }

    /**
     * Retorna a classe de forma mais comum ao usuário
     * @param type $classeEvento
     * @return string
     */
    public static function getEventoDescri($classeEvento) {
        switch ($classeEvento) {
            case 'actionIndex' : $classeEvento = 'Indexado';
                break;
            case 'actionView' : $classeEvento = 'Visualização';
                break;
            case 'actionC' : $classeEvento = 'Registro criado';
                break;
            case 'actionU' : $classeEvento = 'Registro editado';
                break;
            case 'actionDlt' : $classeEvento = 'Registro cancelado';
                break;
            case 'actionDpl' : $classeEvento = 'Registro duplicado';
                break;
            case 'actionLogin' : $classeEvento = 'Usuário entrou';
                break;
            case 'actionLogout' : $classeEvento = 'Usuário saiu';
                break;
            case 'Novo Sistema' : $classeEvento = 'Novo Sistema';
                break;
            case 'actionSetstatus' : $classeEvento = 'Alteração de status';
                break;
            case 'actionPhoto' : $classeEvento = 'Envio de foto';
                break;
            case 'createDefault' : $classeEvento = 'Importação';
                break;
            case 'actionSC' : $classeEvento = 'Geração da folha';
                break;
            default : $classeEvento = $classeEvento;
        }
        return $classeEvento;
    }

}
