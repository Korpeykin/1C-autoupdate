<?php

namespace app\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "licenses".
 *
 * @property int $id
 * @property int $user_id
 * @property int $konf_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $life_time
 * @property string $login
 * @property string $password_hash
 *
 * @property Konfs $konf
 * @property User $user
 */
class Licenses extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN_CREATE = 'adminCreate';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    public $newPassword;
    public $newPasswordRepeat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'licenses';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newPassword', 'newPasswordRepeat'], 'required', 'on' => self::SCENARIO_ADMIN_UPDATE],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
            [['user_id', 'konf_id', 'life_time', 'login', 'password_hash'], 'required'],
            ['login', 'match', 'pattern' => '#^[\w_-]+$#is'],
            [['user_id', 'konf_id', 'created_at', 'updated_at'], 'integer'],
            [['login', 'password_hash'], 'string', 'max' => 255],
            [['konf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Konfs::class, 'targetAttribute' => ['konf_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('licenses', 'ID'),
            'user_id' => Yii::t('licenses', 'User'),
            'user' => Yii::t('licenses', 'User'),
            'konf_id' => Yii::t('licenses', 'Konf'),
            'konf' => Yii::t('licenses', 'Konf'),
            'created_at' => Yii::t('licenses', 'created_at'),
            'updated_at' => Yii::t('licenses', 'updated_at'),
            'life_time' => Yii::t('licenses', 'life_time'),
            'login' => Yii::t('licenses', 'login'),
            'password_hash' => Yii::t('licenses', 'Password Hash'),
            'newPassword' => Yii::t('licenses', 'LICENSES_NEW_PASSWORD'),
            'newPasswordRepeat' => Yii::t('licenses', 'LICENSES_REPEAT_PASSWORD'),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['login', 'user_id', 'konf_id', 'life_time', 'password_hash'];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['login', 'user_id', 'konf_id', 'life_time', 'newPassword', 'newPasswordRepeat'];
        return $scenarios;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->newPassword)) {
                $this->setPassword($this->newPassword);
            }
            if (!is_int($this->life_time)) {
                $this->life_time = \strtotime($this->life_time);
            }
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Konf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKonf()
    {
        return $this->hasOne(Konfs::class, ['id' => 'konf_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)                         // проверка введенного пароля
    {
        $pass_valid = Yii::$app->security->validatePassword($password, $this->password_hash);
        $time_valid = \time() > $this->life_time;
        if ($pass_valid and $time_valid) {
            return true;
        } else {
            return false;
        }
    }
}
