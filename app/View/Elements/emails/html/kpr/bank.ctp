<?php 
		$bank = $this->Rumahku->filterEmptyField($params, 'Bank', 'name');
		$bank_phone = $this->Rumahku->filterEmptyField($params, 'Bank', 'phone');
		$bankContact = $this->Rumahku->filterEmptyField($params, 'BankContact');
		$phone_contact_arr = Set::classicExtract($bank_contact, '{n}.BankContact.phone');

?>
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
	<tbody>
		<tr>
			<td>
              	<table style="line-height: 1.5em;">
          			<?php  
          					echo $this->Rumahku->_callLbl('table', __('Bank'), sprintf(__(': %s'), $bank));

          					if(!empty($phone_contact_arr)){
				                foreach($phone_contact_arr AS $key => $phone_contact){
				                  	echo $this->Rumahku->_callLbl('table', __('No. Tlp %s', ($key+1)), sprintf(__(': %s'), $bank_phone));
				                }
				            }else{
	          					echo $this->Rumahku->_callLbl('table', __('No. Tlp'), sprintf(__(': %s'), $bank_phone));			            	
				            }
          			?>
              	</table>
			</td>
		</tr>
	</tbody>
</table>