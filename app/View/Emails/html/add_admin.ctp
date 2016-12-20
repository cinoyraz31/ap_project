<?php 
		$params = !empty($params)?$params:false;

		$_site_name = Configure::read('__Site.site_name');
		$email = $this->Rumahku->filterEmptyField($params, 'User', 'email');
		$full_name = $this->Rumahku->filterEmptyField($params, 'User', 'full_name');
		$password = $this->Rumahku->filterEmptyField($params, 'User', 'password_ori');
		$division = $this->Rumahku->filterEmptyField($params, 'Group', 'name');

		$site_name = Configure::read('__Site.site_name');
		$site_email = Configure::read('__Site.send_email_from');
		$site_wa = Configure::read('__Site.site_wa');
		$site_phone = Configure::read('__Site.site_phone');

		$even = 'padding: 5px 8px;line-height: 20px;vertical-align: top;text-align: left;background-color: #f4f4f4;';
		$odd = $even.'background-color: transparent;';
		
		echo $this->Html->tag('p', sprintf(__('%s telah terdaftar sebagai divisi %s di %s. Silakan login sesuai dengan informasi Akun yang tertera dibawah.'), $full_name, $division, $_site_name), array(
			'style' => 'color: #303030; font-size: 14px; margin: 5px 0 20px; line-height: 20px;',
		));
?>
<table style="border: 1px solid #dddddd;border-collapse: separate;width: 100%;margin-bottom: 20px;max-width: 100%;background-color: transparent;border-spacing: 0;font-family: Helvetica Neue, Helvetica, rial, sans-serif;font-size: 14px;line-height: 20px;color: #333333;">
	<tbody>
		<?php 
				$contentTr = $this->Html->tag('th', __('Email'), array(
					'style' => 'font-weight: bold;color:#303030;'.$even,
				));
				$contentTr .= $this->Html->tag('td', $email, array(
					'style' => 'width: 70%;border-left: 1px solid #dddddd;'.$odd,
				));

				echo $this->Html->tag('tr', $contentTr);

				$contentTr = $this->Html->tag('th', __('Password'), array(
					'style' => 'font-weight: bold;color:#303030;'.$even,
				));
				$contentTr .= $this->Html->tag('td', $password, array(
					'style' => 'width: 70%;border-left: 1px solid #dddddd;'.$odd,
				));

				echo $this->Html->tag('tr', $contentTr);
		?>
	</tbody>
</table>
<?php
		if( !empty($domain) ) {
			echo $this->Html->tag('p', __('Anda dapat mengunjungi link berikut untuk melakukan Login:'), array(
				'style' => 'color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;',
			));
			echo $this->Html->tag('p', $this->Rumahku->wrapWithHttpLink($domain, true, array(
				'style' => 'color: #06c; font-size: 14px; margin: 5px 0 0; line-height: 20px;',
			)), array(
				'style' => 'color: #303030; font-size: 14px; margin: 5px 0 20px; line-height: 20px;',
			));
		}

		echo $this->Html->tag('p', __('Untuk informasi lebih lanjut, silakan menghubungi Kami di:'), array(
			'style' => 'color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;',
		));
		echo $this->Html->tag('p', sprintf('%s | %s', $site_name, $site_email), array(
			'style' => 'color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;font-weight: bold;',
		));
		echo $this->Html->tag('p', sprintf(__('Sales and Support: %s'), $site_phone), array(
			'style' => 'color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;font-weight: bold;',
		));
		echo $this->Html->tag('p', sprintf(__('WhatsApp: %s'), $site_wa), array(
			'style' => 'color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;font-weight: bold;',
		));
?>
