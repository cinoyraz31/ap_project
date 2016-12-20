<?php 
		$bank = $this->Rumahku->filterEmptyField($params, 'Bank', 'name');
		$bank_phone = $this->Rumahku->filterEmptyField($params, 'Bank', 'phone');

		echo $bank;
		echo "\n";
		
    	printf(__('No. Tlp: %s'), $bank_phone);
		echo "\n\n";
?>