<?php
        $subareas = !empty($subareas)?$subareas:false;
        $manualUploadPhoto = !empty($manualUploadPhoto)?$manualUploadPhoto:false;

        echo $this->Html->tag('h2', __('Informasi Dasar'), array(
            'class' => 'sub-heading'
        ));
?>

<div class="row">
    <div class="col-sm-12">
        <?php
                echo $this->element('blocks/users/forms/profile', array(
                    'manualUploadPhoto' => $manualUploadPhoto,
                    '_add' => true,
                ));

                echo $this->Html->tag('h2', __('Alamat'), array(
                    'class' => 'sub-heading'
                ));
                echo $this->element('blocks/users/forms/address');

                echo $this->Html->tag('h2', __('Informasi Kontak'), array(
                    'class' => 'sub-heading'
                ));
                echo $this->element('blocks/users/forms/contact_info');
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="action-group bottom">
            <div class="btn-group floright">
                <?php
                    echo $this->Form->button(__('Simpan Perubahan'), array(
                        'type' => 'submit', 
                        'class'=> 'btn blue',
                    ));
                ?>
            </div>
        </div>
    </div>
</div>