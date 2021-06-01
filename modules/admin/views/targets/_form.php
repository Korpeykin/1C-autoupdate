<?php

use app\modules\admin\models\Konfs;
use app\modules\admin\models\Products;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Targets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="targets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'konf_id', ['inputOptions' => ['autocomplete' => 'off']])->dropDownList(
        ArrayHelper::map(Konfs::find()->all(), 'id', 'conf_name'),
        [
            'prompt' => Yii::t('products', 'setProduct'),
            'class' => 'form-control'
        ]
    ) ?>

    <?= $form->field($model, 'target', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>