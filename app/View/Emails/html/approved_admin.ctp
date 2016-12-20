<?php
		$site_name = Configure::read('__Site.site_name');
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank', 'code');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
?>

<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 0 20px;">
	  			<?php
		      			echo $this->Html->tag('h1', __('%s telah memverifikasi  dan mengirimkan aplikasi KPR #%s', $site_name, $code), array(
							'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #4a4a4a; text-align:center;margin: 20px 0;line-height: 24px;'
						));
	      		?>
	  		</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
	      		<?php
		      			echo $this->Html->tag('h4', __('Kami informasikan bahwa Agen dibawah ini :'), array(
							'style' => 'margin-top:-10px; font-weight:400; text-align:left; padding-bottom: 10px;font-size: 14px;line-height: 24px;'
						));
						echo $this->element('emails/html/kpr/agent');
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
	      		<?php
						if( !empty($mls_id) ) {
			      			echo $this->Html->tag('h4', __('Mengajukan Aplikasi KPR dengan informasi sebagai berikut : '), array(
								'style' => 'margin-top:10px; font-weight:400; padding-bottom: 10px;font-size: 14px;'
							));
							echo $this->element('emails/html/properties/info');
						}
				?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 20px;">
	      		<?php
						echo $this->element('emails/html/kpr/info');
				?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td>
	      		<?php
		      			$link = $domain.$this->Html->url(array(
							'controller' => 'kpr', 
							'action' => 'user_apply_detail',
							$id,
							'admin' => true,
						));
		      			echo $this->Html->link(__('Lihat Selengkapnya'), $link, array(
		      				'target' => '_blank',
							'style' => 'padding:10px 15px;background:#204798;color:#fff;text-decoration:none;margin:20px auto 20px 200px;text-align:center;line-height: 68px;'
						));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>