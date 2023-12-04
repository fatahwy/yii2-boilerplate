<?php

use app\components\Helper;
use app\helpers\Role;
use app\models\MstBranch;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::toRoute('index'), 'method' => 'GET', 'options' => ['data-pjax' => $pjax ?? true]]);
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <?=
                $form->field($model, 'id_branch')->widget(Select2::classname(), [
                    'data' => MstBranch::getList(),
                    'options' => ['disabled' => !$isAllBranch]
                ])
                ?>
            </div>
            <div class="col-md-2">
                <?=
                $form->field($model, 'date_start')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Tanggal Mulai', 'id' => "date-start"],
                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label('Tanggal Mulai')
                ?>
            </div>
            <div class="col-md-2">
                <?=
                $form->field($model, 'date_end')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Tanggal Selesai', 'id' => "date-end"],
                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label('Tanggal Selesai')
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
