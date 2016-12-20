<?php 
		$client = $this->Rumahku->filterEmptyField($params, 'User', 'full_name');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_hp');
		$rekening_nama_akun = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'rekening_nama_akun');
		$rekening_bank = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'rekening_bank');
		$no_rekening = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_rekening');
		$no_npwp = $this->Rumahku->filterEmptyField($params, 'UserProfile', 'no_npwp');

		if(!empty($params['User'])){
			printf(__('Nama: %s'), $client);
			echo "\n";
			
			printf(__('No. Handphone: %s'), $no_hp);

			if( !empty($no_npwp) ) {
				echo "\n";
				
				printf(__('NPWP: %s'), $no_npwp);
				echo "\n";

				printf(__('Pemilik Rekening: %s'), $rekening_nama_akun);
				echo "\n";

				printf(__('No. Rekening: %s'), $no_rekening);
			}

			echo "\n\n";
		}

?>