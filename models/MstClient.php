<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mst_client".
 *
 * @property int $id_client
 * @property string|null $name
 * @property string|null $brand
 * @property int $status
 * @property string|null $address
 * @property string|null $logo
 * @property string|null $email
 * @property string|null $no_telp
 * @property string|null $postal_code
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $delete_time
 *
 * @property MstBranch[] $branches
 * @property User[] $users
 */
class MstClient extends BaseModel {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'mst_client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['status', 'delete_time'], 'integer'],
            [['address'], 'string'],
            [['email'], 'email'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'brand', 'logo'], 'string', 'max' => 255],
            [['email', 'no_telp', 'postal_code'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_client' => 'Client',
            'name' => 'Nama',
            'brand' => 'Brand',
            'status' => 'Status',
            'address' => 'Alamat',
            'logo' => 'Logo',
            'email' => 'Email',
            'no_telp' => 'No Telpon',
            'postal_code' => 'Kode Pos',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'delete_time' => 'Delete Time',
        ];
    }

    /**
     * Gets query for [[MstBranches]].
     *
     * @return ActiveQuery
     */
    public function getBranches() {
        return $this->hasMany(MstBranch::className(), ['id_client' => 'id_client']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['id_client' => 'id_client']);
    }

    public static function getList() {
        return ArrayHelper::map(static::find()->all(), 'id_client', 'name');
    }

}
