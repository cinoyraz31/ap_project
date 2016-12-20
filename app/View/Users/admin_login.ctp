<?php
     echo $this->Form->create('User');
     $greeting_text = $this->Rumahku->get_greeting_by_time();
?>

    <div class="container">
        <div class="col-md-4 bg-login centered">
            <div class="row">
                <div class="col-md-6 centered">
                    <?php
                        echo $this->Html->tag('h1', __('Your Login'));
                    ?>
                </div>
                <div class="col-md-11 centered">
                    <hr>
                </div>
            </div>
            <div class="h15"></div>

            <?php
                echo $this->Html->tag('h3', $greeting_text);
                echo $this->Html->tag('p', __('Masuk ke akun anda lewat sini'));
            ?>

            <div class="row">
                <div class="col-md-11 input-icon centered">
                    <?php
                        echo $this->Rumahku->icon(false, $this->Rumahku->icon('rv4-user'), 'span');
                        echo $this->Form->input('email', array(
                            'type' => 'text',
                            'placeholder' => __('alamat email anda'),
                            'label' => false,
                            'required' => false,
                        ));
                    ?>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-11 input-icon centered">
                    <?php
                        echo $this->Rumahku->icon(false, $this->Rumahku->icon('rv4-lock'), 'span');
                        echo $this->Form->input('password', array(
                            'placeholder' => __('password anda'),
                            'label' => false,
                            'required' => false,
                        ));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 pull-right">
                    <?php
                        echo $this->Html->link(__('Lupa Password?'), array(
                            'action' => 'forgotpassword',
                            'admin' => true
                        ));
                    ?>
                </div>
            </div>
            <div class="h15"></div>
            <div class="row">
                <div class="col-md-11 centered">
                    <?php
                        echo $this->Form->button(__('Masuk'), array(
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