<?php
	$mandatory = !empty($mandatory)?$mandatory:false;
	echo $this->Rumahku->buildInputForm('appraisal', array_merge($options, array(
		'type' => 'text',
	    'label' => __('Appraisal'),
	    'inputClass' => 'input_price',
		'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));
	echo $this->Rumahku->buildInputForm('administration', array_merge($options, array(
		'type' => 'text',
	    'label' => __('Administrasi'),
	    'inputClass' => 'input_price',
		'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));
	echo $this->Rumahku->buildInputForm('credit_agreement', array_merge($options, array(
	    'type' => 'text',
	    'label' => __('Akta Perjanjian Kredit'),
	    'inputClass' => 'input_price',
	    'textGroup' => $currency,
	    'positionGroup' => 'left',
	)));

	echo $this->Html->tag('h2', __('Biaya Notaris'), array(
	    'class' => 'sub-heading'
	));

?>

