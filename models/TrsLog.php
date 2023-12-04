<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "trs_log".
 *
 * @property int $id_log
 * @property string|null $action
 * @property string|null $table
 * @property int|null $id
 * @property string|null $url
 * @property string|null $ip
 * @property string|null $data
 * @property string|null $olddata
 * @property int $id_user
 * @property string|null $created_at
 *
 * @property User $user
 */
class TrsLog extends SecureModel {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'trs_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'id_user'], 'integer'],
            [['data', 'olddata'], 'string'],
            [['id_user'], 'required'],
            [['created_at'], 'safe'],
            [['action', 'table', 'ip'], 'string', 'max' => 45],
            [['url'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_log' => 'Id Log',
            'action' => 'Action',
            'table' => 'Table',
            'id' => 'ID',
            'url' => 'Url',
            'ip' => 'Ip',
            'data' => 'Data',
            'olddata' => 'Olddata',
            'id_user' => 'Id User',
            'created_at' => 'Tgl Buat',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'id_user']);
    }

}
