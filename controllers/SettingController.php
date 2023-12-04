<?php

namespace app\controllers;

use app\components\Helper;
use app\controllers\BaseController;
use app\models\Account;
use Yii;
use yii\helpers\ArrayHelper;

class SettingController extends BaseController
{

    public function actionIndex()
    {
        $req = Yii::$app->request;
        $user = $this->user;

        $modelBranch = $user->branch;
        $getSetting = $modelBranch->settings;
        $dataSetting = ArrayHelper::map($getSetting, 'key', 'value', 'group');
        $dataUser = Account::getList($modelBranch->id_branch);

        if ($req->isPost) {
            $response = $modelBranch->saveData($getSetting);
            if ($response['status']) {
                Helper::flashSucceed($response['message']);
            } else {
                Helper::flashFailed($response['message']);
            }

            return $this->redirect(['index']);
        }

        return $this->render('index', compact('dataUser', 'modelBranch', 'dataSetting'));
    }
}
