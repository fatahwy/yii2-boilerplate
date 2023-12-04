<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 * @property AuthRule $ruleName
 */
class AuthItem extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['route_menu', 'description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return ActiveQuery
     */
    public function getAuthAssignments() {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren]].
     *
     * @return ActiveQuery
     */
    public function getAuthItemChildren() {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren0]].
     *
     * @return ActiveQuery
     */
    public function getAuthItemChildren0() {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * Gets query for [[Children]].
     *
     * @return ActiveQuery
     */
    public function getChildren() {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * Gets query for [[Parents]].
     *
     * @return ActiveQuery
     */
    public function getParents() {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * Gets query for [[RuleName]].
     *
     * @return ActiveQuery
     */
    public function getRuleName() {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    public static function getList($role = null) {
        $models = static::find()
                ->where(['type' => 1])
                ->all();

        $listRole = ArrayHelper::map($models, 'name', 'name');

        if ($role) {
            return !empty($listRole[$role]) ? $listRole[$role] : null;
        }

        return $listRole;
    }

}
