<?php 
		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'code');
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);

		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');

		printf(__('Pengajuan Proses Akad Kredit - %s'), $code);
		echo "\n\n";

		echo __('Kami informasikan bahwa Agen dibawah ini :');
		echo "\n";

		echo $this->element('emails/text/kpr/agent');

		if( !empty($mls_id) ) {
  			echo __('Dengan informasi sebagai berikut :');
			echo "\n";
			echo $this->element('emails/text/properties/info');
		}

		echo $this->element('emails/text/kpr/info');
		echo $this->element('emails/text/kpr/info_agent');

		$link = $this->Html->url(array(
			'controller' => 'kpr', 
			'action' => 'user_apply_detail',
			$id,
			'admin' => true,
		));
		echo __('Lihat Selengkapnya:');
		echo "\n";
		echo $link;
?>