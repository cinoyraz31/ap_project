<?php 
		$credit_date = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'action_date');
		$note = $this->Rumahku->filterEmptyField($params, 'KprBankCreditAgreement', 'note', false, false, 'EOL');
		$staff_bank = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'full_name');
		$staff_phone = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$date = $this->Rumahku->formatDate($credit_date, 'd M Y');
		$time = $this->Rumahku->formatDate($credit_date, 'H:i');
?>
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	<tbody>
		<tr>
			<td>
				<?php  
						echo $this->Rumahku->_callLbl('table', __('Tanggal'), sprintf(__(': %s'), $date));
      					echo $this->Rumahku->_callLbl('table', __('Pukul'), sprintf(__(': %s'), $time));
      					echo $this->Rumahku->_callLbl('table', __('Nama Klien'), sprintf(__(': %s'), $staff_bank));
      					echo $this->Rumahku->_callLbl('table', __('No. Handphone'), sprintf(__(': %s'), $staff_phone));
      					echo $this->Rumahku->_callLbl('table', __('Bertempat di'), sprintf(__(': %s'), $note));
				?>
			</td>
		</tr>
	</tbody>
</table>