<?php

use app\models\TrsSchedule;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model TrsSchedule */

$this->title = $model->user->name . '|' . Yii::$app->formatter->asDate($model->date);
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Checkin', 'url' => ['index', 'FilterForm[id_branch]' => $model->id_branch, 'type' => 'schedule']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'date',
            'format' => 'date',
        ],
        [
            'label' => 'Nama',
            'attribute' => 'user_id',
            'value' => function ($m) {
                return $m->user->name;
            },
        ],
        [
            'attribute' => 'id_workhour',
            'value' => function ($m) {
                return $m->workhour->name;
            },
        ],
        [
            'attribute' => 'workhour_start',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'workhour_end',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'checkin_date',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'checkout_date',
            'format' => 'datetime',
        ],
        [
            'label' => 'Status',
            'format' => 'raw',
            'value' => function ($m) {
                $stats = [
                    Html::tag('span', 'Telat', ['class' => 'badge badge-danger']),
                    Html::tag('span', 'Tepat Waktu', ['class' => 'badge badge-success']),
                ];

                return $stats[$m->is_ontime];
            },
        ],
        'late_reason',
        [
            'label' => 'Checkin Foto',
            'format' => 'raw',
            'value' => function ($m) use ($mUploadForm) {
                $html = '';
                $img = $m->checkin_image;
                if ($img) {
                    $html .= Html::a(Html::img($mUploadForm->getPath($img), ["width" => "200px"]), $mUploadForm->getPath($img), ["data-lightbox" => "image-prev", 'class' => 'effect-show']);
                    $html .= '<br/><br/>';
                }
                return $html;
            },
        ],
        [
            'label' => 'Checkout Foto',
            'format' => 'raw',
            'value' => function ($m) use ($mUploadForm) {
                $html = '';
                $img = $m->checkout_image;
                if ($img) {
                    $html .= Html::a(Html::img($mUploadForm->getPath($img), ["width" => "200px"]), $mUploadForm->getPath($img), ["data-lightbox" => "image-prev", 'class' => 'effect-show']);
                    $html .= '<br/><br/>';
                }
                return $html;
            },
        ],
    ],
]);
