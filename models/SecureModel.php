<?php

namespace app\models;

use app\components\Helper;
use yii\db\ActiveRecord;

class SecureModel extends ActiveRecord
{

    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    if ($value && is_string($value) && $name != 'name') {
                        $value = Helper::encode($value);
                    }
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }
}
