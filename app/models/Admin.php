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
class Admin extends \Kant\Database\ActiveRecord implements \Kant\Identity\IdentityInterface
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
	
	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		$row = self::find()->where([
					'username' => $username
				])->one();
		return $row;
	}

	/**
	 * Generate authKey
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Kant::$app->security->generateRandomString();
		$this->save(false);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		$row = self::find()->where([
					'id' => $id
				])->one();
		return $row;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		$row = self::find()->where([
					'access_token' => $token
				])->one();
		return $row;
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->auth_key === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password
	 *            password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Kant::$app->security->validatePassword($password, $this->password_hash);
	}
}
