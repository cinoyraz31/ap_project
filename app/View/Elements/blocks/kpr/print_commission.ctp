<?php
	$date_now = date('d M Y');
	$note_footer = null;

	## DATA OFFICER
	$officer = Configure::read('User.data');
	$officer_name = $this->Rumahku->filterEmptyField($officer, 'full_name');

	$id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id');  

	## DATA AGENT
	$rekening_nama_akun = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'rekening_nama_akun');  
	$company_name = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'company_name');  
	$rekening_bank = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'rekening_bank');  
	$no_rekening = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'no_rekening');  
	$no_npwp = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'no_npwp'); 

	$mls_id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'mls_id'); 

	# DATA KPR
	$code_kpr = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'code');  

	$loan_price = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'loan_price'); 
	$loan_plafond = $this->Rumahku->filterEmptyField($value, 'KprApplicationConfirm', 'loan_plafond'); 
	$loan_price = !empty($loan_plafond)?$loan_plafond:$loan_price;
	$customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price, '-');

	$dataKprCommission = $this->Rumahku->filterEmptyField($value, 'dataKprCommission');
	$commission = $this->Rumahku->filterEmptyField($dataKprCommission, 'KprCommissionPayment', 'commission');
	$commission_c = $this->Rumahku->filterEmptyField($dataKprCommission, 'KprCommissionPaymentConfirm', 'commission');
	$commission = !empty($commission_c)?$commission_c:$commission;
	$customComission = $this->Rumahku->getCurrencyPrice($commission, '-');

	$document_status = $this->Rumahku->filterEmptyField($value, 'KprApplication','document_status');
	$label = __('Provisi pengajuan KPR %s');
	if($document_status == 'akad_credit'){
		$konfirmation_comission = __('pembayaran');
		$question = sprintf( $label, __('yang harus dibayarkan'));
	}else{
		$question = sprintf(__('ilustrasi %s'), sprintf($label, ''));
		$konfirmation_comission = __('ilustrasi rincian');
		$note_footer = __('*Perhitungan tertampil hanya angka ilustrasi');
	}
	$question = sprintf('Berikut rincian pembayaran %s:',$question);

	$note_header = sprintf('Bagikan informasi %s Provisi',$konfirmation_comission);

	$receiver_name = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'name');
	$no_account = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'no_account');
	$bank_name = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'bank_name');
	$npwp = $this->Rumahku->filterEmptyField($dataKprCommission, 'profile_claim', 'npwp');

	$extra_note = $this->Rumahku->filterEmptyField($value, 'FinanceConfirmation', 'note');

	$urlProperty = array(
	    'controller' => 'properties',
	    'action' => 'detail',
	    $mls_id,
	    'application' => $id,
	    'admin' => true,
	);
	$urlProperty = $this->Html->url($urlProperty);

	$mls_id = $this->Html->link($mls_id, sprintf('%s%s', FULL_BASE_URL, $urlProperty), array(
		'target' => 'blank',
		'escape' => false,
	));

?>

<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
	<tbody>	
		<tr>
			<td style="padding: 0 20px;text-align:right;">
	  			<?php
	  				echo $this->Html->tag('span', $date_now, array(
	  					'style' => 'margin-top:-10px; font-weight:bold; padding-bottom: 10px;font-size: 14px;line-height: 24px;'
	  				));
	  			?>
	  		</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;">
				<?php
					$code = $this->Html->tag('span', $code_kpr,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('Nomor'),  array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				printf('%s : %s', $label, $code);
				?>
			</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;">
				<?php
					$note_header = $this->Html->tag('span', $note_header,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('Hal'),  array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				printf('%s : %s', $label, $note_header);
				?>
				<br><br>
			</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;">
				<?php

					echo $this->Html->tag('span', __('Kepada <br> Yth. Bagian Keuangan <br> di tempat'), array(
						'style' => 'font-size:12px;',
					));

				?>
				<br><br>
			</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;">
				<?php
					echo $this->Html->tag('span', $question, array(
						'style' => 'font-size:12px;',
					));
				?>
				<br>
			</td>
		</tr>
			<?php
				if(!empty($receiver_name)){
			?>
					<tr>
						<td style="padding: 0 20px;">
							<?php
								$value = $this->Html->tag('span', $receiver_name,array(
				  					'style' => 'font-size:12px;font-weight:bold;',
				  				));

				  				$label =  $this->Html->tag('label',__('Penerima'),  array(
				  					'style' => 'font-size:12px;',
				  				));

				  				printf('%s : %s', $label, $value);
							?>
						</td>
					</tr>
			<?php
				}

				if(!empty($bank_name)){
			?>
					<tr>
						<td style="padding: 0 20px;">
							<?php
								$value = $this->Html->tag('span', $bank_name,array(
				  					'style' => 'font-size:12px;font-weight:bold;',
				  				));

				  				$label =  $this->Html->tag('label',__('Bank'),  array(
				  					'style' => 'font-size:12px;',
				  				));

				  				printf('%s : %s', $label, $value);
							?>
						</td>
					</tr>
			<?php
				}

				if(!empty($no_account)){
			?>
					<tr>
						<td style="padding: 0 20px;">
							<?php
								$value = $this->Html->tag('span', $no_account,array(
				  					'style' => 'font-size:12px;font-weight:bold;',
				  				));

				  				$label =  $this->Html->tag('label',__('No. Rek'),  array(
				  					'style' => 'font-size:12px;',
				  				));

				  				printf('%s : %s', $label, $value);
							?>
						</td>
					</tr>
			<?php
				}

				if(!empty($npwp)){
			?>
					<tr>
						<td style="padding: 0 20px;">
							<?php
								$value = $this->Html->tag('span', $npwp,array(
				  					'style' => 'font-size:12px;font-weight:bold;',
				  				));

				  				$label =  $this->Html->tag('label',__('NPWP'),  array(
				  					'style' => 'font-size:12px;',
				  				));

				  				printf('%s : %s', $label, $value);
							?>
							<br><br>
						</td>
					</tr>
			<?php
				}
			?>

		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', $code_kpr,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('ID KPR '),  array(
	  					'style' => 'font-size:12px;',
	  				));

	  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', $mls_id,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('ID Listing'),  array(
	  					'style' => 'font-size:12px;',
	  				));

	  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', $customLoanPrice,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('Plafon'),  array(
	  					'style' => 'font-size:12px;',
	  				));

	  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', $customComission,array(
	  					'style' => 'font-size:12px;font-weight:bold;',
	  				));

	  				$label =  $this->Html->tag('label',__('Provisi'),  array(
	  					'style' => 'font-size:12px;',
	  				));

	  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', $note_footer, array(
	  					'style' => 'font-size:9px;',
	  				));

	  				print($value);
				?>
				<br><br>
			</td>
		</tr>
		<?php
			if(!empty($extra_note)){
		?>
			<tr>
				<td style="padding: 0 20px;">
					<?php
						echo $this->Html->tag('span',__('Keterangan :'), array(
							'style' => 'font-size:12px;font-weight:bold;display:block;'
						));
						$value = $this->Html->tag('span', $extra_note, array(
		  					'style' => 'font-size:12px;font-style: italic;',
		  				));

		  				print($value);
					?>
					<br><br>
				</td>
			</tr>
		<?php
			}
		?>
		

		<tr>
			<td style="padding: 0 20px;">
				<?php
					$value = $this->Html->tag('span', __('Demikian disampaikan untuk menjadi perhatian, terima kasih.'),array(
	  					'style' => 'font-size:12px;',
	  				));

	  				print($value);
				?>
				<br><br><br>
			</td>
		</tr>

		<tr>
			<td style="padding: 0 20px;text-align:right;">
				<?php
					$value = $this->Html->tag('span', __('Petugas'), array(
	  					'style' => 'font-size:12px;text-align:right',
	  				));

	  				print($value);
	  			?>
	  			<br><br><br><br>
	  			<?php
	  				$value = $this->Html->tag('span', $officer_name, array(
	  					'style' => 'font-size:12px;text-align:right',
	  				));

	  				print($value);
				?>
				<br>
			</td>
		</tr>
	</tbody>
</table>