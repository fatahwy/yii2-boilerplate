<?php

use app\models\AuthItem;
use yii\web\View;

/* @var $this View */
/* @var $model AuthItem */

$this->title = 'Tambah Role';
$this->params['breadcrumbs'][] = ['label' => 'Role', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
