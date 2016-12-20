<?php
		$application_particular = !empty($application_particular)?$application_particular:false;
		$_global_variable = !empty($_global_variable)?$_global_variable:false;
		
		if(!empty($application_particular)){	
			$first_name = $this->Rumahku->filterEmptyField($application_particular, 'first_name');
			$last_name = $this->Rumahku->filterEmptyField($application_particular, 'last_name');
			$full_name = sprintf('%s %s', $first_name, $last_name);
			$gender_id = $this->Rumahku->filterEmptyField($application_particular, 'gender_id');
			$gender = $this->Rumahku->filterEmptyField($_global_variable, 'gender_options', $gender_id);
			$no_ktp = $this->Rumahku->filterEmptyField($application_particular, 'ktp');
			$birthplace = $this->Rumahku->filterEmptyField($application_particular, 'birthplace');
			$birthday = $this->Rumahku->filterEmptyField($application_particular, 'birthday');
			$phone = $this->Rumahku->filterEmptyField($application_particular, 'phone');
			$no_hp = $this->Rumahku->filterEmptyField($application_particular, 'no_hp');
			$email = $this->Rumahku->filterEmptyField($application_particular, 'email');
			$company = $this->Rumahku->filterEmptyField($application_particular, 'company');
			$status_marital = $this->Rumahku->filterEmptyField($application_particular, 'status_marital');
			## ADDRESS
			$address = $this->Rumahku->filterEmptyField($application_particular, 'address');
			$address_2 = $this->Rumahku->filterEmptyField($application_particular, 'address_2');
			$rt = $this->Rumahku->filterEmptyField($application_particular, 'rt');
			$rw = $this->Rumahku->filterEmptyField($application_particular, 'rw');
			$region = $this->Rumahku->filterEmptyField($application_particular, 'Region', 'name');
			$city = $this->Rumahku->filterEmptyField($application_particular, 'City', 'name');
			$subarea = $this->Rumahku->filterEmptyField($application_particular, 'Subarea', 'name');
			$zip = $this->Rumahku->filterEmptyField($application_particular, 'zip');
			##
			## DOCUMENT
			$document = $this->Rumahku->filterEmptyField($application_particular, 'Document');
	 		##
			## DATA PERSONAL 
			$income = $this->Rumahku->filterEmptyField($application_particular, 'income');
			$household_fee = $this->Rumahku->filterEmptyField($application_particular, 'household_fee');
			$other_installment = $this->Rumahku->filterEmptyField($application_particular, 'other_installment');
			$customIncome = $this->Rumahku->getCurrencyPrice($income);
			$customOtherInstallment = $this->Rumahku->getCurrencyPrice($other_installment);
			$customHouseholdFee = $this->Rumahku->getCurrencyPrice($household_fee);
			##
			$same_as_address_ktp = $this->Rumahku->filterEmptyField($application_particular, 'same_as_address_ktp');
			$job_type = $this->Rumahku->filterEmptyField($application_particular, 'JobType', 'name');
			$customBirthday = $this->Rumahku->formatDate($birthday, 'd M Y', false);

	        $location = $this->Rumahku->getGenerateAddress($address, array(
	            'rt' => $rt,
	            'rw' => $rw,
	            'region' => $region,
	            'city' => $city,
	            'subarea' => $subarea,
	            'zip' => $zip,
	        ), ', ', '-');
?>
<div class="mtl5">
	<div class="row">
		<div class="col-sm-6">
			<?php
				echo $this->element('blocks/kpr/apply_detail/sub_detail/biodata_pemohon', array(
					'label_header' => __('Pasangan'),
	                'full_name' => $full_name,
	                'gender' => $gender,
	                'no_ktp' => $no_ktp,
	                'birthday' => $birthday,
	                'birthplace' => $birthplace,
	                'phone' => $phone,
	                'no_hp' => $no_hp,
	                'email' => $email,
	                'status_marital' => $status_marital,
	            ));

	            echo $this->element('blocks/kpr/apply_detail/sub_detail/info_profesi', array(
					'label_header' => __('Pasangan'),
	                'company' => $company,
	                'job_type' => $job_type,
	            ));

			?>
		</div>
		<div class="col-sm-6">
	        <?php
	               echo $this->element('blocks/kpr/apply_detail/sub_detail/biodata_address', array(
						'label_header' => __('Pasangan'),
	                    'address' => $address,
	                    'address_2' => $address_2,
	                    'location' => $location,
	                    'same_as_address_ktp' => $same_as_address_ktp
	                )); 

	               if(in_array(true, array( $customIncome, $customOtherInstallment, $customHouseholdFee))){
			               echo $this->element('blocks/kpr/apply_detail/sub_detail/informasi_financial', array(
								'label_header' => __('Pasangan'),
			                    'customIncome' => $customIncome,
			                    'customOtherInstallment' => $customOtherInstallment,
			                    'customHouseholdFee' => $customHouseholdFee,
			                )); 
	               }

	        ?>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-12">
	        <?php
	                if(!empty($document)){
	                   echo $this->element('blocks/kpr/apply_detail/sub_detail/document_detail', array(
	                        'document' => $document,
	                    )); 
	                }
	        ?>
	    </div>
	</div>
</div>

<?php
	}
?>