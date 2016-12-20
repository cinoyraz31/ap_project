<?php
		$action_type = !empty($action_type)?$action_type:false;
		$kprBankCommission = $this->Rumahku->filterEmptyField($data, 'KprBankCommission');
		$commission = $this->Rumahku->filterEmptyField($kprBankCommission, 'value');
		$type = $this->Rumahku->filterEmptyField($kprBankCommission, 'type');
		$kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
		$document_status_arr = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');

		$customComission = $this->Rumahku->getCurrencyPrice($commission, '-');
		$labelName = false;

		switch ($type) {
			case 'agent':
				$receiver_name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
				$account_name = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'name_account');
				$account_number = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'no_account');
				$bank_name = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'bank_name');
				$npwp = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'no_npwp');
				$labelName = __('Agen');

				if( !empty($account_name) ) {
					$bankTransfers = array(
						array(
							'BankConfirmation' => array(
								'account_name' => $account_name,
								'account_number' => $account_number,
								'npwp' => $npwp,
							),
							'Bank' => array(
								'name' => $bank_name,
							),
						),
					);
				}
				break;
			case 'rku':
				$bankTransfers = $bankConfirmations;
				$labelName = __('Rumahku');
				break;
		}

		printf(__('Provisi %s'), $labelName);
		echo "\n";
		printf(__('Sebesar : %s'), $customComission);
		echo "\n";

		if( !empty($bankTransfers) && !empty($commissionsKprConfirm) ) {
			echo __('Pembayaran dapat dilakukan ke rekening berikut:');
			echo "\n";

			foreach ($bankTransfers as $key => $transfer) {
				$account_name = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'account_name');
				$account_number = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'account_number');
				$npwp = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'npwp');
				$bank = $this->Rumahku->filterEmptyField($transfer, 'Bank', 'name');

				if( !empty($key) ) {
					echo __('Atau');
					echo "\n";
				}

				if(!empty($bank)){
					printf(__('Bank : %s'), $bank);
					echo "\n";
				}

				if(!empty($account_name)){
					printf(__('Atas Nama : %s'), $account_name);
					echo "\n";
				}

				if(!empty($account_number)){
					printf(__('No. Rek : %s'), $account_number);
					echo "\n";
				}

				if(!empty($npwp)){
					printf(__('NPWP : %s'), $npwp);
					echo "\n";
				}
			}
		}
		echo "\n";
?>