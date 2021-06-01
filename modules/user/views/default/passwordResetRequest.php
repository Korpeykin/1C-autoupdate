<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'TITLE_REQUEST_RESET_PASSWORD');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'FILL_FIELDS_TO_LOGIN') ?></p>
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'BUTTON_SEND'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>