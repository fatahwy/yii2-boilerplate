<?php

namespace app\models;

use app\components\Helper;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class User extends Account implements IdentityInterface
{

    public $authKey;
    public $accessToken;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->where(['user_id' => $id, 'status' => Helper::STAT_ACTIVE, 'is_deleted' => Helper::STAT_INACTIVE])
            ->cache()
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where(['username' => $username, 'status' => Helper::STAT_ACTIVE, 'is_deleted' => Helper::STAT_INACTIVE])
            ->cache()
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password_hash === md5($password);
    }
}
