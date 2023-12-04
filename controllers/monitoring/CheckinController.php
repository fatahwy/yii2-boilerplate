<?php

namespace app\controllers\monitoring;

use app\components\DBHelper;
use app\components\Helper;
use app\controllers\BaseController;
use app\helpers\Role;
use app\models\TrsSchedule;
use app\models\UploadForm;
use Yii;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class CheckinController extends BaseController
{

    public function actionIndex()
    {
        $req = Yii::$app->request;
        $type = $req->get('type') ?? 'active';
        $allBranch = Role::allBranch();
        $model = new TrsSchedule();
        $model->load($req->get());
        $model->id_branch = $this->checkAllBranch($model->id_branch, true);

        $query = TrsSchedule::find()
            ->innerJoinWith(['user', 'workhour'])
            ->where(['trs_schedule.id_branch' => $model->id_branch]);

        if ($type == 'active') {
            $query->andWhere(['not', ['checkin_date' => null]])
                ->andWhere(['checkout_date' => null])
                ->orderBy(['workhour_start' => SORT_ASC]);
        } else if ($type == 'approval') {
            $query->andWhere(['not', ['checkin_date' => null]])
                ->andWhere(['checkout_date' => null])
                ->andWhere(['is_ontime' => [0, null]])
                ->andWhere(['>', 'LENGTH(TRIM(late_reason))', 0])
                ->orderBy(['workhour_start' => SORT_ASC]);
        } else {
            $query->andWhere(['not', ['checkin_date' => null]])
                ->andWhere(['not', ['checkout_date' => null]])
                ->orderBy(['date' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'model' => $model,
            'type' => $type,
            'allBranch' => $allBranch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $mUploadForm = new UploadForm('checkin');

        return $this->render('view', [
            'mUploadForm' => $mUploadForm,
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangeStatus($id)
    {
        $req = Yii::$app->request;
        $model = TrsSchedule::find()
            ->where(['id_schedule' => $id])
            ->andWhere(['not', ['checkin_date' => null]])
            ->andWhere(['checkout_date' => null])
            ->andWhere(['is_ontime' => [0, null]])
            ->andWhere(['>', 'LENGTH(TRIM(late_reason))', 0])
            ->one();

        if (!$model) {
            Helper::flashFailed('Tidak ada active checkin');
            return $this->redirect('index');
        }
        $this->checkAllBranch($model->id_branch);

        $modelSchedule = new TrsSchedule();

        if ($modelSchedule->load($req->post())) {
            $model->is_ontime = $modelSchedule->is_ontime;
            // $model->late_reason = $modelSchedule->late_reason;

            if ($model->save()) {
                Helper::flashSucceed('Status berhasil diubah');
                return $this->redirect('index');
            }
            Helper::flashFailed(Html::errorSummary($model));
        }

        return $this->render('change-status', [
            'model' => $model,
        ]);
    }

    public function actionClose($id)
    {
        if ($this->request->isPost) {
            $model = TrsSchedule::find()
                ->where(['id_schedule' => $id])
                ->andWhere(['not', ['checkin_date' => null]])
                ->andWhere(['checkout_date' => null])
                ->one();

            $this->checkAllBranch($model->id_branch);

            if (!$model) {
                Helper::flashFailed('Tidak ada active checkin');
                return $this->redirect('index');
            }

            $this->checkAllBranch($model->id_branch);

            $model->checkout_date = DBHelper::now();

            if ($model->save()) {
                Helper::flashSucceed('Shift sudah ditutup');
            } else {
                Helper::flashFailed(Html::errorSummary($model));
            }
        }
        return $this->redirect('index');
    }

    protected function findModel($id_schedule)
    {
        $model = TrsSchedule::find()
            ->andWhere(['id_schedule' => $id_schedule])
            ->one();

        if ($model) {
            $this->checkAllBranch($model->id_branch);
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
