<?php

namespace app\modules\admin\models;

use yii\helpers\ArrayHelper;
use Yii;

class User extends \app\modules\user\models\User
{
    const SCENARIO_ADMIN_CREATE = 'adminCreate';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    public $newPassword;
    public $newPasswordRepeat;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['newPassword', 'newPasswordRepeat'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
        ]);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['username', 'email', 'status', 'newPassword', 'newPasswordRepeat'];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['username', 'email', 'status', 'newPassword', 'newPasswordRepeat'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'newPassword' => Yii::t('app', 'USER_NEW_PASSWORD'),
            'newPasswordRepeat' => Yii::t('app', 'USER_REPEAT_PASSWORD'),
        ]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->newPassword)) {
                $this->setPassword($this->newPassword);
            }
            return true;
        }
        return false;
    }
}
