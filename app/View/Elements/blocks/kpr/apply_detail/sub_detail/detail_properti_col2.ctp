<?php

	$change_date = $this->Rumahku->filterEmptyField($value, 'Property', 'change_date');
	$created = $this->Rumahku->filterEmptyField($value, 'Property', 'created');
	$user_name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
	
	$customChangeDate = $this->Rumahku->formatDate($change_date, 'd/m/Y H:i:s');
	$customCreated = $this->Rumahku->formatDate($created, 'd/m/Y H:i:s');

    $createdBy = sprintf(__('Dipasarkan oleh: %s'), $this->Html->tag('strong', $user_name));
	$customCreated = sprintf(__('Tgl dibuat: %s'), $this->Html->tag('strong', $customCreated));
	$customChangeDate = sprintf(__('Terakhir update: %s'), $this->Html->tag('strong', $customChangeDate));
	
	echo $this->Html->tag('div', $customCreated, array(
		'class' => 'created-date mt30',
	));
	echo $this->Html->tag('div', $customChangeDate, array(
		'class' => 'created-date',
	));
	echo $this->Html->tag('div', $createdBy, array(
		'class' => 'created-by',
	));
?>