<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Targets */

$this->title = \str_replace('_', '.', $model->target);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Targets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'Products')), 'url' => ['/admin/updates/view-products', 'id' => $model->konf_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="targets-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}.</td></tr>",
        'attributes' => [
            // 'id',
            'konfs.conf_name',
            [
                'attribute' => 'target',
                'value' => function ($data) {
                    return \str_replace('_', '.', $data->target);
                }
            ],
        ],
    ]) ?>

</div>