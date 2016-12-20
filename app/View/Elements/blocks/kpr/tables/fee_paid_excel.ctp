<?php
		$_admin = Configure::read('User.admin');

		if(!empty($values)){
			$field_bank = array();
			$tbody = null;
			$no = 1;


			foreach($values AS $key => $value){
				$code = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');
				$mls_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'mls_id');
				$unpaid_agent = $this->Rumahku->filterEmptyField($value, 'KprBank', 'unpaid_agent');
				$unpaid_rumahku = $this->Rumahku->filterEmptyField($value, 'KprBank', 'unpaid_rumahku');
				$category_bank_name = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'code');
				$property_type = $this->Rumahku->filterEmptyField($value, 'PropertyType', 'name');

				$userProfile = $this->Rumahku->filterEmptyField($value, 'UserProfile');
				$agent_name = $this->Rumahku->filterEmptyField($value, 'Agent', 'full_name');
				$agent_email = $this->Rumahku->filterEmptyField($value, 'Agent', 'email');
				$address = $this->Rumahku->filterEmptyField($userProfile, 'address');
				$rt = $this->Rumahku->filterEmptyField($userProfile, 'rt');
				$rw = $this->Rumahku->filterEmptyField($userProfile, 'rw');
				$zip = $this->Rumahku->filterEmptyField($userProfile, 'zip');
				$phone = $this->Rumahku->filterEmptyField($userProfile, 'phone');
				$no_hp = $this->Rumahku->filterEmptyField($userProfile, 'no_hp');

				$region = $this->Rumahku->filterEmptyField($userProfile, 'Region', 'name');
				$city = $this->Rumahku->filterEmptyField($userProfile, 'City', 'name');
				$subarea = $this->Rumahku->filterEmptyField($userProfile, 'Subarea', 'name');

				$company = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'name');
				$income = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'income');
				$household_fee = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'household_fee');
				$other_installment = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'other_installment');

				$bank_name = $this->Rumahku->filterEmptyField($value, 'Bank', 'code');

				$credit_total = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'credit_total');
				$interest_rate_fix = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'interest_rate_fix');
				$interest_rate_float = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'interest_rate_float');
				$property_price = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'property_price');
				$loan_price = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'loan_price');
				$down_payment = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'down_payment');
				$total_first_credit = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'total_first_credit');

				$appraisal = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'appraisal');
				$insurance = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'insurance');
				$commission = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'commission');
				$commission_rumahku = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'commission_rumahku');

				$cost_bank = $appraisal + $insurance + $commission;

				$mortgage = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'mortgage');
				$letter_mortgage = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'letter_mortgage');
				$credit_agreement = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'credit_agreement');
				$other_certificate = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'other_certificate');
				$transfer_title_charge = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'transfer_title_charge');
				$imposition_act_mortgage = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'imposition_act_mortgage');
				$sale_purchase_certificate = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'sale_purchase_certificate');

				$action_date = $this->Rumahku->filterEmptyField($value, 'KprBankDate', 'action_date');
				$action_date = $this->Rumahku->formatDate($action_date, 'd M y');

				$cost_notary = $mortgage + $letter_mortgage + $credit_agreement + $other_certificate + $transfer_title_charge + $imposition_act_mortgage + $sale_purchase_certificate;


				$location = $this->Rumahku->getGenerateAddress($address, array(
		            'rt' => $rt,
		            'rw' => $rw,
		            'region' => $region,
		            'city' => $city,
		            'subarea' => $subarea,
		            'zip' => $zip,
		        ), ', ', '-');

				$income = $this->Rumahku->getCurrencyPrice($income, '-');
				$household_fee = $this->Rumahku->getCurrencyPrice($household_fee, '-');
				$other_installment = $this->Rumahku->getCurrencyPrice($other_installment, '-');
				$property_price = $this->Rumahku->getCurrencyPrice($property_price, '-');
				$loan_price = $this->Rumahku->getCurrencyPrice($loan_price, '-');
				$down_payment = $this->Rumahku->getCurrencyPrice($down_payment, '-');
				$cost_bank = $this->Rumahku->getCurrencyPrice($cost_bank, '-');
				$cost_notary = $this->Rumahku->getCurrencyPrice($cost_notary, '-');
				$total_first_credit = $this->Rumahku->getCurrencyPrice($total_first_credit, '-');
				$commission = $this->Rumahku->getCurrencyPrice($commission, '-');
				$commission_rumahku = $this->Rumahku->getCurrencyPrice($commission_rumahku, '-');

				$unpaid_agent = ($unpaid_agent == 'no_provision') ? '-' : $unpaid_agent;
				$unpaid_rumahku = ($unpaid_rumahku == 'no_provision') ? '-' : $unpaid_rumahku;

				if($_admin){
					$field_bank = array(
						$bank_name,
					);
				}

				$field_banks = array(
					sprintf('%s Tahun', $credit_total),
					sprintf('%s%%', $interest_rate_fix),
					sprintf('%s%%', $interest_rate_float),
			        array(
		         		$property_price,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$loan_price,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$down_payment,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$cost_bank,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$cost_notary,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$total_first_credit,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$commission,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$unpaid_agent,
			            array(
			            	'class' => 'tacenter',
		            	),
			        ),
			        array(
		         		$commission_rumahku,
			            array(
			            	'class' => 'taright',
		            	),
			        ),
			        array(
		         		$unpaid_rumahku,
			            array(
			            	'class' => 'tacenter',
		            	),
			        ),
					$action_date,
				);

				$field_banks = array_merge($field_bank, $field_banks);

				$default_arr = array_merge(array(
					$no,
					$category_bank_name,
					$code,
					$mls_id,
					$property_type,
					$agent_name,
					$agent_email,
					$location,
					$phone,
					$no_hp,
					$company,
				), $field_banks);

				$tbody .= $this->Html->tableCells(array(
	            	$default_arr
		        ));
		        $no++;
			}
			echo $tbody;
		}
?>