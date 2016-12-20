<?php 
		$client = $this->Rumahku->filterEmptyField($params, 'User', 'full_name');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp');
		$name_account = $this->Rumahku->filterEmptyField($params, 'KprBankTransfer', 'name_account');
		$bank_name = $this->Rumahku->filterEmptyField($params, 'KprBankTransfer', 'bank_name');
		$no_account = $this->Rumahku->filterEmptyField($params, 'KprBankTransfer', 'no_account');
		$no_npwp = $this->Rumahku->filterEmptyField($params, 'KprBankTransfer', 'no_npwp');

		if(!empty($params['User'])){
?>
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	<tbody>
		<tr>
			<td>
              	<table style="line-height: 1.5em;">
          			<?php  
          					echo $this->Rumahku->_callLbl('table', __('Nama'), sprintf(__(': %s'), $client));
          					echo $this->Rumahku->_callLbl('table', __('No. Handphone'), sprintf(__(': %s'), $no_hp));

          					if( !empty($no_npwp) ) {
	          					echo $this->Rumahku->_callLbl('table', __('NPWP'), sprintf(__(': %s'), $no_npwp));
	          					echo $this->Rumahku->_callLbl('table', __('Pemilik Rekening'), sprintf(__(': %s'), $name_account));
	          					echo $this->Rumahku->_callLbl('table', __('No. Rekening'), sprintf(__(': %s'), $no_account));
	          				}
          			?>
              	</table>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
?>