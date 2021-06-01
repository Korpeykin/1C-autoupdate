<?php

namespace app\modules\user\forms;

use yii\base\Model;
use Yii;
use app\modules\user\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],


            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->status = User::STATUS_WAIT;
        $user->generateAuthKey();
        $user->generateEmailConfirmToken();
        $send_ok = Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Подтверждение адреса эл. почты для ' . Yii::$app->name)
            ->send();
        if ($send_ok) {
            if ($user->save()) {
                return $user;
            } else {
                throw new \yii\web\HttpException(500, 'Произошла ошибка сохранения данных в базу!');
            }
        } else {
            throw new \yii\web\HttpException(500, 'Произошла ошибка отправки письма с нашего сайта, обратитесь в поддержку!');
        }
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Докажите, что вы не робот!',
            'username' => 'Логин',
            'email' => 'Адрес вашей электронной почты:',
            'password' => 'Пароль',

        ];
    }
}
