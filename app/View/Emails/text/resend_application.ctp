<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank', 'code');
		$agent_name = $this->Rumahku->filterEmptyField($params, 'Agent', 'full_name');
		$application_status = $this->Rumahku->filterEmptyField($params, 'KprBank', 'application_status');

		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');

		printf(__('Agen %s telah menyetujui & melanjutkan aplikasi KPR, %s', $agent_name, $code));
		echo "\n\n";

		if( !empty($mls_id) ) {
  			echo __('Dengan informasi sebagai berikut :');
			echo "\n";
			echo $this->element('emails/text/properties/info');
		}

		echo $this->element('emails/text/kpr/client');
		echo $this->element('emails/text/kpr/info_agent');

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