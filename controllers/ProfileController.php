<?php

namespace app\controllers;

use app\components\Helper;
use app\controllers\BaseController;
use yii\bootstrap4\Html;

class ProfileController extends BaseController
{

    public function actionIndex()
    {
        $user = $this->user;
        $model = clone $user;
        $model->password_hash = null;
        $type = $this->request->get('type') ?? 'profile';

        if ($model->load($this->request->post())) {
            switch ($type) {
                case 'profile':
                    $password_hash = trim($model->password_hash);

                    $model->id_branch = $user->id_branch;
                    $model->nip = $user->nip;
                    $model->status = $user->status;
                    $model->username = $user->username;
                    $model->password_hash = strlen($password_hash) > 0 ? md5($password_hash) : $user->password_hash;

                    if ($model->save()) {
                        Helper::flashSucceed();
                        return $this->redirect(['index']);
                    }

                    Helper::flashFailed(Html::errorSummary($model));

                    $model->password_hash = $password_hash;
                    break;
                case 'workhour':
                    break;
                default:
                    break;
            }
        }

        return $this->render('index', [
            'type' => $type,
            'model' => $model,
        ]);
    }

}
