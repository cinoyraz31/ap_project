<?php
		$agent_name = $this->Rumahku->filterEmptyField($params, 'Agent', 'full_name');
		$phone = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'phone');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp');
		$no_hp_2 = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp_2');
		$no_hp_is_whatsapp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp_is_whatsapp');
		$no_hp_2_is_whatsapp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp_2_is_whatsapp');
		$company_name = $this->Rumahku->filterEmptyField($params, 'UserCompany', 'name');

		if($no_hp_is_whatsapp){
			$no_hp .= __('(Whatsapp)');
		}

		if($no_hp_2_is_whatsapp){
			$no_hp_2 .= __('(Whatsapp)');
		}
?>
<tr>
	<td>
		<?php
			echo $this->Html->tag('h1', __('Informasi Agen'), array(
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
		              			<?php
		              					if($agent_name){
		              			?>
			                	<tr>
			                  		<td width="200">
			                  			<?php  
			                  				echo __('Nama');
			                  			?>
			                  		</td>
			                  		<td>
			                  			<?php  
		                  						printf(__(': %s'), $agent_name);
			                  			?>
			                  		</td>
			                	</tr>
			              			<?php
			              				}

			              				if($phone){
			              			?>
			                	<tr>
			                  		<td width="200">
			                  			<?php  
			                  				echo __('Telpon');
			                  			?>
			                  		</td>
			                  		<td>
			                  			<?php  
		                  						printf(__(': %s'), $phone);
			                  			?>
			                  		</td>
			                	</tr>
			              			<?php
			              				}

			              				if($no_hp){
			              			?>
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
			              			<?php
			              				}

			              				if($no_hp_2){
			              			?>
			                	<tr>
			                  		<td width="200">
			                  			<?php  
			                  				echo __('No. Handphone 2');
			                  			?>
			                  		</td>
			                  		<td>
			                  			<?php  
		                  						printf(__(': %s'), $no_hp_2);
			                  			?>
			                  		</td>
			                	</tr>
			              			<?php
			              				}

			              				if($company_name){
			              			?>
			                	<tr>
			                  		<td width="200">
			                  			<?php  
			                  				echo __('Perusahaan');
			                  			?>
			                  		</td>
			                  		<td>
			                  			<?php  
		                  						printf(__(': %s'), $company_name);
			                  			?>
			                  		</td>
			                	</tr>
			              			<?php
			              				}
			              			?>
			                </tbody>
		              	</table>
		            </div>
		        </td>
		    </tr>
		</tbody>
		</table>
	</td>
</tr>