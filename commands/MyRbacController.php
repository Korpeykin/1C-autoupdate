<?php

namespace app\commands;

use app\components\rbac\PostAuthorRule;
use Yii;
use yii\console\Controller;

/**
 * Инициализатор RBAC выполняется в консоли php yii my-rbac/init
 */
class MyRbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа и редактора новостей
        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($user);

        // админ наследует роль редактора новостей. Он же админ, должен уметь всё! :D
        $auth->addChild($admin, $user);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);
        // $auth->assign($user, 7);
        // $auth->assign($user, 8);
        $this->stdout('Done!' . PHP_EOL);
    }

    // public function actionAddRule()
    // {
    //     $auth = Yii::$app->authManager;
    //     $user = $auth->getRole('user');
    //     $postAuthorRule = new PostAuthorRule();
    //     $auth->add($postAuthorRule);
    //     $updateOwnPosts = $auth->createPermission('updateOwnPosts');
    //     $updateOwnPosts->description = 'Редактирование собственной статьи';
    //     $updateOwnPosts->ruleName = $postAuthorRule->name;
    //     $auth->add($updateOwnPosts);
    //     $auth->addChild($user, $updateOwnPosts);
    //     $this->stdout('Done!' . PHP_EOL);
    // }
}
