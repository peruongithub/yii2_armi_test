<?php
use \yii\widgets\Pjax;

?>
<div class="row chat">
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                RECENT CHAT HISTORY
            </div>
            <div class="panel-body">
                <ul id="list-messages" class="media-list">

                </ul>
            </div>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <?php Pjax::begin([
                    'id' => 'new-message',
                    'enablePushState' => false
                ]) ?>
                    <?php
                        if(Yii::$app->user->isGuest){
                            echo 'Для отправки сообщений необходимо ввести свое имя >>>>';
                        }else{
                            echo $this->render('_message_form', compact('message'));
                        }
                    ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                ONLINE USERS
            </div>
            <?php Pjax::begin([
                'id' => 'list-users',
                'options' => ['class' => 'panel-body'],
                'enablePushState' => false,
                'formSelector' => false,
                'linkSelector' => false
            ]) ?>

                <?= $this->render('_active_users_list', compact('users')) ?>

            <?php Pjax::end() ?>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <?php Pjax::begin([
                    'id' => 'new-user',
                    'enablePushState' => false,
                ]) ?>
                    <?= Yii::$app->user->isGuest?
                            $this->render('_login_form', compact('user')):
                            $this->render('_logout_form', compact('user'))
                    ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs(<<<JS
$("document").ready(function(){ 
    var lastId=0;//last message id
    
    function updateMessagesList() {
        $.ajax({
        url: '',
        data: 'r=site/messages&id='+lastId,
        dataType: 'json',
        success: function(messages, textStatus){
            $.each(messages, function(i, message) {    // обрабатываем полученные данные
                var list = $('#list-messages');
                var listChildren = list.children();
                var listParent = list.parent();
                
                if(listChildren.length >= 100){
                    listChildren.first().remove();
                }
                
                list.append('<li class="media"><div class="media-body"><div class="media"><div class="media-body" >'+ 
                message.message +'<br/><small class="text-muted">'+ 
                message.user.name +' | '+ 
                message.writing +'</small><hr/></div></div></div></li>');
                
                listParent.stop().animate({scrollTop: listParent[0].scrollHeight}, 500);
                
                lastId = message.id;
            });
        }
      });
    }
    
    
    
    function updateUsersList() {
      $.pjax.reload({container: '#list-users'});
    }
    
    setInterval(updateMessagesList, 1000);
    
    setInterval(updateUsersList, 1000);
    
    //$('#new-message').on("pjax:end", updateMessagesList);
    
    $('#new-user').on("pjax:end", function(){
        updateUsersList();
        $.pjax.reload({container: '#new-message'});
    });
    
    $('#list-users').on("pjax:end", function(){
      $('#list-users').animate({scrollTop: $('#list-users')[0].scrollHeight}, 500);
    });
});
JS
);

$this->registerCss(<<<CSS
            /*This is our main wrapping element, it's made 100vh high to ensure it is always the correct size and then moved into place and padded with negative margin and padding*/
.panel-body {
  height: calc(100vh - 400px);
  overflow-y: auto; 
}
CSS
);
?>

