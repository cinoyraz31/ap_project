<?php
     echo $this->Form->create('User');
     $greeting_text = $this->Rumahku->get_greeting_by_time();
?>

    <div class="container">
        <div class="col-md-4 bg-login centered">
            <div class="row">
                <div class="col-md-6 centered">
                    <?php
                        echo $this->Html->tag('h1', __('Your Email '));
                    ?>
                </div>
                <div class="col-md-11 centered">
                    <hr>
                </div>
            </div>
            <div class="h15"></div>

            <?php
                echo $this->Html->tag('p', __('Lengkapi data dibawah untuk memperoleh kembali akun Anda.'));
            ?>

            <div class="row">
                <div class="col-md-11 input-icon centered">
                    <?php
                        echo $this->Rumahku->icon(false, $this->Rumahku->icon('rv4-user'), 'span');
                        echo $this->Form->input('forgot_email', array(
                            'type' => 'text',
                            'placeholder' => __('alamat email anda'),
                            'label' => false,
                            'required' => false,
                        ));
                    ?>
                </div>
            </div>
            <div class="h15"></div>
            <div class="row">
                <div class="col-md-8">
                    <?php
                        echo $this->Form->button(__('Kirim'), array(
                            'type' => 'submit', 
                            'class'=> 'btn blue',
                        ));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                        echo $this->Html->link(__('Kembali'), array(
                            'action' => 'login',
                            'admin' => true
                        ), array(
                            'class'=> 'btn default',
                        ));
                    ?>
                </div>
            </div>
        </div> 
    </div>

<?php
    echo $this->Form->end();
?>