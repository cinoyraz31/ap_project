<?php
     echo $this->Form->create('User');
?>

    <div class="container">
        <div class="col-md-4 bg-login centered">
            <div class="row">
                <div class="col-md-6 centered">
                    <?php
                        echo $this->Html->tag('h1', __('KPR BTN'));
                    ?>
                </div>
                <div class="col-md-11 centered">
                    <hr>
                </div>
            </div>
            <div class="h15"></div>
            <div class="row">
                <div class="col-md-11 input-icon centered">
                    <?php
                        $label = __('Password Baru');
                        echo $this->Rumahku->icon(false, $this->Rumahku->icon('rv4-lock'), 'span');
                        echo $this->Form->input('new_password', array(
                            'type' => 'password',
                            'placeholder' => $label,
                            'label' => false,
                            'required' => false,
                            'div' => false,
                        ));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-11 input-icon centered">
                    <?php
                        $label = __('Konfirmasi Password');
                        echo $this->Rumahku->icon(false, $this->Rumahku->icon('rv4-lock'), 'span');
                        echo $this->Form->input('new_password_confirmation', array(
                            'type' => 'password',
                            'placeholder' => $label,
                            'label' => false,
                            'required' => false,
                            'div' => false,
                        ));
                    ?>
                </div>
            </div>
            <div class="h15"></div>
            <div class="row">
                <div class="col-md-11 centered">
                    <?php
                        echo $this->Form->button(__('Reset'), array(
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