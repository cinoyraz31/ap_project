<?php
		$username_disabled = $this->Rumahku->filterEmptyField($user, 'User', 'username_disabled', false);
		$genderDefault = $this->Rumahku->filterEmptyField($this->request->data, 'User', 'gender_id');

        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative  col-sm-6 col-xl-5',
        );

        echo $this->element('blocks/users/simple_info');
        echo $this->Html->tag('h2', __('Informasi Dasar'), array(
        	'class' => 'sub-heading'
        ));

        echo $this->Form->create('User');
?>

<div class="row">
    <div class="col-sm-12">
        <?php 
                echo $this->Rumahku->buildInputForm('username', array_merge($options, array(
                    'label' => __('Username *'),
                    'class' => 'relative col-sm-3 col-xl-2',
                    'infoText' => __('Anda hanya dapat melakukan perubahan username sebanyak 1 kali'),
                    'disabled' => $username_disabled,
                )));
                echo $this->element('blocks/users/forms/profile', array(
                    'genderDefault' => $genderDefault,
                    '_bank' => false,
                ));
    			echo $this->Html->tag('h2', __('Tentang Saya'), array(
    	        	'class' => 'sub-heading'
    	        ));
                echo $this->Rumahku->buildInputForm('UserProfile.description', array_merge($options, array(
                    'label' => __('Informasi Biografi'),
                )));
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

<?php 
        echo $this->Form->end(); 
?>