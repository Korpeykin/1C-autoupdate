<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Licenses */

$this->title = Yii::t('licenses', 'update_license', [
    'name' => $model->user->username,
]);
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('licenses', 'Licenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="licenses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>