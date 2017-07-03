<?php
use \yii\widgets\ListView;
?>
<?= ListView::widget([
    'emptyText' => 'Список пуст',
    'itemView' => '_user_row',
    'itemOptions' => [
        'tag' => 'li',
        'class' => 'media',
    ],
    'options' => [
        'tag' => 'ul',
        'class' => 'media-list'
    ],
    'layout' => '{items}',
    'dataProvider' => $users
]) ?>

