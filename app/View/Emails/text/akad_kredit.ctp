<?php 
		$credit_date = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'action_date');
		$note = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'note', false, false, 'EOL');
		$staff_bank = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'full_name');
		$staff_phone = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$date = $this->Rumahku->formatDate($credit_date, 'd M Y');
		$time = $this->Rumahku->formatDate($credit_date, 'H:i');

		echo __('Tanggal : ', $date);
		echo "\n";
		echo __('Pukul : ', $time);
		echo "\n";
		echo __('Nama Klien : ', $staff_bank);
		echo "\n";
		echo __('No. Hanphone : ', $staff_phone);
		echo "\n";
		echo __('Bertempat di : ', $note);
		echo "\n";
?>