<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);

		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$ktp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'ktp');
		$name = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'name');
		$email = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'email');
		$phone = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'phone');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$created = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'created', date('d M Y'));

		$mls_id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'mls_id');
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'property_price');
		$dp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'down_payment');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_total');

		$created = $this->Rumahku->formatDate($created, 'd M Y');
		$customDp = $this->Rumahku->getFormatPrice($dp);
		$customPrice = $this->Rumahku->getFormatPrice($property_price); 
		$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price); 
        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);

		echo __('Pemohon');
		echo "\n\n";

		printf(__('Tanggal Pengajuan : %s'), $created);
		echo "\n";

		printf(__('KTP : %s'), $ktp);	
		echo "\n";

		printf(__('Nama : %s'), $name);	
		echo "\n";

		printf(__('Email : %s'), $email);
		echo "\n";

		printf(__('No. Telp : %s'), $phone);
		echo "\n";

		printf(__('No. Handphone : %s'), $no_hp);
		echo "\n\n";

		echo __('Rincian Properti KPR');
		echo "\n\n";

		printf(__('Properti ID : %s'), $mls_id);
		echo "\n";

		printf(__('Harga Properti : %s'), $customPrice);
		echo "\n";

		printf(__('Uang Muka : %s'), $customDp);
		echo "\n";

		printf(__('Jumlah Pinjaman : %s'), $customLoanPrice);
		echo "\n";

		printf(__('Jangka Waktu : %s'), $customLoanTime);
		echo "\n\n";

		$link = $domain.$this->Html->url(array(
			'controller' => 'kpr', 
			'action' => 'user_apply_detail',
			$id,
			'admin' => true
		));
		echo __('Lihat Detil Permohonan');
		echo "\n";
		echo $link;
?>