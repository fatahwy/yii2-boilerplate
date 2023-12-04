<?php

namespace app\controllers\master;

use app\controllers\BaseController;
use app\models\MstClient;
use app\models\UploadForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ClientController implements the CRUD actions for MstClient model.
 */
class ClientController extends BaseController
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MstClient::find(),
            /*
                  'pagination' => [
                  'pageSize' => 50
                  ],
                  'sort' => [
                  'defaultOrder' => [
                  'id_client' => SORT_DESC,
                  ]
                  ],
                 */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_client)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_client),
        ]);
    }

    public function actionCreate()
    {
        $model = new MstClient();
        $mUploadForm = new UploadForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->logo = $mUploadForm->loadImage($model, 'logo');

                if ($model->save()) {
                    $mUploadForm->upload();
                    return $this->redirect(['view', 'id_client' => $model->id_client]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'mUploadForm' => $mUploadForm,
        ]);
    }

    public function actionUpdate($id_client)
    {
        $model = $this->findModel($id_client);
        $mUploadForm = new UploadForm();

        if ($this->request->isPost && $model->validate() && $model->load($this->request->post())) {
            $model->logo = $mUploadForm->loadImage($model, 'logo');

            if ($model->save()) {
                $mUploadForm->upload();
                return $this->redirect(['view', 'id_client' => $model->id_client]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'mUploadForm' => $mUploadForm,
        ]);
    }

    public function actionDelete($id_client)
    {
        $this->findModel($id_client)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id_client)
    {
        if (($model = MstClient::findOne(['id_client' => $id_client])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
