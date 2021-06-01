<?php

use app\modules\admin\models\Konfs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

$konf = Konfs::findOne($license->konf_id);
?>

Здравствуйте, <?= Html::encode($user->username) ?>!

<br>

Вам была выдана лицензия для конфигурации "<?= Html::encode($konf->conf_name) ?>"

<br>

Информация для подписки на обновления:
<br>
Логин: <?= Html::encode($license->login) ?>
<br>
Пароль: <?= Html::encode($pass) ?>