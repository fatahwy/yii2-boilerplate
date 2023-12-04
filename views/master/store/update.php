<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MstClient */

$this->title = 'Update Client: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id_client' => $model->id_client]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mst-Client-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'mUploadForm' => $mUploadForm,
    ])
    ?>

</div>
