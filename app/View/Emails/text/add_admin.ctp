<?php 
		$params = !empty($params)?$params:false;

		$email = $this->Rumahku->filterEmptyField($params, 'User', 'email');
		$password = $this->Rumahku->filterEmptyField($params, 'User', 'password_ori');

		$domain = $this->Rumahku->filterEmptyField($params, 'Bank', 'sub_domain');
		$bank = $this->Rumahku->filterEmptyField($params, 'Bank', 'name');

		$site_name = Configure::read('__Site.site_name');
		$site_email = Configure::read('__Site.send_email_from');
		$site_wa = Configure::read('__Site.site_wa');
		$site_phone = Configure::read('__Site.site_phone');

		printf(__('Anda telah terdaftar sebagai Admin %s di Rumahku. Silakan login sesuai dengan informasi Akun yang tertera dibawah.'), $bank);
		echo "\n\n";

		printf(__('Email: %s'), $email);
		echo "\n";
		printf(__('Password: %s'), $password);
		echo "\n\n";
		
		echo __('Anda dapat mengunjungi link berikut untuk melakukan Login:');
		echo "\n";
		echo $domain;
		echo "\n\n";

		echo __('Untuk informasi lebih lanjut, silakan menghubungi Kami di:');
		echo "\n";
		printf('%s | %s', $site_name, $site_email);
		echo "\n";
		printf(__('Sales and Support: %s'), $site_phone);
		echo "\n";
		printf(__('WhatsApp: %s'), $site_wa);
		echo "\n\n";
?>
