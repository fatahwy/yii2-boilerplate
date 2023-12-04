<?php

use app\components\Helper;
use app\models\MstBranch;
use app\models\UploadForm;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model MstBranch */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cabang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<p class="text-right">
    <?= Helper::checkRoute('process') ? Html::a(Helper::faUpdate(), ['process', 'id_branch' => $model->id_branch], ['class' => 'btn btn-primary']) : null ?>
    <?=
    Helper::checkRoute('delete') ?
        Html::a(Helper::faDelete(), ['delete', 'id_branch' => $model->id_branch], [
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
        'name',
        [
            'attribute' => 'status',
            'value' => Helper::textStatus($model->status),
        ],
        'region',
        'address:ntext',
        'email:email',
        'no_telp',
        'day_open',
        'time_open',
        [
            'attribute' => 'logo',
            'value' => function ($model) {
                $mUploadForm = new UploadForm('branch');
                return $mUploadForm->getPath() . '/' . $model->logo;
            },
            'format' => ['image', ['width' => '100', 'height' => '100']],
        ],
        [
            'label' => 'Multi Login Device',
            'value' => function () use ($dataSetting) {
                return ['No', 'Ya'][$dataSetting[Helper::SETTING_BRANCH]['multi_login'] ?? 1];
            }
        ],
        'status:boolean:Status Aktif',
    ],
])
?>