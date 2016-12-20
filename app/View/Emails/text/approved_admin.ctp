<?php
		$site_name = Configure::read('__Site.site_name');
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank', 'code');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);

		printf(__('%s telah memverifikasi  dan mengirimkan aplikasi KPR #%s', $site_name, $code));
		echo "\n\n";

		echo __('Kami informasikan bahwa Agen dibawah ini :');
		echo "\n";

		echo $this->element('emails/text/kpr/agent');

		if( !empty($mls_id) ) {
  			echo __('Mengajukan Aplikasi KPR dengan informasi sebagai berikut : ');
			echo "\n";
			echo $this->element('emails/text/properties/info');
		}

		echo $this->element('emails/text/kpr/info');

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

