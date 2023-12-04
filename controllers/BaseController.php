<?php

namespace app\controllers;

use app\components\Helper;
use app\helpers\Role;
use app\models\Account;
use app\models\MstBranch;
use app\models\TrsLog;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{

    public $user;

    public function behaviors()
    {
        $accessControl = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];

        return array_merge(parent::behaviors(), $accessControl);
    }

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

    public function beforeAction($action)
    {
        $user = Yii::$app->user->identity;
        $this->user = $user;

        if (parent::beforeAction($action)) {
            $this->doLog($action);
            return true;
        }

        return false;
    }

    public function checkAllBranch($idBranch = null, $keepExist = false)
    {
        $isAllBranch = Role::allBranch();
        $user = Yii::$app->user->identity;
        $userIdBranch = $user->id_branch;
        if (empty($idBranch)) {
            return $keepExist || !$isAllBranch ? $userIdBranch : null;
        }

        $model = MstBranch::find()->andWhere(['id_branch' => $idBranch, 'id_client' => $user->id_client])->cache()->one();
        if (!$model) {
            throw new NotFoundHttpException('Cabang tidak ditemukan');
        }
        if (!$isAllBranch && $idBranch != $userIdBranch) {
            throw new NotFoundHttpException('Anda tidak memiliki akses di cabang ' . $model->name);
        }

        return $idBranch;
    }

    private function doLog($action)
    {
        $req = Yii::$app->request;
        $user = Yii::$app->user->identity;
        if ($req->isPost && !$req->isAjax) {
            if (empty($user->user_id)) {
                $user = Account::findOne(['username' => $req->post('LoginForm')['username']]);
            }

            $log = new TrsLog();
            $log->action = $action->controller->route;
            $log->url = $req->getAbsoluteUrl();
            $log->ip = $req->getUserIP();
            $log->id_user = $user->user_id ?? null;
            $log->data = json_encode([$req->post(), $req->get(), $_FILES]);
            $log->save();
        }
    }

    protected function isEmptyCell($sheet, $cell, $cellMsg, $msg = '', $isDate = false)
    {
        $val = $sheet->getCell($cell)->getValue();

        if (strlen(trim($val)) <= 0) {
            $tmpError = [];
            $valMsg = $sheet->getCell($cellMsg)->getValue();
            if (!empty($valMsg)) {
                $tmpError[] = $valMsg;
            }
            $tmpError[] = "$msg harus diisi";
            $sheet->getCell($cellMsg)->setValue(implode(', ', $tmpError));
        }
        if ($isDate && is_int($val)) {
            $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val);
            return date('Y-m-d', $timestamp);
        }

        return Helper::lowerTrim($val);
    }

    public function test($object = [])
    {
        $user = Yii::$app->user->identity;
        if ($user->user_id == 1) {
            echo '<pre>';
            print_r($object);
            die;
        }
    }
}
