<?php

namespace app\helpers;

use app\components\Helper;
use Yii;

class Role {

    public static function isSuperAdmin() {
        return Helper::identity()->user_id == 1;
    }

    public static function allBranch() {
        return Yii::$app->user->can('all_branch');
    }

    public static function allBranchPresent() {
        return Yii::$app->user->can('all_branch_present');
    }

    public static function adminApproval() {
        return Yii::$app->user->can('admin_approval');
    }

    public static function outletApproval() {
        return Yii::$app->user->can('outlet_approval');
    }

}
