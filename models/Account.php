<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $name
 * @property string|null $no_telp
 * @property int $id_branch
 *
 * @property MstBranch $branch
 * @property MstClient $Client
 */
class Account extends SecureModel
{

    public $password_hash_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_branch', 'status', 'name', 'username', 'password_hash', 'password_hash_repeat'], 'required', 'on' => 'create'],
            [['id_branch', 'status', 'name', 'username', 'password_hash'], 'required', 'on' => 'import'],
            [['id_branch', 'name'], 'required', 'on' => 'update'],
            [['is_deleted'], 'required', 'on' => 'delete'],
            [['status', 'id_branch', 'has_trx', 'online'], 'integer'],
            [['password_hash_repeat', 'activity_time', 'updated_at', 'is_deleted'], 'safe'],
            [['username'], 'string', 'max' => 32],
            [['nip'], 'string', 'max' => 20],
            [['password_hash', 'name', 'path_info'], 'string', 'max' => 255],
            [['id_branch', 'username'], 'unique', 'targetAttribute' => ['id_branch', 'username']],
            [['email'], 'email'],
            ['password_hash_repeat', 'compare', 'compareAttribute' => 'password_hash', 'message' => 'Password don\'t match'],
            [['no_telp', 'pharmacist_sia_number', 'sip_number'], 'string', 'max' => 45],
            [['id_branch'], 'exist', 'skipOnError' => true, 'targetClass' => MstBranch::className(), 'targetAttribute' => ['id_branch' => 'id_branch']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'password_hash' => 'Password',
            'password_hash_repeat' => 'Confirm Password',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Nama',
            'nip' => 'NIP',
            'no_telp' => 'No Telpon',
            'id_branch' => 'Cabang',
            'activity_time' => 'Terakhir Aktivitas',
        ];
    }

    public static function find($isAll = false)
    {
        if ($isAll) {
            $model = parent::find();
        } else {
            $model = parent::find()->where(['is_deleted' => 0]);
        }

        return $model;
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(MstBranch::className(), ['id_branch' => 'id_branch']);
    }

    public function getRoles()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'user_id']);
    }

    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'user_id']);
    }

    public static function getList($id_branch = null)
    {
        $models = static::find()
            ->filterWhere(['id_branch' => $id_branch])
            ->all();

        return ArrayHelper::map($models, 'user_id', 'name');
    }
}
