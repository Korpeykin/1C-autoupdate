<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => \array_filter([
                /* Yii::$app->user->isGuest ? (
                    ['label' => Yii::t('app', 'NAV_SIGNUP'), 'url' => ['/user/default/signup']]
                    ) : false, */
                Yii::$app->user->can('user') ? (['label' => Yii::t('licenses', 'Licenses'), 'url' => ['/user/licenses']]) : false,
                Yii::$app->user->can('admin') ? (['label' => Yii::t('app', 'NAV_ADMIN'), 'url' => ['/admin']]) : false,
                Yii::$app->user->isGuest ? (['label' => Yii::t('app', 'NAV_LOGIN'), 'url' => ['/user/default/login']]) : ('<li>'
                    . Html::beginForm(['/user/default/logout'], 'post')
                    . Html::submitButton(
                        Yii::t('app', 'NAV_LOGOUT') . ' (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'),
            ]),
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy;<?= Yii::$app->name . ' ' . date('Y') ?></p>

            <p class="pull-right"><?= Yii::$app->name ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>