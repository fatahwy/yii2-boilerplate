<?php

namespace app\controllers;

use app\components\Helper;
use app\controllers\BaseController;
use app\models\AuthItem;
use app\models\AuthItemChild;
use Yii;
use yii\base\Exception;
use yii\bootstrap4\Html;

class AccessRuleController extends BaseController {

    public function actionIndex($role = 'Super') {
        $req = Yii::$app->request;
        $listRole = AuthItem::getList();

        $model = new AuthItemChild();
        $model->load($req->get());
        $model->parent = $model->parent ?: $role;

        if (empty($listRole[$model->parent])) {
            Helper::flashFailed('Role tidak ditemukan');
            return $this->redirect(['index']);
        }

        $modelAuthItems = AuthItem::find()
                ->where(['not', ['description' => '']])
                ->orderBy(['order_val' => SORT_ASC])
                ->all();

        $replaceRule = '_';
        $authItems = $listAuthItems = [];
        foreach ($modelAuthItems as $m) {
            $title = explode('|', $m->description);
            $m->description = $title[1];
            $originalName = $m->name;
            $m->name = preg_replace('/[^A-Za-z0-9\-]/', $replaceRule, $m->name);
            $authItems[$title[0]][] = $m;
            $listAuthItems[$m->name] = $originalName;
        }

        if ($model->load($req->post())) {
            $postAuthItemsChild = $req->post('AuthItemChild');
            $postAuthItemsChild['dashboard'] = ['child' => Helper::STAT_ACTIVE];
            $listAuthItems['dashboard'] = 'dashboard';

            if (!empty($listRole[$model->parent])) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $flag = AuthItemChild::deleteAll(['parent' => $model->parent]);

                    foreach ($postAuthItemsChild as $child_name => $child_value) {
                        if (!empty($listAuthItems[$child_name]) && !empty($child_value['child'])) {
                            $m = new AuthItemChild();
                            $m->parent = $model->parent;
                            $m->child = $listAuthItems[$child_name];

                            if (($flag = $m->save()) == false) {
                                $transaction->rollBack();
                                Helper::flashFailed(Html::errorSummary($m));
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Helper::flashSucceed();
                        Helper::cacheFlush();
                        return $this->redirect(['index', 'role' => $model->parent]);
                    } else {
                        Helper::flashFailed($m);
                    }
                } catch (Exception $exc) {
                    Helper::flashFailed($exc->getTraceAsString());
                }
            }

            return $this->redirect(['index']);
        }

        return $this->render('index', [
                    'replaceRule' => $replaceRule,
                    'listRole' => $listRole,
                    'model' => $model,
                    'authItems' => $authItems,
        ]);
    }

}
