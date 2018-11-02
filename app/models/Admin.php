<?php

namespace app\models;

use Kant\Kant;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property string $email
 * @property string $realname
 * @property string $logintime
 * @property string $loginip
 * @property int $is_lock
 * @property int $is_sub 是否是子账号
 * @property int $pertain 属于谁的子账号
 * @property int $created_at
 * @property int $updated_at
 */
class Admin extends \Kant\Database\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['logintime'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key', 'access_token'], 'string', 'max' => 32],
            [['email', 'realname'], 'string', 'max' => 100],
            [['loginip'], 'string', 'max' => 30],
            [['is_lock'], 'string', 'max' => 1],
            [['is_sub', 'pertain'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Kant::t('app', 'ID'),
            'username' => Kant::t('app', 'Username'),
            'password_hash' => Kant::t('app', 'Password Hash'),
            'auth_key' => Kant::t('app', 'Auth Key'),
            'access_token' => Kant::t('app', 'Access Token'),
            'email' => Kant::t('app', 'Email'),
            'realname' => Kant::t('app', 'Realname'),
            'logintime' => Kant::t('app', 'Logintime'),
            'loginip' => Kant::t('app', 'Loginip'),
            'is_lock' => Kant::t('app', 'Is Lock'),
            'is_sub' => Kant::t('app', 'Is Sub'),
            'pertain' => Kant::t('app', 'Pertain'),
            'created_at' => Kant::t('app', 'Created At'),
            'updated_at' => Kant::t('app', 'Updated At'),
        ];
    }
}
