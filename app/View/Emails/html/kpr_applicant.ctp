<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
		$kprBankInstallment = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment');
		$from_kpr = $this->Rumahku->filterEmptyField($params, 'KprBank', 'from_kpr');
    	$forward_app = $this->Rumahku->filterEmptyField($params, 'KprBank', 'forward_app');
		$kprBankInstallment[0] = !empty($kprBankInstallment[0]) ? $kprBankInstallment[0] : false;
		$kprBank = $this->Rumahku->filterEmptyField($params, 'KprBank');
		$id = $this->Rumahku->filterEmptyField($kprBank, 'id');
		$ktp = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'ktp');
		$name = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'full_name');
		$email = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'email');
		$phone = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'phone');
		$no_hp = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'no_hp');
		$created = $this->Rumahku->filterEmptyField($kprBank, 'KprApplication', 'created', date('d M Y'));

		$mls_id = $this->Rumahku->filterEmptyField($kprBank, 'mls_id');
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'property_price');
		$property_price = $this->Rumahku->filterEmptyField($kprBankInstallment[0], 'KprBankInstallment', 'property_price', $property_price);

		$dp = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'down_payment');
		$dp = $this->Rumahku->filterEmptyField($kprBankInstallment[0], 'KprBankInstallment', 'down_payment', $dp);

		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'loan_price');
		$loan_price = $this->Rumahku->filterEmptyField($kprBankInstallment[0], 'KprBankInstallment', 'loan_price', $loan_price);

		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'credit_total');
		$credit_total = $this->Rumahku->filterEmptyField($kprBankInstallment[0], 'KprBankInstallment', 'credit_total', $credit_total);
		

		$created = $this->Rumahku->formatDate($created, 'd M Y');
		$customDp = $this->Rumahku->getFormatPrice($dp);
		$customPrice = $this->Rumahku->getFormatPrice($property_price); 
		$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price); 
        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td>
	      		<?php
	      			echo $this->Html->tag('h1', __('Pemohon'), array(
						'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #000;margin-bottom: 20px;'
					));
	      		?>

	      		<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	      			<tbody>
		        		<tr>
		          			<td>
					            <div>
					              	<table style="line-height: 1.5em;">
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('Tanggal Pengajuan');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $created);
					                  			?>
					                  		</td>
					                	</tr>
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('KTP');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $ktp);
					                  			?>
					                  		</td>
					                	</tr>
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('Nama');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $name);
					                  			?>
					                  		</td>
					                	</tr>
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('Email');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $email);
					                  			?>
					                  		</td>
					                	</tr>
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('No. Telp');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $phone);
					                  			?>
					                  		</td>
					                	</tr>
					                	<tr>
					                  		<td width="200">
					                  			<?php  
					                  				echo __('No. Handphone');
					                  			?>
					                  		</td>
					                  		<td>
					                  			<?php  
					                  					printf(__(': %s'), $no_hp);
					                  			?>
					                  		</td>
					                	</tr>
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
	      			echo $this->Html->tag('h1', __('Rincian Properti KPR'), array(
						'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #000;margin-bottom: 20px;'
					));
	      		?>
      			<table cellpadding="0" cellspacing="0" border="0">
      				<tbody>
	        			<tr>
	          				<td>
					            <div>
					              	<table style="line-height: 1.5em;">
					              		<tbody>
						                	<tr>
						                  		<td width="200">
						                  			<?php  
						                  				echo __('Properti ID');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
					                  						printf(__(': %s'), $mls_id);

					                  						if( !empty( $mls_id ) ){
							                  					echo ' - ';
							                  					$link = $domain.$this->Html->url(array(
																	'controller' => 'properties', 
																	'action' => 'detail',
																	$mls_id,
																	'application' => $id,
																	'admin' => true,
																));
												      			echo $this->Html->link(__('Lihat Properti'), $link, array(
												      				'target' => '_blank',
																	'style' => 'text-decoration: none; cursor: pointer;'
																));
							                  				}
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td width="200">
						                  			<?php  
						                  				echo __('Harga Properti');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
					                  						printf(__(': %s'), $customPrice);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td width="200">
						                  			<?php  
						                  				echo __('Uang Muka');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
					                  						printf(__(': %s'), $customDp);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td width="200">
						                  			<?php  
						                  				echo __('Jumlah Pinjaman');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
					                  						printf(__(': %s'), $customLoanPrice);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td width="200">
						                  			<?php  
						                  				echo __('Jangka Waktu');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
					                  						printf(__(': %s'), $customLoanTime);
						                  			?>
						                  		</td>
						                	</tr>
						                </tbody>
					              	</table>
					            </div>
					        </td>
					    </tr>
					</tbody>
      			</table>
	    	</td>
	  	</tr>
    	<?php
    			if($from_kpr == 'frontend' && empty($forward_app)){
	    			echo $this->element('emails/html/kpr/info_agent');				
    			}
    	?>
	  	<tr>
	    	<td style="height: 70px; text-align: center;padding:20px 0 0;">
	      		<?php
		      			$link = $domain.$this->Html->url(array(
							'controller' => 'kpr', 
							'action' => 'user_apply_detail',
							$id,
							'admin' => true,
						));
		      			echo $this->Html->link(__('Lihat Detil Permohonan'), $link, array(
		      				'target' => '_blank',
							'style' => 'padding:10px 15px; background:#204798; color: #fff; text-decoration: none; border-radius: 3px; margin: 10px 0 30px; text-align: center;'
						));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>
