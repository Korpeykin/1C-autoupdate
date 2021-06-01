<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Targets */

$this->title = Yii::t('products', 'Update_Targets', [
    'name' => \str_replace('_', '.', $model->target),
]);
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'Products')), 'url' => ['/admin/updates/view-products', 'id' => $model->konf_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'Targets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => \str_replace('_', '.', $model->target), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="targets-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>