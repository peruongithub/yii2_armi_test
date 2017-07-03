<?php
use \yii\bootstrap\Html;
use \yii\bootstrap\ActiveForm;
?>
<?php $form =  ActiveForm::begin([
    'action' => ['logout'],
    'method' => 'post',
    'options' => ['class' => 'form-inline', 'data-pjax' => true]
])?>
<?= Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>