<?php

namespace app\controllers\monitoring;

use app\components\Helper;
use app\controllers\BaseController;
use app\models\Account;
use Yii;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{

    public function actionIndex()
    {
        $model = new Account();
        $model->load($this->request->get());

        $model->id_branch = $this->checkAllBranch($model->id_branch, true);

        $query = Account::find()
            ->innerJoinWith(['branch'])
            ->andWhere(['mst_branch.id_client' => $this->user->branch->id_client])
            ->andWhere(['user.id_branch' => $model->id_branch])
            ->andFilterWhere(['like', 'username', $model->username])
            ->andFilterWhere(['like', 'nip', $model->nip])
            ->andFilterWhere(['like', 'user.email', $model->email])
            ->andFilterWhere(['like', 'user.name', $model->name])
            ->orderBy(['online' => SORT_DESC, 'activity_time' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*
                  'pagination' => [
                  'pageSize' => 50
                  ],
                  'sort' => [
                  'defaultOrder' => [
                  'user_id' => SORT_DESC,
                  ]
                  ],
                 */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionUpdate($user_id)
    {
        $model = $this->findModel($user_id);
        if ($this->request->isPost) {
            $model->online = 0;
            $model->path_info = 'logout by admin';

            if ($model->save()) {
                Helper::flashSucceed();
                Helper::cacheFlush();
            } else {
                Helper::flashFailed(Html::errorSummary([$model]));
            }
        }

        return $this->redirect(['index']);
    }


    private function findModel($user_id)
    {
        if (($model = Account::findOne(['user_id' => $user_id, 'id_client' => $this->user->id_client])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
