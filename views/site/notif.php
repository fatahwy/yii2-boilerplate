<a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-danger navbar-badge"><?= $total ?></span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-header"><?= $total ?> Notifications</span>
    <div class="dropdown-divider"></div>
    <?php foreach ($data as $url => $d) : ?>
        <?php if ($d['total'] > 0) : ?>
            <a href="<?= $url ?>" class="dropdown-item">
                <i class="mr-2"></i><?= $d['label'] ?>
                <span class="float-right"><?= $d['total'] ?></span>
            </a>
            <div class="dropdown-divider"></div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>