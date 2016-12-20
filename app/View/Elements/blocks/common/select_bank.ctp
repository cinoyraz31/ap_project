<?php
	// $url = array(
	// 		'controller' => 'users',
	// 		'action' => 'admin_account',
	// 		'index',
	// 		'admin' => true,
	// 	);	
	echo $this->Form->create('change_bank', array(
		));
	echo $this->Form->input('set_bank', array(
		'label' => false,
		'class' => 'form-control',
		'style' => '    width: 100%;padding: 10px 12px;border: 1px solid #CCC;
						-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;',
		'options' => configure::read('getBankAll'),
		'default' => $this->Session->read('Session.bank_id'),
		'onChange' => 'submit();',
	));
	echo $this->Form->end();

;
?>
