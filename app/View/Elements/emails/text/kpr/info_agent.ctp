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

		echo __('Informasi Agen');
		echo "\n\n";

		if($agent_name){
			printf(__('Nama : %s'), $agent_name);
			echo "\n";			
		}

		if($no_hp){
			printf(__('No. Handphone : %s'), $no_hp);
			echo "\n";
		}

		if($no_hp_2){
			printf(__('No. Handphone 2 : %s'), $no_hp_2);
			echo "\n";
		}

		if($company_name){
			printf(__('Perusahaan : %s'), $company_name);
			echo "\n";
		}
?>
