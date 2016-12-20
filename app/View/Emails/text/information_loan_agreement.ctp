<?php
		$date_now = date('d M Y');
		// debug($params);die();

		## DATA CLIENT
		$code = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'code');
		$full_name = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'full_name');
		$email = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'email');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$no_hp_2 = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp_2');
		$address = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'address');
		$address_2 = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'address_2');
		$same_as_address_ktp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'same_as_address_ktp');
		$company = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'company');

		# DATA AGENT
		$agent = $this->Rumahku->filterEmptyField($params, 'User');
		$agent_profile = $this->Rumahku->filterEmptyField($params, 'UserProfile');
		$agent_name = $this->Rumahku->filterEmptyField($agent, 'full_name');
		$agent_no_hp = $this->Rumahku->filterEmptyField($agent_profile, 'no_hp');
		$agent_no_hp_2 = $this->Rumahku->filterEmptyField($agent_profile, 'no_hp_2');
		$agent_address = $this->Rumahku->filterEmptyField($agent_profile, 'address');
		$agent_address2 = $this->Rumahku->filterEmptyField($agent_profile, 'address2');
		$agent_email = $this->Rumahku->filterEmptyField($agent, 'email');

		# DATA KPR
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'property_price');
		$loan_plafond = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'loan_plafond');
		$down_payment = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'down_payment');
		$periode_fix = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'periode_fix');
		$persen_loan = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'persen_loan');
		$interest_rate = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'interest_rate');
		$creditFix = $this->Rumahku->creditFix($loan_plafond, $interest_rate, $periode_fix);
		$customCreditFix = $this->Rumahku->getCurrencyPrice($creditFix, '-');
		$customPropertyPrice = $this->Rumahku->getCurrencyPrice($property_price, '-');
		$customDownPayment = $this->Rumahku->getCurrencyPrice($down_payment, '-');
		$customLoanPlafond = $this->Rumahku->getCurrencyPrice($loan_plafond, '-');

		// AKad Kredit
		$credit_date = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'process_akad_date');
		$creditNote = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'description_akad', false, false, 'EOL');
		$creditContactName = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_name');
		$creditContactEmail = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_email');
		$creditContactBank = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_bank');

		$creditDate = $this->Rumahku->formatDate($credit_date, 'd M Y');
		$creditTime = $this->Rumahku->formatDate($credit_date, 'H:i');

		printf(__('Kode KPR : %s'), $code);
		echo "\n\n";

		printf(__('INFORMASI KLIEN'));
		echo "\n";

		if(!empty($full_name)){
			printf(__('Nama : %s'), $full_name);
			echo "\n";
		}

		if(!empty($no_hp)){
			printf(__('No. HP : %s'), $no_hp);
			echo "\n";
		}

		if(!empty($no_hp_2)){
			printf(__('No. HP 2 : %s'), $no_hp_2);
			echo "\n";
		}

		if(!empty($email)){
			printf(__('Email : %s'), $email);
			echo "\n";
		}

		if(!empty($address)){
			printf(__('Alamat : %s'), $address);
			echo "\n";
		}

		if(!empty($address2) && !$same_as_address_ktp){
			printf(__('Alamat 2 : %s'), $address2);
			echo "\n";
		}

		if(!empty($company)){
			printf(__('Perusahaan : %s'), $company);
			echo "\n";
		}

		echo "\n";
		printf(__('INFORMASI AGEN'));
		echo "\n";

		if(!empty($agent_name)){
			printf(__('Nama : %s'), $agent_name);
			echo "\n";
		}

		if(!empty($agent_no_hp)){
			printf(__('No. HP : %s'), $agent_no_hp);
			echo "\n";
		}

		if(!empty($agent_no_hp_2)){
			printf(__('No. HP 2 : %s'), $agent_no_hp_2);
			echo "\n";
		}

		if(!empty($agent_email)){
			printf(__('Email : %s'), $agent_email);
			echo "\n";
		}

		if(!empty($agent_address)){
			printf(__('Alamat : %s'), $agent_address);
			echo "\n";
		}

		if(!empty($agent_address2)){
			printf(__('Alamat 2 : %s'), $agent_address2);
			echo "\n";
		}

		echo "\n";
		printf(__('INFORMASI KPR'));
		echo "\n";

		if(!empty($property_price)){
			printf(__('Harga Properti : %s'), $customPropertyPrice);
			echo "\n";
		}

		if(!empty($loan_plafond)){
			printf(__('Jumlah Pinjaman : %s'), $customLoanPlafond);
			echo "\n";
		}

		if(!empty($down_payment)){
			printf(__('Uang Muka : %s'), $customDownPayment);
			echo "\n";
		}

		if(!empty($periode_fix)){
			printf(__('Installment : %s Tahun'), $periode_fix);
			echo "\n";
		}

		if(!empty($interest_rate)){
			printf(__('Bunga tetap : %s %%'), $interest_rate);
			echo "\n";
		}

		if(!empty($customCreditFix)){
			printf(__('Cicilan pertama : %s'), $customCreditFix);
			echo "\n";
		}

		echo "\n";
		printf(__('INFORMASI AKAD KREDIT'));
		echo "\n";

		if(!empty($credit_date)){
			printf(__('Tanggal : %s'), $creditDate);
			echo "\n";

			printf(__('Pukul : %s'), $creditTime);
			echo "\n";
		}

		if(!empty($creditNote)){
			printf(__('Lokasi : %s'), $creditNote);
			echo "\n";
		}

		if(!empty($creditContactName)){
			printf(__('Bertemu dengan : %s'), $creditContactName);
			echo "\n";
		}

		if(!empty($creditContactBank)){
			printf(__('Telp. : %s'), $creditContactBank);
			echo "\n";
		}

		if(!empty($creditContactEmail)){
			printf(__('Email : %s'), $creditContactEmail);
			echo "\n";
		}
?>