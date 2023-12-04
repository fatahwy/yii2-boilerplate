<?php

use app\models\AuthItem;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $model AuthItem */
/* @var $form ActiveForm */
?>

<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <div class="form-group text-right">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
