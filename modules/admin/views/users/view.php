<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_ADMIN'), 'url' => ['/admin/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'BUTTON_DELETE'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'ARE_YOU_SURE_TO_DELETE_USER'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Сделать юзером', ['makeuser', 'id' => $model->id], [
                                'class' => 'btn btn-success',
                                'data' => [
                                    'confirm' => 'Вы прада хотите сделать этого пользователя юзером?',
                                    'method' => 'post',
                                ],
                            ]) ?>

            <?= Html::a('Сделать модератором', ['makeeditor', 'id' => $model->id], [
                                'class' => 'btn btn-primary',
                                'data' => [
                                    'confirm' => 'Вы прада хотите сделать этого пользователя модератором?',
                                    'method' => 'post',
                                ],
                            ]) ?>

            <?= Html::a('Сделать админом', ['makeadmin', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы прада хотите дать этому пользователю все права?',
                            'method' => 'post',
                        ],
                    ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
        ],
    ]) ?>

</div>
