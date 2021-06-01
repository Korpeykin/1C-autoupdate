<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\user\models\User */

$this->title = Yii::t('app', 'NAV_ADMIN');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-default-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Html::encode(Yii::t('app', 'ADMIN_USERS')), ['users/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Html::encode(Yii::t('app', 'UPDATES_MANAGE')), ['updates/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Html::encode(Yii::t('licenses', 'LICENSES_MANAGE')), ['licenses/index'], ['class' => 'btn btn-primary']) ?>
        <!-- <?= Html::a(Html::encode('Статьи'), ['posts/index'], ['class' => 'btn btn-primary']) ?> -->
    </p>

</div>