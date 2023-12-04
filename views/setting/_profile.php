<?php

use app\components\Helper;
use app\models\MstBranch;
use richardfan\widget\JSRegister;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this View */
/* @var $modelBranch MstBranch */

$group = Helper::SETTING_BRANCH;

JSRegister::begin()
?>
<script>
    $('.trim-value').on('input, keyup', function(e) {
        this.value = this.value.replaceAll(' ', '');
    })
</script>
<?php JSRegister::end() ?>

<div class="card">
    <div class="card-body">
        <label>Data Cabang</label>
        <hr />
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($modelBranch, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($modelBranch, 'postal_code')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($modelBranch, 'no_telp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($modelBranch, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($modelBranch, 'address')->textarea(['rows' => 3]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Multi Login Device</label>
                <?= Html::dropDownList("value[$group][multi_login]", $dataSetting[$group]['multi_login'] ?? 1, ['Disable', 'Enable'], ['class' => 'form-control']) ?>
            </div>
        </div>
    </div>
</div>
