<?php

namespace app\controllers;

use app\helpers\Role;
use app\models\TrsLog;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class LogController extends BaseController
{

    public function actionIndex()
    {
        $user = $this->user;

        $model = new TrsLog();
        $model->load(Yii::$app->request->get());
        $tempUser = function ($query) use ($user) {
            $query->filterWhere(['id_branch' => Role::allBranch() ? NULL : $user->id_branch]);
        };

        $query = TrsLog::find()
            ->joinWith(['user' => $tempUser])
            ->andFilterWhere(['like', 'trs_log.created_at', $model->created_at])
            ->andFilterWhere(['like', 'user.username', $model->id_user])
            ->andFilterWhere(['like', 'url', $model->url]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC]
            ]
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $isAllBranch = Role::allBranch();
        $model = $this->findModel($id);

        return $this->render('view', [
            'isAllBranch' => $isAllBranch,
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        $model = TrsLog::find()
            ->innerJoinWith(['user'])
            ->where(['id_log' => $id])
            ->andFilterWhere(['id_branch' => Role::allBranch() ? NULL : $this->user->id_branch])
            ->one();

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
