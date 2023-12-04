<?php

use app\components\Helper;
use app\models\MstClient;
use app\models\UploadForm;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model MstClient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="card">
    <div class="card-body">
        <p class="float-right">
            <?= Html::a('Update', ['update', 'id_client' => $model->id_client], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete', ['delete', 'id_client' => $model->id_client], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'brand',
                [
                    'attribute' => 'status',
                    'value' => Helper::textStatus($model->status),
                ],
                'address:ntext',
                [
                    'attribute' => 'logo',
                    'value' => function($model) {
                        $mUploadForm = new UploadForm();
                        return $mUploadForm->getPath() . '/' . $model->logo;
                    },
                    'format' => ['image', ['width' => '100', 'height' => '100']],
                ],
                'email:email',
                'no_telp',
                'postal_code',
            ],
        ])
        ?>
    </div>
</div>