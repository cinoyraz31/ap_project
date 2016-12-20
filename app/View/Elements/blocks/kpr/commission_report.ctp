<?php
		$date_now = date('d M Y');
		$note_footer = null;
		$params = !empty($params)?$params:false;
		$kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
		$document_status_arr = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');
		$kprBankCommissions = $this->Rumahku->filterEmptyField($value, 'KprBankCommission');
		$kprBankInstallment = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment');

		$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');  
		$code_kpr = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');  
		$label = __('Provisi pengajuan KPR %s');

		if( in_array('approved_bank', $document_status_arr) ){
			$konfirmation_comission = __('pembayaran');
			$question = sprintf( $label, __('yang harus dibayarkan'));
		}else{
			$question = sprintf(__('ilustrasi %s'), sprintf($label, ''));
			$konfirmation_comission = __('ilustrasi rincian');
			$note_footer = __('*Perhitungan tertampil hanya angka ilustrasi');
		}

		$question = sprintf('Berikut rincian pembayaran %s:', $question);
		$note_header = sprintf('Bagikan informasi %s Provisi', $konfirmation_comission);

		$extra_note = $this->Rumahku->filterEmptyField($params, 'FinanceConfirmation', 'note');

		$loan_price = $this->Rumahku->filterEmptyField($kprBankInstallment, 'loan_price'); 
		$customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price, '-');
		$mls_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'mls_id');
?>
<table align="center" style="background:#fff;width: 100%;font-size: 14px;margin-top:20px;" cellpadding="0" cellspacing="0" border="0">
	<tbody>	
		<tr>
			<td style="padding: 0 20px;">
				<?php
						$code = $this->Html->tag('span', $code_kpr,array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));
		  				$label =  $this->Html->tag('label',__('Nomor'),  array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				printf('%s : %s', $label, $code);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						$note_header = $this->Html->tag('span', $note_header,array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				$label =  $this->Html->tag('label',__('Hal'),  array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				printf('%s : %s', $label, $note_header);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						echo $this->Html->tag('span', __('Kepada <br> Yth. Bagian Keuangan <br> di tempat'), array(
							'style' => 'font-size:14px;',
						));
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						echo $this->Html->tag('p', $question, array(
							'style' => 'font-size:14px;',
						));
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						if(!empty($kprBankCommissions)){
							foreach ($kprBankCommissions as $key => $kprBankCommission) {
			    				echo $this->element('blocks/kpr/tables/print_comission', array(
			    					'data' => $kprBankCommission,
		    					));
							}
						}
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						echo $this->Html->tag('h3', __('Informasi Pinjaman'), array(
		  					'style' => 'font-size:14px;font-weight:bold;margin: 20px 0 10px;',
		  				));

						$value = $this->Html->tag('span', $mls_id,array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				$label =  $this->Html->tag('label',__('ID Listing'),  array(
		  					'style' => 'font-size:14px;',
		  				));

		  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						$value = $this->Html->tag('span', $customLoanPrice,array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				$label =  $this->Html->tag('label',__('Plafon'),  array(
		  					'style' => 'font-size:14px;',
		  				));

		  				printf('%s : %s', $label, $value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				&nbsp;
			</td>
		</tr>
		<?php
				if(!empty($extra_note)){
		?>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						echo $this->Html->tag('span',__('Keterangan :<br>'), array(
							'style' => 'font-size:14px;font-weight:bold;display:block;'
						));
						$value = $this->Html->tag('span', $extra_note, array(
		  					'style' => 'font-size:14px;font-style: italic;',
		  				));

		  				print($value);
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				&nbsp;
			</td>
		</tr>
		<?php
				}

				if( !empty($note_footer) ) {
		?>
		<tr>
			<td style="padding: 0 20px;">
				<?php
						echo $this->Html->tag('p', $note_footer, array(
		  					'style' => 'font-size:9px;margin: 0 0 20px;',
		  				));
				?>
				<br>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				&nbsp;
			</td>
		</tr>
		<?php 
				}
		?>
	</tbody>
</table>