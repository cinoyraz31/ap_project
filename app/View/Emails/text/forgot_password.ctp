<?php
		$ip = !empty($params['ip']) ? sprintf('dari IP %s', $this->Rumahku->safeTagPrint( $params['ip'] )) : '';
		$reset_code = !empty($params['reset_code']) ? $this->Rumahku->safeTagPrint( $params['reset_code']) : false;
		$url = $this->Html->url(array(
			'controller' => 'users',
			'action' => 'password_reset',
			$reset_code,
			'admin' => true,
		), true);

		printf(__('Permintaan Reset Password'));
		echo "\n\n";
		
		printf(__('Anda telah melakukan permintaan untuk mereset password Anda %s. Untuk melanjutkan proses reset, silahkan kunjungi link di bawah ini.'), $ip);
		echo "\n\n";

		echo $url;
		echo "\n\n";

		echo __('Kode reset hanya berlaku selama dua hari. Setelah itu, Anda harus mengulang proses reset password.');
		echo "\n\n";

		echo __('Jika Anda tidak merasa melakukan permintaan reset password, mohon periksa kembali account Anda, dan mengganti password Anda apabila dirasa perlu, untuk keamanan akun Anda dan mencegah hal-hal yang tidak diinginkan.');
?>
