<?php

use app\components\Helper;
use app\helpers\Role;
use app\models\MstBranch;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'User Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">
    <?=
    GridView::widget([
        'filterModel' => $model,
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'app\components\SerialColumn'],
            [
                'attribute' => 'id_branch',
                'value' => 'branch.name',
                'visible' => Role::allBranch(),
                'filter' => MstBranch::getList(),
                'filterType' => GridView::FILTER_SELECT2,
            ],
            'username',
            'name',
            'activity_time:datetime:Terakhir Aktivitas',
            'path_info:ntext:Path',
            [
                'label' => 'Online',
                'format' => 'raw',
                'value' => function ($m) {
                    return Helper::textLabel($m->online ? 'Online' : 'Offline', $m->online);
                }
            ],
            [
                'format' => 'raw',
                'value' => function ($m) {
                    if ($m->online) {
                        return Html::a('Set Offline', ['update', 'user_id' => $m->user_id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'data-confirm' => 'Anda yakin mau set Offline?']);
                    }
                }
            ]
        ],
        'toolbar' => [
            'content' => '',
        ]
    ]);
    ?>

</div>