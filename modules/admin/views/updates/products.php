<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="konfs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_productsForm', [
        'model' => $model,
    ]) ?>

    <?php Pjax::begin(['id' => 'products']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'konf_version',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->konf_version), ['view', 'id' => $model->id], ['data-pjax' => 0]);
                }
            ],

            [
                'attribute' => 'redaction_num',
            ],

            [
                'attribute' => 'platform_version',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', ['delete-product', 'id' => $model->id], [
                            'class' => 'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => Yii::t('products', 'delete_product'),
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>