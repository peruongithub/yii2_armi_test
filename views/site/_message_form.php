<?php
use \yii\bootstrap\Html,
    \yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'action' => ['message'],
    'method' => 'post',
    'options' => ['class' => 'form-inline', 'data-pjax' => true]
]) ?>
<?= $form->field($message,'message')->textarea(['placeholder' => 'Enter Message','class' => 'form-control'])?>
<?= Html::submitButton('SEND',['class' => 'btn btn-default submit']) ?>
<?php ActiveForm::end() ?>
