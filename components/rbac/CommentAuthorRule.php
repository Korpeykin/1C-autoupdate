<?php

namespace app\components\rbac;

use Yii;
use yii\rbac\Rule;

class CommentAuthorRule extends Rule
{
    public $name = 'isCommentAuthor';

    /**
     * @param string|integer $user ID пользователя.
     * @param Item $item роль или разрешение с которым это правило ассоциировано
     * @param array $params параметры, переданные в ManagerInterface::checkAccess(), например при вызове проверки
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */

    public function execute($user, $item, $params)
    {
        return isset($params['comments']) ? $params['comments']->createdBy == $user : false;
    }
}
