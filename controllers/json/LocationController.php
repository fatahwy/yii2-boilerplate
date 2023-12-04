<?php

namespace app\controllers\json;

use app\models\Cities;
use app\models\Districts;
use app\models\Subdistricts;

class LocationController extends ApiController
{

    public function actionGetCity()
    {
        $out = [];
        $parentId = $_POST['depdrop_all_params']['id-province'] ?? null;
        if ($parentId != null) {
            $models = Cities::find()->where(['prov_id' => $parentId])->cache()->all();
            $out = [];
            foreach ($models as $m) {
                $out[] = [
                    'id' => $m->city_id,
                    'name' => $m->city_name,
                ];
            }

            return ['output' => $out ?: '', 'selected' => ''];
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetDistrict()
    {
        $out = [];
        $parentId = $_POST['depdrop_all_params']['id-city'] ?? null;
        if ($parentId != null) {
            $models = Districts::find()->where(['city_id' => $parentId])->cache()->all();
            $out = [];
            foreach ($models as $m) {
                $out[] = [
                    'id' => $m->dis_id,
                    'name' => $m->dis_name,
                ];
            }

            return ['output' => $out ?: '', 'selected' => ''];
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetSubdistrict()
    {
        $out = [];
        $parentId = $_POST['depdrop_all_params']['id-district'] ?? null;
        if ($parentId != null) {
            $models = Subdistricts::find()->where(['dis_id' => $parentId])->cache()->all();
            $out = [];
            foreach ($models as $m) {
                $out[] = [
                    'id' => $m->subdis_id,
                    'name' => $m->subdis_name,
                ];
            }

            return ['output' => $out ?: '', 'selected' => ''];
        }
        return ['output' => '', 'selected' => ''];
    }
}
