<?php

namespace app\modules\user\models;

use app\modules\main\models\Konfs;
use Yii;

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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'licenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'duration' => Yii::t('licenses', 'duration'),
        ];
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

    public function getDuration()
    {
        $lessTime = $this->life_time - \time();
        if ($lessTime < 0) {
            return Yii::t('licenses', 'no_duration');
        }
        return $this->secondsToTime($this->life_time - \time());
    }

    protected function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('Дней: %a, часов: %h, минут: %i, секунд: %s');
    }
}
