<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\ArrayHelper;

class Konfs extends \app\modules\main\models\Konfs
{
    // const SCENARIO_ADMIN_CREATE = 'adminCreate';
    // const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            // [['newPassword', 'newPasswordRepeat'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            ['conf_name', 'unique', 'targetClass' => self::class, 'message' => Yii::t('app', 'KONF_EXISTS')],
        ]);
    }
}
