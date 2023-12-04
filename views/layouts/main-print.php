<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

//FontAwesomeAsset::register($this);
//AdminLteAsset::register($this);
AppAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style media="all">
            body {
                /*font-family: serif;*/
                color: #212529;
                background-color: white;
                /*font-size: 12pt !important;*/
            }
        </style>
    </head>
    <body>
        <div class="">
            <?php $this->beginBody() ?>
            <?= $this->render('content', ['content' => $content]) ?>
            <?php $this->endBody() ?>
        </div>
        <script>
            window.print();
            window.onafterprint = window.close;
        </script>
    </body>
</html>
<?php $this->endPage() ?>
