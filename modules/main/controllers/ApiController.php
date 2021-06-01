<?php

namespace app\modules\main\controllers;

use app\modules\admin\models\Licenses;
use app\modules\main\models\Api;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `main` module
 */
class ApiController extends Controller
{
    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => \yii\filters\auth\HttpBasicAuth::class,
                'only' => ['close'],
                'auth' => function ($login, $password) {
                    $license = Licenses::find()->where(['login' => $login])->one();
                    if ($license->validatePassword($password)) {
                        return true;
                    }
                    return null;
                },
                'realm' => Yii::$app->name
            ],
        ];
    }
    public function actionOpen()
    {
        try {
            $model = new Api();
            $response = $model->giveOpenData();
            switch ($response['res_name']) {
                case 'txt':
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    \Yii::$app->response->headers->add('Content-Type', 'text/plain');
                    return $this->renderFile($response['data']);
                    break;
                case 'htm':
                    return $this->renderFile($response['data']);
                    break;
                case 'xml':
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    \Yii::$app->response->headers->add('Content-Type', 'application/zip');
                    return $this->renderFile($response['data']);
                    break;
                default:
                    throw new NotFoundHttpException('Bad api request');
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Bad api request');
        }
    }

    public function actionClose()
    {
        try {
            $model = new Api();
            return Yii::$app->response->sendFile($model->giveCloseData());
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Bad api request');
        }
    }
}
