<?php

use app\components\ButtonActionColumn;
use app\components\Helper;
use app\components\StyleHelper;
use app\models\MstClient;
use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Client';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-Client-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'app\components\SerialColumn'],
            'name',
            'brand',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return Helper::textStatus($model->status);
                },
            ],
            'email:email',
            [
                'class' => ButtonActionColumn::className(),
                'urlCreator' => function ($action, MstClient $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_client' => $model->id_client]);
                },
                'contentOptions' => StyleHelper::buttonActionStyle(),
            ],
        ],
        'toolbar' => [
            'content' => Helper::checkValidRoute('create', Html::a('Tambah', ['create'], ['class' => 'btn btn-success', 'data-pjax' => 0])),
        ],
    ]);
    ?>

</div>
