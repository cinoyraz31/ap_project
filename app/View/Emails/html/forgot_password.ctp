<?php 
		$ip = !empty($params['ip']) ? sprintf('dari IP %s', $this->Rumahku->safeTagPrint( $params['ip'] )) : '';
		$reset_code = !empty($params['reset_code']) ? $this->Rumahku->safeTagPrint( $params['reset_code']) : false;
		$url = $this->Html->url(array(
			'controller' => 'users',
			'action' => 'password_reset',
			$reset_code,
			'admin' => true,
		), true);

?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('h1', __('Permintaan Reset Password'), array(
						'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 5px; color: #4a4a4a; text-align:center'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', sprintf(__('Anda telah melakukan permintaan untuk mereset password Anda %s. Untuk melanjutkan proses reset, silahkan kunjungi link di bawah ini.'), $ip), array(
						'style' => 'font-size: 14px; color: #4a4a4a; line-height: 20px;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', $this->Html->link(__('Klik disini untuk reset password'), $url, array(
						'style' => 'color: #00af00; text-decoration: none; font-size: 14px;', 
						'target' => '_blank'
					)), array(
						'style' => 'color: #303030; font-size: 14px;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', __('Jika Anda tidak dapat mengklik link diatas, Anda juga dapat mereset password Anda dengan mengunjungi URL di bawah ini :'), array(
						'style' => 'color: #303030; font-size: 14px; margin: 5px 0 10px; line-height: 20px;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', $url, array(
						'style' => 'cursor:pointer; color: #00af00; text-decoration: none; font-size: 14px; margin: 0 0 20px; padding: 0;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', __('Kode reset hanya berlaku selama dua hari. Setelah itu, Anda harus mengulang proses reset password.'), array(
						'style' => 'color: #303030; font-size: 14px; margin: 5px 0 10px; line-height: 20px;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('p', __('Jika Anda tidak merasa melakukan permintaan reset password, mohon periksa kembali account Anda, dan mengganti password Anda apabila dirasa perlu, untuk keamanan akun Anda dan mencegah hal-hal yang tidak diinginkan.'), array(
						'style' => 'color: #303030; font-size: 14px; margin: 5px 0 10px; line-height: 20px;'
					));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>