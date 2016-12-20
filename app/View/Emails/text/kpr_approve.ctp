<?php 
		$currency = Configure::read('__Site.config_currency_symbol');

		$mls_id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'mls_id');
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'property_price');
		$created = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'created');
		$currency = $this->Rumahku->filterEmptyField($params, 'Currency', 'symbol', $currency);
		$currency = sprintf('%s ', $currency);
		
		$dp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'dp');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_total');
		$credit_fix = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_fix');
		$interest_rate = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'interest_rate');
		$credit_float = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_float');
		$floating_rate = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'floating_rate');
		$total_first_credit = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'total_first_credit');

		$customDp = $this->Rumahku->getFormatPrice($dp, '-', $currency);
		$customPrice = $this->Rumahku->getFormatPrice($property_price, '-', $currency); 
		$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price, '-', $currency); 
		$customTotalFirstCredit = $this->Rumahku->getFormatPrice($total_first_credit, '-', $currency); 
        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);
        $customRate = sprintf("%s%s effective fixed %s tahun", $interest_rate, '%', $credit_fix);
        $customCreated = $this->Rumahku->formatDate($created, 'd M Y');

        $costKPR = $this->Kpr->_callCalcCostKpr($params);
        $total = $this->Rumahku->filterEmptyField($costKPR, 'total');
		$customTotal = $this->Rumahku->getFormatPrice($total, '-', $currency);

		echo __('Selamat permohonan KPR Anda telah di setujui');
		echo "\n\n";

		printf(__('Tgl Pengajuan : %s'), $customCreated);
		echo "\n";

		printf(__('Properti ID : %s'), $mls_id);	
		echo "\n";

		printf(__('Harga Properti : %s'), $customPrice);	
		echo "\n";

		printf(__('Uang Muka : %s'), $customDp);	
		echo "\n";

		printf(__('Jumlah Pinjaman : %s'), $customLoanPrice);	
		echo "\n";

		printf(__('Jangka Waktu : %s'), $customLoanTime);	
		echo "\n";

		printf(__('Suku Bunga : %s'), $customRate);	
		echo "\n";

		if( !empty($credit_float) ) {
			printf(__('Suku Bunga Floating : %s%s'), $floating_rate, '%');	
			echo "\n";
		}

		printf(__('Angsuran per Bulan : %s'), $customTotalFirstCredit);	
		echo "\n";

		printf(__('Pembayaran Pertama : %s'), $customTotal);	
		echo "\n\n";
?>