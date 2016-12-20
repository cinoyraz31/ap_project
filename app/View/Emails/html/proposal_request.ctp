<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$code = $this->Rumahku->filterEmptyField($params, 'KprBank','code');
		$agent = $this->Rumahku->filterEmptyField($params, 'Agent', 'full_name');
		$phone = $this->Rumahku->filterEmptyField($params, 'AgentProfile', 'phone');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'AgentProfile', 'no_hp');
		$no_hp_2 = $this->Rumahku->filterEmptyField($params, 'AgentProfile', 'no_hp_2');
		$no_hp_is_whatsapp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp_is_whatsapp');
		$no_hp_2_is_whatsapp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp_2_is_whatsapp');
		$company_name = $this->Rumahku->filterEmptyField($params, 'UserCompany', 'name');

		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');

		if($no_hp_is_whatsapp){
			$no_hp .= __('(Whatsapp)');
		}

		if($no_hp_2_is_whatsapp){
			$no_hp_2 .= __('(Whatsapp)');
		}


		if( !empty($mls_id) ) {
        	$photo = $this->Rumahku->filterEmptyField($params, 'Property', 'photo');
       	 	$title = $this->Rumahku->filterEmptyField($params, 'Property', 'title');
        	$property_path = Configure::read('__Site.property_photo_folder');
	        $label = $this->Property->getNameCustom($params);
	        $slug = $this->Rumahku->toSlug($label);
	        $price  = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'property_price');

       	 	if( !empty($price) ) {
	        	$price = $this->Rumahku->getCurrencyPrice($price);
	        } else {
	        	$price = $this->Property->getPrice($params);
	        }

	        $customPhoto = $this->Rumahku->photo_thumbnail(array(
	            'save_path' => $property_path, 
	            'src'=> $photo, 
	            'size' => 'l',
	            'fullbase' => $domain,
	        ), array(
	            'title' => $title,
	            'alt' => $title,
	            'style' => 'width: 150px;margin-right: 15px;',
	        ));
	        $url = $domain.$this->Html->url(array(
	            'controller'=> 'properties', 
	            'action' => 'detail',
	            $mls_id,
	            'admin'=> true,
	        ));
	    }
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td>
	      		<?php
		      			echo $this->Html->tag('h1', sprintf(__('Referral KPR %s'), $code), array(
							'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #4a4a4a; text-align:center;margin: 20px 0;line-height: 24px;'
						));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td>
	      		<?php
		      			echo $this->Html->tag('h4', __('Kami informasikan bahwa Agen dibawah ini :'), array(
							'style' => 'margin-top:-10px; font-weight:400; text-align:left; padding-bottom: 10px;font-size: 14px;line-height: 24px;'
						));
	      		?>
	      		<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	      			<tbody>
		        		<tr>
		          			<td>
					            <div>
					              	<table style="line-height: 1.5em;">
			                  			<?php  
			                  					echo $this->Rumahku->_callLbl('table', __('Nama'), sprintf(__(': %s'), $agent));
			                  					echo $this->Rumahku->_callLbl('table', __('No. Telp'), sprintf(__(': %s'), $phone));
			                  					echo $this->Rumahku->_callLbl('table', __('No. Hanphone'), sprintf(__(': %s'), $no_hp));
			                  					echo $this->Rumahku->_callLbl('table', __('No. Hanphone 2'), sprintf(__(': %s'), $no_hp_2));
			                  					echo $this->Rumahku->_callLbl('table', __('Perusahaan'), sprintf(__(': %s'), $company_name));
			                  			?>
					              	</table>
					            </div>
		          			</td>
		        		</tr>
		        	</tbody>
	      		</table>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td>
	      		<?php
						if( !empty($mls_id) ) {
			      			echo $this->Html->tag('h4', __('Mengajukan referral KPR dengan informasi sebagai berikut :'), array(
								'style' => 'margin-top:10px; font-weight:400; padding-bottom: 10px;font-size: 14px;'
							));
	      		?>
	      		<table class="property" border="0" cellspacing="0" cellpadding="20" width="100%" bgcolor="#f0f0f0" style="border-bottom: 1px solid #e5e5e5; border-radius: 5px 5px 0 0;">
					<tbody>
						<tr>
							<td align="left" valign="top" style="padding: 10px;">
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
									<tr>
										<td align="left" valign="top" style="width: 150px;">
											<?php 
													echo $this->Html->link($customPhoto, $url, array(
														'escape' => false,
														'style' => 'margin: 0;padding: 0;',
													));
											?>
										</td>
										<td align="left" valign="top">
											<div style="color: #000000;">
												<?php 
														echo $this->Html->tag('div', $label, array(
															'style' => 'font-size: 12px;   display: block;line-height: 18px;',
														));
														echo $this->Html->link($title, $url, array(
															'escape' => false,
															'style' => 'display: block; margin: 0 0 2px; color: #000000; text-decoration: none;',
															'target' => '_blank',
														));
														echo $this->Html->tag('div', $this->Html->tag('strong', $price));
												?>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<?php 
						}
				?>
	    	</td>
	  	</tr>
	  	<tr>
	  		<td>
	      		<?php
						echo $this->element('emails/html/kpr/info');
				?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="text-align: center;padding:20px 0;">
	      		<?php
		      			$link = $domain.$this->Html->url(array(
							'controller' => 'kpr', 
							'action' => 'user_apply_detail',
							$id,
							'admin' => true,
						));
		      			echo $this->Html->link(__('Lihat Selengkapnya'), $link, array(
							'style' => 'padding:10px 15px;background:#204798;color:#fff;text-decoration:none;margin:0;text-align:center;line-height: 68px;',
							'target' => '_blank',
						));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>
