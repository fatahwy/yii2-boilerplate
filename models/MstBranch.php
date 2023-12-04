<?php

namespace app\models;

use app\components\Helper;
use app\models\MstProduct;
use app\models\MstClient;
use Exception;
use Yii;
use yii\bootstrap4\Html;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\User;

/**
 * This is the model class for table "mst_branch".
 *
 * @property int $id_branch
 * @property int $id_client
 * @property int $status
 * @property string $name
 * @property string|null $address
 * @property string|null $postal_code
 * @property string|null $no_telp
 * @property string|null $email
 * @property string|null $npwp
 * @property string|null $capital
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $delete_time
 *
 * @property MstClient $client
 * @property User[] $users
 */
class MstBranch extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'delete_time'], 'integer'],
            [['address'], 'string'],
            [['name', 'postal_code', 'npwp'], 'string', 'max' => 255],
            [['no_telp', 'email', 'capital', 'day_open', 'time_open', 'region'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_branch' => 'Id Branch',
            'id_client' => 'Client',
            'status' => 'Status',
            'name' => 'Nama',
            'address' => 'Alamat',
            'postal_code' => 'Kode Pos',
            'no_telp' => 'No Telpon',
            'email' => 'Email',
            'npwp' => 'Npwp',
            'capital' => 'Modal Awal',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'delete_time' => 'Delete Time',
            'region' => 'Kabupaten/Kota',
            'day_open' => 'Hari Buka',
            'time_open' => 'Jam Buka',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(MstClient::className(), ['id_client' => 'id_client']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id_branch' => 'id_branch']);
    }

    public function getSettings()
    {
        return $this->hasMany(MstSetting::className(), ['id_branch' => 'id_branch']);
    }

    public static function getList($id_branch = null)
    {
        $models = static::find()
            ->andWhere(['id_client' => Helper::identity()->branch->id_client])
            ->andFilterWhere(['id_branch' => $id_branch])
            ->cache()
            ->all();

        return ArrayHelper::map($models, 'id_branch', 'name');
    }

    public function saveData($modelSettings)
    {
        $req = Yii::$app->request;

        $this->load($req->post());
        $this->save();

        $modelSetting = ArrayHelper::index($modelSettings, 'key', 'group');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $flag = true;
            foreach ($req->post('value') as $group => $groups) {
                if (in_array($group, [Helper::SETTING_BRANCH])) {
                    foreach ($groups as $key => $v) {
                        if (is_array($v)) {
                            $v = implode(',', $v);
                        }
                        $model = !empty($modelSetting[$group][$key]) ? $modelSetting[$group][$key] : new MstSetting();
                        if ($model->isNewRecord) {
                            $model->id_branch = $this->id_branch;
                            $model->group = $group;
                            $model->key = $key;
                        }
                        $model->value = $v;

                        $model->validate();
                        $error = $model->getErrors();
                        if ($error) {
                            echo '<pre>';
                            print_r($error);
                            die;
                        }

                        $flag = $flag && $model->save();
                        if ($flag === false) {
                            break;
                        }
                    }
                }
            }

            if ($flag) {
                $transaction->commit();
                Helper::cacheFlush();
                return [
                    'status' => 1,
                    'message' => 'Berhasil update',
                ];
            }
            return [
                'status' => 0,
                'message' => Html::errorSummary($model),
            ];
        } catch (Exception $e) {
            return [
                'status' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }
}
