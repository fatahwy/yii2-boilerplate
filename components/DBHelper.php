<?php

namespace app\components;

use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\mdmsoft\Menu;
use Yii;
use yii\db\Exception;

class DBHelper
{

    public static function doLogin($user, $remember = false)
    {
        self::doLoginUpdate($user);
        $id = Yii::$app->user->login($user, $remember ? 3600 * 24 * 30 : 1440);
        return $id;
    }

    public static function doLoginUpdate($user)
    {
        $user->lastlogin = self::now();
        $user->passwordrepeat = $user->password;
        return $user->save();
    }

    public static function nextID($id, $table, $id_branch, $isDaily = true)
    {
        $c = $isDaily ? date('Ymd') : date('Ym');
        $sql = "SELECT COALESCE(MAX(" . $id . "),0) id FROM $table "
            . "WHERE $id LIKE '$c%' AND delete_time IS NULL AND id_branch = $id_branch";
        $retval = Yii::$app->getDb()->createCommand($sql)->queryOne();

        $res = $retval['id'] == 0 ? (int) (date('Ymd') . "000") : ($retval['id'] + 1);
        if ($isDaily) {
            return $res;
        }

        return date('Ymd') . substr($res, 8);
    }

    public static function today()
    {
        return date('Y-m-d');
    }

    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public static function toSqlDate($date, $format = 'Y-m-d')
    {
        if (!empty($date)) {
            return date($format, strtotime($date));
        }
        return null;
    }

    public static function toHumanDate($date)
    {
        if (!empty($date)) {
            return date('d-m-Y', strtotime($date));
        }
        return null;
    }

    public static function initMenu()
    {
        $dashboard = 1;
        $report = 2;
        $master = 3;
        $setting = 4;
        $monitoring = 5;
        $log = 6;

        // name, parent, route, stat
        $menu[$dashboard] = ['Dashboard', null, '/site/index', 1];
        $menu[$report] = ['Laporan', null, null, 1];
        $menu[$master] = ['Master', null, null, 1];
        $menu[$setting] = ['Setting', null, null, 1];
        $menu[$monitoring] = ['Monitoring', null, null, 1];
        $menu[$log] = ['Log', null, '/log', 1];

        $submenu = [
            // Master
            ['Client', $master, '/master/Client/index', 0],
            ['Cabang', $master, '/master/branch/index', 1],
            ['User', $master, '/user/index', 1],
            ['Role', $master, '/master/role/index', 1],
            // Monitoring
            ['User Login', $monitoring, '/monitoring/user/index', 1],
            // Setting
            ['Konfigurasi', $setting, '/setting/index', 1],
            ['Access Rule', $setting, '/access-rule/index', 1],
        ];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $flag = true;

            Yii::$app->db->createCommand("truncate table menu")->execute();
            foreach ($menu as $i => $d) {
                $model = new Menu();
                $model->id = $i;
                $model->name = $d[0];
                $model->parent = $d[1];
                $model->route = $d[2];
                $model->stat = $d[3];
                $model->order = $i;

                if (($flag = $model->save()) == false) {
                    $transaction->rollBack();
                    break;
                }
            }

            $counter = count($menu);
            foreach ($submenu as $i => $d) {
                $id = $counter + $i + 1;
                $model = new Menu();
                $model->id = $id;
                $model->name = $d[0];
                $model->parent = $d[1];
                $model->route = $d[2];
                $model->stat = $d[3];
                $model->order = $id;

                if (($flag = $model->save()) == false) {
                    $transaction->rollBack();
                    echo '<pre>';
                    print_r($model);
                    die;
                }
            }

            if ($flag) {
                $transaction->commit();
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        // ACCESS RULE
        $authItems = [
            // Super
            'all_access' => ' Super Akses|Semua Menu',
            'all_branch' => " Super Akses|Semua Cabang",
            // Dashboard
            'dashboard' => '',
            // Laporan
            // Master
            '/user/*' => "User|Kelola User",
            '/master/role/*' => "Master|Role",
            // Monitoring
            '/monitoring/user/*' => "Monitoring|User Login",
            // Setting
            '/setting/*' => "Setting|Konfigurasi",
            '/access-rule/*' => "Setting|Hak Akses",

            // mendaftarkan route yang dilist
            "/site/*" => '',
            "/profile/*" => '',
            "/api/*" => '',
            "/api/location/*" => '',
            "/gridview/*" => '',
            "/*" => '',

        ];

        $authItemChilds = [
            'dashboard' => [
                "/api/*",
                "/api/location/*",
                "/gridview/*",
                "/site/*",
                "/profile/*",
            ],
            'all_access' => [
                "/*",
            ],
        ];

        AuthItem::updateAll(['description' => null]);
        $i = 0;
        foreach ($authItems as $route => $data) {
            $model = AuthItem::findOne(['name' => $route, 'type' => 2]);

            if (is_array($data)) {
                $description = $data['label'];
                $route_menu = $data['route_menu'];
            } else {
                $route_menu = $route;
                $description = $data;
                if (strpos($route, '*') !== false) {
                    $route_menu = str_replace('*', 'index', $route);
                }
            }

            if ($model) {
                $model->description = $description;
            } else {
                $model = new AuthItem();
                $model->name = $route;
                $model->type = 2;
                $model->description = $description;
            }
            $model->route_menu = $route_menu;
            $model->order_val = $i;
            $model->save();
            $i++;
        }

        foreach ($authItemChilds as $parent => $arrRoute) {
            AuthItemChild::deleteAll(['parent' => $parent]);

            foreach ($arrRoute as $child) {
                $model = new AuthItemChild();
                $model->parent = $parent;
                $model->child = $child;
                $model->save();
            }
        }
        Helper::cacheFlush();
    }
}
