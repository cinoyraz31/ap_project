<?php 
        echo $this->element('blocks/users/tabs');
        echo $this->Form->create('User', array(
            'url' => array(
                'controller' => 'users', 
                'action' =>'forgotpassword',
                'admin' => false,
            ), 
        ));
?>
<div class="login-form">
    <div class="form-group">
        <div class="input-group">
            <?php 
                    echo $this->Html->tag('div', $this->Rumahku->icon('envelope'), array(
                        'class' => 'input-group-addon',
                    ));
                    echo $this->Form->input('forgot_email', array(
                        'label' => false,
                        'placeholder' => __('Email Anda'),
                        'required' => false,
                        'div' => false,
                        'class' => 'form-control',
                    ));
            ?>
        </div>
        <?php 
                echo $this->Form->error('forgot_email');
        ?>
    </div>
    <div class="form-group">
        <?php
                echo $this->Form->button(__('Kirim'), array(
                    'type' => 'submit', 
                    'class'=>'btn btn-block text-white',
                ));
        ?>
    </div>
</div>
<?php echo $this->Form->end() ?>