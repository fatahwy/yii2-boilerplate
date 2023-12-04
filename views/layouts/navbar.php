<?php

use richardfan\widget\JSRegister;
use yii\bootstrap4\Html;
use yii\helpers\Url;

if (($this->context->module->id ?? '') != 'log-reader') :
    JSRegister::begin();
?>
    <script>
        $('.notif')
            .html('<span class="fa fa-spin fa-spinner"></span>')
            .load('<?= Url::to(['site/notif']) ?>');
    </script>
<?php
    JSRegister::end();
endif;
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <?php
    if (!empty($this->params['components'])) {
        foreach ($this->params['components'] as $comp) {
            echo $comp . '&nbsp;';
        }
    }
    ?>
    <!-- SEARCH FORM -->
    <!--    <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="notif nav-item dropdown">
        </li>
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->