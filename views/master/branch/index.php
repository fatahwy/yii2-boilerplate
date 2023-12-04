<?php

use app\components\ButtonActionColumn;
use app\components\Helper;
use app\components\StyleHelper;
use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Cabang';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-branch-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'app\components\SerialColumn'],
            'name',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return Helper::textStatus($model->status);
                },
            ],
            'address:ntext',
            'no_telp',
            'email:email',
            [
                'class' => ButtonActionColumn::className(),
                'template' => Helper::filterActionColumn('{view} {process} {delete}'),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_branch' => $model->id_branch]);
                },
                'contentOptions' => StyleHelper::buttonActionStyle(),
            ],
        ],
        'toolbar' => [
            'content' => Helper::checkValidRoute('process', Html::a(Helper::faAdd(), ['process'], ['class' => 'btn btn-success', 'data-pjax' => 0])),
        ]
    ]);
    ?>

</div>
