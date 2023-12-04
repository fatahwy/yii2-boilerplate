<?php

use app\components\Helper;
use app\models\AuthItemChild;
use kartik\select2\Select2;
use richardfan\widget\JSRegister;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model AuthItemChild */
/* @var $form ActiveForm */

$this->title = 'Hak Akses';
$this->params['breadcrumbs'][] = $this->title;


$listRuleAccess = [];
foreach (AuthItemChild::findAll(['parent' => $model->parent]) as $m) {
    $listRuleAccess[preg_replace('/[^A-Za-z0-9\-]/', $replaceRule, $m->child)] = $m->child;
}

JSRegister::begin();
?>
<script>
    $('#cb-role').on('change', function(e) {
        window.location = '<?= Url::to(['/access-rule/index']) ?>?role=' + this.value;
    });

    function cbCheckAll(flag) {
        $('.cb-all input[type="checkbox"]').prop('checked', flag).prop('disabled', flag);
    }

    $('#authitemchild-all_access-child').on('change', function(e) {
        cbCheckAll(this.checked);
    });

    $('.cb-group').on('change', function(e) {
        var selector = $(this).data('val');

        $('.' + selector + ' input[type="checkbox"]').prop('checked', this.checked);
    });

    if (<?= (int) !empty($listRuleAccess['all_access']) ?>) {
        cbCheckAll(true);
    }
</script>
<?php JSRegister::end(); ?>

<div class="card loader-page">
    <div class="card-body">

        <?php
        $form = ActiveForm::begin();

        echo Html::beginTag('div', ['class' => 'row mb-3']);
        echo '<div class="col-md-3">';
        echo $form->field($model, 'parent')->widget(Select2::classname(), [
            'data' => $listRole,
            'options' => [
                'id' => 'cb-role',
            ]
        ])->label('Role');
        echo '</div>';
        echo Html::endTag('div');

        echo Html::tag('b', 'Kelola Hak Akses');
        echo Html::beginTag('div', ['class' => 'row']);
        foreach ($authItems as $title => $arrAuthItems) {
            $title = trim($title);
            $isSuperAccess = $title == "Super Akses";

            if (!$isSuperAccess) {
                echo Html::beginTag('div', ['class' => "col-md-12 b cb-all"]);
                echo Html::tag('label', $title . '&nbsp;' . Html::checkbox(null, null, ['class' => 'cb-group', 'data-val' => "cb-group-$title"]));
                echo Html::endTag('div');
            }

            foreach ($arrAuthItems as $authItem) {
        ?>
                <div class="col-md-3 cb-group-<?= $title . ($isSuperAccess ? "" : " cb-all") ?>">
                    <?php
                    $model->child = !empty($listRuleAccess[$authItem->name]);
                    echo $form->field($model, "[{$authItem->name}]child", ['labelOptions' => ['style' => 'font-weight:unset !important']])->checkbox()->label($authItem->description);
                    ?>
                </div>
        <?php
            }
        }
        echo Html::endTag('div');
        ?>

        <div class="form-group float-right">
            <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>