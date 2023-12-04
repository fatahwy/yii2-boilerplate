<?php

use app\components\ButtonActionColumn;
use app\components\Helper;
use app\components\StyleHelper;
use app\helpers\Role;
use app\models\MstBranch;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'User';
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
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Pilih Cabang'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ],
            'username',
            'nip',
            'email:email',
            'name',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Helper::textStatus($model->status);
                },
                'filter' => Helper::textStatus(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Pilih Status'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ],
            //'no_telp',
            [
                'class' => ButtonActionColumn::className(),
                'template' => Helper::filterActionColumn(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'user_id' => $model->user_id]);
                },
                'contentOptions' => StyleHelper::buttonActionStyle(),
            ],
        ],
        'toolbar' => [
            'content' => Helper::checkValidRoute('import', Html::a(Helper::faUpload(), ['import'], ['class' => 'btn btn-primary', 'data-pjax' => 0])) . '&nbsp;' .
                Helper::checkValidRoute('create', Html::a(Helper::faAdd(), ['create'], ['class' => 'btn btn-success', 'data-pjax' => 0])),
        ]
    ]);
    ?>

</div>