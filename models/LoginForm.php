<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $name;
    public $rememberMe = true;

    private $_user;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/ui', 'message' => 'только латинские буквы и цифры'],
            [['name'], 'required'],
            [['name'], 'string', 'min'=> 3, 'tooShort' => 'Не менее 3 символов', 'max'=> 100],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if (null === $user) {//register
                $user = new User();
                $user->scenario = User::SCENARIO_CREATE;
                $user->name = $this->name;
                $user->validate();
                $user->save();
            }

            //update
            $user->scenario = User::SCENARIO_UPDATE;
            $user->save();

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->_user = User::findByUsername($this->name);
        }

        return $this->_user;
    }
}
