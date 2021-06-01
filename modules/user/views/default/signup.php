<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'TITLE_SIGNUP');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php
            $form = ActiveForm::begin(['id' => 'form-signup']);
            echo $form->errorSummary($model);
            ?>
                <?= $form->field($model, 'username', [
                    'inputOptions' => ['autocomplete' => 'off'],
                ])->textInput([
                    'placeholder' => 'Логин',
                    'title' => 'Логин',
                ]) ?>

                <?= $form->field($model, 'email', [
                    'inputOptions' => ['autocomplete' => 'off'],
                ])->textInput([
                    'placeholder' => 'E-mail',
                    'title' => 'E-mail',
                ]) ?>

                <?= $form->field($model, 'password', [
                    'inputOptions' => ['autocomplete' => 'off'],
                ])->passwordInput([
                    'placeholder' => 'Пароль',
                    'title' => 'Пароль',
                ]) ?>

                <?= $form->field($model, 'verifyCode', [
                    'inputOptions' => ['autocomplete' => 'off'],
                ])->widget(Captcha::class, [
                    'captchaAction' => '/user/default/captcha',
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'BUTTON_SIGNUP'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>