<?php
		$subareas = !empty($subareas)?$subareas:false;

        echo $this->Html->tag('h2', __('Informasi Dasar'), array(
        	'class' => 'sub-heading'
        ));

        echo $this->Form->create('User', array(
            'type' => 'file',
        ));
?>

<div class="row">
    <div class="col-sm-12">  	
		<?php
                echo $this->element('blocks/users/forms/profile', array(
                    'manualUploadPhoto' => true,
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
                        echo $this->Html->link(__('Kembali'), array(
                            'action' => 'admins',
                            'admin' => true
                        ), array(
                            'class'=> 'btn default',
                        ));
                        echo $this->Form->button(__('Simpan Perubahan'), array(
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