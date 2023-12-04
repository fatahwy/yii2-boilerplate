<?php

use app\components\Helper;
use kartik\tabs\TabsX;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Setting';
?>
<div class="loader-page">
    <div>
        <?php
        $form = ActiveForm::begin();

        $items = [
            [
                'label' => 'Profil',
                'content' => $this->render("_profile", [
                    'form' => $form,
                    'modelBranch' => $modelBranch,
                    'dataSetting' => $dataSetting,
                ]), 'active' => true
            ],
        ];

        echo TabsX::widget([
            'items' => $items,
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false
        ]);
        ?>
        <div class="form-group text-right">
            <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
        </div>

        <?php $form->end(); ?>
    </div>
</div>