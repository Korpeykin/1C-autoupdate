<?php

use app\modules\admin\models\Konfs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('licenses', 'Licenses');
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="licenses-index">

    <h1><?= Html::encode($this->title) ?></h1>


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
                'attribute' => 'konf',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->konf->conf_name), ['view', 'id' => $model->id], ['data-pjax' => 0]);
                },
                'filter' => ArrayHelper::map(Konfs::find()->asArray()->all(), 'conf_name', 'conf_name'),
            ],

            // 'life_time:datetime',
            [
                'attribute' => 'life_time',
                'format' => 'datetime',
                'filter' => false,
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['style' => 'background-color:'
                        . ($model->life_time > \time()
                            ? '#30c330' : 'e03f3f')];
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;'],
                'template' => '{view}',
            ],
        ],
    ]); ?>


</div>