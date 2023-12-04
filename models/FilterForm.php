<?php

namespace app\models;

use yii\base\Model;

class FilterForm extends Model
{

    public $name;
    public $id_branch;
    public $type;
    public $status;
    public $date_start;
    public $date_end;
    public $created_at;
    public $id_province;
    public $id_city;
    public $id_district;
    public $id_subdistrict;
    public $id_location;
    public $no_batch;
 

    public function __construct($fieldName = null)
    {
        parent::__construct();
        $this->type = $fieldName;
    }

    public function rules()
    {
        return [
            [['name', 'date_start', 'date_end', 'id_branch', 'id_location', ], 'safe'],
            [['id_province', 'id_city', 'id_district', 'id_subdistrict'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_branch' => 'Cabang',
            'date_start' => 'Tgl Mulai',
            'date_end' => 'Tgl Selesai',
            'id_province' => 'Provinsi',
            'id_city' => 'Kabupaten/Kota',
            'id_district' => 'Kecamatan',
            'id_subdistrict' => 'Desa',
        ];
    }
}
