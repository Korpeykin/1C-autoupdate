<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

$this->title = \str_replace('_', '.', $model->konf_version);
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'NAV_ADMIN')), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'KONFS')), 'url' => ['/admin/updates']];
$this->params['breadcrumbs'][] = ['label' => Html::encode(Yii::t('app', 'Products')), 'url' => ['/admin/updates/view-products', 'id' => $model->konf_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="konfs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'viewProduct']); ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'konfs.provider_name',
            'konfs.conf_name',
            [
                'attribute' => 'konf_version',
                'format' => 'raw',
                'value' => function ($model) {
                    return \str_replace('_', '.', $model->konf_version);
                }
            ],
            [
                'attribute' => 'xmls.body',
                'format' => 'text',
            ],
            [
                'attribute' => 'txts.body',
                'format' => 'text',
            ],
            [
                'attribute' => 'zip',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        Html::encode($model->zip),
                        ['update-file', 'fileName' => $model->zip, 'id' => $model->id],
                        [
                            'title' => 'Update File',
                            'data-toggle' => 'modal',
                            'data-target' => '#modalfile',
                        ]
                    );
                }
            ]
        ],
    ]) ?>
    <?php Pjax::end(); ?>
    <?= $this->render('_htmUpdate', [
        'model' => $htmModel,
    ]) ?>
</div>

<div class="modal remote fade" id="modalfile">
    <div class="modal-dialog">
        <div class="modal-content loader-lg"></div>
    </div>
</div>