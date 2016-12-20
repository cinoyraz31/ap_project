<?php
	
	$data = $this->request->data;
	$mandatory = !empty($mandatory)?$mandatory:false;
	$kpr_commission_confirms = $this->Rumahku->filterEmptyField($data, 'KprCommissionPaymentConfirm');

	echo $this->Rumahku->buildInputForm('commission', array_merge($options, array(
		'type' => 'text',
	    'label' => __('Provisi Agen'),
	    'inputClass' => 'input_price',
		'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));

	echo $this->Rumahku->buildInputForm('commission_rumahku', array_merge($options, array(
		'type' => 'text',
	    'label' => __('Provisi %s', Configure::read('__Site.site_name')),
	    'inputClass' => 'input_price',
		'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));

    echo $this->Rumahku->buildInputForm('insurance', array_merge($options, array(
		'type' => 'text',
	    'label' => __('Asuransi'),
	    'inputClass' => 'input_price',
		'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));

?>
