<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subdistricts".
 *
 * @property int $subdis_id
 * @property string|null $subdis_name
 * @property int|null $dis_id
 *
 */
class Subdistricts extends SecureModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subdistricts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dis_id'], 'integer'],
            [['subdis_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'subdis_id' => 'Subdis ID',
            'subdis_name' => 'Subdis Name',
            'dis_id' => 'Dis ID',
        ];
    }
}
