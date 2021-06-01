<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Security;

class Products extends \app\modules\main\models\Products
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['konf_version', 'redaction_num', 'platform_version'], 'required'],
            // ['konf_version', 'match', 'pattern' => '/^\d(?:_\d)+$/u', 'message' => Yii::t('products', 'KONF_PATTERN')],
            ['konf_version', 'unique', 'targetClass' => self::class, 'message' => Yii::t('products', 'KONF_EXISTS')],
        ]);
    }
}
