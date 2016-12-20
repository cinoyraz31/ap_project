<?php
    $data = $this->request->data;
    $options = !empty($options)?$options:false;
    $subareas = !empty($subareas_1)?$subareas_1:FALSE;
    $application = !empty($data['KprApplication'][1])?$data['KprApplication'][1]:array();
	$labelHeader = __('pasangan');
    // $subareas = !empty($KprSpouseParticular_subareas)?$KprSpouseParticular_subareas:false;
	echo $this->Html->tag('h2', sprintf(__('Profil Pembeli %s'), $labelHeader), array(
            'class' => 'sub-heading'
        ));
	echo $this->element('blocks/kpr/form/profile_client', array(
        'idx' => 1,
        'options' => $options,
        'disabled_marital' => TRUE,
    ));
    echo $this->element('blocks/kpr/form/contact_client', array(
        'idx' => 1,
    	'labelHeader' => $labelHeader,
        'options' => $options,
    ));
    echo $this->element('blocks/kpr/form/address_client', array(
        'idx' => 1,
    	'labelHeader' => $labelHeader,
        'options' => $options,
        'value' => $application,
        'subareas' => $subareas,
        'aditionals' => 1,
        'optionAttributes' => array(
            'attributes' => array(
                'display-attributes' => '.hide-address-spouse',
                'hideAddress' => 'hide-address-spouse',
            ),
        ),

    ));
    echo $this->element('blocks/kpr/form/information_personal', array(
        'idx' => 1,
    	'labelHeader' => $labelHeader,
        'options' => $options,
    ));

    echo $this->element('blocks/kpr/form/information_document', array(
        'idx' => 1,
    	'labelHeader' => $labelHeader,
        'options' => $options,
        'documents' => $document_category_spouses,
    ));
?>