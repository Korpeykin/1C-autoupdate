<?php

namespace app\modules\user\forms;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use Yii;
use app\modules\user\models\User;

class EmailConfirmForm extends Model
{
    /**
     * @var User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        parent::__construct($config);
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Отсутствует код подтверждения.');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Неверный токен.');
        }
    }

    /**
     * Confirm email.
     *
     * @return boolean if email was confirmed.
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('user');
        $auth->assign($role, $user->id);
        
        return $user->save();
    }
}
