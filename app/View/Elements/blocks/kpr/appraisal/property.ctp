<?php
	$mandatory = !empty($mandatory)?$mandatory:false;
	echo $this->Rumahku->buildInputForm('KprBank.appraisal_date', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__('Tanggal Appraisal %s'), $mandatory),
        'inputClass' => 'datepicker',
        'default' => date('d/m/Y'),
		'class' => 'relative col-sm-4 col-md-3 col-xl-2',
    )));

    echo $this->Rumahku->buildInputForm('property_price', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__('Harga Terjual %s'), $mandatory),
        'inputClass' => 'input_price KPR-price',
    	'textGroup' => $currency,
        'positionGroup' => 'left',
		'attributes' => array(
			'action_type' => 'loan_price',
		),
    )));

    echo $this->Rumahku->buildInputForm('loan_price', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__('Plafond Pinjaman %s'), $mandatory),
        'inputClass' => 'input_price loan_plafond',
    	'textGroup' => $currency,
        'positionGroup' => 'left',
        'attributes' => array(
        	'action_type' => 'loan_price',
        ),
    )));

    echo $this->Rumahku->buildInputForm('dp_percent', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__(' DP (%%) %s'), $mandatory),
		'class' => 'relative col-sm-4 col-md-3 col-xl-2',
		'inputClass' => 'persen-loan-id',
    	'textGroup' => __('%'),
    	'attributes' => array(
        	'action_type' => 'persen_loan',
        ),
    )));

    echo $this->Rumahku->buildInputForm('down_payment', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__('Uang Muka %s'), $mandatory),
        'inputClass' => 'input_price down-payment-id',
    	'textGroup' => $currency,
        'positionGroup' => 'left',
		'attributes' => array(
            'action_type' => 'down_payment',
        ),
    )));

    echo $this->Rumahku->buildInputForm('total_first_credit', array_merge($options, array(
    	'type' => 'text',
        'label' => sprintf(__('Cicilan Pertama %s'), $mandatory),
        'inputClass' => 'input_price pay-btn',
        'disabled' => 'disabled',
    	'textGroup' => $currency,
        'positionGroup' => 'left',
    )));

    echo $this->Html->tag('h2', __('Bunga KPR'), array(
        'class' => 'sub-heading'
    ));

?>