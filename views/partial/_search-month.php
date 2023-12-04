<?php

use app\components\Helper;
use app\models\MstBranch;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$type = isset($type) ? $type : null;
$form = ActiveForm::begin(['action' => Url::toRoute('index'), 'method' => 'GET', 'id' => "form-$type", 'options' => ['data-pjax' => true]]);
if ($type) {
    echo $form->field($model, 'type')->hiddenInput(['value' => $type])->label(false);
}
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <?=
                $form->field($model, 'id_branch')->widget(Select2::classname(), [
                    'data' => MstBranch::getList(),
                    'options' => [
                        'disabled' => !$isAllBranch,
                        'id' => 'branch-' . $type,
                    ]
                ])
                ?>
            </div>
            <div class="col-md-2">
                <?=
                $form->field($model, 'date')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Bulan',
                        'id' => "date-$type"
                    ],
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'mm-yyyy',
                    ]
                ])->label('Bulan')
                ?>
            </div>
            <div class="col-md-2 mt-md-4">
                <?= Html::submitButton(Helper::faSearch(), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<?php
$form->end();
