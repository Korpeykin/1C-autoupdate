<?php

namespace app\modules\admin\forms;

use app\modules\admin\models\Licenses;
use app\modules\admin\models\User;
use Yii;
use yii\base\Model;

class LicensesForm extends Model
{
    public $id;
    public $user_id;
    public $konf_id;
    public $life_time;
    public $login;
    public $password;

    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'required'],
            ['login', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['login', 'unique', 'targetClass' => Licenses::class, 'message' => Yii::t('licenses', 'login_exists')],
            ['login', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['user_id', 'konf_id', 'life_time'], 'required'],
            [['user_id', 'konf_id'], 'integer'],
            // [['life_time'], 'datetime']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('licenses', 'ID'),
            'user_id' => Yii::t('licenses', 'User'),
            'konf_id' => Yii::t('licenses', 'Konf'),
            'life_time' => Yii::t('licenses', 'life_time'),
            'login' => Yii::t('licenses', 'login'),
            'password' => Yii::t('licenses', 'password'),
        ];
    }

    public function addNewLicense()
    {
        try {
            $license = new Licenses();
            $license->scenario = Licenses::SCENARIO_ADMIN_CREATE;
            $license->user_id = $this->user_id;
            $license->konf_id = $this->konf_id;
            $license->life_time = $this->life_time;
            $license->login = $this->login;
            $license->setPassword($this->password);
            $user = User::findOne($license->user_id);
            $license->save();
            if ($license->hasErrors()) {
                debug($license->errors);
                exit;
            }
            Yii::$app->mailer->compose('@app/modules/admin/mails/emailConfirm', ['license' => $license, 'user' => $user, 'pass' => $this->password])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Лицензии ' . Yii::$app->name)
                ->send();
            $this->id = $license->id;
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException(500, $th);
        }
    }
}
