<?php

use app\modules\admin\models\Konfs;
use app\modules\admin\models\Licenses;
use app\modules\admin\models\User;
use kartik\datetime\DateTimePicker;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Licenses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="licenses-form">

    <?php
    $form = ActiveForm::begin();
    $security = new Security();
    if (empty($model->password) and empty($model->login)) {
        $model->password = $security->generateRandomString(12);
        $model->login = $security->generateRandomString(6);
    }
    if (!empty($model->life_time)) {
        $model->life_time = Yii::$app->formatter->asDateTime($model->life_time, 'dd.MM.yyyy HH:mm');
    }
    ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(User::findAll(['status' => User::STATUS_ACTIVE]), 'id', 'username'),
        [
            'prompt' => Yii::t('licenses', 'set_user')
        ]
    ) ?>

    <?= $form->field($model, 'konf_id')->dropDownList(
        ArrayHelper::map(Konfs::find()->all(), 'id', 'conf_name'),
        [
            'prompt' => Yii::t('licenses', 'set_konf')
        ]
    ) ?>

    <?= $form->field($model, 'life_time')->widget(DateTimePicker::class, [
        'type' => DateTimePicker::TYPE_BUTTON,
        'layout' => '{picker} {remove} {input}',
        'options' => [
            'type' => 'text',
            'readonly' => true,
            'class' => 'text-muted small',
            'style' => 'border:none;background:none',
        ],
        'value' => null,
        'readonly' => true,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii',
            'todayHighlight' => true,
            'startDate' => 'd',
        ]
    ]) ?>

    <?= $form->field($model, 'login', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <?php
    if ($model::className() == 'app\modules\admin\forms\LicensesForm') {
        echo $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']);
    }
    if ($model->scenario == Licenses::SCENARIO_ADMIN_UPDATE) {
        echo $form->field($model, 'newPassword', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']);
        echo $form->field($model, 'newPasswordRepeat', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']);
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>