<?php 
		$client = $this->Rumahku->filterEmptyField($params, 'User', 'full_name');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp');

		printf(__('Nama Pembeli: %s'), $client);
		echo "\n";
		printf(__('No. Tlp: %s'), $no_hp);
		echo "\n\n";
?>