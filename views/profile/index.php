<?php

use app\models\Account;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model Account */

$this->title = $model->name;

echo TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'bordered' => true,
    'items' => [
        [
            'active' => $type == 'profile' || $type == null,
            'label' => 'Profile',
            'url' => Url::to(['', 'type' => 'profile']),
            'content' => $this->render('_profile', [
                'model' => $model,
            ]),
        ],
    ],
]);
?>