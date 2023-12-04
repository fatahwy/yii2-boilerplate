<?php

use app\models\Account;
use yii\web\View;

/* @var $this View */
/* @var $model Account */

$this->title = 'Update User: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="account-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'modelAuthAssignment' => $modelAuthAssignment,
    ])
    ?>

</div>
