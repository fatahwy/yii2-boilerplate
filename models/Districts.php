<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "districts".
 *
 * @property int $dis_id
 * @property string|null $dis_name
 * @property int|null $city_id
 *
 */

class Districts extends SecureModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'districts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id'], 'integer'],
            [['dis_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dis_id' => 'Dis ID',
            'dis_name' => 'Dis Name',
            'city_id' => 'City ID',
        ];
    }
}
