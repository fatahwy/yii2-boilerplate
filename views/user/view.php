<?php

use app\components\Helper;
use app\helpers\Role;
use app\models\Account;
use app\models\AuthItem;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Account */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<p class="text-right">
    <?= Helper::checkRoute('update') ? Html::a(Helper::faUpdate(), ['update', 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) : null ?>
    <?=
    Helper::checkRoute('delete') ?
            Html::a(Helper::faDelete(), ['delete', 'user_id' => $model->user_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) : null
    ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'id_branch',
            'value' => $model->branch->name,
            'visible' => Role::allBranch(),
        ],
        'name',
        'nip',
        'username',
        'email:email',
        'no_telp',
        'pharmacist_sia_number',
        'sip_number',
        [
            'label' => 'Role',
            'value' => AuthItem::getList($model->role->item_name),
        ],
        [
            'attribute' => 'status',
            'value' => Helper::textStatus($model->status),
        ],
    ],
])
?>
