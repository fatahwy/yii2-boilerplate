<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mst_setting".
 *
 * @property int $id_setting
 * @property int $id_branch
 * @property string $group
 * @property string $key
 * @property string $value
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property MstBranch $branch
 */
class MstSetting extends SecureModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_branch', 'group', 'key'], 'required'],
            [['id_branch'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['group', 'key', 'value'], 'string', 'max' => 255],
            [['id_branch', 'group', 'key'], 'unique', 'targetAttribute' => ['id_branch', 'group', 'key']],
            [['id_branch'], 'exist', 'skipOnError' => true, 'targetClass' => MstBranch::className(), 'targetAttribute' => ['id_branch' => 'id_branch']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_setting' => 'Id Setting',
            'id_branch' => 'ID Cabang',
            'group' => 'Group',
            'key' => 'Key',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    public static function getAll($id_branch)
    {
        return self::find()
            ->where(['id_branch' => $id_branch])
            ->cache()
            ->all();
    }

    public static function get($id_branch)
    {
        return ArrayHelper::map(self::getAll($id_branch), 'key', 'value', 'group');
    }
}
