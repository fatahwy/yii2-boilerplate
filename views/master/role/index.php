<?php

use app\components\ButtonActionColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Role';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <p class="text-right">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            [
                'class' => ButtonActionColumn::className(),
                'template' => "{update} {delete}",
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'name' => $model->name]);
                },
                'visibleButtons' => [
                    'delete' => function ($model) {
                        return !$model->authAssignments;
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
