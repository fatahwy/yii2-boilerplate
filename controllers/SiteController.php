<?php

namespace app\controllers;

use app\components\DBHelper;
use app\components\Helper;
use app\models\LoginForm;
use Yii;
use yii\web\Response;
use const YII_ENV_TEST;

class SiteController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = "main-login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = Yii::$app->user->identity;
            $user->online = 1;
            $user->activity_time = DBHelper::now();
            $user->path_info = 'after login';
            $user->save();

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $user = Yii::$app->user->identity;
        $user->online = 0;
        $user->save();

        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionInit($id = null, $id_branch = null)
    {
        ini_set('max_execution_time', 60);
        switch ($id) {
            case "menu":
                DBHelper::initMenu();
                die($id);
            case "flush":
                Helper::cacheFlush();
                die($id);
            case "update-stock":
        }
    }

}
