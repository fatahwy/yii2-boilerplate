<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap4\BootstrapAsset;
use yii\helpers\Html;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
app\assets\AppAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$this->registerCssFile('@web/library/lightbox2/lightbox.min.css');
$this->registerJsFile('@web/library/lightbox2/lightbox.min.js', [
    'depends' => [BootstrapAsset::class],
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title ?: Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini">
    <?php $this->beginBody() ?>

    <div class="wrapper" style="overflow:hidden;">
        <!-- Navbar -->
        <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

        <!-- Content Wrapper. Contains page content -->
        <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <?= $this->render('control-sidebar') ?>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php $this->render('footer') ?>
    </div>

    <?php $this->endBody() ?>

    <script>
        lightbox.option({
            'resizeDuration': 0,
            'wrapAround': true
        });

        function updateOnline() {
            $.get("/site/update-online", {path: '<?= $_SERVER["PATH_INFO"] ?? 'dashboard' ?>'});
        }

        setInterval(updateOnline, 5 * 60 * 1000)
    </script>
</body>

</html>
<?php $this->endPage() ?>