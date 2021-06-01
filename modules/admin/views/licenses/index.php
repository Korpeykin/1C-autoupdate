<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\searchModels\LicensesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('licenses', 'Licenses');
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="licenses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('licenses', 'Create_Licenses'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'MM.dd.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',

        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user',
                'value' => 'user.username'
            ],
            [
                'attribute' => 'konf',
                'value' => 'konf.conf_name'
            ],

            'life_time:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>