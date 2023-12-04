<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provinces".
 *
 * @property int $prov_id
 * @property string|null $prov_name
 * @property int|null $locationid
 * @property int|null $status
 *
 */
class Provinces extends SecureModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provinces';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['locationid', 'status'], 'integer'],
            [['prov_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prov_id' => 'Prov ID',
            'prov_name' => 'Prov Name',
            'locationid' => 'Locationid',
            'status' => 'Status',
        ];
    }

}
