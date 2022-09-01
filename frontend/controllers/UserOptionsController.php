<?php

namespace frontend\controllers;

use Yii;
use common\models\UserOptions;
use common\models\UserOptionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use common\models\User;
use yii\helpers\Url;

/**
 * UserOptionsController implements the CRUD actions for UserOptions model.
 */
class UserOptionsController extends \common\controllers\UserOptionsController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->UserOptions = Yii::$app->user->identity->usuarios;
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
                        'actions' => ['set-geo'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->UserOptions >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

}
