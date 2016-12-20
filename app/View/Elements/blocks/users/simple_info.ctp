<?php 
        $user_id = $this->Rumahku->filterEmptyField($user, 'User', 'id');
?>
<div id="simple-info">
    <div class="quick-response">
        <div id="user-action">
            <?php 
                    echo $this->element('blocks/users/profile', array(
                        'User' => $user,
                    ));
                    echo $this->UploadForm->loadUser($this->Html->url(array(
                        'controller' => 'ajax',
                        'action' => 'profile_photo',
                        $user_id,
                        'admin' => false
                    )));
            ?>
        </div>
    </div>
</div>