<?php 
		$email = $this->Rumahku->filterEmptyField($params, 'User', 'email');
		$password = $this->Rumahku->filterEmptyField($params, 'User', 'new_password_ori');
		
		printf(__('Admin %s telah mereset password Anda.'), Configure::read('__Site.site_name'));
		echo "\n\n";

		echo printf(__('%s : %s'), __('Email'), $email);	
		echo "\n";

		echo printf(__('%s : %s'), __('Password'), $password);
		echo "\n\n";

		echo __('Jika karena suatu alasan, Anda tidak merasa melakukan permintaan reset password, mohon segera mencoba mereset password Anda kembali, lalu login ke akun Anda dan lakukan perubahan password sekali lagi untuk mencegah akses dari pihak yang tidak bertanggung-jawab. Apabila Anda menemukan kesulitan, harap menghubungi Online Support kami.');
?>