<?php
use \yii\widgets\Pjax,
    \yii\bootstrap\ActiveForm,
    \yii\bootstrap\Html;
?>
<div class="chat">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
        <?php Pjax::begin([
            'id' => 'list-users',
            'enablePushState' => false,
            'formSelector' => false,
            'linkSelector' => false
        ]) ?>

        <?= $this->render(
            '_active_users_list',
            [
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => (new \yii\db\Query())
                        ->select('user_id')
                        ->from(Yii::$app->session->sessionTable)
                        ->where(
                        'DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :delta SECOND) <= last_activity',
                        [':delta' => \Yii::$app->params['userActivityInterval']]
                    ),
                    'pagination' => false,
                ])
            ]
        ) ?>

        <?php Pjax::end() ?>
    </div>
</div>
<?php ActiveForm::begin(['options' => ['class' => 'pjax-form']]) ?>

    <?= Html::activeTextarea($message, 'message') ?>
    <?= Html::submitButton('Отправить') ?>

<?php ActiveForm::end() ?>

