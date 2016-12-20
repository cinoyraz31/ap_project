<?php
		$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
		$note = $this->Rumahku->filterEmptyField($params, 'FinanceConfirmation', 'note');  
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		
		echo $this->element('emails/text/kpr/commission_report');

		if( !empty($note) ) {
  			echo __('Keterangan:');
  			echo "\n";
			echo $note;
		}
?>

