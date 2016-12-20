<?php 
		$_global_variable = !empty($_global_variable) ? $_global_variable : false;
		$kpr_bank = $this->Rumahku->filterEmptyField($params, 'KprBank');

		$client = $this->Rumahku->filterEmptyField($params, 'KprBank', 'client_name');
		$client = $this->Rumahku->filterEmptyField($kpr_bank, 'KprApplication', 'full_name', $client);
		$client = $this->Rumahku->filterEmptyField($params, 'User', 'full_name', $client);

		$no_hp = $this->Rumahku->filterEmptyField($params, 'KprBank', 'client_hp');
		$no_hp = $this->Rumahku->filterEmptyField($kpr_bank, 'KprApplication', 'no_hp', $no_hp);
		$no_hp = $this->Rumahku->filterEmptyField($params, 'User', 'no_hp', $no_hp);

		$email = $this->Rumahku->filterEmptyField($params, 'KprBank', 'client_email');
		$email = $this->Rumahku->filterEmptyField($kpr_bank, 'KprApplication', 'email', $email);
		$email = $this->Rumahku->filterEmptyField($params, 'User', 'email', $email);

		$gender_id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'gender_id');
		$gender_id = $this->Rumahku->filterEmptyField($kpr_bank, 'KprApplication', 'gender_id', $gender_id);

		$gender = $this->Rumahku->filterEmptyField($_global_variable, 'gender_options', $gender_id);
		$genderCustom = !empty($gender_id) ? $gender : '-';

?>
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	<tbody>
		<tr>
			<td>
              	<table style="line-height: 1.5em;">
          			<?php  
          					if($client){
	          					echo $this->Rumahku->_callLbl('table', __('Nama Pembeli'), sprintf(__(': %s'), $client));			
          					}

          					if($email){
 	         					echo $this->Rumahku->_callLbl('table', __('Email'), sprintf(__(': %s'), $email));	
          					}

          					if($no_hp){
 	         					echo $this->Rumahku->_callLbl('table', __('No. Tlp'), sprintf(__(': %s'), $no_hp));	
          					}

          					if($gender_id){
 	         					echo $this->Rumahku->_callLbl('table', __('Jenis Kelamin'), sprintf(__(': %s'), $genderCustom));	
          					}
          			?>
              	</table>
			</td>
		</tr>
	</tbody>
</table>