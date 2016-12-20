<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'code');
		$agent_name = $this->Rumahku->filterEmptyField($params, 'User', 'full_name');

		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');

		printf(__('Agent %s tidak melanjutkan Proses KPR - %s'), $agent_name, $code);
		echo "\n\n";

		if( !empty($mls_id) ) {
  			echo __('Dengan informasi sebagai berikut :');
			echo "\n";
			echo $this->element('emails/text/properties/info');
		}

		echo $this->element('emails/text/kpr/client');

		echo __('Dikarenakan Klien Pembeli telah memilih Bank lain');
		echo "\n\n";

		$link = $domain.$this->Html->url(array(
			'controller' => 'kpr', 
			'action' => 'user_apply_detail',
			$id,
			'admin' => true,
		));
		echo __('Lihat Selengkapnya:');
		echo "\n";
		echo $link;
?>