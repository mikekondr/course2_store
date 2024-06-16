<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $fullname;

    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'fullname'], 'required'],
            // password is validated by validatePassword()
            ['username', 'validateUsername'],
        ];
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Users::findOne(['username' => $this->username]);

            if ($user) {
                $this->addError($attribute, 'User with this username already exists.');
            }
        }
    }

    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('app/users', 'Username'),
            'password' => Yii::t('app/users', 'Password'),
            'fullname' => Yii::t('app/users','Fullname'),
        ];
    }

}