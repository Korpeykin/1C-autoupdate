<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\admin\models\User;
use app\components\grid\SetColumn;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TITLE_USERS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_ADMIN'), 'url' => ['/admin/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'TITLE_CREATE_USER'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ],
                    'options' => [
                        'autocomplete' => 'off',
                    ]
                ]),
                'attribute' => 'created_at',
                'format' => ['datetime', 'h:i:s dd.MM.Y'],
            ],

            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
                }
            ],

            'email:email',
            [
                'attribute' => 'role',
                'filter' => array('admin' => "admin", 'editor' => "editor", 'user' => 'user'),
                'label' => 'Роль',
                'format' => 'text',
                'value' => function ($data) {
                    $role = Yii::$app->authManager->getRolesByUser($data->id);
                    $role = array_shift($role);
                    //debug($role);
                    return $role->name;
                },
                //'contentOptions' => [ 'style' => 'width: 12%;' ],
                'filterInputOptions' => [
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                ],
            ],
            [
                'class' => SetColumn::class,
                'filter' => User::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    User::STATUS_ACTIVE => 'success',
                    User::STATUS_WAIT => 'warning',
                    User::STATUS_BLOCKED => 'default',
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>