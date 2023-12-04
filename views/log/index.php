<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'idlog',
            'created_at:datetime',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => Html::activeTextInput($model, 'created_at', ['type' => 'date', 'class' => 'form-control'])
            ],
            [
                'label' => 'Username',
                'attribute' => 'id_user',
                'value' => 'user.username',
            ],
//            'action',
//            'table',
//            'id',
            'url:url',
            // 'ip',
            // 'data:ntext',
            // 'olddata:ntext',
            [
                'class' => 'app\components\ButtonActionColumn',
                'template' => '{view}'
            ],
        ],
    ]);
    ?>

</div>
