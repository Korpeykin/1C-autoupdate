<?php

use yii\helpers\Html;
use app\modules\admin\models\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username'/* , ['inputOptions' => ['autocomplete' => 'off']] */)->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($model, 'status')->dropDownList(User::getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
