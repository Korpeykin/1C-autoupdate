<?php

namespace app\modules\user\controllers;

use app\modules\user\forms\EmailConfirmForm;
use app\modules\user\forms\LoginForm;
use app\modules\user\forms\PasswordResetRequestForm;
use app\modules\user\forms\PasswordResetForm;
use app\modules\user\forms\SignupForm;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use app\modules\admin\models\FilesUpload;
use yii\web\UploadedFile;
use app\modules\user\models\User;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {                // если пользак уже залогинен возвращаем на домашнюю страницу
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {          // если что то ввели провереяем и логиним
            return $this->goBack();
        } else {
            return $this->render('login', [                                         // или рендерим форму заполнения логина
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /* public function actionSignup()                  // экшн отображения страницы регистрации
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) and $model->validate()) {
            if ($model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Подтвердите ваш электронный адрес.');
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    } */

    public function actionEmailConfirm($token)              //проверяем емайл
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно подтверждён.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }

        return $this->goHome();
    }

    public function actionPasswordResetRequest()            //контроллер запроса на смену пароля
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Спасибо! На ваш Email было отправлено письмо со ссылкой на восстановление пароля.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Извините. У нас возникли проблемы с отправкой.');
            }
        }

        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }

    public function actionPasswordReset($token)                     // экшн на который введет ссылка посланная на почту, передает токин сброса
    {
        try {
            $model = new PasswordResetForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

            return $this->goHome();
        }

        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        return $this->redirect(['profile/index'], 301);
    }

    public function actionDeleteFiles($img_id, $img_name)                         //экшн для удаления фоток из картик инпута
    {
        $path = __DIR__ . '/../../../' . Yii::$app->params['users.profile.photos'];
        $user = $this->findModel();                                    //подчищаем названия файлов в базе, в обоих строках
        if (file_exists($path . $user->user_pic)) {
            unlink($path . $user->user_pic);
            $user->user_pic = null;
            $user->save();
        }
        return '{}';                                                               //надо вернуть это, чтобы картик инпут не вываливался в ошибку
    }
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }
}
