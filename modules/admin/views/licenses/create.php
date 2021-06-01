<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Licenses */

$this->title = Yii::t('licenses', 'Create_Licenses');
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('licenses', 'Licenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="licenses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>