<?php 
		$company_profile = Configure::read('__Site.company_profile');
		$company_premiere = strtoupper($this->Rumahku->filterEmptyField($company_profile, 'name_premiere'));
		$company_name = strtoupper($this->Rumahku->filterEmptyField($company_profile, 'name'));
		$company_address = $this->Rumahku->filterEmptyField($company_profile, 'address');
		$company_phone = $this->Rumahku->filterEmptyField($company_profile, 'phone');
		$company_phone2 = $this->Rumahku->filterEmptyField($company_profile, 'phone2');
		$company_email = $this->Rumahku->filterEmptyField($company_profile, 'email');
		$company_link = $this->Rumahku->filterEmptyField($company_profile, 'link');
		
		echo $this->Html->tag('div', $company_premiere, array(
			'style' => 'font-size:18px;'
		));
		echo $this->Html->tag('div', $company_name, array(
			'style' => 'font-size:14px'
		));

		echo $this->Html->tag('div', $company_address, array(
			'style' => 'font-size:12px'
		));
		$text_email = $this->Html->tag('span', sprintf('%s %s', __('Email : '), $company_email));
		$telp = $this->Html->tag('span', sprintf('%s %s', __('Telp : '), $company_phone));
		$wa = $this->Html->tag('span', sprintf('%s %s', __('WA : '), $company_phone2));

		echo $this->Html->tag('div', sprintf('%s, %s, %s', $text_email, $telp, $wa), array(
			'style' => 'font-size:9px'
		));
?>