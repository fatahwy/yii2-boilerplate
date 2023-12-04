<?php

use app\components\Helper;
use app\models\mdmsoft\Menu;
use app\models\MstClient;
use richardfan\widget\JSRegister;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$user = $this->context->user ?? null;
if ($user) {
    $branch = $user->branch;
    $Client = MstClient::find()->where(['id_client' => $branch->id_client])->cache()->one();
?>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <!-- <a href="<?= Url::to(['/']) ?>" class="brand-link">
            <img src="<?= $branch->logo ? Helper::getBaseFile($Client->id_client . '/branch/' . $branch->logo) : '' ?>" onerror="this.onerror=null;this.src='/images/default.png';" alt="logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><?= $Client->name ?></span>
        </a> -->

        <!-- Sidebar -->
        <div class="sidebar" style="display: none;">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <!--<img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">-->
                </div>
                <div class="info">
                    <a href="<?= Url::to('/profile') ?>" class="d-block"><?= $user->name ?><br><?= $branch->name ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <?php
                $routes = (new Query())
                    ->select(['ai.route_menu'])
                    ->from(['aa' => 'auth_assignment'])
                    ->innerJoin(['aic' => 'auth_item_child'], 'aa.item_name=aic.parent')
                    ->innerJoin(['ai' => 'auth_item'], 'aic.child=ai.name')
                    ->where(['aa.user_id' => $user->user_id])
                    // ->cache()
                    ->all();
            
                $filteredRoutes = ArrayHelper::map($routes, 'route_menu', 'route_menu');

                function nestedMenu($menus, $level)
                {
                    $res = [];
                    foreach ($menus as $i => $val) {
                        if ($val['parent_name'] == $level) {
                            $res[$i] = [];
                            $res[$i]['label'] = $val['name'];
                            $res[$i]['icon'] = $val['parent_name'] == null ? 'th' : '';
                            $res[$i]['url'] = [$val['route']];
                            $res[$i]['items'] = nestedMenu($menus, $val['name']);
                        } else {
                            continue;
                        }
                    }

                    return $res;
                }

                $menus = Menu::getMenuSource();
            
                if (!empty($filteredRoutes['all_access'])) {
                    $d = nestedMenu($menus, null);
                } else {
                    $filteredMenus = [];
                    foreach ($menus as $value) {
                        if ($value['name'] == 'Dashboard' || $value['route'] == null || !empty($filteredRoutes[$value['route']])) {
                            $filteredMenus[] = $value;
                        }
                    }
                    $filteredMenusNested = nestedMenu($filteredMenus, null);

                    $d = [];
                    foreach ($filteredMenusNested as $value) {
                        if (!empty($value['url'][0]) || (empty($value['url'][0]) && $value['items'])) {
                            $d[] = $value;
                        }
                    }
                }

                echo \hail812\adminlte\widgets\Menu::widget(['items' => $d]);
                ?>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
<?php } ?>

<?php JSRegister::begin() ?>
<script>
    $('.sidebar').css('display', 'block');
</script>
<?php JSRegister::end() ?>