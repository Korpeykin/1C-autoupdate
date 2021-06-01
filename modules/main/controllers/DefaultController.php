<?php

namespace app\modules\main\controllers;

use app\modules\main\models\MainCaruselImages;
use app\modules\main\models\Posts;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
 
    public function actionIndex()
    {
        return $this->render('index');
    }
}
