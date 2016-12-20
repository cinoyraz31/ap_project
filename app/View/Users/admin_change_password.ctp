<?php
        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative  col-sm-6 col-xl-5',
        );

        echo $this->element('blocks/users/simple_info');
        echo $this->Form->create('User');
        echo $this->Rumahku->buildInputForm('new_password', array_merge($options, array(
            'type' => 'password',
            'label' => __('Password Baru *'),
            'autocomplete' => 'off',
        )));
        echo $this->Rumahku->buildInputForm('new_password_confirmation', array_merge($options, array(
            'type' => 'password',
            'label' => __('Konfirmasi Password Baru *'),
            'autocomplete' => 'off',
        )));
?>
<div class="row">
    <div class="col-sm-12">
        <div class="action-group bottom">
            <div class="btn-group floright">
                <?php
                        echo $this->Form->button(__('Simpan'), array(
                            'type' => 'submit', 
                            'class'=> 'btn blue',
                        ));
                ?>
            </div>
        </div>
    </div>
</div>

<?php 
        echo $this->Form->end(); 
?>