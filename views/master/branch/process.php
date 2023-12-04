<?php

use app\components\Helper;
use app\models\MstBranch;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MstBranch */
/* @var $form ActiveForm */

if ($model->isNewRecord) {
    $this->title = 'Tambah';
    $this->params['breadcrumbs'][] = ['label' => 'Cabang', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->title = 'Update Cabang';
    $this->params['breadcrumbs'][] = ['label' => 'Cabang', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id_branch' => $model->id_branch]];
    $this->params['breadcrumbs'][] = 'Update';
}
$group = Helper::SETTING_BRANCH;
?>

<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'no_telp')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'day_open')->textInput() ?>
                <?= $form->field($model, 'time_open')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'npwp')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'region')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>
            </div>
            <div class="col-4">
                <?=
                $form->field($mUploadForm, "inputFile")->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => Yii::$app->params['kartikConfig']['fileInput'],
                ])->label('Logo')
                ?>
                <img height="150px" src="<?= $mUploadForm->getPath() . '/' . $model->logo ?>" onerror="this.onerror=null; this.remove();">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Multi Login Device</label>
                <?= Html::dropDownList("value[$group][multi_login]", $dataSetting[$group]['multi_login'] ?? 1, ['Disable', 'Enable'], ['class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
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
        </div>

        <div class="form-group text-right">
            <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
