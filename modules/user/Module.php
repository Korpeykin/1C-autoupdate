<?php

namespace app\modules\user;

use yii\filters\AccessControl;

/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\user\controllers';

    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'rules' => [
    //                 [
    //                     'allow' => true,
    //                     'roles' => ['user'],
    //                 ],
    //             ],
    //         ],
    //     ];
    // }


    public function init()
    {
        parent::init();
    }
}
