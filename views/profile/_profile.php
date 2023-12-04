<?php

use app\components\Helper;
use app\models\Account;
use richardfan\widget\JSRegister;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Account */
/* @var $form ActiveForm */

$this->title = 'Profil';

JSRegister::begin();
?>
<script>
    $('#reveal-password').change(function () {
        $('#user-password').attr('type', this.checked ? 'text' : 'password');
    });
</script>
<?php JSRegister::end() ?>

<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-3">
                <label>Cabang</label>
                <?= Html::textInput(NULL, $model->branch->name, ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'nip')->textInput(['disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'no_telp')->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md-3">
                <label>Role</label>
                <?= Html::textInput(NULL, $model->role->item_name, ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <?= Html::textInput(NULL, Helper::textStatus($model->status), ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => !$model->isNewRecord]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true, 'id' => 'user-password'])->label($model->isNewRecord ? "Password" : "Password Baru (*kosongkan jika tidak perlu perubahan)") ?>
                <?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> 
                <?= Html::label('Show password', 'reveal-password') ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>