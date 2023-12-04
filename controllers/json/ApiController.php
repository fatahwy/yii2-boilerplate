<?php

namespace app\controllers\json;

use app\controllers\BaseController;
use Yii;
use yii\web\Response;

class ApiController extends BaseController {

    public function beforeAction($action) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (parent::beforeAction($action)) {
            return true;
        }

        return false;
    }

}
