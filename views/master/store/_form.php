<?php

use app\components\Helper;
use app\models\MstClient;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MstClient */
/* @var $form ActiveForm */
?>

<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?=
                $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => Helper::textStatus(),
                    'options' => ['placeholder' => 'Select Status'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'no_telp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?=
                $form->field($mUploadForm, "inputFile")->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => Yii::$app->params['kartikConfig']['fileInput'],
                ])
                ?>
                <img src="<?= $mUploadForm->getPath() . '/' . $model->logo ?>" onerror="this.onerror=null; this.remove();">
            </div>
            <div class="col-6">
                <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
            </div>
        </div>

        <div class="form-group float-right">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

