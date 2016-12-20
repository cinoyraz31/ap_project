<?php 
        $id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id', 0);

        echo $this->Form->create('KprApplication', array(
            'class' => 'ajax-form',
            'data-wrapper-write' => '#wrapper-modal-write',
            'data-reload' => 'true',
        ));
?>
<div id="wrapper-modal-write">
    <?php
            echo $this->Rumahku->buildInputForm('description_reject_akad', array(
                'type' => 'textarea',
                'rows' => 3,
                'frameClass' => 'col-sm-12',
                'class' => 'relative col-sm-12 col-xl-12',
            ));
            echo $this->Form->error('KprApplication.description_akad'); 
    ?>
</div>
<div class="modal-footer">
    <?php
            echo $this->Form->button(__('Batal'), array(
                'class' => 'btn default',
                'data-dismiss' => 'modal',
            ));

            echo $this->Form->button(__('Tolak'), array(
                'type' => 'submit',
                'class' => 'btn red',
            ));
    ?>
</div>
<?php 
        echo $this->Form->end();
?>