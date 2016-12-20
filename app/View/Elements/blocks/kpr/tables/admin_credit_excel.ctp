<?php
		
		$admin_kredit_excel = !empty($admin_kredit_excel)?$admin_kredit_excel:false;
		$fee_paid = !empty($fee_paid)?$fee_paid:false;

		if(!empty($values)){
?>

			<tbody>

      		<?php 	$no = 1;
      				$total_income = 0;
      				$total_household_fee = 0;
	      			$total_other_installment = 0;
	      			$total_price = 0;
	      			$total_loan_price = 0;
	      			$total_down_payment = 0;
	      			$total_appraisal = 0;
	      			$total_administration = 0;
	      			$total_credit_agreement = 0;
	      			$total_commission_agent = 0;
	      			$total_commission_company = 0;

	      			foreach( $values as $key => $value ) {

	      				$id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id');
	      				$mls_id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'mls_id');
	      				$code = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'code');
	      				$first_name = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'first_name');
	      				$last_name = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'last_name');
	      				$name = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'full_name');
	      				$ktp = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'ktp');
	      				$birthplace = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'birthplace');
	      				$birthday = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'birthday');
	      				$created = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'created');
	      				$approved_admin_date = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'approved_admin_date');
	      				$assign_project_date = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'assign_project_date');
        				$is_id_company = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'is_id_company');

	      				$address = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'address');
	      				$address_2 = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'address_2');
	      				$rt = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'rt');
	      				$rw = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'rw');
	      				$zip = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'zip');

	      				$phone = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'phone');
	      				$no_hp = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'no_hp');
	      				$no_hp_2 = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'no_hp_2');

	      				$company = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'company');
	      				$job_type = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'job_type');

	      				$ktp_file = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'ktp_file');
	      				$income_file = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'income_file');

	      				$interest_rate = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'interest_rate');
	      				$floating_rate = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'floating_rate');
	      				$customInterestRate = sprintf('%s%s',$interest_rate,__('%'));
	      				$customFloatingRate = sprintf('%s%s',$floating_rate,__('%'));


	      				$income = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'income');
	      				$household_fee = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'household_fee');
	      				$other_installment = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'other_installment');
	      				$property_price = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'property_price');
	      				$loan_price = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'loan_price', 0);

	      				$appraisal = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'appraisal', 0);
	      				$administration = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'administration', 0);
	      				$credit_agreement = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_agreement', 0);

	      				$credit_fix = sprintf("%s Tahun", $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_fix', 0));	
	      				$credit_float = sprintf("%s Tahun", $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_float', 0));
	      				$credit_total = sprintf("%s Tahun", $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_total', 0));

	      				$down_payment = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'down_payment', 0);
	      				$commission_agent = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'komisi_agen', 0);
	      				$commission_company = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'komisi_rku', 0);

	      				$customApplyDate = $this->Rumahku->formatDate($created, 'd M Y');
	      				$approved_admin_date = $this->Rumahku->formatDate($approved_admin_date, 'd M Y');
	      				$assign_project_date = $this->Rumahku->formatDate($assign_project_date, 'd M Y');
	      				$customBirthday = $this->Rumahku->formatDate($birthday, 'd M Y');

	      				$customIncome = $this->Rumahku->getFormatPrice($income);
	      				$customHouseHoldFee = $this->Rumahku->getFormatPrice($household_fee);
	      				$customOtherInstallment = $this->Rumahku->getFormatPrice($other_installment);

	      				$customAppraisal = $this->Rumahku->getFormatPrice($appraisal);
	      				$customAdministration = $this->Rumahku->getFormatPrice($administration);
	      				$customCreditAgreement = $this->Rumahku->getFormatPrice($credit_agreement);

	      				$customCommissionAgent = $this->Rumahku->getFormatPrice($commission_agent);
	      				$customCommissionCompany = $this->Rumahku->getFormatPrice($commission_company);

	      				$customPropertyPrice = $this->Rumahku->getFormatPrice($property_price);
	      				$customLoanPrice = $this->Rumahku->getFormatPrice($loan_price);
	      				$customDownPayment = $this->Rumahku->getFormatPrice($down_payment);
	      				$customPhone = $this->Rumahku->_callFormatPhoneNumber($phone);
	      				$customNoHp = $this->Rumahku->_callFormatPhoneNumber($no_hp);
	      				$customNoHp2 = $this->Rumahku->_callFormatPhoneNumber($no_hp_2);
	      				$status = $this->Kpr->_callStatus($value, true);

	      				$bank_apply_category = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'code');

	      				$property_address =  $this->Rumahku->filterEmptyField($value,'PropertyAddress');
	      				$subarea = $this->Rumahku->filterEmptyField($property_address, 'Subarea', 'name');
	      				$city = $this->Rumahku->filterEmptyField($property_address, 'City', 'name');
	      				$region = $this->Rumahku->filterEmptyField($property_address, 'Region', 'name');

	      				$property_type = $this->Rumahku->filterEmptyField($value,'PropertyType','name');
	      				$bank_name = $this->Rumahku->filterEmptyField($value,'Bank','name');
	      				$agent_name = $this->Rumahku->filterEmptyField($value,'User','full_name');
	      				$email_agent = $this->Rumahku->filterEmptyField($value,'User','email');

        				
        				$save_path = $this->Rumahku->_callPathKprApplication($is_id_company);


		      			## GRAND TOTAL

		      			$total_income += $income;
		      			$total_household_fee += $household_fee;
		      			$total_other_installment += $other_installment;
		      			$total_price += $property_price;
		      			$total_loan_price += $loan_price;
		      			$total_down_payment += $down_payment;
		      			$total_appraisal += $appraisal;
		      			$total_administration += $administration;
		      			$total_credit_agreement += $credit_agreement;
		      			$total_commission_agent += $commission_agent;
		      			$total_commission_company += $commission_company;

						$default_arr = array(
							$no,
				            array(
				            	$bank_apply_category,
				            	array(
	               					'style' => 'text-align:center;'
			            		),
			            	),
			            	$code,
	      					$mls_id,
	      					$property_type,
	      					$bank_name,
				            $agent_name,
	      					$email_agent,
	      					$name,
	      					array(
				            	$rt,
				            	array(
	               					'style' => 'text-align:center;'
			            		),
			            	),
	      					array(
				            	$rw,
				            	array(
	               					'style' => 'text-align:center;'
			            		),
			            	),
	      					array(
	      						$zip,
				            	array(
	               					'style' => 'text-align:center;'
			            		),
			            	),
	      					$subarea,
	      					$city,
	      					$region,
	      					array(
	      						$customPhone,
				            	array(
	               					'style' => 'text-align:left;'
			            		),
			            	),
	      					array(
	      						$customNoHp,
				            	array(
	               					'style' => 'text-align:left;'
			            		),
			            	),
	      					array(
	      						$customNoHp2,
				            	array(
	               					'style' => 'text-align:left;'
			            		),
			            	),
	      					$company,
	      					$job_type,

	      					$credit_fix,
	      					$credit_float,
	      					$credit_total,
	      					$customInterestRate,
	      					$customFloatingRate,
	      					array(
				            	$customIncome,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customHouseHoldFee,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customOtherInstallment,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customPropertyPrice,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customLoanPrice,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customDownPayment,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
	      					array(
				            	$customAppraisal,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
	      					array(
				            	$customAdministration,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
	      					array(
				            	$customCreditAgreement,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customCommissionAgent,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
			            	array(
				            	$customCommissionCompany,
				            	array(
	               					'style' => 'text-align:right;'
			            		),
			            	),
	      					$assign_project_date
	            		);

	      				echo $this->Html->tableCells(array($default_arr));
	      				$no++;
					}

					$total_income = $this->Rumahku->getFormatPrice($total_income);
      				$total_household_fee = $this->Rumahku->getFormatPrice($total_household_fee);
	      			$total_other_installment = $this->Rumahku->getFormatPrice($total_other_installment);
	      			$total_price = $this->Rumahku->getFormatPrice($total_price);
	      			$total_loan_price = $this->Rumahku->getFormatPrice($total_loan_price);
	      			$total_down_payment = $this->Rumahku->getFormatPrice($total_down_payment);
	      			$total_appraisal = $this->Rumahku->getFormatPrice($total_appraisal);
	      			$total_administration = $this->Rumahku->getFormatPrice($total_administration);
	      			$total_credit_agreement = $this->Rumahku->getFormatPrice($total_credit_agreement);
	      			$total_commission_agent = $this->Rumahku->getFormatPrice($total_commission_agent);
	      			$total_commission_company = $this->Rumahku->getFormatPrice($total_commission_company);

					$default_total = array(
						array(
							'TOTAL',
							array(
									'colspan' => 25,
									'style' => 'text-align:right;font-weight: bold;'
								),
						),
						array(
			            	$total_income,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_household_fee,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_other_installment,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_price,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_loan_price,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_down_payment,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
      					array(
			            	$total_appraisal,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
      					array(
			            	$total_administration,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
      					array(
			            	$total_credit_agreement,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_commission_agent,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
		            	array(
			            	$total_commission_company,
			            	array(
               					'style' => 'text-align:right;font-weight: bold;'
		            		),
		            	),
					);

					echo $this->Html->tableCells(array($default_total));

      		?>
			</tbody>

<?php

		}
?>