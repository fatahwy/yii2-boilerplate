<?php

namespace app\controllers\master;

use app\components\Helper;
use app\controllers\BaseController;
use app\models\MstBranch;
use app\models\TrsBranchPharmacist;
use app\models\UploadForm;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * BranchController implements the CRUD actions for MstBranch model.
 */
class BranchController extends BaseController
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MstBranch::find()
                ->andWhere(['id_client' => $this->user->branch->id_client])
                ->cache(),
            // 'pagination' => [
            //     'pageSize' => 1
            // ],
            // 'sort' => [
            //     'defaultOrder' => [
            //         'id_branch' => SORT_DESC,
            //     ]
            // ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_branch)
    {
        $model = $this->findModel($id_branch);

        $getSetting = $model->settings;
        $dataSetting = ArrayHelper::map($getSetting, 'key', 'value', 'group');

        return $this->render('view', [
            'model' => $model,
            'dataSetting' => $dataSetting,
        ]);
    }

    public function actionProcess($id_branch = null)
    {
        $oldPharmacistData = [];
        $dataSetting = $getSetting = [];

        if ($id_branch) {
            $model = $this->findModel($id_branch);
      
            $getSetting = $model->settings;
            $dataSetting = ArrayHelper::map($getSetting, 'key', 'value', 'group');
        } else {
            $model = new MstBranch();
        }
        $mUploadForm = new UploadForm('branch');

        if ($model->load($this->request->post())) {
            $model->id_client = $this->user->branch->id_client;
            $model->logo = $mUploadForm->loadImage($model, 'logo');

            $response = $model->saveData($getSetting, $oldPharmacistData);
            if ($response['status']) {
                Helper::cacheFlush();
                Helper::flashSucceed();
                $mUploadForm->upload();
                return $this->redirect(['view', 'id_branch' => $model->id_branch]);
            }
            Helper::flashFailed(Html::errorSummary($model));
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('process', [
            'model' => $model,
            'dataSetting' => $dataSetting,
            'mUploadForm' => $mUploadForm,
        ]);
    }

    public function actionDelete($id_branch)
    {
        $this->findModel($id_branch)->delete();
        Helper::cacheFlush();

        return $this->redirect(['index']);
    }

    protected function findModel($id_branch)
    {
        $model = MstBranch::find()
            ->andWhere(['id_client' => $this->user->branch->id_client])
            ->andWhere(['id_branch' => $id_branch])
            ->one();

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
