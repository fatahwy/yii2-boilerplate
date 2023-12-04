<?php

namespace app\controllers;

use app\components\ExportHelper;
use app\components\Helper;
use app\helpers\Role;
use app\models\Account;
use app\models\AuthAssignment;
use app\models\MstBranch;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UserController extends BaseController
{

    public $importTitle = ['Username*', 'Password (Isi jika ingin merubah)*', 'Role*', 'Email', 'Nama*', 'No Telp'];

    public function actionIndex()
    {
        $model = new Account();
        $model->load($this->request->get());

        $model->id_branch = $this->checkAllBranch($model->id_branch);

        $query = Account::find()
            ->innerJoinWith(['branch'])
            ->andWhere(['mst_branch.id_client' => $this->user->branch->id_client])
            ->andFilterWhere(['user.id_branch' => $model->id_branch])
            ->andFilterWhere(['like', 'username', $model->username])
            ->andFilterWhere(['like', 'nip', $model->nip])
            ->andFilterWhere(['like', 'user.email', $model->email])
            ->andFilterWhere(['like', 'user.name', $model->name])
            ->andFilterWhere(['user.status' => $model->status]);

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

    public function actionView($user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Account(['scenario' => 'create']);
        $modelAuthAssignment = new AuthAssignment();
        $model->id_branch = $this->checkAllBranch($model->id_branch, true);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $modelAuthAssignment->load($this->request->post())) {
                $password_hash = trim($model->password_hash);
                $password_hash_repeat = trim($model->password_hash_repeat);

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $user = $this->user;

                    $model->password_hash = md5($model->password_hash);
                    $model->password_hash_repeat = md5($model->password_hash_repeat);
                    $model->id_client = $user->id_client;
                    $model->nip = !empty(trim($model->nip)) ? $model->nip : null;
                    $model->id_branch = $this->checkAllBranch($model->id_branch, true);

                    $modelAuthAssignment->created_at = time();

                    if ($model->save()) {
                        $modelAuthAssignment->user_id = $model->user_id;

                        if ($modelAuthAssignment->save()) {
                            Helper::flashSucceed();
                            $transaction->commit();
                            Helper::cacheFlush();
                            return $this->redirect(['view', 'user_id' => $model->user_id]);
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                }
                Helper::flashFailed(Html::errorSummary([$model, $modelAuthAssignment]));

                $model->password_hash = $password_hash;
                $model->password_hash_repeat = $password_hash_repeat;
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'modelAuthAssignment' => $modelAuthAssignment,
        ]);
    }

    public function actionUpdate($user_id)
    {
        $model = $this->findModel($user_id);
        $user = clone $model;
        $model->scenario = 'update';
        $modelAuthAssignment = $model->role;
        $model->password_hash = null;

        $tempModelAuthAssignment = new AuthAssignment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $tempModelAuthAssignment->load($this->request->post())) {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $password_hash = trim($model->password_hash);

                    AuthAssignment::deleteAll(['user_id' => $model->user_id]);

                    $model->username = $user->username;
                    $model->id_client = $user->id_client;
                    $model->password_hash = $password_hash > 0 ? md5($password_hash) : $user->password_hash;
                    $model->nip = !empty(trim($model->nip)) ? $model->nip : null;
                    $model->id_branch = $this->checkAllBranch($model->id_branch, true);

                    $tempModelAuthAssignment->user_id = $model->user_id;
                    $tempModelAuthAssignment->created_at = time();

                    if ($model->save() && $tempModelAuthAssignment->save()) {
                        Helper::flashSucceed();
                        $transaction->commit();
                        Helper::cacheFlush();
                        return $this->redirect(['view', 'user_id' => $model->user_id]);
                    }
                    Helper::flashFailed(Html::errorSummary([$model, $modelAuthAssignment]));
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelAuthAssignment' => $modelAuthAssignment,
        ]);
    }

    public function actionImport($id_branch = null)
    {
        $user = $this->user;
        $userId = $user->user_id;
        $filename = "assets/excel-user_$userId.xls";
        $xls = NULL;
        $done = false;
        $req = Yii::$app->request;

        if (!(Role::allBranch() && $id_branch)) {
            $id_branch = $user->id_branch;
        }

        $listBranch = MstBranch::getList();
        if (!isset($listBranch[$id_branch])) {
            Helper::flashFailed('ID Cabang tidak diketahui');
            return $this->redirect(['import']);
        }

        $index = 3;
        $dataXl = [];

        if ($req->isPost) {
            $reader = IOFactory::createReader('Xls');

            if ($req->post(Helper::SUBMIT_ACT) == Helper::SUBMIT_SAVE) {
                if (!is_file($filename)) {
                    Helper::flashFailed('Gagal! silakan import kembali file Excel.');
                    return $this->refresh();
                }
                $transaction = Yii::$app->db->beginTransaction();
                $xls = $reader->load($filename);
                try {
                    $haserror = false;
                    $sheet = $xls->getActiveSheet();

                    //['Username', 'Password', 'Role', 'Email', 'Name', 'Nip', 'No Telp', 'Cabang'];
                    $dataXl = $this->getData($xls, $index);
                    foreach ($dataXl['data'] as $i => $d) {
                        $modelAuthAssignment = new AuthAssignment();

                        $username = $d['username'];

                        if (!empty($username)) {
                            $model = Account::findOne(['username' => $username, 'id_branch' => $id_branch, 'id_client' => $user->id_client]);
                            if ($model) {
                                $model->scenario = 'update';
                                AuthAssignment::deleteAll(['user_id' => $model->user_id]);

                                if (!empty($d['password_hash'])) {
                                    $model->password_hash = md5($d['password_hash']);
                                }
                            } else {
                                $model = new Account(['scenario' => 'import']);
                                $model->username = $username;
                                $model->status = 1;
                                $model->password_hash = !empty($d['password_hash']) ? md5($d['password_hash']) : NULL;
                            }

                            $model->id_client = $user->id_client;
                            $model->email = $d['email'];
                            $model->name = (string) $d['name'];
                            $model->nip = !empty($d['nip']) ? (string) $d['nip'] : NULL;
                            $model->no_telp = (string) $d['no_telp'];
                            $model->id_branch = $id_branch;

                            $modelAuthAssignment->item_name = $d['role'];
                            $modelAuthAssignment->created_at = time();

                            if ($model->save()) {
                                $modelAuthAssignment->user_id = $model->user_id;

                                if (!$modelAuthAssignment->save()) {
                                    $sheet->getCell('H' . $i)->setValue('GAGAL!');
                                    $haserror = true;
                                    Helper::flashFailed(Html::errorSummary($model));
                                    break;
                                }

                                $sheet->getCell('H' . $i)->setValue('OK');
                            } else {
                                $sheet->getCell('H' . $i)->setValue('GAGAL!');
                                $haserror = true;
                                Helper::flashFailed(Html::errorSummary($model));
                                break;
                            }
                        }
                    }

                    if (!$haserror) {
                        $transaction->commit();
                        Helper::cacheFlush();
                    }
                } catch (Exception $e) {
                    Helper::flashFailed('Gagal! silakan import kembali file Excel.');
                    $haserror = true;
                    $transaction->rollBack();
                }

                if (!$haserror) {
                    unlink($filename);
                }
                $done = TRUE;
            } else {
                $file = UploadedFile::getInstanceByName('file');

                if ($file && $file->extension == 'xls' && $file->saveAs($filename)) {
                    $xls = $reader->load($filename);
                    $dataXl = $this->getData($xls, $index);
                } else {
                    Helper::flashFailed('File tidak sesuai! Pastikan ekstensi file Excel adalah <code>.xls</code>.');
                }
            }
        }

        return $this->render('@app/views/partial/import', [
            'title' => 'User',
            'id_branch' => $id_branch,
            'index' => $index,
            'xls' => $xls,
            'done' => $done,
            'dataXl' => $dataXl,
        ]);
    }

    //['Username', 'Password', 'Email', 'Name', 'Nip', 'No Telp', 'Cabang'];
    public function getData($xls, $index)
    {
        $result = [];
        $result['title'] = $this->importTitle;

        $sheet = $xls->getActiveSheet();
        for ($i = $index; $i <= $sheet->getHighestRow(); $i++) {
            $result['data'][$i] = [
                'username' => Helper::lowerTrim($sheet->getCell('A' . $i)->getValue()),
                'password_hash' => $sheet->getCell('B' . $i)->getValue(),
                'role' => $sheet->getCell('C' . $i)->getValue(),
                'email' => Helper::lowerTrim($sheet->getCell('D' . $i)->getValue()),
                'name' => $sheet->getCell('E' . $i)->getValue(),
                'nip' => $sheet->getCell('F' . $i)->getValue(),
                'no_telp' => $sheet->getCell('G' . $i)->getValue(),
            ];
        }
        return $result;
    }

    public function actionDownloadtemplate($id_branch = null)
    {
        if (!(Role::allBranch() && $id_branch)) {
            $id_branch = $this->user->id_branch;
        }

        $listBranch = MstBranch::getList();
        if (!isset($listBranch[$id_branch])) {
            Helper::flashFailed('ID Cabang tidak diketahui');
            return $this->redirect(['import']);
        }

        ExportHelper::templateUser($id_branch, $listBranch[$id_branch], $this->importTitle);
    }

    public function actionDelete($user_id)
    {
        $model = $this->findModel($user_id);

        if (!empty($model->has_trx)) {
            Helper::flashFailed('Tidak bisa dihapus. User sudah melakukan transaksi');
            return $this->redirect(['index']);
        }

        $model->scenario = 'delete';
        $model->is_deleted = Helper::STAT_ACTIVE;
        $model->save();

        Helper::cacheFlush();
        Helper::flashSucceed();
        return $this->redirect(['index']);
    }

    protected function findModel($user_id)
    {
        if (($model = Account::findOne(['user_id' => $user_id, 'id_client' => $this->user->id_client])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
