<?php

use app\modules\admin\models\Konfs;
use app\modules\admin\models\Products;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\searchModels\TargetsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('products', 'Targets');
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="targets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('products', 'Create_Targets'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'konf_name',
                'value' => 'konfs.conf_name',
                'filter' => ArrayHelper::map(Konfs::find()->all(), 'conf_name', 'conf_name'),
                'options' => ['width' => '300'],
            ],

            'target',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        if (!Products::findOne(['konf_version' => $model->target])) {
                            return Html::a('', ['delete', 'id' => $model->id], [
                                'class' => 'glyphicon glyphicon-trash',
                                'data' => [
                                    'confirm' => Yii::t('products', 'delete_version'),
                                ],
                            ]);
                        }
                        return null;
                    },
                    'update'  => function ($url, $model, $key) {
                        if (!Products::findOne(['konf_version' => $model->target])) {
                            return Html::a('', ['update', 'id' => $model->id], [
                                'class' => 'glyphicon glyphicon-edit',
                            ]);
                        }
                        return null;
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>