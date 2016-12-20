<?php
		$date_now = date('d M Y');
		$note_footer = null;
		$params = !empty($params)?$params:false;
		$kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
		$document_status_arr = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');
		$kprBankCommissions = $this->Rumahku->filterEmptyField($value, 'KprBankCommission');
		$kprBankInstallment = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment');

		$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');  
		$code_kpr = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');  
		$label = __('Provisi pengajuan KPR %s');

		if( in_array('approved_bank', $document_status_arr) ){
			$konfirmation_comission = __('pembayaran');
			$question = sprintf( $label, __('yang harus dibayarkan'));
		}else{
			$question = sprintf(__('ilustrasi %s'), sprintf($label, ''));
			$konfirmation_comission = __('ilustrasi rincian');
			$note_footer = __('*Perhitungan tertampil hanya angka ilustrasi');
		}

		$question = sprintf('Berikut rincian pembayaran %s:', $question);
		$note_header = sprintf('Bagikan informasi %s Provisi', $konfirmation_comission);

		$extra_note = $this->Rumahku->filterEmptyField($params, 'FinanceConfirmation', 'note');

		$loan_price = $this->Rumahku->filterEmptyField($kprBankInstallment, 'loan_price'); 
		$customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price, '-');
		$mls_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'mls_id');
		
		printf(__('Nomor : %s'), $code_kpr);
		echo "\n";

		printf(__('Hal : %s'), $note_header);
		echo "\n\n";

		echo __('Kepada');
		echo "\n";
		echo __('Yth. Bapak/Ibu');
		echo "\n";
		echo __('di tempat');
		echo "\n\n";

		echo $question;
		echo "\n\n";

		if(!empty($kprBankCommissions)){
			foreach ($kprBankCommissions as $key => $kprBankCommission) {
				echo $this->element('emails/text/kpr/print_comission', array(
					'data' => $kprBankCommission,
				));
			}
		}

		echo __('Informasi Pinjaman');
		echo "\n";
		printf(__('ID Listing : %s'), $mls_id);
		echo "\n";
		printf(__('Plafon : %s'), $customLoanPrice);
		echo "\n\n";

		if(!empty($extra_note)){
			printf(__('Keterangan :'));
			echo "\n";
			echo $extra_note;
			echo "\n\n";
		}

		if( !empty($note_footer) ) {
			echo $note_footer;
			echo "\n\n";
		}
?>