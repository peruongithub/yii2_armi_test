<?php
use \yii\bootstrap\Html;
use \yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => ['login'],
    'method' => 'post',
    'options' => ['class' => 'form-inline', 'data-pjax' => true]
]) ?>

    <?= $form->field($user, 'name')->textInput([
        'placeholder' => Yii::$app->user->isGuest ?
            'Enter you name':
            Yii::$app->user->identity->username,
        'minlength' => 3,
        'maxlength' => 30,
        'autofocus' => Yii::$app->user->isGuest,
        'class' => Yii::$app->user->isGuest ?
            'form-control' :
            'form-control disabled'
    ]) ?>
    <?= Html::submitButton('JOIN', [
        'class' => Yii::$app->user->isGuest ?
            'btn btn-primary' :
            'btn btn-primary hidden'
    ]) ?>
<?php ActiveForm::end() ?>
