<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
// use bootstart\form\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;

$this->registerJs(
    '$("document").ready(function(){
            $("#new_products").on("pjax:end", function() {
            $.pjax.reload({container:"#products"});
        });
    });'
);
?>

<div class="konfs-form">
    <?php yii\widgets\Pjax::begin(['id' => 'new_products']) ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'text-products-inline',
        'options' => ['data-pjax' => true],
    ]);
    // echo $form->errorSummary($model);
    ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'konf_version', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control', /* 'type' => 'number' */]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'redaction_num', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control', 'type' => 'number']) ?>
        </div>
        <div class="col-md-4 mb">
            <?= $form->field($model, 'platform_version', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['maxlength' => true, 'class' => 'form-control', 'type' => 'number']) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php

            echo $form->field($model, 'targets')->widget(Select2::class, [
                // 'name' => 'kv_theme_bootstrap_2',
                'data' => $model->TargetsDropdown,
                // 'attribute' => 'targets',
                // 'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => Yii::t('products', 'drop_placeholder'), 'multiple' => true, 'autocomplete' => 'off'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>

        </div>
        <div class="col-sm-6">
            <?= Html::a('', ['targets/index'], ['data-pjax' => 0, 'class' => 'glyphicon glyphicon-open']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'file')->widget(FileInput::class, [
                'options' => ['accept' => 'application/zip'],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-primary mr-1']) ?>
            <?= Html::resetButton(Yii::t('app', 'RESET'), ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end(); ?>
    <br>
</div>