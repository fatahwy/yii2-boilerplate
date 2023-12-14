<?php

use richardfan\widget\JSRegister;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$js = JSRegister::begin();
?>
<script>
    $('#toggle-password').click(function(e) {
        const isShow = $('#toggle-password').find('.fa-eye-slash').length;

        if (isShow) {
            $('#toggle-password').find('.fa-eye-slash').addClass('fa-eye').removeClass('fa-eye-slash');
        } else {
            $('#toggle-password').find('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
        }

        $('#loginform-password').attr('type', isShow ? 'text' : 'password');
    })
</script>
<?php $js->end() ?>

<div class="login-logo"><?= Yii::$app->name ?></div>

<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'password', [
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text" id="toggle-password"><span class="fas fa-eye-slash"></span></div></div>',
            'template' => '{label}{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->passwordInput() ?>

        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <!-- /.social-auth-links -->
</div>
<!-- /.login-card-body -->
</div>