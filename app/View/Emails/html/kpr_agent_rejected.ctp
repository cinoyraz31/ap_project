<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank', 'code');
		$agent_name = $this->Rumahku->filterEmptyField($params, 'Agent', 'full_name');
		$application_status = $this->Rumahku->filterEmptyField($params, 'KprBank', 'application_status');

		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 20px;">
	      		<?php
		      			echo $this->Html->tag('h1', sprintf(__('Agent %s tidak melanjutkan Proses KPR - %s'), $agent_name, $code), array(
							'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #4a4a4a; text-align:center'
						));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
	      		<?php
						if( !empty($mls_id) ) {
			      			echo $this->Html->tag('h4', __('Dengan informasi sebagai berikut :'), array(
								'style' => 'margin-top:-10px; font-weight:400; text-align:center; padding-bottom: 10px;'
							));
							echo $this->element('emails/html/properties/info');
						}
				?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td>
	      		<?php
	      				if(!in_array($application_status, array('pending', 'resend'))){
							echo $this->element('emails/html/kpr/client');      					
	      				}
				?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td>
	      		<?php
						echo $this->element('emails/html/kpr/info_agent');      					
				?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td style="padding: 20px;">
	      		<?php
						echo $this->Html->tag('p', __('Dikarenakan Klien Pembeli telah memilih Bank lain'), array(
							'style' => 'font-weight:400;padding: 0;margin: 0;'
						));
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
