<?php 
		$currency = Configure::read('__Site.config_currency_symbol');

		$id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'id');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'mls_id');
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'property_price');
		$created = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'created');
		$currency = $this->Rumahku->filterEmptyField($params, 'Currency', 'symbol', $currency);
		$currency = sprintf('%s ', $currency);

		$dp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'dp');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_total');
		$credit_fix = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_fix');
		$interest_rate = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'interest_rate');
		$credit_float = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'credit_float');
		$floating_rate = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'floating_rate');
		$total_first_credit = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'total_first_credit');

		$customDp = $this->Rumahku->getFormatPrice($dp, '-', $currency);
		$customPrice = $this->Rumahku->getFormatPrice($property_price, '-', $currency); 
		$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price, '-', $currency); 
		$customTotalFirstCredit = $this->Rumahku->getFormatPrice($total_first_credit, '-', $currency); 
        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);
        $customRate = sprintf("%s%s %s %s tahun", $interest_rate, '%', $this->Html->tag('i', __('effective fixed')), $credit_fix);
        $customCreated = $this->Rumahku->formatDate($created, 'd M Y');

        $costKPR = $this->Kpr->_callCalcCostKpr($params);
        $total = $this->Rumahku->filterEmptyField($costKPR, 'total');
		$customTotal = $this->Rumahku->getFormatPrice($total, '-', $currency);
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr style="background: #fff;">
	    	<td align="center" style="padding: 15px 0">
	      		<?php
	    			echo $this->Html->image('/img/email/btn/approved.png', array(
						'fullBase' => true,
					));
	    		?>
	    	</td>
	  	</tr>
	</tbody>
</table>

<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('h1', __('Terima Kasih.'), array(
						'style' => 'font-size: 31px; margin-bottom: 0; font-weight: 700; color: #214798; text-align:center'
					));
					echo $this->Html->tag('h4', __('Pengajuan permohonan KPR Bank BTN Anda, <br>telah kami terima. Berikut adalah informasi detil mengenai permohonan KPR Anda.'), array(
						'style' => 'font-size:21px; font-weight:400; text-align:center; margin-top: 0; padding-bottom: 30px; line-height: 30px; border-bottom: 1px solid #ccc;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 10px 20px;">
      			<table cellpadding="0" cellspacing="0" border="0">
      				<tbody>
	        			<tr>
	          				<td>
					            <div>
					              	<table style="line-height: 1.5em; font-size: 16px;">
					              		<tbody>
					              			<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Tgl Pengajuan');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
							                  				echo sprintf(__(': %s'), $customCreated);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Properti ID');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
							                  				echo sprintf(__(': %s'), $mls_id);
							                  				if( !empty( $mls_id ) ){
							                  					echo ' - ';
							                  					$link = $this->Html->url(array(
																	'controller' => 'properties', 
																	'action' => 'detail',
																	$mls_id,
																	'application' => $id,
																	'admin' => true,
																), true);
												      			echo $this->Html->link(__('Lihat Properti'), $link, array(
																	'style' => 'text-decoration: none; cursor: pointer;'
																));
							                  				}
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Harga Properti');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(': %s', $customPrice);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Uang Muka');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(': %s', $customDp);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Jumlah Pinjaman');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(__(': %s'), $customLoanPrice);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Jangka Waktu');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(__(': %s'), $customLoanTime);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Suku Bunga');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(__(': %s'), $customRate);
						                  			?>
						                  		</td>
						                	</tr>
						                	<?php
						                		if( !empty($credit_float) ) {
						                	?>

						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Suku Bunga Floating');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(__(':%s%s'), $floating_rate, '%');
						                  			?>
						                  		</td>
						                	</tr>
						                	
						                	<?php
						                		}
						                	?>

						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Angsuran perbulan');
						                  			?>
						                  		</td>
						                  		<td style="padding-bottom:10px;">
						                  			<?php  
						                  					printf(__(': %s'), $customTotalFirstCredit);
						                  			?>
						                  		</td>
						                	</tr>
						                	<tr>
						                  		<td style="padding-bottom:10px;" width="200">
						                  			<?php  
						                  				echo __('Pembayaran Pertama');
						                  			?>
						                  		</td>
						                  		<td>
						                  			<?php  
						                  					printf(': %s', $customTotal);
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
	    	<td style="padding: 0 20px;">
	    		<?php
	      			echo $this->Html->tag('p', __('Anda akan segera dihubungi, terkait permohonan KPR Anda. <br> Informasi lebih lanjut, silahkan hubungi hotline Rumahku.com:<br> <span style="font-weight:bold;">(021) 5332555 Ext. 705 (Ibu Mar&rsquo;atin)</span>'), array(
						'style' => 'border-top: 1px solid #ccc; line-height: 30px; font-weight:400; padding-top:20px; text-align:center;'
					));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>
