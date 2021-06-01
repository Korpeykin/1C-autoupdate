<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Yii::t('app', 'TITLE_CREATE_USER');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_ADMIN'), 'url' => ['/admin/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
