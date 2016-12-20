<?php

		$notification = $this->Rumahku->filterEmptyField($params, 'notif');
		$kprBankCreditAgreement = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement');

		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$domain = FULL_BASE_URL; 

		echo $notification;
		echo "\n\n";

		echo __('Kami informasikan bahwa Agen dibawah ini :');
		echo $this->element('emails/text/kpr/agent');

		if(!empty($kprBankCreditAgreement)){
			$credit_date = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'action_date');
			$note = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'note', false, false, 'EOL');
			$staff_bank = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'staff_name');
			$staff_phone = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'staff_phone');
			$date = $this->Rumahku->formatDate($credit_date, 'd M Y');
			$time = $this->Rumahku->formatDate($credit_date, 'H:i');

			printf(__('Tanggal: %s'), $date);
			echo "\n";
			printf(__('Pukul: %s'), $time);
			echo "\n";
			printf(__('Staff / Bertemu dengan: %s'), $staff_bank);
			echo "\n";
			printf(__('No. Hanphone: %s'), $staff_phone);
			echo "\n";
			printf(__('Bertempat di: %s'), $note);
			echo "\n";
		}

		$link = $domain.$this->Html->url(array(
			'controller' => 'kpr', 
			'action' => 'application_detail',
			$id,
			'admin' => true,
		));

		echo $this->Html->link(__('Lihat Detil'), $url, array(
			'target' => '_blank',
		));
?>
