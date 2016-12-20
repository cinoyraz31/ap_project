<?php
		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$down_payment = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'down_payment');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_total');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');
		
        $interest_rate = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'interest_rate');
        $total_first_credit = $this->Rumahku->creditFix($loan_price, $interest_rate, $credit_total);

		$total_first_credit = $this->Rumahku->getCurrencyPrice($total_first_credit);
		$dp = $this->Rumahku->getCurrencyPrice($down_payment);
		$loan_price = $this->Rumahku->getCurrencyPrice($loan_price);
   	 	$price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'property_price');

   	 	if( !empty($price) ) {
        	$price = $this->Rumahku->getCurrencyPrice($price);
        } else {
        	$price = $this->Property->getPrice($params);
        }
        
		$link = $this->Html->url(array(
			'controller' => 'properties', 
			'action' => 'detail',
            $mls_id,
            'application' => $id,
			'admin' => true,
		), true);

		echo __('Rincian Informasi KPR');
		echo "\n";

		printf(__('Properti ID: %s'), $mls_id);
		echo "\n";
		printf(__('Harga Properti: %s'), $price);
		echo "\n";
		printf(__('Uang Muka: %s'), $dp);
		echo "\n";
		printf(__('Jumlah Pinjaman: %s'), $loan_price);
		echo "\n";
		printf(__('Jangka Waktu: %s Tahun'), $credit_total);
		echo "\n";
		printf(__('Angsuran Per Bulan: %s'), $total_first_credit);
		echo "\n\n";
?>