<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'options' => [
        'id' => 'dynamic-form'
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><?= Yii::t('products', 'update_file') ?></h4>
</div>
<div class="modal-body">
    <?php echo $form->field($model, 'file')->widget(FileInput::class, [
        'options' => ['accept' => 'application/zip'],
    ]);
    echo Html::a(
        Html::encode($fileName),
        ['download-zip', 'fileName' => $fileName],
        ['class' => 'btn btn-info']
    );
    ?>

</div>
<div class="modal-footer">
    <?php echo Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'BUTTON_CLOSE') ?></button>
</div>
<?php ActiveForm::end(); ?>