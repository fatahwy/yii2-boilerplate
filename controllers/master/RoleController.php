<?php

namespace app\controllers\master;

use app\components\Helper;
use app\controllers\BaseController;
use app\models\AuthItem;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class RoleController extends BaseController {

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()
                    ->with(['authAssignments'])
                    ->where(['type' => 1]),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new AuthItem();

        if ($model->load($this->request->post())) {
            $model->type = 1;

            if ($model->save()) {
                Helper::flashSucceed();
                return $this->redirect(['index']);
            }
            Helper::flashFailed(Html::errorSummary($model));
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($name) {
        $model = $this->findModel($name);

        if ($model->load($this->request->post())) {
            $model->type = 1;

            if ($model->save()) {
                Helper::flashSucceed();
                return $this->redirect(['index']);
            }
            Helper::flashFailed(Html::errorSummary($model));
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($name) {
        $model = $this->findModel($name);
        if ($model->authAssignments) {
            Helper::flashFailed('Role sedang digunakan.');
        } else {
            Helper::flashSucceed();
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    protected function findModel($name) {
        if (($model = AuthItem::findOne(['name' => $name, 'type' => 1])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
