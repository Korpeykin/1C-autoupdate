<?php

namespace app\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\modules\main\models\Posts;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $username
 * @property string|null $auth_key
 * @property string|null $email_confirm_token
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_PROFILE = 'profile';

    const STATUS_BLOCKED = 0;                                               // статус заблокирован
    const STATUS_ACTIVE = 1;                                                // статус активен
    const STATUS_WAIT = 2;                                                  // статус ожидает активации (активация будет после перехода по ссылке, отправленной по почте)

    public function behaviors()
    {
        return [
            TimestampBehavior::class,                                 // занесение временной метки создания/изменения пользователя
        ];
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_PROFILE => ['email'],
        ]);
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],                   // проверка чтобы юзер нейм был только из вот эти вот символов/букв
            ['username', 'unique', 'targetClass' => self::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 3, 'max' => 40],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::class, 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'max' => 255],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],      //проверка чтобы статус был равен одному из ключей массива
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()                                               //перевод названий столбцов таблицы
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'status' => 'Статус',
        ];
    }

    public static function findByEmailConfirmToken($email_confirm_token)        //найти запись(юзера) в БД по емайл-токину
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()                                  //сгенерировать емайл-токин
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()                                   //удалить (обнулить) емайл-токин
    {
        $this->email_confirm_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)             //найти юзера по токену сброса пароля
    {
        if (!static::isPasswordResetTokenValid($token)) {               //если токин не действителен (истекло время жизни)
            return null;                                                //возвращаем путой результат
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)            // проверка на действительность токена сброса пароля
    {
        if (empty($token)) {                                            // проверяем пришедший в функцию токен (переменную) на заполненность
            return false;                                               // если пришла пустая переменная возвращаем ложь
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];   // вытягиваем в переменную значение жизни токина из параметров йии
        $parts = explode('_', $token);                                  // разбиваем полученный токин по символу '_', в конце токена после "_" записано время, когда ключ был сгенерирован
        $timestamp = (int) end($parts);                                 // в новую переменную пишем крайний элемент массива партс и приводим ее к численному типу данных
        return $timestamp + $expire >= time();                          // если время создания токина + время жизни больше или равно текущего времени, возвращается тру, иначе фалс
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()                        // генерируется токин сброса пароля
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();   // токин = рандомнаястрока_временнаяметка
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()                          // обнулить токин сброса пароля
    {
        $this->password_reset_token = null;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);    // хеширует (кодирует) введенный пароль
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()                                   // генерирует "запомнить меня" ключ
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)                                // если вызвать метод save() от этой модели, то перед сохранением будет вызван метод generateAuthKey()
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {                                             // если это новая запись
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    public static function findByUsername($username)                    // найти юзера по юзернейму
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)                         // проверка введенного пароля
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findIdentity($id)                            // найти юзера по айди и статусу активен
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getNotActiveUsers()
    {
        $users = self::find()->where(['status' => User::STATUS_WAIT])
            ->andWhere(['<', 'created_at', time() - Yii::$app->params['user.emailConfirmTokenExpire']]);
        return $users;
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Ожидает подтверждения',
        ];
    }
}
