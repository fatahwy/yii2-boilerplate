<?php
/* @var $content string */

use app\components\DBHelper;
use app\components\Helper;
use app\models\TrsSchedule;
use richardfan\widget\JSRegister;
use yii\bootstrap4\Breadcrumbs;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?php
                        if (!is_null($this->title)) {
                            echo \yii\helpers\Html::encode($this->title);
                        } else {
                            echo '';
                            // echo \yii\helpers\Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <?php
                    echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options' => [
                            'class' => 'breadcrumb float-sm-right'
                        ]
                    ]);
                    ?>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <style>
        .loader-page>div {
            display: none;
        }

        .loader-page::before {
            content: 'Loading...';
            font-size: 30px;
            text-align: center;
        }
    </style>

    <div class="content">
        <?= app\widgets\Alert::widget() ?>
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<?php JSRegister::begin() ?>
<script>
    setTimeout(() => {
        $('.loader-page').removeClass('loader-page');
    }, 100);
</script>
<?php JSRegister::end() ?>