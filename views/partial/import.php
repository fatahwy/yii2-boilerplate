<?php

use app\components\Helper;
use app\helpers\Role;
use app\models\MstBranch;
use app\models\MstProduct;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model MstProduct */
/* @var $xls PHPExcel */

$this->title = "Impor $title";
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$id_branch = !empty($id_branch) ? $id_branch : NULL;

$js = JSRegister::begin();
?>
<script>
    $('.need-reload').on('change', function (e) {
        window.location = '<?= Url::to(['import']) ?>?id_branch=' + this.value;
    });
</script>
<?php $js->end() ?>
<div class="schedule-create">

    <div class="text-right">
        <?= Html::a(Helper::faDownload('Dowload Template'), ['downloadtemplate', 'id_branch' => $id_branch], ['class' => 'btn btn-primary']) ?>
    </div>

    <?= Html::beginForm('', 'post', ['id' => 'import-form', 'enctype' => 'multipart/form-data']); ?>

    <?php
    echo Html::beginTag('div', ['class' => 'col-md-4']);
    if (Role::allBranch() && $id_branch) {
        echo '<label class="control-label">Cabang</label>';

        echo Html::beginTag('div', ['class' => 'form-group']);
        echo Html::dropDownList('', $id_branch, MstBranch::getList(), ['class' => 'form-control need-reload', 'width' => '200px']);
        echo Html::endTag('div');
    }

    echo Html::beginTag('div', ['class' => 'form-group']);
    echo Html::fileInput('file', null, ['class' => 'pull-left', 'required' => true]);

    echo Html::submitButton(Helper::faUpload('Upload'), ['class' => 'btn btn-primary pull-left']);
    echo Html::endTag('div');
    echo Html::endTag('div');
    ?>

    <div class="clearfix"></div>

    <?= Html::endForm(); ?>
    <br/>
    <?php
    if (isset($dataXl['data'])) {
        echo Html::beginForm('', 'post', ['id' => 'do-import-form', 'class' => 'mb-5']);
        $haserror = false;
        $sheet = $xls->getActiveSheet();

        if ($done) {
            echo Html::tag('h4', 'Pastikan status pada kolom terakhir adalah OK.');
        } else {
            echo Html::tag('h4', 'Periksa tabel berikut sebelum menyimpan.');
        }
        echo '<div class="table-responsive">';
        echo '<table class="table table-sm table-bordered table-condensed">';
        echo '<thead><tr>';
        echo Html::tag('th', 'No');
        foreach ($dataXl['title'] as $key => $fName) {
            echo Html::tag('th', $fName);
        }
        echo Html::tag('th', '');
        echo '</tr></thead>';

        $errorTemp = [];
        foreach ($dataXl['data'] as $i => $d) {
            echo '<tr>';
            echo Html::tag('td', ($i - $index) + 1);
            try {
                $row = 'A';
                foreach ($d as $v) {
                    echo Html::tag('td', $v);
                    $row++;
                }

                $ret = $sheet->getCell($row . $i)->getValue();
                if (empty($ret)) {
                    echo Html::tag('td', '');
                } else {
                    if ($ret != 'OK') {
                        $haserror = TRUE;
                    }
                    echo Html::tag('td', $ret, ['style' => $ret == 'OK' ? 'background: green;' : 'background: red;']);
                }
            } catch (Exception $e) {
                $haserror = TRUE;
                echo Html::tag('td', 'ERROR! CELL ' . $row . $i, ['style' => 'background: red;']);
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        echo $haserror ? '<code>Perbaiki ERROR sebelum menyimpan!</code>' : '';
        if (!$done) {
            echo '<div class="text-right">';
            echo Html::submitButton(Helper::faSave(), ['onclick' => 'return confirm("Apakah anda yakin sudah benar?");', 'name' => Helper::SUBMIT_ACT, 'value' => Helper::SUBMIT_SAVE, 'disabled' => $haserror, 'class' => 'btn btn-success']);
            echo '</div>';
        }
        echo Html::endForm();
        echo '<br/>';
    }
    ?>
</div>