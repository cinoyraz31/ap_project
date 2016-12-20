<?php
		$action_type = !empty($action_type)?$action_type:false;
		$kprBankCommission = $this->Rumahku->filterEmptyField($data, 'KprBankCommission');
		$commission = $this->Rumahku->filterEmptyField($kprBankCommission, 'value');
		$type = $this->Rumahku->filterEmptyField($kprBankCommission, 'type');
		$kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
		$document_status_arr = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');
		$customComission = $this->Rumahku->getCurrencyPrice($commission, '-');
		$labelName = false;

		if( $action_type == 'excel' ) {
			$stylePadding = 'padding: 0 10px;';
		} else {
			$stylePadding = false;
		}

		switch ($type) {
			case 'agent':
				$receiver_name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
				$account_name = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'name_account');
				$account_number = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'no_account');
				$bank_name = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'bank_name');
				$npwp = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer', 'no_npwp');
				$labelName = __('Agen');

				if( !empty($account_name) ) {
					$bankTransfers = array(
						array(
							'BankConfirmation' => array(
								'account_name' => $account_name,
								'account_number' => $account_number,
								'npwp' => $npwp,
							),
							'Bank' => array(
								'name' => $bank_name,
							),
						),
					);
				}
				break;
			case 'rumahku':
				$bankTransfers = $bankConfirmations;
				$labelName = Configure::read('__Site.site_name');
				break;
		}

?>
<table align="center" style="background:#fff;width: 100%;" cellpadding="0" cellspacing="0" border="0">
	<tbody>	
		<tr>
			<td style="<?php echo $stylePadding; ?>">
				<?php
						$value = $this->Html->tag('span', $customComission,array(
		  					'style' => 'font-size:14px;',
		  				));

		  				$label =  $this->Html->tag('strong', sprintf(__('Provisi %s'), $labelName),  array(
		  					'style' => 'font-size:14px;',
		  				));

		  				printf('%s : %s', $label, $value);
		  				// echo '<br><br>';
				?>
			</td>
		</tr>
		<?php
				if( !empty($bankTransfers) && in_array('approved_bank', $document_status_arr) ) {
		?>
		<tr>
			<td style="<?php echo $stylePadding; ?>">
				<?php 
						echo $this->Html->tag('p', __('Pembayaran dapat dilakukan ke rekening berikut:'), array(
		  					'style' => 'font-size:14px;margin: 0;',
		  				));
				?>
			</td>
		</tr>
		<?php

					foreach ($bankTransfers as $key => $transfer) {
						$account_name = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'account_name');
						$account_number = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'account_number');
						$npwp = $this->Rumahku->filterEmptyField($transfer, 'BankConfirmation', 'npwp');
						$bank = $this->Rumahku->filterEmptyField($transfer, 'Bank', 'name');

					?>
		<tr>
			<td style="<?php echo $stylePadding; ?>">
				<ul>
					<?php
						if(!empty($bank)){
							$value = $this->Html->tag('span', $bank,array(
			  					'style' => 'font-size:14px;font-weight:bold;',
			  				));

			  				$label =  $this->Html->tag('label',__('Bank'),  array(
			  					'style' => 'font-size:14px;',
			  				));

			  				echo $this->Html->tag('li', sprintf('%s : %s', $label, $value));
		  				}

		  				if(!empty($account_name)){
							$value = $this->Html->tag('span', $account_name,array(
			  					'style' => 'font-size:14px;font-weight:bold;',
			  				));

			  				$label =  $this->Html->tag('label',__('Atas Nama'),  array(
			  					'style' => 'font-size:14px;',
			  				));

			  				echo $this->Html->tag('li', sprintf('%s : %s', $label, $value));
		  				}

		  				if(!empty($account_number)){
							$value = $this->Html->tag('span', $account_number,array(
			  					'style' => 'font-size:14px;font-weight:bold;',
			  				));

			  				$label =  $this->Html->tag('label',__('No Rek.'),  array(
			  					'style' => 'font-size:14px;',
			  				));

			  				echo $this->Html->tag('li', sprintf('%s : %s', $label, $value));
		  				}

		  				if(!empty($npwp)){
							$value = $this->Html->tag('span', $npwp,array(
			  					'style' => 'font-size:14px;font-weight:bold;',
			  				));

			  				$label =  $this->Html->tag('label',__('NPWP'),  array(
			  					'style' => 'font-size:14px;',
			  				));

			  				echo $this->Html->tag('li', sprintf('%s : %s', $label, $value));
		  				}
					?>
				</ul>
			</td>
		</tr>
		<?php
					}
				}
		?>
		<tr>
			<td style="<?php echo $stylePadding; ?>">
				&nbsp;
			</td>
		</tr>
	</tbody>
</table>