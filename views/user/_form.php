<?php

use app\components\Helper;
use app\helpers\Role;
use app\models\Account;
use app\models\AuthItem;
use app\models\MstBranch;
use kartik\select2\Select2;
use richardfan\widget\JSRegister;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Account */
/* @var $form ActiveForm */
JSRegister::begin();
?>
<script>
    $('#reveal-password').change(function () {
        $('#user-password').attr('type', this.checked ? 'text' : 'password');
    });
</script>
<?php JSRegister::end() ?>

<div class="card loader-page">
    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-3">
                <?=
                $form->field($model, 'id_branch')->widget(Select2::classname(), [
                    'data' => MstBranch::getList(),
                    'options' => ['disabled' => !Role::allBranch()],
                ])
                ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'nip')->textInput(['maxlength' => true, 'onkeydown' => "return event.key != 'Enter';"]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'no_telp')->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'pharmacist_sia_number')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'sip_number')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($modelAuthAssignment, 'item_name')->widget(Select2::classname(), [
                    'data' => AuthItem::getList(),
                ])
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => Helper::textStatus(),
                    'options' => ['placeholder' => 'Pilih Status'],
                ])
                ?>
            </div>

        </div>

        <span class="data-account">
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

                <div class="col-md-4">
                    <?= $model->isNewRecord ? $form->field($model, 'password_hash_repeat')->passwordInput() : null ?>
                </div>
            </div>
        </span>

        <div class="form-group text-right">
            <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>