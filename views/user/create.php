<?php

use app\models\Account;
use yii\web\View;

/* @var $this View */
/* @var $model Account */

$this->title = 'Tambah';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">

    <?=
    $this->render('_form', [
        'model' => $model,
        'modelAuthAssignment' => $modelAuthAssignment,
    ])
    ?>

</div>
