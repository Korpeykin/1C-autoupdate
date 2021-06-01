<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::t('app', 'hello') ?></h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2><?= Yii::t('app', 'open_path') ?></h2>

                <h3><?= yii\helpers\Url::base(true) . '/open' ?></h3>
            </div>

            <div class="col-lg-6">
                <h2><?= Yii::t('app', 'close_path') ?></h2>

                <h3><?= yii\helpers\Url::base(true) . '/close' ?></h3>
            </div>

        </div>

    </div>
</div>