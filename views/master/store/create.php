<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MstClient */

$this->title = 'Create Mst Client';
$this->params['breadcrumbs'][] = ['label' => 'Mst Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-Client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'mUploadForm' => $mUploadForm,
    ])
    ?>

</div>
