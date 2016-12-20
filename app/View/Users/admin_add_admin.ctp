<?php
        echo $this->Form->create('User', array(
            'type' => 'file',
    	));
		echo $this->element('blocks/users/add_user', array(
			'manualUploadPhoto' => true,
		));
    	echo $this->Form->end(); 
?>