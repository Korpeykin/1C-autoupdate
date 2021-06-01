<?php

namespace app\modules\user\forms;
 
use app\modules\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([                         // изем пользака по статусы и емайлу
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;                               // если такого пользака нет возвращаем фальсе
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {    // если токен в БД не действительный - делаем новый
            $user->generatePasswordResetToken();
            if (!$user->save()) {                                               // если сохранение прошло плохо возвращаем фальсе
                return false;
            }
        }

        return Yii::$app                                                        // если все до этого прошло норм высылаем письмо с ссылкой на сброс пароля
            ->mailer
            ->compose(
                '@app/modules/user/mails/passwordReset',
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Смена пароля для ' . Yii::$app->name)
            ->send();
    }
}
