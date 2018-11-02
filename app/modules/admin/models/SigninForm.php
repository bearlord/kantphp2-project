<?php

namespace app\modules\admin\models;

use Kant\Kant;
use Kant\Model\Model;
use Kant\Identity\User;
use app\models\Admin;

class SigninForm extends Model
{

    public $username;

    public $password;

    public $verifyCode;
    
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password' ], 'required' ],
            ['password', 'string', 'length' => [6, 14]],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha', 'captchaAction' => '/common/service/captcha']
        ];
    }

    /**
     * attributeLabels
     *
     * @return type
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifyCode' => '验证码'
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute
     *            the attribute currently being validated
     * @param array $params
     *            the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (! $this->hasErrors()) {
            $user = $this->getUser();
            if (! $user || ! $user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        Kant::$app->admin->on(User::EVENT_BEFORE_LOGIN, [
            $this->getUser(),
            'generateAuthKey'
        ]);
        return Kant::$app->admin->login($this->getUser(), 60);
    }
}