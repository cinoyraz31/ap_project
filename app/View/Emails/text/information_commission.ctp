<?php
	$date_now = date('d M Y H:i:s');
	$note_footer = null;
	## DATA AGENT
	$rekening_nama_akun = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'rekening_nama_akun');  
	$company_name = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'company_name');  
	$rekening_bank = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'rekening_bank');  
	$no_rekening = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_rekening');  
	$no_npwp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_npwp'); 

	$mls_id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'mls_id'); 

	# DATA KPR
	$code_kpr = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'code');  

	$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price'); 
	$loan_plafond = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'loan_plafond'); 
	$loan_price = !empty($loan_plafond)?$loan_plafond:$loan_price;
	$customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price, '-');

	$dataKprCommission = $this->Rumahku->filterEmptyField($params, 'dataKprCommission');
	$commission = $this->Rumahku->filterEmptyField($dataKprCommission, 'KprCommissionPayment', 'commission');
	$commission_c = $this->Rumahku->filterEmptyField($dataKprCommission, 'KprCommissionPaymentConfirm', 'commission');
	$commission = !empty($commission_c)?$commission_c:$commission;
	$customComission = $this->Rumahku->getCurrencyPrice($commission, '-');

	$document_status = $this->Rumahku->filterEmptyField($params, 'KprApplication','document_status');
	$label = __('Provisi pengajuan KPR %s');
	if($document_status == 'akad_credit'){
		$konfirmation_comission = __('pembayaran');
		$question = sprintf( $label, __('yang harus dibayarkan'));
	}else{
		$question = sprintf(__('ilustrasi %s'), sprintf($label, ''));
		$konfirmation_comission = __('ilustrasi rincian');
		$note_footer = __('*Perhitungan tertampil hanya angka ilustrasi');
	}
	$question = sprintf('Berikut rincian pembayaran %s:',$question);

	$note_header = sprintf('Bagikan informasi %s Provisi',$konfirmation_comission);

	$receiver_name = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'name');
	$no_account = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'no_account');
	$bank_name = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'bank_name');
	$npwp = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'npwp');

	$extra_note = $this->Rumahku->filterEmptyField($params, 'FinanceConfirmation', 'note');

	printf(__('Nomor : %s'), $code_kpr);
		echo "\n\n";

	printf(__('Hal : %s'), $note_header);
		echo "\n\n";
	print(__('==================================='));
		echo "\n\n";
	print(__('Kepada <br> Yth. Bagian Keuangan <br> di tempat'));
		echo "\n\n";
	print($question);
		echo "\n\n";

	printf(__('Penerima : %s'), $receiver_name);
		echo "\n\n";
	printf(__('Bank : %s'), $bank_name);
		echo "\n\n";
	printf(__('No. Rek : %s'), $no_account);
		echo "\n\n";
	printf(__('NPWP : %s'), $npwp);
		echo "\n\n";
	print(__('==================================='));
		echo "\n\n";
	printf(__('ID KPR : %s'), $code_kpr);
		echo "\n\n";
	printf(__('ID Listing : %s'), $mls_id);
		echo "\n\n";
	print(__('==================================='));
		echo "\n\n";
	printf(__('Plafon : %s'), $customLoanPrice);
		echo "\n\n";
	printf(__('Provisi : %s'), $customComission);
		echo "\n\n";

?>

