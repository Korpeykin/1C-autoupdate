<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Konfs */

$this->title = Yii::t('products', 'Update Konfs: {name}', [
    'name' => $model->conf_name,
]);
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="konfs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_konfsForm', [
        'model' => $model,
    ]) ?>

</div>