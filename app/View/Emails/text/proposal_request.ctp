<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank','code');
		$agent = $this->Rumahku->filterEmptyField($params, 'Agent', 'full_name');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'AgentProfile', 'no_hp');
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');

		if( !empty($mls_id) ) {
        	$photo = $this->Rumahku->filterEmptyField($params, 'Property', 'photo');
       	 	$title = $this->Rumahku->filterEmptyField($params, 'Property', 'title');
        	$property_path = Configure::read('__Site.property_photo_folder');
	        $label = $this->Property->getNameCustom($params);
	        $slug = $this->Rumahku->toSlug($label);
	        $price  = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'property_price');

       	 	if( !empty($price) ) {
	        	$price = $this->Rumahku->getCurrencyPrice($price);
	        } else {
	        	$price = $this->Property->getPrice($params);
	        }

	        $customPhoto = $this->Rumahku->photo_thumbnail(array(
	            'save_path' => $property_path, 
	            'src'=> $photo, 
	            'size' => 'l',
	            'fullbase' => $domain,
	        ), array(
	            'title' => $title,
	            'alt' => $title,
	            'style' => 'width: 150px;margin-right: 15px;',
	        ));
	        $url = $domain.$this->Html->url(array(
	            'controller'=> 'properties', 
	            'action' => 'detail',
	            $mls_id,
	            'admin'=> true,
	        ));
	    }

		printf(__('Referral KPR %s'), $code);
		echo "\n\n";

		echo __('Kami informasikan bahwa Agen dibawah ini :');
		echo "\n";

		printf(__('Nama: %s'), $agent);
		echo "\n";
		printf(__('No. Telp: %s'), $no_hp);
		echo "\n\n";

		if( !empty($mls_id) ) {
			echo __('Mengajukan referral KPR dengan informasi sebagai berikut :');
			echo "\n";
			
			echo $label;
			echo "\n";

			echo $title;
			echo "\n";

			echo $price;
			echo "\n\n";
		}

		echo __('Lihat Selengkapnya:');
		echo "\n";
		echo $domain.$this->Html->url(array(
			'controller' => 'kpr', 
			'action' => 'user_apply_detail',
			$id,
			'admin' => true,
		));
?>