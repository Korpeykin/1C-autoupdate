<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Licenses */

$this->title = Yii::t('licenses', 'TITLE_VIEW_LICENSE', [
    'name' => $model->user->username,
]);
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('licenses', 'Licenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
\yii\web\YiiAsset::register($this);
?>
<div class="licenses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('licenses', 'delete_license'),
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'MM.dd.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm::ss',
        ],
        'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}.</td></tr>",
        'attributes' => [
            'user.username',
            'konf.conf_name',
            'created_at:datetime',
            'updated_at:datetime',
            'life_time:datetime',
            'login',
        ],
    ]) ?>

</div>