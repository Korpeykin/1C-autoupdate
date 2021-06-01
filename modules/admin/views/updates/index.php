<?php

use app\modules\main\models\Konfs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use bupy7\dynafields\DynaFields;

$this->title = Yii::t('app', 'KONFS');
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="konfs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_konfsForm', [
        'model' => $model,
    ]) ?>

    <?php Pjax::begin(['id' => 'updates']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'provider_name',
                'filter' => Html::activeDropDownList($searchModel, 'provider_name', ArrayHelper::map(Konfs::find()->asArray()->all(), 'provider_name', 'provider_name'), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Provider Name')])
            ],

            [
                'attribute' => 'conf_name',
                'filter' => Html::activeDropDownList($searchModel, 'conf_name', ArrayHelper::map(Konfs::find()->asArray()->all(), 'conf_name', 'conf_name'), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Conf Name')]),
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->conf_name), ['view-products', 'id' => $model->id], ['data-pjax' => 0]);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
                // 'template' => '{update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', ['delete-konfs', 'id' => $model->id], [
                            'class' => 'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => Yii::t('products', 'delete_konf'),
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>