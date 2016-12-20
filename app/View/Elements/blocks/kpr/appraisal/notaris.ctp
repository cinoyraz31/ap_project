<?php
		echo $this->Rumahku->buildInputForm('sale_purchase_certificate', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Akte Jual Beli'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));

		echo $this->Rumahku->buildInputForm('transfer_title_charge', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Bea Balik Nama'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));

		echo $this->Rumahku->buildInputForm('letter_mortgage', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Akta SKMHT'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));

		echo $this->Rumahku->buildInputForm('mortgage', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Perjanjian HT'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));

		echo $this->Rumahku->buildInputForm('imposition_act_mortgage', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Akta APHT'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));

		echo $this->Rumahku->buildInputForm('other_certificate', array_merge($options, array(
			'type' => 'text',
		    'label' => __('Cek Sertifikat, ZNT, PNBP HT'),
		    'inputClass' => 'input_price',
			'textGroup' => $currency,
		    'positionGroup' => 'left',
		)));
?>