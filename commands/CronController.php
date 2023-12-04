<?php

namespace app\commands;

use app\components\DBHelper;
use Yii;
use yii\console\Controller;

class CronController extends Controller
{

    public function actionDate()
    {
        echo DBHelper::now();
    }

    public function actionTestEmail()
    {
        $params = Yii::$app->params;
        Yii::$app->mailer->compose()
            ->setFrom($params['senderEmail'])
            ->setTo($params['adminEmail'])
            ->setSubject('Test Email')
            // ->setTextBody('Plain text content')
            ->setHtmlBody("test")
            ->send();
    }

}
