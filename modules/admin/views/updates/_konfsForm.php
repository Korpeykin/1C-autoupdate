<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php
$this->registerJs(
    '$("document").ready(function(){
            $("#new_konf").on("pjax:end", function() {
            $.pjax.reload({container:"#updates"});  //Reload GridView
        });
    });'
);
?>

<div class="konfs-form">
    <?php yii\widgets\Pjax::begin(['id' => 'new_konf']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <?= $form->field($model, 'provider_name', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <?= $form->field($model, 'conf_name', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'BUTTON_SAVE') : Yii::t('app', 'BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end(); ?>
</div>