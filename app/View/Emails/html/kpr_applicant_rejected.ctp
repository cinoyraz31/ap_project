<?php 
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);

		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$ktp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'ktp');
		$name = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'full_name');
		$email = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'email');
		$phone = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'phone');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$created = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'created');

		$mls_id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'mls_id');
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'property_price');
		$dp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'down_payment');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_total');

		$created = $this->Rumahku->formatDate($created, 'd M Y');
		$customDp = $this->Rumahku->getFormatPrice($dp);
		$customPrice = $this->Rumahku->getFormatPrice($property_price); 
		$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price); 
        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 20px;">
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
	    	<td style="padding: 0 20px;">
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
	  	<tr>
	    	<td style="height: 70px;">
	      		<?php
		      			$link = $domain.$this->Html->url(array(
							'controller' => 'kpr', 
							'action' => 'user_apply_detail',
							$id,
							'admin' => true,
						));
		      			echo $this->Html->link(__('Lihat Detil Permohonan'), $link, array(
							'style' => 'width: 200px; padding:10px 15px; background:#204798; color: #fff; text-decoration: none; border-radius: 3px; margin: 10px 0 30px 190px; text-align: center;'
						));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>
