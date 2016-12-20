<?php
class RmKprComponent extends Component {

	var $components = array(
		'RmCommon', 'RmImage'
	);

	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
	}

	public function _callRefineParams( $data = '', $modelName = false, $default_options = false ) {
		
		$keyword = $this->RmCommon->filterEmptyField($data, 'named', 'keyword');
		if( !empty($keyword) ) {
			$default_options['conditions'] = array(
				'OR' => array(
					'KprApplication.full_name LIKE' => '%'.$keyword.'%',
					'KprApplication.email LIKE' => '%'.$keyword.'%',
				),
			);
		}

		$this->controller->request->data['Search']['keyword'] = $keyword;
		return $default_options;
	}

	function creditFix($amount, $rate, $year=20){
		
		if( empty($rate) ){
			return 0;
		} else {

			if( $rate != 0 ) {
				$rate = ($rate/100)/12;
			}

			$rateYear = pow((1+$rate), ($year*12));
			$rateMin = (pow((1+$rate), ($year*12))-1);

			if( $rateMin != 0 ) {
				$rateYear = $rateYear / $rateMin;
			}

			$mortgage = $rateYear * $amount * $rate; // rumus angsuran fix baru 
			return $mortgage;
		}
	}

	function _calculateKPR( $applicants = false ){

		if( !empty( $applicants ) ) {

			foreach( $applicants as $key => $value ) {

				$kpr_application_confirm = $this->RmCommon->filterEmptyField($value, 'KprApplicationConfirm');
				$kpr_application = $this->RmCommon->filterEmptyField($value, 'KprApplication');

				if(!empty($kpr_application_confirm)){

			       	$property_price = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'property_price', false, 0);
			       	$max_loan_price = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'loan_plafond', false, 0);
			       	$persen_loan = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'persen_loan', false, 0);
			       	$interest_rate = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'interest_rate', false, 0);
			       	$floating_rate = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'floating_rate', false, 0);
			       	$down_payment = (floatval($persen_loan / 100) * $property_price);

			       	$credit_fix = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'credit_fix', false, 0);
			       	$credit_total = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'periode_fix', false, 0);

			       	## BANK CHARGE
			       	$provision = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'provision', false, 0);
			       	$insurance = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'insurance', false, 0);
			       	$appraisal = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'appraisal', false, 0);
			       	$administration = $this->RmCommon->filterEmptyField($kpr_application_confirm, 'administration', false, 0);
				
				}else if(!empty($kpr_application)){

			       	$property_price = $this->RmCommon->filterEmptyField($kpr_application, 'property_price', false, 0);
			       	$max_loan_price = $this->RmCommon->filterEmptyField($kpr_application, 'loan_price', false, 0);
			       	$persen_loan = $this->RmCommon->filterEmptyField($kpr_application, 'persen_loan', false, 0);
			       	$interest_rate = $this->RmCommon->filterEmptyField($kpr_application, 'interest_rate', false, 0);
			       	$floating_rate = $this->RmCommon->filterEmptyField($kpr_application, 'floating_rate', false, 0);
			       	$down_payment = (floatval($persen_loan / 100) * $property_price);

			       	$credit_fix = $this->RmCommon->filterEmptyField($kpr_application, 'credit_fix', false, 0);
			       	$credit_float = $this->RmCommon->filterEmptyField($kpr_application, 'credit_float', false, 0);
			       	$credit_total = $credit_fix + $credit_float;

			       	// BANK CHARGE
			       	$provision = $this->RmCommon->filterEmptyField($kpr_application, 'provision', false, 0);
			       	$insurance = $this->RmCommon->filterEmptyField($kpr_application, 'insurance', false, 0);
			       	$appraisal = $this->RmCommon->filterEmptyField($kpr_application, 'appraisal', false, 0);
			       	$administration = $this->RmCommon->filterEmptyField($kpr_application, 'administration', false, 0);

				}
				
				$provision_price = (floatval($provision / 100) * $max_loan_price);
				$insurance_price = (floatval($insurance / 100) * $property_price);
				$total_bank_charge = $appraisal + $administration + $provision_price + $insurance_price;

				// NOTARY CHARGE
				$certificate_price = 0.0074 * $property_price;
				$transfer_title_charge = 0.0037 * $property_price;
				$credit_agreement = 0.0019 * $property_price;
				$SKMHT = 0.0009 * $property_price;
				$APHT = 0.0026 * $property_price;
				$HT = 0.0022 * $property_price;
				$additional_cost = 0.003 * $property_price;
				$total_notary_charge = $certificate_price + $transfer_title_charge + $credit_agreement + $SKMHT + $APHT + $HT + $additional_cost;

				// TOTAL FIRST CREDIT
				$total_first_credit = $this->creditFix($max_loan_price, $interest_rate, $credit_total);
				
				// GRAND TOTAL
				$total = $down_payment + $total_bank_charge + $total_notary_charge + $total_first_credit;

				// SET COMPUTE RESULT
				if(!empty($kpr_application_confirm)){
					$value['KprApplicationConfirm']['credit_total'] = $credit_total;
					$value['KprApplicationConfirm']['dp'] = $down_payment;
					$value['KprApplicationConfirm']['total_first_credit'] = $total_first_credit;
					$value['KprApplicationConfirm']['total'] = $total;

				}else if(!empty($kpr_application)){
					$value['KprApplication']['credit_total'] = $credit_total;
					$value['KprApplication']['dp'] = $down_payment;
					$value['KprApplication']['total_first_credit'] = $total_first_credit;
					$value['KprApplication']['total'] = $total;
				}
				

				$applicants[$key] = $value;
			}
		}

		return $applicants;
	}

	function beforeSaveSyncInformation($data = array(), $dataSave = array()){
		if(!empty($data) && !empty($dataSave)){
			
			$kpr_bank_id = $this->RmCommon->filterEmptyField($dataSave, 'KprBank', 'id');
			$applications = $this->RmCommon->filterEmptyField($data, 'KprApplication');
			$applications = $this->RmCommon->filterEmptyField($data, 'Kpr', 'KprApplication', $applications);

			if(!empty($applications)){
				if(!empty($applications[0])){
					$applications = Set::sort($applications, '{n}.KprApplication.parent_id', 'asc');
					foreach ($applications as $key => $application) {
						$application['KprApplication']['kpr_bank_id'] = $kpr_bank_id;	
						$application = $this->RmCommon->_callUnset( array(
							'KprApplication' => array(
								'id',
								'created',
								'modified',
								'kpr_id',
								'parent_id',
								'JobType',
								'Region',
								'City',
								'Subarea',
						)), $application);
						$applications[$key] = $application;
					}
					$dataSave['KprApplication'] = $applications;
				}
			}
		}
		return $dataSave;
	}

	function _calCreditFloat($credit_total,$credit_fix){
		return ($credit_total - $credit_fix);
	}

	function _calPeriodeFix($credit_float,$credit_fix){
		return ($credit_float + $credit_fix);
	}

	function _calPersenLoan($price,$down_payment){
		return @($down_payment/$price)*100;
	}

	function _calPersenValue($value, $persen){
		return ($persen/100)*$value;
	}

	function generateCode($bank_id) {
		$bank = $this->controller->User->KprApplication->Bank->getData('first',array(
				'conditions' => array(
					'Bank.ID' => $bank_id,
				)
		));
		
		$bank_code = $this->RmCommon->filterEmptyField($bank, 'Bank', 'code');

		$last_order = $this->controller->User->KprApplication->getData('first', array(
			'fields'=> array(
				'SUBSTRING(KprApplication.code,13,5) as code'
			),
			'conditions'=> array(
				'LEFT(KprApplication.code, 12)' => $bank_code.date('Ymd'),
			),
			'order'=> array(
				'KprApplication.code' => 'DESC',			
			),
		));



		$new_code = $bank_code.date('Ymd');

		if(!empty($last_order[0]['code'])) {
			$new_code .= str_pad((int)$last_order[0]['code']+1, 5, '0', STR_PAD_LEFT);
		} else {
			$new_code .= str_pad(1, 5, '0', STR_PAD_LEFT);
		}

		// if(!empty($bank_code)){
		// 	$new_code = sprintf('%s-%s',$new_code,$bank_code);
		// }

		return $new_code;
	}

	public function buildKpr($data, $owner_id = false){
	
		$result = array();
		$date_now = date('Y-m-d H:i:s');
		$kprApplicationRequest  = $this->RmCommon->filterEmptyField($data, 'KprApplicationRequest');
		$kpr_application  = $this->RmCommon->filterEmptyField($data, 'KprApplication');	
		$kpr_commission_payments = $this->RmCommon->filterEmptyField($data,'KprCommissionPayment');

		$kprApplication = $this->RmCommon->filterEmptyField($data, 'KprApplication');
		$kprApplication = $this->RmCommon->_callUnset(array(
			'KprApplication' => array(
				'application_form'
			),
		), $kprApplication);

		if( !empty($kprApplication) ) {
			$bank_id 						= $this->RmCommon->filterEmptyField($kprApplicationRequest, 'bank_id');
			$interest_rate 					= $this->RmCommon->filterEmptyField($kprApplicationRequest,'interest_rate_fix');
			$price 							= $this->RmCommon->filterEmptyField($kpr_application,'property_price');
			$down_payment 					= $this->RmCommon->filterEmptyField($kprApplicationRequest,'down_payment');
			$credit_total 					= $this->RmCommon->filterEmptyField($kprApplicationRequest,'credit_total');
			$kpr_application_request_id 	= $this->RmCommon->filterEmptyField($kprApplicationRequest,'id');
			$floating_rate 					= $this->RmCommon->filterEmptyField($kprApplicationRequest,'interest_rate_float');
			$provision 						= $this->RmCommon->filterEmptyField($kprApplicationRequest,'provision');
			$administration 				= $this->RmCommon->filterEmptyField($kprApplicationRequest,'administration');
			$appraisal 						= $this->RmCommon->filterEmptyField($kprApplicationRequest,'appraisal');
			$insurance 						= $this->RmCommon->filterEmptyField($kprApplicationRequest,'insurance');
			$sale_purchase_certificate 		= $this->RmCommon->filterEmptyField($kprApplicationRequest,'sale_purchase_certificate');
			$transfer_title_charge 			= $this->RmCommon->filterEmptyField($kprApplicationRequest,'transfer_title_charge');
			$credit_agreement 				= $this->RmCommon->filterEmptyField($kprApplicationRequest,'credit_agreement');
			$letter_mortgage 				= $this->RmCommon->filterEmptyField($kprApplicationRequest,'letter_mortgage');
			$imposition_act_mortgage 		= $this->RmCommon->filterEmptyField($kprApplicationRequest,'imposition_act_mortgage');
			$mortgage 						= $this->RmCommon->filterEmptyField($kprApplicationRequest,'mortgage');
			$other_certificate 				= $this->RmCommon->filterEmptyField($kprApplicationRequest,'other_certificate');
			$credit_fix_bank = $credit_fix 	= $this->RmCommon->filterEmptyField($kprApplicationRequest,'periode_fix');
			$loan_price 					= $this->RmCommon->filterEmptyField($kprApplicationRequest,'loan_price');
			$agent_id 						= $this->RmCommon->filterEmptyField($kprApplicationRequest,'agent_id');
			$user_id 						= $this->RmCommon->filterEmptyField($kpr_application,'user_id');


			$utm 							= $this->RmCommon->filterEmptyField($data, 'utm');

			$credit_float 					= $this->_calCreditFloat($credit_total,$credit_fix);
			$persen_loan 					= $this->_calPersenLoan($price,$down_payment);

			$set_data = array(
				'bank_id' 					=> $bank_id,
				'code' 						=> $this->generateCode($bank_id),
				'interest_rate' 			=> $interest_rate,
				'floating_rate' 			=> $floating_rate,
				'provision' 				=> $provision,
				'administration' 			=> $administration,
				'credit_fix' 				=> $credit_fix,
				'credit_float' 				=> $credit_float,
				'persen_loan' 				=> $persen_loan,
				'appraisal' 				=> $appraisal,
				'loan_price' 				=> $loan_price,
				'insurance' 				=> $insurance,
				'sale_purchase_certificate' => $sale_purchase_certificate,
				'transfer_title_charge' 	=> $transfer_title_charge,
				'credit_agreement' 			=> $credit_agreement,
				'letter_mortgage' 			=> $letter_mortgage,
				'imposition_act_mortgage' 	=> $imposition_act_mortgage,
				'mortgage' 					=> $mortgage,
				'other_certificate' 		=> $other_certificate,
				'is_id_company' 			=> $kpr_application_request_id,
				'down_payment'				=> $down_payment,
				'agent_id' 					=> $agent_id,
				'user_id' 					=> $user_id,
				'utm' 						=> $utm,
				'approved_admin'			=> 1,
				'approved_admin_date' 		=> $date_now,
				'kpr_owner_id' 				=> $owner_id,

			);

			$result['KprApplication'] = $kprApplication;
			$result['KprApplication'] = array_merge($kprApplication, $set_data);
		} 

		if(!empty($kpr_commission_payments)){

			foreach($kpr_commission_payments AS $key => $val){
				
				$kpr_application_request_id = $this->RmCommon->filterEmptyField($val,'kpr_application_request_id');
				$setting_loan_id = $this->RmCommon->filterEmptyField($val,'setting_loan_id');
				$type_komisi = $this->RmCommon->filterEmptyField($val,'type_komisi');
				$rate_komisi = $this->RmCommon->filterEmptyField($val,'rate_komisi');
				$commission = $this->RmCommon->filterEmptyField($val,'commission');
				$keterangan = $this->RmCommon->filterEmptyField($val,'keterangan');
				$region_name = $this->RmCommon->filterEmptyField($val,'region_name');
				$city_name = $this->RmCommon->filterEmptyField($val,'city_name');



				$result['KprCommissionPayment'][$key] = array(
						'setting_loan_id' => $setting_loan_id,
						'type_komisi' => $type_komisi,
						'rate_komisi' => $rate_komisi,
						'commission' => $commission,
						'keterangan' => $keterangan,
						'region_name' => $region_name,
						'city_name' => $city_name
				);

			}

		}

		return $result;
	}

	function _callBeforeSaveCommission ( $data ) {
		$min_loan = $this->RmCommon->filterEmptyField($data, 'BankCommissionSetting', 'min_loan');
		$commissionLoan = $this->RmCommon->filterEmptyField($data, 'BankCommissionSettingLoan');
		$data['BankCommissionSetting']['min_loan'] = $this->RmCommon->_callPriceConverter($min_loan);

		if(empty($data['BankCommissionSetting']['status_floating'])){
			$data['BankCommissionSetting']['status_floating'] = 1;
		}

		if( !empty($commissionLoan) ) {
			foreach ($commissionLoan as $key => $value) {
				$value = $this->RmCommon->dataConverter($value, array(
					'price' => array(
						'min_loan',
					)
				));

				$commissionLoan[$key] = $value;
			}

			$data['BankCommissionSettingLoan'] = $commissionLoan;
		}

		$data = $this->RmCommon->dataConverter($data, array(
			'price' => array(
				'BankCommissionSetting' => array(
					'min_loan',
				),
			)
		));

		return $data;
	}


	function SetSchedule($data){

		if(empty($data)){
			$date = date('Y-m-d H:i:s');
			$this->Controller->request->data['date_from'] = date('Y-m-d H:i:s', strtotime($date . ' -1 month'));
			$this->Controller->request->data['date_to'] = $date;
		}
		debug($this->Controller->request->data);die();

	}

	function beforeViewFeePaid($values){

		if(!empty($values)){

			if(!empty($values[0])){

				foreach($values AS $key => $value){

					$user = $this->RmCommon->filterEmptyField($value,'User');
					$user_id = $this->RmCommon->filterEmptyField($user,'id');
					
					if($user){
						$value = $this->controller->User->UserProfile->getMerge($value,$user_id);
					}

					$kpr_commission_payment = $this->RmCommon->filterEmptyField($value,'KprCommissionPayment');

					if(!empty($kpr_commission_payment)){
						foreach($kpr_commission_payment As $key2 => $val){

							$tipe_komisi = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','type_komisi');
							$komisi = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','commission');
							$paid_date = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','paid_date',null);
							$paid_fee_approved = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','paid_fee_approved');
							$paid_fee_rejected = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','paid_fee_rejected');

							if(!empty($paid_fee_approved)){
								$paid_fee = __('Dibayarkan');
							}else if(!empty($paid_fee_rejected)){
								$paid_fee = __('Ditolak');
							}else{
								$paid_fee = __('Pending');
							}

							if($tipe_komisi && $komisi){
								$tipe_komisi = sprintf('%s_%s',__('komisi'),$tipe_komisi);
								$paid_komisi = sprintf('%s_%s',__('paid'),$tipe_komisi);
								$date_komisi = sprintf('%s_%s',__('date'),$tipe_komisi);
								$flag_approved = __('flag_approved');
								$flag_rejected = __('flag_cancel');

								$value['KprApplication'][$tipe_komisi] = $komisi;
								$value['KprApplication'][$paid_komisi] = $paid_fee;
								$value['KprApplication'][$date_komisi] = $paid_date;
								$value['KprApplication'][$flag_approved] = array($key2 => $paid_fee_approved);
								$value['KprApplication'][$flag_rejected] = array($key2 => $paid_fee_rejected);
							}
						}

					}
					
					$values[$key] = $value;
				}
			}else{

				$kpr_commission_payment = $this->RmCommon->filterEmptyField($values, 'KprApplication', 'KprCommissionPayment');

				if(!empty($kpr_commission_payment)){
					foreach($kpr_commission_payment As $key2 => $val){	
						$tipe_komisi = $this->RmCommon->filterEmptyField($val, 'type_komisi');
						$komisi = $this->RmCommon->filterEmptyField($val, 'commission');
						$paid_date = $this->RmCommon->filterEmptyField($val, 'paid_date', false,null);
						$paid_fee_approved = $this->RmCommon->filterEmptyField($val, 'paid_fee_approved');
						$paid_fee_rejected = $this->RmCommon->filterEmptyField($val, 'paid_fee_rejected');

						if(!empty($paid_fee_approved)){
							$paid_fee = __('Dibayarkan');
						}else if(!empty($paid_fee_rejected)){
							$paid_fee = __('Ditolak');
						}else{
							$paid_fee = __('Pending');
						}

						if($tipe_komisi && $komisi){
								$tipe_komisi = sprintf('%s_%s',__('komisi'),$tipe_komisi);
								$paid_komisi = sprintf('%s_%s',__('paid'),$tipe_komisi);
								$date_komisi = sprintf('%s_%s',__('date'),$tipe_komisi);
								$flag_approved = __('flag_approved');
								$flag_rejected = __('flag_cancel');

								$values['KprApplication'][$tipe_komisi] = $komisi;
								$values['KprApplication'][$paid_komisi] = $paid_fee;
								$values['KprApplication'][$date_komisi] = $paid_date;
								$values['KprApplication'][$flag_approved][$key2] = $paid_fee_approved;
								$values['KprApplication'][$flag_rejected][$key2] = $paid_fee_rejected;
						}
							

					}

				}
									
			}
			return $values;
		}

	}

	function beforeSavePayment($data){
		$kpr_application_payments = $this->RmCommon->filterEmptyField($data,'KprCommissionPayment');

		if(!empty($kpr_application_payments)){

			foreach($kpr_application_payments AS $key => $kpr_application_payment){
				$label_name = $this->RmCommon->filterEmptyField($kpr_application_payment,'label_name',false,null);
				$paid_date = $this->RmCommon->filterEmptyField($kpr_application_payment,'paid_date',false,null);
				$note_reason = $this->RmCommon->filterEmptyField($kpr_application_payment,'note_reason',false,null);
				$note_reason_rejected = $this->RmCommon->filterEmptyField($kpr_application_payment,'note_reason_rejected',false,null);

				if($label_name == Configure::read('__Site.site_name')){
					$label_name = __('rku');
				}

				if($paid_date){
					$paid_date = $this->RmCommon->getDate($paid_date);
				}

				$data['KprCommissionPayment'][$key] = array(
					'label_name' => $label_name,
					'paid_date' => $paid_date,
					'note_reason' => $note_reason,
					'note_reason_rejected' => $note_reason_rejected,
				);	
			}
		}
		return $data;
	}

	function beforeViewApprovedBank($data){
		if(!empty($data)){
			$id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');
			$value = $this->controller->User->KprBank->getData('first', array(
				'conditions' => array(
					'KprBank.id' => $id
				)
			));
			$bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id');
			$data = $this->controller->User->KprBank->Bank->BankSetting->getMerge($data, $bank_id);
			$property_price = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'property_price');
			$down_payment = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'down_payment');
			$dp_percent = $this->_calPersenLoan($property_price, $down_payment);
			$data['KprBankInstallment']['dp_percent'] = $dp_percent;
			$data = $this->RmCommon->dataConverter($data, array(
				'date' => array(
					'KprBank' => array(
						'appraisal_date'
					),
				),
				'round' => array(
					'KprBankInstallment' => array(
						'property_price',
						'down_payment',
						'loan_price',
						'total_first_credit',
						'commission',
						'commission_rumahku',
						'administration',
						'appraisal',
						'insurance',
						'sale_purchase_certificate',
						'transfer_title_charge',
						'credit_agreement',
						'letter_mortgage',
						'mortgage',
						'other_certificate',
					),
				),
			));
			return $data;
		}
	}

	function generateCommissionKPR($data){
	
		if(!empty($data)){
				$kpr_bank_id = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'kpr_bank_id');
				$value = $this->controller->User->KprBank->KprBankInstallment->getData('first', array(
					'conditions' => array(
						'KprBankInstallment.kpr_bank_id' => $kpr_bank_id,
						'KprBankInstallment.status_confirm' => FALSE,
					),
				));
				$kpr_bank_installment_id = $this->RmCommon->filterEmptyField($value, 'KprBankInstallment', 'id');
				$value = $this->controller->User->KprBank->KprBankInstallment->KprBankCommission->getMerge($value, $kpr_bank_installment_id, array(
					'fieldName' => 'kpr_bank_installment_id',
					'find' => 'all',
				));
				$note = $this->RmCommon->filterEmptyField($value, 'KprBankCommission', 'note');
				$region_id = $this->RmCommon->filterEmptyField($value, 'KprBankCommission', 'region_id');
				$region_name = $this->RmCommon->filterEmptyField($value, 'KprBankCommission', 'region_name');
				$city_id = $this->RmCommon->filterEmptyField($value, 'KprBankCommission', 'city_id');
				$city_name = $this->RmCommon->filterEmptyField($value, 'KprBankCommission', 'city_name');
				$loan_price = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'loan_price');
				$loan_price = $this->RmCommon->_callPriceConverter($loan_price);
				$commission = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'commission');
				
				if(!empty($commission)){
					$commission_arrs[0]['commission'] = $commission;
					$commission_arrs[0]['type'] = "agent";
					$data['KprBank']['unpaid_agent'] = 'pending';
				}else{
					$data['KprBank']['unpaid_agent'] = 'no_provision';
				}
				
				$commission_rku = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment', 'commission_rumahku');
				
				if(!empty($commission_rku)){
					$commission_arrs[1]['commission'] = $commission_rku;
					$commission_arrs[1]['type'] = "rumahku";
					$data['KprBank']['unpaid_rumahku'] = 'pending';
				}else{
					$data['KprBank']['unpaid_rumahku'] = 'no_provision';
				}

				if(!empty($commission_arrs)){
					foreach($commission_arrs AS $key => $arr){
						$commission = $this->RmCommon->filterEmptyField($arr, 'commission');
						$commission = $this->RmCommon->_callPriceConverter($commission);
						$percent = $this->_calPersenLoan($loan_price, $commission);
						$arr['value']  = $commission;	
						$arr['percent']  = $percent;
						$arr['status_confirm']  = TRUE;
						$arr['note']  = $note;
						$arr['region_id']  = $region_id;
						$arr['region_name']  = $region_name;
						$arr['city_id']  = $city_id;
						$arr['city_name']  = $city_name;
						$commission_arr['KprBankCommission'][$key] = $arr;
					}
					$data = array_merge($data, $commission_arr);
				}
		}
		return $data;
	}

	function beforeSaveApprovedBank($data, $id){
		if(!empty($data)){
			$kpr_bank = $this->RmCommon->filterEmptyField($data, 'KprBank');
			$kpr_bank_installment = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment');

			if(!empty($kpr_bank_installment)){
				$interest_rate_cabs = $this->RmCommon->filterEmptyField($kpr_bank_installment, 'interest_rate_cabs');
				$periode_cab = $this->RmCommon->filterEmptyField($kpr_bank_installment, 'periode_cab');

				if(in_array(TRUE, array($interest_rate_cabs, $periode_cab))){
					$this->controller->User->KprBank->KprBankInstallment->validator()->add('interest_rate_cabs', array(
	                   'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Suku bunga cap harap diisi',
						),
						'notZero' => array(
							'rule' => array('notZero'),
							'message' => 'Suku bunga cap harap diisi lebih besar dari 0',
						),
	                ))->add('periode_cab', array(
	                   'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Periode bunga cap harap diisi',
						),
						'notZero' => array(
							'rule' => array('notZero'),
							'message' => 'Periode bunga cap harap diisi lebih besar dari 0',
						),
	                ))->add('interest_rate_float', array(
	                   'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Suku bunga floating harap diisi',
						),
						'notZero' => array(
							'rule' => array('notZero'),
							'message' => 'Suku bunga floating harap diisi lebih besar dari 0',
						),
	                ));
				}

				$value = $this->controller->User->KprBank->KprBankInstallment->getData('first', array(
					'conditions' => array(
						'KprBankInstallment.kpr_bank_id' => $id,
						'KprBankInstallment.status_confirm' => TRUE,
					)
				));

				if(!empty($value)){
					$kpr_bank_installment_id = $this->RmCommon->filterEmptyField($value, 'KprBankInstallment', 'id');
					$data['KprBankInstallment']['id'] = $kpr_bank_installment_id;
				}


				$data['KprBankInstallment']['kpr_bank_id'] = $id;
				$data['KprBankInstallment']['status_confirm'] = TRUE;
				$data = $this->RmCommon->dataConverter($data,array(
					'price' => array(
						'KprBankInstallment' => array(
							'total_first_credit',
							'property_price',
							'loan_price',
							'down_payment',
							'insurance',
							'appraisal',
							'commission',
							'commission_rumahku',
							'administration',
							'credit_agreement',
							'sale_purchase_certificate',
							'transfer_title_charge',
							'letter_mortgage',
							'mortgage',
							'imposition_act_mortgage',
							'other_certificate',
						),
					),
					'date' => array(
						'KprBank' => array(
							'appraisal_date'
						),
					),
				),0);

				$data = $this->generateCommissionKPR($data);
			}

			if(!empty($kpr_bank)){
				$data['KprBank']['id'] = $id;
				$data['KprBank']['document_status'] = 'approved_bank';
				$data['KprBank']['snyc'] = FALSE;
			}

			if(!empty($kpr_bank_installment) && !empty($kpr_bank)){
				$data = $this->controller->User->KprBank->KprBankDate->setBeforeSaveID($data, array(
					'KprBankDate' => array(
						'kpr_bank_id' => $id,
						'slug' => 'approved_bank',
						'note' => __('Aplikasi pengajuan/installment sudah di setujui dengan data baru'),
					),
				));
			}
		}
		return $data;
	}

	function _callKprConfirm( $value ){

		if(!empty($value)){
			foreach( $value AS $key => $val ){
				$appraisal_date = date('Y-m-d H:i:s');
				$kpr_application = $this->RmCommon->filterEmptyField($val, 'KprApplication');
				$kpr_application_id = $this->RmCommon->filterEmptyField($kpr_application, 'id');
				$cnt_val = $this->controller->KprApplication->KprApplicationConfirm->getData('count', array(
					'conditions' => array(
						'KprApplicationConfirm.kpr_application_id' => $kpr_application_id,
					),
				));

				if( empty($cnt_val) ){
					$value_commissions = $this->controller->KprApplication->KprCommissionPayment->getData('all', array(
						'conditions'=> array(
							'KprCommissionPayment.kpr_application_id' => $kpr_application_id,
						),
					));

					$loan_plafond = $this->RmCommon->filterEmptyField($val, 'KprApplication', 'loan_price');
					$provision = $this->RmCommon->filterEmptyField($val, 'KprApplication', 'provision');
					$credit_fix = $this->RmCommon->filterEmptyField($val, 'KprApplication', 'credit_fix');
					$credit_float = $this->RmCommon->filterEmptyField($val, 'KprApplication', 'credit_float');
					$same_with_kpr = true;
					$periode_fix = $this->_calPeriodeFix($credit_float, $credit_fix);

					if(!empty($value_commissions)){
						foreach($value_commissions AS $key2 => $value_commission){
							$rate = $this->RmCommon->filterEmptyField($value_commission, 'KprCommissionPayment', 'rate_komisi');
							$komisi = $this->RmCommon->filterEmptyField($value_commission, 'KprCommissionPayment', 'commission');
							$type_komisi = $this->RmCommon->filterEmptyField($value_commission, 'KprCommissionPayment', 'type_komisi');

							if($type_komisi == 'agen'){
								$provision = $rate;
								$commission = $komisi;
							}

							$kpr_commission_payment = array(
								'type_komisi' => $type_komisi,
								'rate_komisi' => $rate,
								'commission' => $commission,
							);
							$val['KprCommissionPaymentConfirm'][$key2] = $kpr_commission_payment;
						}
						
					}else{
						if(!empty($provision)){
							$commission = $this->_calPersenValue($loan_plafond, $provision);
						}else{
							$commission = null;
						}
					}


					$kpr_application_confirm = array(
						'kpr_application_id' => $kpr_application_id,
						'loan_plafond' => $loan_plafond,
						'provision' => $provision,
						'periode_fix' => $periode_fix,
						'commission' => $commission,
						'same_with_kpr' => $same_with_kpr,
						'appraisal_date' => $appraisal_date,
					);

					$val['KprApplicationConfirm'] = array_merge($kpr_application, $kpr_application_confirm);
					$val = $this->RmCommon->_callUnset( array(
						'KprApplicationConfirm' => array(
							'id'
						)
					), $val);
				}
				$value[$key] = $val;
			}
		}
		return $value;
	}

	function beforeRejected( $data, $options = array()){
		$kpr_bank_id = $this->RmCommon->filterEmptyField($options, 'kpr_bank_id');
		$slug = $this->RmCommon->filterEmptyField($options, 'slug');
		$time = date('H:i:s');
		$data = $this->RmCommon->dataConverter($data, array(
			'date' => array(
				'KprBankDate' => array(
					'action_date',
				),
			),
		));

		if(!empty($data['KprBankDate'])){
			$action_date = $this->RmCommon->filterEmptyField($data, 'KprBankDate', 'action_date');

			if(!empty($action_date)){
				$data['KprBankDate']['action_date'] = sprintf('%s %s', $action_date, $time);
			}

			$data['KprBankDate']['kpr_bank_id'] = $kpr_bank_id;
			$data['KprBankDate']['slug'] = $slug;
		}
		return $data;
	}

	// function beforeViewRejected( $data, $options = array()){
	// 	debug($data);die();
	// 	$field_date = $this->RmCommon->filterEmptyField($data, 'field_date');

	// 	$this->controller->request->data = $this->RmCommon->dataConverter($data, array(
	// 		'date' => array(
	// 			'KprApplication' => array(
	// 				$field_date,
	// 			),
	// 		),
	// 	), true);
	// }

    function _getStatus ( $data = array() ) {
        $allow_edit = false;
        $id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');
        $document_status = $this->RmCommon->filterEmptyField($data, 'KprBank', 'document_status');
        $application_status = $this->RmCommon->filterEmptyField($data, 'KprBank', 'application_status');
        $application_snyc = $this->RmCommon->filterEmptyField($data, 'KprBank', 'application_snyc');
        $from_kpr = $this->RmCommon->filterEmptyField($data, 'KprBank', 'from_kpr');
        $from_web = $this->RmCommon->filterEmptyField($data, 'KprBank', 'from_web');

        if($from_web == 'primesystem'){
	        switch($document_status){
	        	case 'approved_admin':
	        		$status = 'pending';
	        		break;
	        	case 'rejected_proposal':
	        		$status = 'rejected_proposal';
	        		break;
	        	case 'approved_proposal':
	            	$allow_edit = TRUE;
	        		$status = 'approved_proposal';
	        		break;
	        	case 'proposal_without_comiission':
	            	$allow_edit = !empty($application_snyc)?TRUE:FALSE;
	        		$status = 'proposal_without_comiission';
	        		break;
	        	case 'rejected_bank':
	        		$status = 'rejected_application';
	        		break;
	        	case 'approved_bank':
	        		$status = 'approved_application';
	        		break;
	        	case 'rejected_credit':
	        		$status = 'rejected_agent';
	        		break;
	        	case 'credit_process':
	        		$status = 'process_akad';
	        		break;
	        	case 'approved_credit':
	        		$status = 'approved_akad_credit';
	        		break;
	        	case 'completed':
	        		$status = 'approved_akad_credit';
	        		break;
	        }
        }else{
        	switch ($document_status) {
        		case 'approved_admin':
        			$status = 'pending';
        			$allow_edit = true;
        			break;
        		case 'pending':
        			$status = 'pending';
        			$allow_edit = true;
        			break;
        	}
        }
        return array(
            'status' => $status,
            'allow_edit' => $allow_edit,
        );
    }

    function getDocumentSort($options_conditions = array(), $value = false, $options = array(), $sort = 'DESC'){
		$kpr_bank_id = $this->RmCommon->filterEmptyField($options, 'kpr_bank_id');
		$application_id = $this->RmCommon->filterEmptyField($options, 'id');
		$document_type = $this->RmCommon->filterEmptyField($options, 'document_type', false, 'kpr_application');
		$property_type = $this->RmCommon->filterEmptyField($options, 'property_type', false, 'property');

		$data = $this->controller->User->KprBank->KprApplication->Document->DocumentCategory->getData( 'all', array(
			'conditions' => array(
				$options_conditions
			),
			'order' => array('DocumentCategory.id' => 'ASC'),
		));

    	if(!empty($data)){
			foreach($data AS $key => $document_category){
				$document_category_id = $this->RmCommon->filterEmptyField($document_category, 'DocumentCategory', 'id');
				$slug = $this->RmCommon->filterEmptyField($document_category, 'DocumentCategory', 'slug');
				if($document_category_id == 5){
					$v_document_type = $property_type;
				}else{
					$v_document_type = $document_type;
				}

				$document = $this->controller->User->KprBank->KprApplication->Document->getData('first', array(
					'conditions' => array(
						'Document.document_type' => $v_document_type,
						'Document.owner_id' => $application_id,
						'Document.document_category_id' => $document_category_id,
					),
					'order' => array('Document.created' => $sort),
				));

				$master_document = $this->controller->User->KprBank->KprApplication->Document->getData('first', array(
					'conditions' => array(
						'Document.document_type' => $v_document_type,
						'Document.owner_id' => $kpr_bank_id,
						'Document.document_category_id' => $document_category_id,
					),
					'order' => array('Document.created' => $sort),
				));

				$document = !empty($document) ? $document : $master_document;

				if(!empty($document)){
					$document['Document']['slug'] = $slug;
				}

				$data[$key] = array_merge($document_category, $document);

			}
			$value['Document'] = $data;
		}
		return $value;
    }

    function buildDocument($data, $options = array()){
		Configure::write('__Site.allowed_ext', array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'xls', 'xlsx'));
		// $allowed_mime = array_merge(Configure::read('__Site.allowed_mime'), array('application/pdf'));

    	$save_path = $this->RmCommon->filterEmptyField($options, 'save_path');
    	$old_path = $this->RmCommon->filterEmptyField($options, 'old_path');
    	$owner_id = $this->RmCommon->filterEmptyField($options, 'owner_id');
    	$document_type = $this->RmCommon->filterEmptyField($options, 'document_type');
    	$client_id = $this->RmCommon->filterEmptyField($options, 'client_id');
    	$property_id = $this->RmCommon->filterEmptyField($options, 'property_id');

    	$document = $this->RmCommon->filterEmptyField($data, 'Document');
		if(!empty($document)){
			unset($data['Document']);

			foreach($document AS $key => $val){
				$file_save_path = $this->RmCommon->filterEmptyField($val, 'file_save_path');
				$doc_file = $this->RmCommon->filterEmptyField($val, 'file');
				$status = $this->RmCommon->filterEmptyField($val, 'file', 'error');
				$document_category_id = $this->RmCommon->filterEmptyField($val, 'document_category_id');
				$document = $this->RmImage->upload( $doc_file, $save_path, String::uuid(), array(
					'fullsize' => true,
				));

				if($status == 4){
					$photo_name = $this->RmCommon->filterEmptyField($document, 'imageName', false);
					$photo_name = $this->RmCommon->filterEmptyField($val, 'file_hide', false, $photo_name);
					$save_path = !empty($file_save_path)?$file_save_path:$save_path;
				}else{
					$photo_name = $this->RmCommon->filterEmptyField($document, 'imageName', false);
				}

				$baseName = $this->RmCommon->filterEmptyField($document, 'baseName');
				$document['Document'] = $val;
				$document['Document']['file'] = $photo_name;
				$document['Document']['file_hide'] = $photo_name;
				$document['Document']['file_save_path'] = !empty($file_save_path)?$file_save_path:$save_path;				
				$document['Document']['name'] = $baseName;

				$document['Document']['document_type'] = $document_type;
				$document['Document']['save_path'] = $save_path;
				$document['Document']['owner_id'] = $owner_id;
				$data['Document'][$key] = $document;
				
			}
		}
		return $data;
    }

    function beforeSaveApplication( $data, $value){
    	$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    	$value_applications = $this->RmCommon->filterEmptyField($value, 'KprApplication', false, array());

    	if(!empty($value_applications)){
    		foreach($value_applications AS $key => $value_application){
    			$id = $this->RmCommon->filterEmptyField($value_application, 'id', false, null);
    			$kpr_bank_id = $this->RmCommon->filterIssetField($value_application, 'kpr_bank_id', false, null);
    			$parent_id = $this->RmCommon->filterIssetField($value_application, 'parent_id', false, null);
    			$data['KprApplication'][$key]['id'] = $id;
    			$data['KprApplication'][$key]['kpr_bank_id'] = $kpr_bank_id;
    			$data['KprApplication'][$key]['parent_id'] = $parent_id;
    		}
    	}

    	$kpr_application = !empty($data['KprApplication'][0])?$data['KprApplication'][0]:array();
    	$status_marital = $this->RmCommon->filterEmptyField($kpr_application, 'status_marital');
    	if($status_marital == 'single' && !empty($data['KprApplication'][1])){
    		unset($data['KprApplication'][1]);
    	}
    	
    	$kpr_application_particular = !empty($data['KprApplication'][1])?$data['KprApplication'][1]:array();

    	$client_id = $this->RmCommon->filterEmptyField( $value, 'KprBank', 'user_id');
	    $property_id = $this->RmCommon->filterEmptyField( $value, 'KprBank', 'property_id');

    	if(!empty($kpr_application)){
    		$first_name = $this->RmCommon->filterEmptyField($kpr_application, 'first_name');
    		$last_name = $this->RmCommon->filterEmptyField($kpr_application, 'last_name');
    		$full_name = sprintf('%s %s', $first_name, $last_name);
    		$same_as_address_ktp = $this->RmCommon->filterEmptyField($kpr_application, 'same_as_address_ktp');
	    	$address = $this->RmCommon->filterEmptyField($kpr_application, 'address');
	    	$address_2 = $this->RmCommon->filterEmptyField($kpr_application, 'address_2');
	    	$data['KprApplication'][0]['address_2'] = !empty($same_as_address_ktp)?$address:$address_2;
	    	$data['KprApplication'][0]['full_name'] = $full_name;
	    	$data['KprApplication'][0]['kpr_bank_id'] = $kpr_bank_id;

	    	$document = $this->RmCommon->filterEmptyField($kpr_application ,'Document');
	    	$id = $this->RmCommon->filterEmptyField($kpr_application ,'id');

	    	if(!empty($document)){
	    		$data['KprApplication'][0] = $this->buildDocument( $data['KprApplication'][0], array(
					'save_path' => Configure::read('__Site.document_folder'),
					'old_path' => Configure::read('__Site.document_folder'),
					'owner_id' => $id,
					'client_id' => $client_id,
					'property_id' => $property_id,
					'document_type' => 'kpr_application',
				));
	    	}
    	}

    	if(!empty($kpr_application_particular)){
    		$first_name = $this->RmCommon->filterEmptyField($kpr_application_particular, 'first_name');
    		$last_name = $this->RmCommon->filterEmptyField($kpr_application_particular, 'last_name');
    		$full_name = sprintf('%s %s', $first_name, $last_name);
    		$same_as_address_ktp = $this->RmCommon->filterEmptyField($kpr_application_particular, 'same_as_address_ktp');
	    	$address = $this->RmCommon->filterEmptyField($kpr_application_particular, 'address');
	    	$address_2 = $this->RmCommon->filterEmptyField($kpr_application_particular, 'address_2');
	    	$data['KprApplication'][1]['address_2'] = !empty($same_as_address_ktp)?$address:$address_2;
	    	$data['KprApplication'][1]['full_name'] = $full_name;
	    	$data['KprApplication'][1]['status_marital'] = 'marital';
	    	$data['KprApplication'][1]['kpr_bank_id'] = $kpr_bank_id;

	    	$document = $this->RmCommon->filterEmptyField($kpr_application_particular,'Document');
	    	$id = $this->RmCommon->filterEmptyField($kpr_application ,'id');

	    	if(!empty($document)){
	    		$data['KprApplication'][1] = $this->buildDocument( $data['KprApplication'][1], array(
					'save_path' => Configure::read('__Site.document_folder'),
					'old_path' => Configure::read('__Site.document_folder'),
					'owner_id' => $id,
					'client_id' => $client_id,
					'property_id' => $property_id,
					'document_type' => 'kpr_spouse_particular',
				));
	    	}
    	}

    	if(!empty($data['KprApplication'])){
    		$data['Document'] = array();
    		foreach($data['KprApplication'] AS $key => $kpr_application){
    			$document_temp = $this->RmCommon->filterEmptyField($kpr_application, 'Document', false, array());
    			$kpr_application = $this->RmCommon->dataConverter($kpr_application, array(
					'price' => array(
						'income',
						'household_fee',
						'other_installment',
						'loan_price',
						'property_price',
					),
					'date' => array(
		    			'birthday'
		    		),
				));
				$kpr_application = $this->RmCommon->_callUnset(array(
					'Document'
				), $kpr_application);
				$data['KprApplication'][$key] = $kpr_application;
				$data['Document'] = array_merge($data['Document'], $document_temp);
    		}
    	}
    	return $data;
    }


    function filterFormatPrice($data, $modelName, $fieldArr, $reverse = 0){
    	if(!empty($data) && !empty($fieldArr)){
    		foreach($fieldArr AS $key => $fieldName){
    			$fieldCategory = $this->RmCommon->filterEmptyField($data, $modelName, sprintf('category_%s', $fieldName));

    			if($fieldCategory == 'nominal'){
    				if(!empty($data[$modelName][$fieldName])){
    					$price = doubleval(trim($data[$modelName][$fieldName]));
    					$data[$modelName][$fieldName] = $this->RmCommon->_callPriceConverter($price);
    				}
    			}
    		}
    	}
    	return $data;
    }

    function reservedPriceSettingKpr($data, $modelName){
    	if(!empty($data)){
	    	$data = $this->RmCommon->dataConverter($data, array(
    			'price' => array(
	    			$modelName => array(
						'insurance',
						'appraisal',
						'administration',
						'credit_agreement',
						'sale_purchase_certificate',
						'transfer_title_charge',
						'letter_mortgage',
						'mortgage',
						'imposition_act_mortgage',
						'other_certificate',
	    			),
    			),
			));
    	}
    	return $data;
    }

    function _callBeforeSaveSetting ( $data, $bank_id ) {
    	if( !empty($data) ) {
    		$dataSave = array();
    		$interest_rates = $this->RmCommon->filterEmptyField($data, 'BankSettingRate', 'interest_rate');
    		$periodes = $this->RmCommon->filterEmptyField($data, 'BankSettingRate', 'periode');

    		$data = $this->reservedPriceSettingKpr($data, 'BankSetting');
			$data['BankSetting']['type'] = "default";

    		if( !empty($interest_rates) ) {
    			foreach ($interest_rates as $key => $interest_rate) {
					$periode = !empty($periodes[$key])?$periodes[$key]:false;

					$dataSave['BankSettingRate'][] = array(
						'BankSettingRate' => array(
							'bank_id' => $bank_id,
							'interest_rate' => $interest_rate,
							'periode' => $periode,
						),
					);
    			}

    			$data = $this->RmCommon->_callUnset(array(
					'BankSettingRate',
				),$data);
				$data['BankSettingRate'] = $this->RmCommon->filterEmptyField($dataSave, 'BankSettingRate');
    		}
    	}
    	return $data;
    }

    function _callBeforeViewSetting ( $data ) {
    	if( !empty($data) ) {
    		$dataRate = array();
    		$rates = $this->RmCommon->filterEmptyField($data, 'BankSettingRate');

    		if( !empty($rates) ) {
    			foreach ($rates as $key => $value) {
		    		$interest_rate = $this->RmCommon->filterEmptyField($value, 'BankSettingRate', 'interest_rate');
		    		$periode = $this->RmCommon->filterEmptyField($value, 'BankSettingRate', 'periode');

		    		$dataRate['BankSettingRate']['interest_rate'][$key] = $interest_rate;
		    		$dataRate['BankSettingRate']['periode'][$key] = $periode;
    			}
    		}

			$data = $this->RmCommon->_callUnset(array(
				'BankSettingRate',
			),$data);
			$data['BankSettingRate'] = $this->RmCommon->filterEmptyField($dataRate, 'BankSettingRate');
    	}

    	return $data;
    }

    function beforeViewApplication($data){
    	$applications = $this->RmCommon->filterEmptyField($data, 'KprApplication');
    	if(!empty($applications)){
    		foreach ($applications as $key => $application) {
    			$application = $this->RmCommon->dataConverter( $application, array(
		    		'date' => array(
		    			'birthday',
		    	)), true);
    			$applications[$key] = $application;
    		}
    		if(!empty($applications)){
    			$data['KprApplication'] = $applications;
    		}
    	}
    	return $data;
    }

    function setCommissionConfirm($value, $kpr_payment_confirms){
    	if(!empty($kpr_payment_confirms)){

    		foreach($kpr_payment_confirms AS $key => $kpr_payment_confirm){
    			$value['KprCommissionPaymentConfirm'][$key] = $this->RmCommon->filterEmptyField($kpr_payment_confirm, 'KprCommissionPaymentConfirm');
    		}

    	}
    	return $value;
    }

    function beforeSaveProposals($data){
    	$dataSave = array();
		$id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');
		$from_kpr = $this->RmCommon->filterEmptyField($data, 'KprBank', 'from_kpr');
		$forward_app = $this->RmCommon->filterEmptyField($data, 'KprBank', 'forward_app');

    	$property = $this->RmCommon->filterEmptyField($data, 'Property');
		$kpr = $this->RmCommon->filterEmptyField($data, 'Kpr');
		$company_id = $this->RmCommon->filterEmptyField($kpr, 'company_id');
		$bank_apply_category_id = $this->RmCommon->filterEmptyField($kpr, 'bank_apply_category_id');
		$mls_id = $this->RmCommon->filterEmptyField($kpr, 'mls_id');
		$property_id = $this->RmCommon->filterEmptyField($kpr, 'property_id');
		$agent_id = $this->RmCommon->filterEmptyField($kpr, 'agent_id');
		$kpr_owner_id = $this->RmCommon->filterEmptyField($kpr, 'kpr_owner_id');
		$user_id = $this->RmCommon->filterEmptyField($kpr, 'user_id');
		$currency_id = $this->RmCommon->filterEmptyField($kpr, 'currency_id');
		$sold_date = $this->RmCommon->filterEmptyField($kpr, 'sold_date');
		$client_email = $this->RmCommon->filterEmptyField($kpr, 'client_email');
		$client_name = $this->RmCommon->filterEmptyField($kpr, 'client_name');
		$client_hp = $this->RmCommon->filterEmptyField($kpr, 'client_hp');
		$client_job_type_id = $this->RmCommon->filterEmptyField($kpr, 'client_job_type_id');
		$gender_id = $this->RmCommon->filterEmptyField($kpr, 'gender_id');
		$address = $this->RmCommon->filterEmptyField($kpr, 'address');
		$birthplace = $this->RmCommon->filterEmptyField($kpr, 'birthplace');
		$birthday = $this->RmCommon->filterEmptyField($kpr, 'birthday');
		$ktp = $this->RmCommon->filterEmptyField($kpr, 'ktp');
		$region_id = $this->RmCommon->filterEmptyField($data, 'Client', 'region_id');
		$city_id = $this->RmCommon->filterEmptyField($data, 'Client', 'city_id');
		$subarea_id = $this->RmCommon->filterEmptyField($data, 'Client', 'subarea_id');
		$status_marital = $this->RmCommon->filterEmptyField($data, 'Client', 'status_marital');

		$value = $this->controller->User->KprBank->getData('first', array(
			'conditions' => array(
				'KprBank.prime_kpr_bank_id' => $id,
			),	
		), array(
			'status_snyc' => 'crontab',
		));
		
		if($from_kpr == 'frontend' && empty($forward_app)){
			$document_status = "pending";
		}else{
			$document_status = "approved_admin";
		}

		if(empty($bank_apply_category_id)){
			$proeprty_type_id = $this->RmCommon->filterEmptyField($property, 'property_type_id');
			$bank_apply_category_id = ($proeprty_type_id == 3)?2:1;
		}

		$data_merge = array(
			'company_id' => $company_id,
			'bank_apply_category_id' => $bank_apply_category_id,
			'mls_id' => $mls_id,
			'property_id' => $property_id,
			'agent_id' => $agent_id,
			'kpr_owner_id' => $kpr_owner_id,
			'user_id' => $user_id,
			'currency_id' => $currency_id,
			'sold_date' => $sold_date,
			'snyc' => TRUE,
			'document_status' => $document_status,
			'client_email' => $client_email,
			'client_name' => $client_name,
			'client_hp' => $client_hp,
			'client_job_type_id' => $client_job_type_id,
			'gender_id' => $gender_id,
			'address' => $address,
			'birthplace' => $birthplace,
			'birthday' => $birthday,
			'ktp' => $ktp,
			'region_id' => $region_id,
			'city_id' => $city_id,
			'subarea_id' => $subarea_id,
			'status_marital' => $status_marital,
			'approved_admin_date' => date('Y-m-d H:i:s'),
		);

		if(!empty($data['KprBank'])){
			$data['KprBank'] = array_merge($data['KprBank'], $data_merge);
			$bank =  $this->RmCommon->filterEmptyField($data, 'Bank');
			// if(!empty($bank)){
			// 	unset($data['Bank']);
			// }
			$dataSave = $this->getDataSaveKPR($data, $value);
    		return $dataSave;
		}else{
			return false;
		}
    }

    function getDataSaveKPR($data, $value){
    	$kprBank =  $this->RmCommon->filterEmptyField($data, 'KprBank');
    	$document_status =  $this->RmCommon->filterEmptyField($data, 'KprBank', 'document_status');
    	
		if(!empty($kprBank)){
			$kprBankDates = $this->RmCommon->filterEmptyField($data, 'KprBankDate');
	    	$kprBankInstallments = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment');
	    	$id = $this->RmCommon->filterEmptyField($kprBank, 'id');
	    	$kpr_id = $this->RmCommon->filterEmptyField($kprBank, 'kpr_id');
	    	$bank_id = $this->RmCommon->filterEmptyField($kprBank, 'bank_id');
	    	$data = $this->RmCommon->_callUnset( array(
	    		'KprBank' => array(
	    			'id',
	    			'kpr_id',
	    			'created',
	    			'modified',
	    		),
	    	), $data);

	    	if(!empty($value)){
	    		$data['KprBank']['id'] = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
	    		$data['KprBank']['code'] = $this->RmCommon->filterEmptyField($value, 'KprBank', 'code');
	    	}else{
	    		$data['KprBank']['code'] = $this->controller->User->KprBank->generateCode($bank_id);
	    	}

	    	$data['KprBank']['prime_kpr_id'] = $kpr_id;
	    	$data['KprBank']['prime_kpr_bank_id'] = $id;

	    	if(!empty($kprBankDates)){
	    		foreach( $kprBankDates AS $key => $kprBankDate){
	    			$kprBankDate = $this->RmCommon->_callUnset(array(
						'KprBankDate' => array(
							'id',
							'kpr_bank_id',
			    			'created',
			    			'modified',
						),
					), $kprBankDate);
					$kprBankDate['KprBankDate']['slug'] = $document_status;
					$kprBankDates[$key]['KprBankDate'] = $kprBankDate['KprBankDate'];
	    		}
	    		$data['KprBankDate'] = $kprBankDates;
	    	}

	    	if(!empty($kprBankInstallments)){
	    		foreach ($kprBankInstallments as $key => $kprBankInstallment) {
	    			$kprBankCommissions = $this->RmCommon->filterEmptyField($kprBankInstallment,'KprBankCommission');

	    			if(!empty($kprBankCommissions)){
	    				foreach($kprBankCommissions As $loop => $kprBankCommission){
	    					$kprBankCommission = $this->RmCommon->_callUnset(array(
								'KprBankCommission' => array(
									'id',
									'kpr_bank_installment_id',
									'photo',
					    			'created',
					    			'modified',
								),
							), $kprBankCommission);

							$kprBankCommissions[$loop]['KprBankCommission'] = $kprBankCommission['KprBankCommission'];
	    				}
	    				$kprBankInstallment['KprBankCommission'] = $kprBankCommissions;
	    			}
	    		}

				$kprBankInstallment = $this->RmCommon->_callUnset(array(
					'KprBankInstallment' => array(
						'id',
						'kpr_bank_id',
		    			'created',
		    			'modified',
					),
				), $kprBankInstallment);
	    		$data['KprBankInstallment'][$key] = $kprBankInstallment;
	    	}

	    	//pengajuan frontend belum di cek 
	    	$data = $this->beforeSaveSyncInformation($data, $data);
		}
		return $data;
    }

  //   function getExistKPR($value){
  //   	$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');

		// $value['KprBank']= $this->controller->User->KprBank->KprBankInstallment->getMerge($value['KprBank'], $kpr_bank_id, array(
		// 	'fieldName' => 'kpr_bank_id'
		// ));

		// $kpr_bank_installment = $this->RmCommon->filterEmptyField($value, 'KprBank','KprBankInstallment');
		// $kpr_bank_installment_id = $this->RmCommon->filterEmptyField($kpr_bank_installment, 'id');

		// $value['KprBank']['KprBankInstallment'] = $this->controller->User->KprBank->KprBankInstallment->KprBankCommission->getMerge($value['KprBank']['KprBankInstallment'], $kpr_bank_installment_id, array(
		// 	'find' => 'all',
		// 	'fieldName' => 'kpr_bank_installment_id',
		// ));
		// $value = $this->RmCommon->_callUnset( array(
		// 	'KprBank' => array(
		// 		'appraisal_date',
		// 		'document_status',
		// 		'application_status',
		// 		'KprBankDate',
		// 	),
		// 	'Bank'
		// ), $value);
		// return $value;
  //   }

  //   function beforeViewKPRDetail($value){
  //   	$id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
		// $kpr_application = $this->RmCommon->filterEmptyField($value, 'KprBank', 'KprApplication');
		// if(!empty($kpr_application)){
		// 	$value['KprBank']['KprApplication'] = $this->getDocumentSort( array(
		// 		'DocumentCategory.is_required' => 1,
		// 		'DocumentCategory.id <>' => array( 3, 7, 19, 20),
		// 	), $value['KprBank']['KprApplication'], array(
		// 		'id' => $id, 
		// 		'document_type' => 'kpr_application',
		// 	));
		// }

		// $kpr_application_particular = $this->RmCommon->filterEmptyField($value, 'KprBank', 'KprApplicationParticular');
		// if(!empty($kpr_application_particular)){
		// 	$value['KprBank']['KprApplicationParticular'] = $this->getDocumentSort( array(
		// 		'DocumentCategory.is_required' => 1,
		// 		'DocumentCategory.id <>' => array( 1, 2, 3, 4, 5, 6, 7, 15, 16, 17),
		// 	), $value['KprBank']['KprApplicationParticular'], array(
		// 		'id' => $id, 
		// 		'document_type' => 'kpr_spouse_particular',
		// 	));
		// }
		// return $value;
  //   }

    function sendEmailCreditProcess($data){
    	$sendEmails['SendEmail'] = $this->RmCommon->filterEmptyField($data, 'SendEmail');
    	$notifications['Notification'] = $this->RmCommon->filterEmptyField($data, 'Notification');

    	if(!empty($sendEmails['SendEmail'])){
    		$this->RmCommon->validateEmail($sendEmails);
    	}

    	if(!empty($notifications['Notification'])){
    		$this->RmCommon->_saveNotification($notifications);
    	}

    }

    function doBeforeCreditProcess($data, $value){
    	$data = $this->controller->User->KprBank->KprBankDate->getSlug($data, $value, array(
    		'slug' => 'credit_process',
    	));

    	$data = $this->RmCommon->_callUnset(array(
    		'KprBankTransfer' => array(
    			'id',
    			'kpr_bank_id',
    			'created',
    			'modified',
    		),
    		'credit_process' => array(
    			'KprBankDate' => array(
    				'id',	
    				'created',
    				'modified'
    			),
    		),
    	), $data);

    	return $data;
    }

    function getDocuemntExcel($value){
    	$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');

		if(!empty($value['KprApplication'][0])){
			$value['KprApplication'][0] = $this->documentList($value['KprApplication'][0], array(
				'KprApplication' => array(
					'document_type' => 'kpr_application',
					'exlude' => array(3, 7, 19, 20),
				),
				'kpr_spouse_particular' => array(
					'document_type' => 'kpr_spouse_particular',
					'exlude' => array(1, 2, 3, 4, 5, 6, 7, 15, 16, 17),
				),
				'merge' => true,
			));
		}else{
			$document = $this->getDocumentSort( array(
				'DocumentCategory.is_required' => 1,
				'DocumentCategory.id <>' => array(3, 7, 19, 20),
			), array(), array(
				'kpr_bank_id' => $kpr_bank_id,
				'document_type' => 'kpr_application',
			));
			if(!empty($document)){
				$value = array_merge($value, $document);
			}
		}
		return $value;
    }

    function documentList($value, $options = array(), $params = array()){
    	if(!empty($value)){
    		$merge = $this->RmCommon->filterEmptyField($options, 'merge');
    		$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'id');
	    	$kpr_application_id = $this->RmCommon->filterEmptyField($value, 'KprApplication', 'id');

	    	if(!empty($options) && is_array($options)){
	    		foreach($options AS $modelName => $option){
	    			$document_type = $this->RmCommon->filterEmptyField($option, 'document_type');
	    			$exlude = $this->RmCommon->filterEmptyField($option, 'exlude');

	    			if($modelName == 'kpr_spouse_particular' && !empty($merge)){

	    				if($modelName == 'kpr_spouse_particular'){
	    					$modelName = 'KprApplication';

	    					$documents = $this->getDocumentSort( array(
								'DocumentCategory.is_required' => 1,
								'DocumentCategory.id <>' => $exlude,
							), array(), array(
								'id' => $kpr_application_id,
								'kpr_bank_id' => $kpr_bank_id,
								'document_type' => $document_type,
							));
	    					if(!empty($value['KprApplication']['Document'])){
								$value['KprApplication']['Document'] = array_merge($value['KprApplication']['Document'], $documents['Document']);
	    					}
	    				}
	    			}else if(!empty($value)){
	    				$val = $this->RmCommon->filterEmptyField($value, $modelName, false, array());
	    				$value[$modelName] = array_merge($this->getDocumentSort( array(
							'DocumentCategory.is_required' => 1,
							'DocumentCategory.id <>' => $exlude,
						), $val, array(
							'id' => $kpr_application_id, 
							'kpr_bank_id' => $kpr_bank_id,
							'document_type' => $document_type,
						)), $val);
	    			}
	    		}
	    	}
    	}
    	return $value;
    }

    

    function beforeSaveKprLog($data){

    	if(!empty($data) && is_array($data)){
    		$data['KprBank'] = array_merge($data['KprBank'], array(
	    		'prime_kpr_bank_id' => $this->RmCommon->filterEmptyField($data, 'KprBank', 'id'),
	    		'prime_kpr_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'id'),
		    	'company_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'company_id'),
		    	'bank_apply_category_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'bank_apply_category_id'),
		    	'mls_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'mls_id'),
		    	'property_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'property_id'),
		    	'agent_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'agent_id'),
		    	'user_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'user_id'),
		    	'currency_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'currency_id'),
		    	'client_email' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'client_email'),
		    	'client_name' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'client_name'),
		    	'client_hp' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'client_hp'),
		    	'client_job_type_id' => $this->RmCommon->filterEmptyField($data, 'Kpr', 'client_job_type_id'),
	    	));

	    	$data = $this->RmCommon->_callUnset(array(
	    		'Kpr',
	    		'KprBank' => array(
	    			'id'
	    		),
	    	), $data);
    	}
    	return $data;
    }

    function beforeViewHistoryDetail($value){

    	if(!empty($value)){
    		$client = $this->RmCommon->filterEmptyField($value, 'Client', false, array());
    		$clientProfile = $this->RmCommon->filterEmptyField($value, 'UserProfile', false, array());
    		$clientProfile = $this->RmCommon->dataConverter($clientProfile, array(
    			'date' => array(
    				'birthday'
    			),
    		), true);
    		$value['KprBank']['KprApplication'] = 	array_merge($client, $clientProfile);
    		$value = $this->RmCommon->_callUnset( array(
    			'Client',
    			'UserProfile'
    		), $value);
    	}
    	return $value;
    }

    function beforeSaveShareKprs($datas, $setting){
    	$from_web = $this->RmCommon->filterEmptyField($setting, 'Setting', 'name');

		if(!empty($datas)){
			foreach($datas AS $key => $data){
				$rumahku_kpr_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');
				$mls_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'mls_id');
				$modified = $this->RmCommon->filterEmptyField($data, 'KprBank', 'modified');
				$type = $this->RmCommon->filterEmptyField($data, 'KprBank', 'type');

				$kprBank = $this->controller->User->KprBank->getData('first', array(
					'conditions' => array(
						'KprBank.rumahku_kpr_id' => $rumahku_kpr_id,
					),
				), array(
					'status_snyc' => 'crontab',
				));

				$id = $this->RmCommon->filterEmptyField($kprBank, 'KprBank', 'id');
				$code = $this->RmCommon->filterEmptyField($kprBank, 'KprBank', 'code');

				if(!empty($mls_id)){
					$data = $this->RmCommon->dataConverter2($data, array(
						'unset' => array(
							'User',
							'KprBank' => array(
								'id',
								'created',
								'modified',
							),
							'KprApplication' => array(
								'id',
								'kpr_bank_id',
								'modified',
								'created',
								'KprApplicationJob' => array(
									'id',
									'kpr_application_id',
									'region_id',
									'city_id',
									'subarea_id',
									'zip',
									'created',
									'modified',
								),
							),
							'KprBankInstallment' => array(
								'id',
								'kpr_bank_id',
								'modified',
								'created',
								'KprBankCommission' => array(
									0 => array(
										'KprBankCommission' => array(
											'id',
											'kpr_bank_installment_id',
											'modified',
											'created',
										),
									),
								),
							),
							'KprBankDate' => array(
								'id',
								'kpr_bank_id',
								'modified',
								'created',
							),
						),
					));
					if(!empty($data['KprApplication']['KprApplicationJob'])){
						$data = $this->mergeKprApplicationJob($data);
					}
					$data['KprBank']['rumahku_kpr_id'] = $rumahku_kpr_id;
					$data['KprBank']['from_kpr'] = 'frontend';
					$data['KprBank']['from_web'] = !empty($from_web)?$from_web:'primesystem';

					if($type == 'kpr'){
						$data['KprBank']['application_status'] = 'completed';
					}
					
					if($id){
						$data['KprBank']['id'] = $id;
						$data['KprBank']['code'] = $code;
						$data['KprBank']['flag'] = 'change';
					}

					$datas[$key] = $data;
				}else{
					unset($datas[$key]);
				}
			}
			$datas['modified'] = $modified;
		}
		return $datas;
    }

    function mergeKprApplicationJob($data){
    	if(!empty($data['KprApplication']['KprApplicationJob'])){
    		$data['KprApplication'] = array_merge($data['KprApplication'], $data['KprApplication']['KprApplicationJob']);
    		$data = $this->RmCommon->_callUnset(array(
    			'KprApplication' => array(
    				'KprApplicationJob'
    			),
    		), $data);
    	}
    	return $data;
    }

    function callSettingType($data, $modelName, $bank_id){
    	if(!empty($data)){
    		// set rate floating
    		$value = $this->controller->User->Bank->BankSetting->getData('first', array(
    			'conditions' => array(
    				'BankSetting.bank_id' => $bank_id,
    			),
    		));

    		$interest_rate_fix = $this->RmCommon->filterEmptyField($data, $modelName, 'interest_rate_fix');
    		$interest_rate_cabs = $this->RmCommon->filterEmptyField($data, $modelName, 'interest_rate_cabs');
    		$interest_rate_float = $this->RmCommon->filterEmptyField($value, $modelName, 'interest_rate_float');

    		if(!empty($interest_rate_cabs) && $interest_rate_cabs > 0){
    			$type = "fixncabs";
    		}else if(!empty($interest_rate_float) && $interest_rate_float > 0){
    			$type = "floating";
    		}else{
    			$type = "flat";
    		}

    		if(!empty($type)){
    			$data[$modelName]['type'] = $type;
    			$data[$modelName]['interest_rate_float'] = $interest_rate_float;
    		}
    	}
    	return $data;
    }

    function beforeSaveProduct($data, $bank_id, $modelName, $product_id = false){
    	if(!empty($data)){
    		$data = $this->callSettingType($data, $modelName, $bank_id);
    		$other_fee = $this->RmCommon->filterEmptyField($data, 'BankProduct', 'other_fee');
    		$date_range = $this->RmCommon->filterEmptyField($data, 'BankProduct', 'date_range');
    		$interest_rate_cabs = $this->RmCommon->filterEmptyField($data, 'BankSetting', 'interest_rate_cabs');
    		$periode_cab = $this->RmCommon->filterEmptyField($data, 'BankSetting', 'periode_cab');
    		$data['BankProduct']['bank_id'] = $bank_id;
    		$data = $this->reservedPriceSettingKpr($data, $modelName);

    		if(in_array(TRUE, array($interest_rate_cabs, $periode_cab))){
    			$this->controller->User->Bank->BankSetting->validator()->add('interest_rate_cabs', array(
                   'notempty' => array(
						'rule' => array('notempty'),
						'message' => 'Suku bunga cap harap diisi',
					),
					'notZero' => array(
						'rule' => array('notZero'),
						'message' => 'Suku bunga cap harap diisi lebih besar dari 0',
					),
                ))->add('periode_cab', array(
                   'notempty' => array(
						'rule' => array('notempty'),
						'message' => 'Periode bunga cap harap diisi',
					),
					'notZero' => array(
						'rule' => array('notZero'),
						'message' => 'Periode bunga cap harap diisi lebih besar dari 0',
					),
                ))->add('interest_rate_float', array(
                   'notempty' => array(
						'rule' => array('notempty'),
						'message' => 'Suku bunga floating harap diisi',
					),
					'notZero' => array(
						'rule' => array('notZero'),
						'message' => 'Suku bunga floating harap diisi lebih besar dari 0',
					),
                ));
    		}


    		if(!empty($date_range)){
    			$data['BankProduct'] = $this->RmCommon->_callConvertDateRange($data['BankProduct'], $date_range);
    		}

    		// if(empty($other_fee)){
    		// 	$value = $this->controller->User->Bank->BankSetting->getData('first', array(
    		// 		'conditions' => array(
    		// 			sprintf('%s.bank_id', $modelName) => $bank_id,
    		// 		),
    		// 	));
    			
    		// 	if(!empty($value)){
    		// 		$value = $this->RmCommon->_callUnset(array(
    		// 			'BankSetting' => array(
    		// 				'id',
    		// 				'type',
    		// 				'product_id',
    		// 				'interest_rate_fix',
    		// 				'interest_rate_cabs',
    		// 				'interest_rate_float',
    		// 				'periode_fix',
    		// 				'periode_cab',
    		// 				'created',
    		// 				'modified',
    		// 			),
    		// 		), $value);

    		// 		$data[$modelName] = array_merge($data['BankSetting'], $value['BankSetting']);
    		// 	}else{
    		// 		$data[$modelName]['empty_default'] = TRUE;
    		// 	}
    		// }else{
    		// }

    		if(!empty($product_id)){
    			$data[$modelName]['bank_id'] = $bank_id;
    			$data['BankProduct']['id'] = $product_id;
    			$bankSetting = $this->controller->User->Bank->BankSetting->getData('first', array(
    				'conditions' => array(
    					'BankSetting.product_id' => $product_id,
    				),
    			), array(
    				'type' => 'all',
    			));
    			$bank_setting_id = $this->RmCommon->filterEmptyField($bankSetting, 'BankSetting', 'id');
    			$data[$modelName]['id'] = $bank_setting_id;
    		}
    	}
    	return $data;
    }

    function beforeViewProduct($value){
    	if(!empty($value)){
    		$date_from = $this->RmCommon->filterEmptyField($value, 'BankProduct', 'date_from');
    		$date_to = $this->RmCommon->filterEmptyField($value, 'BankProduct', 'date_to');

    		$value['BankProduct']['date_range'] = $this->RmCommon->_callReverseDateRange($date_from, $date_to, 'd-m-Y');
    	}
    	return $value;
    }
    #### MIGRATION DATA ####################################################################################################################
    function  merge_data($data, $value, $modelName){
    	if(empty($data[$modelName]) && !empty($value[$modelName])){
    		$data = array_merge($data, $value);
    	}
    	return $data;
    }

    function retriveMigration($value){
    	if(!empty($value)){	
			$kpr_application_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
			## GET DATA KPR CONFIRM
			$kprConfirm = $this->controller->User->KprBank->KprApplicationConfirm->find('first', array(
				'conditions' => array(
					'KprApplicationConfirm.kpr_application_id' => $kpr_application_id,
				),
			));
			$value = $this->merge_data($value, $kprConfirm, 'KprApplicationConfirm');
			##
			## GET DATA SPOUSEPARTICULAR/PASANGAN
			$spouse = $this->controller->User->KprBank->KprSpouseParticular->find('first', array(
				'conditions' => array(
					'KprSpouseParticular.kpr_application_id' => $kpr_application_id,
				),
			));
			$value = $this->merge_data($value, $spouse, 'KprSpouseParticular');
			##
			## GET DATA Comiission BEFORE UDPATE BANK
			$commission_before['KprCommissionPayment'] = $this->controller->User->KprBank->KprCommissionPayment->find('all', array(
				'conditions' => array(
					'KprCommissionPayment.kpr_application_id' => $kpr_application_id,
				),
			));
			$value = $this->merge_data($value, $commission_before, 'KprCommissionPayment');
			##
			$kpr_application_confirm_id = $this->RmCommon->filterEmptyField($value, 'KprApplicationConfirm', 'id');
			## GET DATA Comiission BEFORE UDPATE BANK
			$commission_after['KprCommissionPaymentConfirm'] = $this->controller->KprCommissionPaymentConfirm->find('all', array(
				'conditions' => array(
					'KprCommissionPaymentConfirm.kpr_application_confirm_id' => $kpr_application_confirm_id,
				),
			));
			$value = $this->merge_data($value, $commission_after, 'KprCommissionPaymentConfirm');
    	}
		return $value;
    }

    
    function checkPaidCommission($values, $modelName, $options = array()){
	    $com = $this->RmCommon->filterEmptyField($options, 'com');
	    $buildSave = $this->RmCommon->filterEmptyField($options, 'buildSave');
	    $status = $this->RmCommon->filterEmptyField($options, 'status');
	    $alias = $this->RmCommon->filterEmptyField($options, 'alias', false, 'KprCommission');

    	$unpaid = array(
    		'unpaid_agent' => 'no_provision',
    		'unpaid_rumahku' => 'no_provision',
    	);

    	$commission = array(); 

    	if(!empty($values)){
    		foreach($values AS $key => $value){
    			$type_komisi = $this->RmCommon->filterEmptyField($value, $modelName, 'type_komisi');
    			$rate_komisi = $this->RmCommon->filterEmptyField($value, $modelName, 'rate_komisi');
    			$commission = $this->RmCommon->filterEmptyField($value, $modelName, 'commission');

    			$type = ($type_komisi == 'agen') ? 'agent' : 'rumahku';
    			$type_commission = ($type_komisi == 'agen') ? '' : '_rumahku';
    			$paid = $this->RmCommon->filterEmptyField($value, $modelName, 'paid_fee_approved');
    			$status_paid = !empty($paid) ? 'approved' : 'pending';
    			$unpaid[sprintf('unpaid_%s', $type)] = $status_paid;

    			if(!empty($com)){
    				$unpaid[sprintf('provision%s', $type_commission)] = $rate_komisi;
    				$unpaid[sprintf('commission%s', $type_commission)] = $commission;
    			}

    			if(!empty($buildSave)){
    				$keterangan = $this->RmCommon->filterEmptyField($value, $modelName, 'keterangan');
    				$region_name = $this->RmCommon->filterEmptyField($value, $modelName, 'region_name');
    				$city_name = $this->RmCommon->filterEmptyField($value, $modelName, 'city_name');
    				$paid_fee_approved = $this->RmCommon->filterEmptyField($value, $modelName, 'paid_fee_approved');
    				$approve_date = $this->RmCommon->filterEmptyField($value, $modelName, 'approve_date');
    				$paid_fee_rejected = $this->RmCommon->filterEmptyField($value, $modelName, 'paid_fee_rejected');
    				$cancel_date = $this->RmCommon->filterEmptyField($value, $modelName, 'cancel_date');
    				$note_reason = $this->RmCommon->filterEmptyField($value, $modelName, 'note_reason');

    				if(!empty($paid_fee_approved)){
    					$paid_status = 'approved';
    					$action_date = $approve_date;

    				}else if($paid_fee_rejected){
    					$paid_status = 'rejected';
    					$action_date = $cancel_date;

    				}else{
    					$paid_status = 'pending';
    					$action_date = null;
    				}

    				$unpaid[$alias][$key] = array(
    					$alias => array(
	    					'type' => $type,
	    					'percent' => $rate_komisi,
	    					'value' => $commission,
	    					'note' => $keterangan,
	    					'region_name' => $region_name,
	    					'city_name' => $city_name,
	    					'paid_status' => $paid_status,
	    					'action_date' => $action_date,
	    					'status_confirm' => $status,
    					)
    				);
    			}
    		}
    	}

    	return $unpaid;
    }

    function G_kpr_bank($value, $from_web){
    	$first_name = $this->RmCommon->filterEmptyField($value, 'KprBank', 'first_name');
    	$last_name = $this->RmCommon->filterEmptyField($value, 'KprBank', 'last_name');
    	$full_name = sprintf('%s %s', $first_name, $last_name);
    	$application_form = $this->RmCommon->filterEmptyField($value, 'KprBank', 'application_form');
    	$application_status = ($application_form == 'fill') ? 'completed' : 'pending';
    	$appraisal_date = $this->RmCommon->filterEmptyField($value, 'KprBank', 'appraisal_date');

    	$kpr_application_payment = $this->RmCommon->filterEmptyField($value, 'KprCommissionPayment');
    	$kpr_commission_payment_confirms = $this->RmCommon->filterEmptyField($value, 'KprCommissionPaymentConfirm'); // diganti dengan confirm

    	$modelNameCommission = !empty($kpr_application_payment) ? 'KprCommissionPayment' : false;
    	$modelNameCommission = !empty($kpr_commission_payment_confirms) ? 'KprCommissionPaymentConfirm' : $modelNameCommission;
    	$kpr_commission_payment_confirms = $this->RmCommon->filterEmptyField($value, 'KprCommissionPaymentConfirm', false, $kpr_application_payment);
    	$unpaid_arr = $this->checkPaidCommission($kpr_commission_payment_confirms, 'KprCommissionPaymentConfirm');// diganti dengan confirm
    	$unpaid_agent = $this->RmCommon->filterEmptyField($unpaid_arr, 'unpaid_agent');
    	$unpaid_rumahku = $this->RmCommon->filterEmptyField($unpaid_arr, 'unpaid_rumahku');

    	return array(
    		'id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'id'),
    		'type' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'type'),
    		'type_log' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'type_log'),
    		'company_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'company_id'),
    		'mls_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'mls_id'),
    		'kpr_owner_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'kpr_owner_id'),
    		'bank_apply_category_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_apply_category_id'),
    		'property_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'property_id'),
    		'bank_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id'),
    		'agent_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'agent_id'),
    		'user_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'user_id'),
    		'currency_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'currency_id'),
    		'code' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'code'),
    		'client_email' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'email'),
    		'client_name' => $full_name,
    		'client_hp' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'no_hp'),
    		'client_job_type_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'job_type_id'),
    		'work_day' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'work_day'),
    		'prime_kpr_bank_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'is_id_company'),
    		'sold_date' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'sold_date'),
    		'appraisal_date' => $this->RmCommon->filterEmptyField($value, 'KprApplicationConfirm', 'appraisal_date', $appraisal_date),
    		'application_status' => $application_status,
    		'read' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'read'),
    		'from_kpr' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'from_kpr'),
    		'from_web' => $from_web,
    		'approved_admin_date' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'approved_admin_date'),
    		'unpaid_agent' => $unpaid_agent,
    		'unpaid_rumahku' => $unpaid_rumahku,
    	);
    }

    function G_kpr_application($value){
    	$kpr_spouse_particular = $this->RmCommon->filterEmptyField($value, 'KprSpouseParticular');

    	$first_name = $this->RmCommon->filterEmptyField($value, 'KprBank', 'first_name');
    	$last_name = $this->RmCommon->filterEmptyField($value, 'KprBank', 'last_name');
    	$full_name = sprintf('%s %s', $first_name, $last_name);

    	$kpr_application[0] = array(
    		'kpr_bank_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'id'),
    		'first_name' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'first_name'),
    		'last_name' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'last_name'),
    		'full_name' => $full_name,
    		'birthday' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'birthday'),
    		'birthplace' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'birthplace'),
    		'email' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'email'),
    		'gender_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'gender_id'),
    		'address' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'address'),
    		'address_2' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'address_2'),
    		'same_as_address_ktp' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'same_as_address_ktp'),
    		'rt' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'rt'),
    		'rw' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'rw'),
    		'region_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'region_id'),
    		'city_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'city_id'),
    		'subarea_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'subarea_id'),
    		'zip' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'zip'),
    		'phone' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'phone'),
    		'no_hp' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'no_hp'),
    		'no_hp_2' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'no_hp_2'),
    		'company' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'company'),
    		'job_type_id' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'job_type_id'),
    		'ktp' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'ktp'),
    		'income' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'income'),
    		'household_fee' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'household_fee'),
    		'other_installment' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'other_installment'),
    		'status_marital' => $this->RmCommon->filterEmptyField($value, 'KprBank', 'status_marital'),
    	);

    	if(!empty($kpr_spouse_particular)){
    		$kpr_spouse_particular = $this->RmCommon->_callUnset(array(
    			'kpr_application_id',
    		), $kpr_spouse_particular);
    		$kpr_application[1] = $kpr_spouse_particular;
    		$kpr_application[1]['kpr_bank_id'] = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    	}
    	return $kpr_application;
    }

    function installmentKPR($value, $modelName, $modelCommission, $status = false){
    	$credit_fix = $this->RmCommon->filterEmptyField($value, $modelName, 'credit_fix');
    	$credit_float = $this->RmCommon->filterEmptyField($value, $modelName, 'credit_float');
    	$credit_float = $this->RmCommon->filterEmptyField($value, $modelName, 'credit_float');
    	$loan_price = $this->RmCommon->filterEmptyField($value, $modelName, 'loan_price');
    	$interest_rate = $this->RmCommon->filterEmptyField($value, $modelName, 'interest_rate');
    	$credit_total = $credit_float + $credit_fix;

    	$total_first_credit = $this->creditFix($loan_price, $interest_rate, $credit_total);
    	$commission = $this->RmCommon->filterEmptyField($value, $modelCommission);
    	$provision_arr = $this->checkPaidCommission($commission, $modelCommission, array(
    		'com' => true,
    		'buildSave' => true,
    		'status' => $status,
    		'alias' => 'KprBankCommission',
    	));
    	$provision = $this->RmCommon->filterEmptyField($provision_arr, 'provision');
    	$commission = $this->RmCommon->filterEmptyField($provision_arr, 'commission');
    	$provision_rumahku = $this->RmCommon->filterEmptyField($provision_arr, 'provision_rumahku');
    	$commission_rumahku = $this->RmCommon->filterEmptyField($provision_arr, 'commission_rumahku');
    	$kpr_bank_commission = $this->RmCommon->filterEmptyField($provision_arr, 'KprBankCommission');

    	$loan_plafond = $this->RmCommon->filterEmptyField($value, $modelName, 'loan_plafond', 0);

    	if(!empty($loan_plafond)){
    		$fieldLoanPrice = 'loan_plafond';
    	}else{
    		$fieldLoanPrice = 'loan_price';
    		$loan_plafond = $this->RmCommon->filterEmptyField($value, $modelName, 'loan_price', 0);
    	}

    	$data['KprBankInstallment'] = array(
    		'property_price' => $this->RmCommon->filterEmptyField($value, $modelName, 'property_price', 0),
    		'down_payment' => $this->RmCommon->filterEmptyField($value, $modelName, 'down_payment', 0),
    		$fieldLoanPrice => $loan_plafond,
    		'credit_total' => $credit_total,
    		'interest_rate_fix' => $interest_rate,
    		'total_first_credit' => $total_first_credit,
    		'interest_rate_float' => $this->RmCommon->filterEmptyField($value, $modelName, 'floating_rate'),
    		'periode_fix' => $credit_fix,
    		'periode_installment' => $credit_total,
    		'provision' => $provision,
    		'commission' => $commission,
    		'provision_rumahku' => $provision_rumahku,
    		'commission_rumahku' => $commission_rumahku,
    		'administration' => $this->RmCommon->filterEmptyField($value, $modelName, 'administration', 0),
    		'administration_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'administration_percent', 0),
    		'administration_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_administration'),
    		'appraisal' => $this->RmCommon->filterEmptyField($value, $modelName, 'appraisal', 0),
    		'appraisal_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'appraisal_percent', 0),
    		'param_appraisal' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_appraisal'),
    		'insurance' => $this->RmCommon->filterEmptyField($value, $modelName, 'insurance', 0),
    		'insurance_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'insurance_percent', 0),
    		'insurance_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_insurance'),
    		'sale_purchase_certificate' => $this->RmCommon->filterEmptyField($value, $modelName, 'sale_purchase_certificate', 0),
    		'sale_purchase_certificate_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'sale_purchase_certificate_percent', 0),
    		'sale_purchase_certificate_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_sale_purchase_certificate'),
    		'transfer_title_charge' => $this->RmCommon->filterEmptyField($value, $modelName, 'transfer_title_charge', 0),
    		'transfer_title_charge_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'transfer_title_charge_percent', 0),
    		'transfer_title_charge_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_transfer_title_charge'),
    		'credit_agreement' => $this->RmCommon->filterEmptyField($value, $modelName, 'credit_agreement', 0),
    		'credit_agreement_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'credit_agreement_percent', 0),
    		'credit_agreement_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_credit_agreement'),
    		'letter_mortgage' => $this->RmCommon->filterEmptyField($value, $modelName, 'letter_mortgage', 0),
    		'letter_mortgage_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'letter_mortgage_percent', 0),
    		'letter_mortgage_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_letter_mortgage'),
    		'imposition_act_mortgage' => $this->RmCommon->filterEmptyField($value, $modelName, 'imposition_act_mortgage', 0),
    		'imposition_act_mortgage_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'imposition_act_mortgage_percent', 0),
    		'imposition_act_mortgage_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_imposition_act_mortgage'),
    		'mortgage' => $this->RmCommon->filterEmptyField($value, $modelName, 'mortgage', 0),
    		'mortgage_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'mortgage_percent', 0),
    		'mortgage_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_mortgage'),
    		'other_certificate' => $this->RmCommon->filterEmptyField($value, $modelName, 'other_certificate', 0),
    		'other_certificate_percent' => $this->RmCommon->filterEmptyField($value, $modelName, 'other_certificate_percent', 0),
    		'other_certificate_params' => $this->RmCommon->filterEmptyField($value, 'BankSetting', 'param_other_certificate'),
    		'status_confirm' => $status,

    	);

		if(!empty($kpr_bank_commission)){
			$data = array_merge($data, array(
				'KprBankCommission' => $kpr_bank_commission,
			));
		}

		return $data;
    }


    function G_kpr_bank_installment($value){
    	$kpr_application_confirm = $this->RmCommon->filterEmptyField($value, 'KprApplicationConfirm');
    	$kpr_bank_installment[0] = $this->installmentKPR($value, 'KprBank', 'KprCommissionPayment');
    	$kpr_bank_installment[0]['KprBankInstallment']['kpr_bank_id'] = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    	
    	if(!empty($kpr_application_confirm)){
    		$kpr_bank_installment[1] = $this->installmentKPR($value, 'KprApplicationConfirm', 'KprCommissionPaymentConfirm', true);
    		$kpr_bank_installment[1]['KprBankInstallment']['kpr_bank_id'] = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    	}
    	return 	$kpr_bank_installment;
    }

    function generateBeforeSave($value, $from_web){
    	if(!empty($value)){
    		$id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    		$bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id');
    		$value = $this->controller->User->Bank->getMerge($value, $bank_id);
    		$value = $this->controller->User->Bank->BankSetting->getMerge($value, $bank_id);
    		$dataSave['KprBank'] = $this->G_kpr_bank($value, $from_web);
    		$dataSave['KprApplication'] = $this->G_kpr_application($value);
    		$dataSave['KprBankInstallment'] = $this->G_kpr_bank_installment($value);
    		$dataSave['KprBankDate'] = $this->G_kpr_bank_date($value);
    		$document_status = $this->RmCommon->filterEmptyField($dataSave, 'KprBankDate', 'document_status');
    		$dataSave['KprBank']['document_status'] = $document_status;
    		$dataSave = $this->RmCommon->_callUnset(array(
    			'KprBankDate' => array(
    				'document_status'
    			),
    		), $dataSave);
    		$dataSave = $this->transferAndCreditAggrement($dataSave, $value);

    		return $dataSave;
    	}
    }

    function transferAndCreditAggrement($data, $value){
    	$kpt_bank_date = $this->RmCommon->filterEmptyField($data, 'KprBankDate');
    	$slug_arr = Set::classicExtract($kpt_bank_date, '{n}.KprBankDate.slug');
    	$slug_arr = !empty($slug_arr)?$slug_arr:array();

    	$agent_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'agent_id');
    	$rekening_nama_akun = $this->RmCommon->filterEmptyField($data, 'KprBank', 'rekening_nama_akun');
    	$no_rekening = $this->RmCommon->filterEmptyField($data, 'KprBank', 'no_rekening');
    	$rekening_bank = $this->RmCommon->filterEmptyField($data, 'KprBank', 'rekening_bank');
    	$no_npwp = $this->RmCommon->filterEmptyField($data, 'KprBank', 'no_npwp');

    	$contact_name = $this->RmCommon->filterEmptyField($data, 'KprBank', 'contact_name');
    	$contact_bank = $this->RmCommon->filterEmptyField($data, 'KprBank', 'contact_bank');
    	$contact_email = $this->RmCommon->filterEmptyField($data, 'KprBank', 'contact_email');
    	$description_akad = $this->RmCommon->filterEmptyField($data, 'KprBank', 'description_akad');
    	$process_akad_date = $this->RmCommon->filterEmptyField($data, 'KprBank', 'process_akad_date');
    	$send_email_staff = $this->RmCommon->filterEmptyField($data, 'KprBank', 'send_email_staff');

    	if(in_array('credit_process', $slug_arr)){
    		$data['KprBankTransfer'] = array(
    			'agent_id' => $agent_id,
    			'name_account' => $rekening_nama_akun,
    			'no_account' => $no_rekening,
    			'bank_name' => $rekening_bank,
    			'no_npwp' => $no_npwp,
    		);
    	}

    	if(in_array('approved_credit', $slug_arr)){
    		$data['KprBankCreditAgreement'] = array(
    			'staff_name' => $contact_name,
    			'staff_phone' => $contact_bank,
    			'staff_email' => $contact_email,
    			'note' => $description_akad,
    			'action_date' => $process_akad_date,
    			'sent_email' => $send_email_staff,
    		);
    	}
    	return $data;
    }

    function mergeKprBankDate($data, $kpr_bank_id, $modelName, $slugFields = array()){
    	$result = array();

    	if(!empty($data) && !empty($slugFields)){
    		foreach($slugFields AS $slug => $field){
    			$enum = $this->RmCommon->filterEmptyField($field, 'enum');
    			$date = $this->RmCommon->filterEmptyField($field, 'date');
    			$note = $this->RmCommon->filterEmptyField($field, 'note');
    			$field = $this->RmCommon->filterEmptyField($field, 'field');

    			if(!empty($enum)){
    				$field = $this->RmCommon->filterEmptyField($enum, 'field');
    				$option = $this->RmCommon->filterEmptyField($enum, 'option');
    			}
    			$value = $this->RmCommon->filterEmptyField($data, $modelName, $field);
    			$action_date = $this->RmCommon->filterEmptyField($data, $modelName, $date);

    			if(!empty($option) && ($value == $option)){
    				$value = TRUE;
    			}

    			if(!empty($value)){
    				$result[] = $this->buildBankDate($slug, $kpr_bank_id, $action_date, $note);
    				$result['document_status'] = $slug;
    			}
    		}
    	}
    	return $result;
    }

    function buildBankDate( $slug, $kpr_bank_id, $action_date = false, $note = false){
    	return array(
    		'KprBankDate' => array(
    			'kpr_bank_id' => $kpr_bank_id,
    			'slug' => $slug,
    			'action_date' => $action_date,
    			'note' => $note,
    		),
    	);
    }

    function G_kpr_bank_date($value){
    	$code = $this->RmCommon->filterEmptyField($value, 'KprBank', 'code');
    	$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');

    	return $this->mergeKprBankDate($value, $kpr_bank_id, 'KprBank', array(
    		'approved_admin' => array(
				'field' => 'approved_admin',
				'date' => 'approved_admin_date',
				'note' => __('Disetujui oleh admin rumahku untuk kode KPR %s', $code),
    		),
    		'rejected_admin' => array(
    			'field' => 'rejected_admin',
    			'date' => 'rejected_admin_date',
				'note' => __('Ditolak oleh admin rumahku untuk kode KPR %s', $code),
    		),
    		'rejected_proposal' => array(
    			'field' => 'reject_proposal',
    			'date' => 'proposal_date',
				'note' => __('Menolak referral oleh bank untuk kode KPR %s', $code),
    		),
    		'approved_proposal' => array(
    			'field' => 'aprove_proposal',
    			'date' => 'proposal_date',
				'note' => __('Menyetujui referral oleh bank untuk kode KPR %s', $code),
    		),
    		'proposal_without_comiission' => array(
    			'field' => 'aprove_proposal',
    			'field2' => 'rejected_commission',
    			'date' => 'proposal_date',
				'note' => __('Disetujui referral tanpa provisi agen untuk kode KPR %s', $code),
    		),
    		'approved_bank' => array(
    			'field' => 'approved',
    			'date' => 'approved_date',
				'note' => __('Disetujui aplikasi oleh bank untuk kode KPR %s', $code),
    		),
    		'rejected_bank' => array(
    			'field' => 'rejected',
    			'date' => 'rejected_date',
				'note' => __('Ditolak aplikasi oleh bank untuk kode KPR %s', $code),
    		),
    		'credit_process' => array(
    			'field' => 'assign_project',
    			'date' => 'assign_project_date',
				'note' => __('Disetujui aplikasi oleh agen untuk kode KPR %s', $code),
    		),
    		'cancel' => array(
    			'field' => 'cancel_project',
    			'date' => 'cancel_project_date',
    			'note' => __('Dibatalkan aplikasi oleh agen untuk kode KPR %s', $code),
    		),
    		'rejected_credit' => array(
    			'enum' => array(
    				'field' => 'document_status_application',
    				'option' => 'cancel_credit'
    			),
    			'date' => 'process_akad_date',
    			'note' => __('Menolak akad kredit oleh bank untuk kode KPR %s', $code),
    		),
    		'approved_credit' => array(
    			'enum' => array(
    				'field' => 'document_status_application',
    				'option' => 'akad_credit'
    			),
    			'date' => 'process_akad_date',
    			'note' => __('Menyetujui akad kredit oleh bank untuk kode KPR %s', $code),
    		),
    	));
    }

    function flagSlugDocumentStatus($value, $filter, $params){
    	if(!empty($value)){
    		$slug_arr = Set::classicExtract($value, $filter);

    		if(!empty($slug_arr) && array_intersect($params, $slug_arr)){
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }

    function getKPRStatus( $values ) {
    	$result = array();

    	if( !empty($values) ) {
    			if( !isset($values['KprBank']) ) {
	    			foreach( $values as $key => $value ) {
	    				$document_status = $this->RmCommon->filterEmptyField($value, 'KprBank', 'document_status');
    					$status = $this->_callStatus( $document_status );
		  				$value['KprBank']['status_desc'] = $status;
		  				$values[$key] = $value;
		    		}
		    	} else {
	    			$document_status = $this->RmCommon->filterEmptyField($values, 'KprBank', 'document_status');
					$status = $this->_callStatus( $document_status );
	  				$values['KprBank']['status_desc'] = $status;
		    	}
    	}
    	return $values;
    }

    function _callStatus ( $document_status = false) {
    	if( Configure::read('User.admin') ) {
    		switch($document_status){
	    		case 'approved_admin':
	    			$status = 'Approved';
	    			break;
	    		case 'rejected_admin':
	    			$status = 'Rejected';
	    			break;
	    		case 'pending' :
	    			$status = 'Pending';
	    			break;
	    		default:
	    			$status = FALSE;
	    			break;
	    	}
    	}else{
    		switch($document_status){
	    		case 'credit_process':
	    			$status = 'Assign';
	    			break;
	    		case 'rejected_credit':
	    			$status = 'Cancel';
	    			break;
	    		case 'approved_bank':
	    			$status = 'Approved';
	    			break;
	    		case 'rejected_bank':
	    			$status = 'Rejected';
	    			break;
	    		default :
	    			$status = 'Pending';
	    			break;
	    	}
    	}
    	
		return $status;
    }
    // nyalakan lagi nanti ketika mau dinaikin ke pasiris
    function getNotifEmail($slug, $value){
    	if(!empty($slug) && !empty($value)){
    		$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
    		$bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id');
    		$code = $this->RmCommon->filterEmptyField($value, 'KprBank', 'code');
    		$property_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'property_id');
    		$agent_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'agent_id');
    		$document_status = $this->RmCommon->filterEmptyField($value, 'KprBank', 'document_status');

    		$value = $this->controller->User->KprBank->KprBankDate->getMerge($value, $kpr_bank_id, array(
				'fieldName' => 'Kpr_bank_id',
				'find' => 'all',
			));

    		$flag = $this->flagSlugDocumentStatus($value, 'KprBankDate.{n}.KprBankDate.slug', array('approved_bank'));
			$status_confirm = !empty($flag) ? 'confirm' : 'no_confirm';

			$value = $this->controller->User->KprBank->KprBankInstallment->getMerge($value, $kpr_bank_id, array(
				'fieldName' => 'Kpr_bank_id',
				'elements' => array(
					'status' => $status_confirm
				),
			));

			$value = $this->controller->User->Property->getMerge($value, $property_id);
    		$value = $this->controller->User->KprBank->Bank->getMerge($value, $bank_id);
    		$value = $this->controller->User->getMerge($value, $agent_id, true);

    		$value['BankDomain'] = $this->RmCommon->filterEmptyField($value, 'Bank');
    		$bank_name = $this->RmCommon->filterEmptyField($value, 'Bank', 'name');


    		$template = 'notification_admin';

    		switch ($slug) {
    			case 'approved_proposal':
    				$notif = $subject = sprintf(__('Referral KPR %s telah disetujui oleh %s'), $code, $bank_name);
    				break;
    			case 'proposal_without_comiission':
    				$notif = $subject = sprintf(__('Referral KPR %s telah disetujui namun %s tidak menyediakan Provisi'), $code, $bank_name);
    				break;
    			case 'rejected_proposal':
    				$notif = $subject = sprintf(__('%s menolak Referral KPR %s'), $bank_name, $code);
    				break;
    			case 'approved_bank':
    				$notif = $subject = sprintf(__('Aplikasi KPR %s telah disetujui oleh %s'), $code, $bank_name);
    				break;
    			case 'rejected_bank':
    				$notif = $subject = sprintf(__('%s menolak Aplikasi KPR %s'), $bank_name, $code);
    				break;
    			case 'approved_credit':
    				$notif = $subject = sprintf(__('%s telah menetapkan jadwal Akad Kredit %s'), $bank_name, $code);
    				break;
    			case 'rejected_credit':
    				$notif = $subject = sprintf(__('%s menolak Akad Kredit %s'), $bank_name, $code);
    				break;
    		}

    		if(!empty($template)){
    			$value['notif'] = $notif;
    			$value['subject'] = $subject;

    			$this->RmCommon->validateEmail(array(
					'SendEmail' => array(
						'admin' => true,
                    	'subject' => $subject,
                    	'template' => $template,
                    	'data' => $value,
                    	// 'debug' => 'view',
                    ),
				));

				$this->RmCommon->_saveNotification(array(
					'Notification' => array(
                        'admin' => true,
                        'name' => $notif,
                        'link' => array(
                            'controller' => 'kpr',
                            'action' => 'user_apply_detail',
                            $kpr_bank_id,
                            'admin' => true,
                        ),
                    ),
				));
    		}
    	}
    }

    function switchKPR($data){
    	$result = array();
    	$document_status = $this->RmCommon->filterEmptyField($data, 'KprBank', 'document_status');
    	$application_status = $this->RmCommon->filterEmptyField($data, 'KprBank', 'application_status');
    	$application_snyc = $this->RmCommon->filterEmptyField($data, 'KprBank', 'application_snyc');
    	$prime_kpr_bank_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');

    	if(!empty($document_status)){
    		$value = $this->controller->User->KprBank->getData('first', array(
				'conditions' => array(
					'KprBank.prime_kpr_bank_id' => $prime_kpr_bank_id,
				),
			), array(
				'status_snyc' => 'crontab',
			));

			if(!empty($value) || $document_status == 'process'){
	    		switch ($document_status) {
	    			case 'process':
	    				$result = $this->apiReferrals($data, $value);
	    				break;

	    			case 'proposal_without_comiission':
	    				if(!empty($application_snyc)){
	    					$result = $this->controller->User->KprBank->resend($data, $value);
	    				}
	    				break;

	    			case 'cancel' :
	    				$data = $this->beforeSaveApiCancel($data, $value);
	    				$result = $this->controller->User->KprBank->doSaveApiCancel($data, $value);
	    				break;

	    			// case 'credit_process' :
	    			// 	$data = $this->doBeforeCreditProcess($data, $value);
	    			// 	$result = $this->controller->User->KprBank->doSaveCreditProcess($data, $value);
	    			// 	break;
	    		}
			}else{
				$code = $this->RmCommon->filterEmptyField($data, 'KprBank', 'code');
				$result = array(
					'msg' => __('Data KPR %s tidak ditemukan di bank atau data rusak dikarenakan salah satu terhapus secara manual', $code)
				);
			}

    	}
    	return $result;
    }

    function api_sync_application($data){

		if(!empty($data)){
	    	$principle = $this->RmCommon->filterEmptyField($data, 'Principle');
			$company = $this->RmCommon->filterEmptyField($data, 'UserCompany');
			$agent = $this->RmCommon->filterEmptyField($data, 'Agent');
			$prime_kpr_bank_id = $this->RmCommon->filterEmptyField($data, 'KprBank','id');

			$dataSave = $this->controller->User->KprBank->getData('first', array(
				'conditions' => array(
					'KprBank.prime_kpr_bank_id' => $prime_kpr_bank_id,
				)
			), array(
				'status_snyc' => 'crontab',
			));

			$dataSave = $this->beforeSaveSyncInformation($data, $dataSave);

			if( !empty($principle) ) {
				// $dataSave['Principle'] = array(
				// 	'User' => $principle,
				// 	'UserCompany' => $company,
				// 	'Agent' => $agent,
				// );
			}

			$documentCategories = $this->RmCommon->filterEmptyField($data, 'documentCategories');
			if($documentCategories){
				$dataSave['documentCategories'] = $documentCategories;
			}

			$documentCategoriesSpouse = $this->RmCommon->filterEmptyField($data, 'documentCategoriesSpouse');
			if($documentCategoriesSpouse){
				$dataSave['documentCategoriesSpouse'] = $documentCategoriesSpouse;
			}
			$result = $this->controller->User->KprBank->KprApplication->DoSaveAllByPrime($dataSave, true);
			$this->RmCommon->setProcessParams( $result, false, array(
				'noRedirect' => true,
			));

		}
		return $result;
    }

    function apiReferrals($data, $value){
    	$dataEmail = $data;
    	$userCompany = $this->RmCommon->filterEmptyField($data, 'UserCompany');
    	$userCompanyConfig = $this->RmCommon->filterEmptyField($data, 'UserCompanyConfig');

    	$email_principle = $this->RmCommon->filterEmptyField($data, 'Principle', 'email');
    	$from_kpr = $this->RmCommon->filterEmptyField($data, 'KprBank', 'from_kpr');
    	$forward_app = $this->RmCommon->filterEmptyField($data, 'KprBank', 'forward_app');
		$dataPrinciple['User'] = $this->RmCommon->filterEmptyField($data, 'Principle');
		$dataPrinciple['UserCompany'] = $this->RmCommon->filterEmptyField($data, 'UserCompany');
		$principle = $this->controller->User->getMerge(array(), $email_principle, false, 'User.email');

		## CHECK PROPERTY EXIST
		if(!empty($data['Property'])){
			$data = $this->controller->User->UserCompany->saveExist($data, array(
				'Kpr' => 'company_id',
				'Agent' => 'parent_id',
				'Principle' => 'id',
				'UserCompany' => 'user_id',
			));

			$email = $this->RmCommon->filterEmptyField($data, 'Agent', 'email');
			$data = $this->controller->User->doSaveSync($data, array(
				'changeModels' => array(
					'User' => 'Agent',
					'UserProfile' => 'UserProfile',
				),
				'changes' => array(
					'Property' => 'user_id',
					'Kpr' => 'agent_id',
					'UserProfile' => 'user_id',
				),
				'conditions' => array(
					'User.email' => $email,
				), 
				'elements' => array(
					'status' => 'all',
				),
			));

			$result = $this->Property->apiSaveAll($data);
			$property_id = $this->RmCommon->filterEmptyField($result,'id');
			$data['Property']['id'] = $property_id;

			$data['Kpr']['property_id'] = !empty($property_id)?$property_id:$this->RmCommon->filterEmptyField($data, 'Kpr', 'property_id');
			$result = $this->controller->User->KprBank->KprOwner->doSaveSync($data, $property_id);
			$owner_id = $this->RmCommon->filterEmptyField($result, 'id');
			$data['Kpr']['kpr_owner_id'] = !empty($owner_id)?$owner_id:null;
			$kpr_id = $this->RmCommon->filterEmptyField($data, 'Kpr', 'id');

			if(!empty($data['KprBank'])){
				$data = $this->beforeSaveProposals($data);
				$document = $this->RmCommon->filterEmptyField($data, 'documentCategories');
				$bank_name = $this->RmCommon->filterEmptyField($data, 'Bank', 'name');

				$dataSave['KprBank'] = $this->RmCommon->filterEmptyField($data, 'KprBank');
				$dataSave['KprBankInstallment'] = $this->RmCommon->filterEmptyField($data, 'KprBankInstallment');
				$dataSave['KprBankDate'] = $this->RmCommon->filterEmptyField($data, 'KprBankDate');
				$kpr_bank_id = $this->RmCommon->filterEmptyField($dataSave, 'KprBank', 'id');

				$code = $this->RmCommon->filterEmptyField($dataSave, 'KprBank', 'code');
				$result = $this->controller->User->KprBank->doSaveAll($dataSave, array(
					'prime_kpr_id' => $kpr_id,
					'validate' => false,
					'id' => $kpr_bank_id,
					'default_msg' => __('Pengajuan KPR %s, sedang diproses oleh %s', $code, $bank_name),
				));

				$document_id = $this->RmCommon->filterEmptyField($result, 'Log', 'document_id');
				$this->RmCommon->setProcessParams($result, false, array(
					'noRedirect' => true,
				));

				$status = $this->RmCommon->filterEmptyField($result, 'status');

				if($status == 'success'){
					$dataSave = $this->beforeSaveSyncInformation($data, $dataSave);
					$documentCategories = $this->RmCommon->filterEmptyField($data, 'documentCategories');
					if($documentCategories){
						$dataSave['documentCategories'] = $documentCategories;
					}

					$documentCategoriesSpouse = $this->RmCommon->filterEmptyField($data, 'documentCategoriesSpouse');
					if($documentCategoriesSpouse){
						$dataSave['documentCategoriesSpouse'] = $documentCategoriesSpouse;
					}

					if(!empty($dataSave['KprApplication']) || !empty($dataSave['Kpr']['KprApplication'])){
						$result_apps = $this->controller->User->KprBank->KprApplication->DoSaveAllByPrime($dataSave, false, $document_id);
					}else{
						$result_apps = $this->controller->User->KprBank->KprApplication->Document->KprDocument($dataSave, array(
							'kpr_application_id' => $document_id,
						));
					}

					$this->RmCommon->setProcessParams($result_apps, false, array(
						'noRedirect' => true,
					));

					$data = $this->RmCommon->filterEmptyField($result, 'data');

					if(!empty($data)){
						$id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'id');
						$property_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'property_id');
						$agent_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'agent_id');
						$code = $this->RmCommon->filterEmptyField($data, 'KprBank', 'code');
						$bank_id = $this->RmCommon->filterEmptyField($data, 'KprBank', 'bank_id');
						$data = $this->controller->User->Property->getMerge($data, $property_id);
						$data = $this->controller->User->Property->PropertyAddress->getMerge($data, $property_id);
						$property_address = $this->RmCommon->filterEmptyField($data, 'PropertyAddress');
						$city = $this->RmCommon->filterEmptyField($property_address, 'City', 'name');

						$agent = $this->controller->User->getData('first', array(
							'conditions' => array(
								'User.id' => $agent_id,
							),
						));

						$agent = $this->controller->User->UserProfile->getMerge($agent, $agent_id);

						$data['Agent'] = $this->RmCommon->filterEmptyField($agent, 'User', false, array());
						$data['AgentProfile'] = $this->RmCommon->filterEmptyField($agent, 'UserProfile', false, array());
						$first_name = $this->RmCommon->filterEmptyField($data, 'Agent', 'first_name');
						$last_name = $this->RmCommon->filterEmptyField($data, 'Agent', 'last_name');
						$agent_name = sprintf('%s %s', $first_name, $last_name);
						$data = $this->controller->User->KprBank->getMergeAll($data, $id, array(
							'with_contain' => TRUE
						));

						if($from_kpr == 'frontend' && empty($forward_app)){
				    		$client_name = $this->RmCommon->filterEmptyField($dataEmail, 'Kpr', 'client_name');
				    		$mls_id = $this->RmCommon->filterEmptyField($dataEmail, 'Kpr', 'mls_id');
				    		$domain = $this->RmCommon->filterEmptyField($dataEmail, 'UserCompanyConfig', 'domain');
				    		$kprBankInstallments = $this->RmCommon->filterEmptyField($dataEmail, 'KprBankInstallment');
				    		$kprBankInstallment = array_shift($kprBankInstallments);
				    		$dataEmail['KprBankInstallment'] = $this->RmCommon->filterEmptyField($kprBankInstallment, 'KprBankInstallment');
				    		$kprApplications = $this->RmCommon->filterEmptyField($dataEmail, 'Kpr', 'KprApplication');
							$kprApplication = array_shift($kprApplications);
							$dataEmail['KprBank']['KprApplication'] = $this->RmCommon->filterEmptyField($kprApplication, 'KprApplication');
							$dataEmail['KprBank']['mls_id'] = $mls_id;

				    		$dataEmail['headerDomain'] = "Company";
							$this->RmCommon->validateEmail(array(
								'SendEmail' => array(
				                	'admin' => true,
					            	'subject' => sprintf(__('Klien %s mengirimkan Aplikasi KPR melalui %s'), $client_name, $domain),
					            	'template' => 'kpr_applicant',
					            	'data' => $dataEmail,
					            	// 'debug' => 'view',
				                ),
							));

							$this->RmCommon->_saveNotification(array(
								'Notification' => array(
				                    'admin' => true,
				                    'name' => sprintf(__('Klien %s mengirimkan Aplikasi KPR melalui detail properti %s'), $client_name, $domain),
				                    'link' => array(
				                        'controller' => 'kpr',
				                        'action' => 'user_apply_detail',
				                        $id,
				                        'admin' => true,
				                    ),
				                ),
							));
				    	}else{
							$data['headerDomain'] = 'Company';
							$data['UserCompany'] = $userCompany;
							$data['UserCompanyConfig'] = $userCompanyConfig;

							$this->RmCommon->validateEmail(array(
								'SendEmail' => array(
		                        	'bank_id' => $bank_id,
		                        	'subject' => sprintf(__('Pengajuan Referral KPR - %s'), $code),
		                        	'template' => 'proposal_request',
		                        	'data' => $data,
		                        	// 'debug' => 'view',
			                    ),
							));

							$this->RmCommon->validateEmail(array(
								'SendEmail' => array(
		                        	'admin' => true,
		                        	'subject' => sprintf(__('Pengajuan Referral KPR - %s'), $code),
		                        	'template' => 'proposal_request',
		                        	'data' => $data,
		                        	// 'debug' => 'view',
			                    ),
							));

							$this->RmCommon->_saveNotification(array(
								'Notification' => array(
		                            'bank_id' => $bank_id,
		                            'name' => sprintf(__('Agen %s mengajukan Referral KPR %s untuk properti di %s'), $agent_name, $code, $city),
		                            'link' => array(
		                                'controller' => 'kpr',
		                                'action' => 'user_apply_detail',
		                                $id,
		                                'admin' => true,
		                            ),
		                        ),
							));

							$this->RmCommon->_saveNotification(array(
								'Notification' => array(
		                            'admin' => true,
		                            'name' => sprintf(__('Agen %s mengajukan Referral KPR %s untuk properti di %s'), $agent_name, $code, $city),
		                            'link' => array(
		                                'controller' => 'kpr',
		                                'action' => 'user_apply_detail',
		                                $id,
		                                'admin' => true,
		                            ),
		                        ),
							));
				    	}
					}
				}
			}
		}else{
			$result = array(
				'msg' => __('Data Properti tidak ditemukan'),
			);
		}
		##
		return $result;
    }

    function beforeSaveApiCancel($data, $value){
    	$data = $this->controller->User->KprBank->KprBankDate->getSlug($data, $value, array(
    		'slug' => 'cancel',
    	));
    	if(!empty($data) && !empty($value)){
    		$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
	    	$data = $this->RmCommon->_callUnset( array(
	    		'KprBankDate',
	    		'cancel' => array(
	    			'KprBankDate' => array(
	    				'id', 
	    				'created',
	    				'modified',
	    			),
	    		),
	    	), $data);
    	}
    	return $data;
    }

    function _api_saveKprBank($data){
		$this->Property = ClassRegistry::init('Property');
		$result = array();
		$status = 0;
		$flag = true;
		$prime_kpr_id = array();
		$code = array();
		$msg = __('Aplikasi KPR tidak ditemukan');

		if(!empty($data)){
			$result = $this->switchKPR($data);
			$msg = $this->RmCommon->filterEmptyField($result, 'msg');
			$this->RmCommon->setProcessParams($result, false, array(
				'noRedirect' => true,
			));

			printf('%s <br>', $msg);
		}

		return $result;
	}

	function setViewApplication($status, $value, $data){
		$document = false;
		$document_categories = $this->RmCommon->filterEmptyField($value, 'document_categories');
		$document_category_spouses = $this->RmCommon->filterEmptyField($value, 'document_category_spouses');

		if(!empty($document_categories)){
			foreach ($document_categories as $key => $documentCategori) {
				$document_category_id = $this->RmCommon->filterEmptyField($documentCategori, 'DocumentCategory', 'id');
				$document_type = 'kpr_application';
				$path = sprintf('/Document[document_category_id=%s][document_type=%s]', $document_category_id, $document_type);
				$doc = Set::extract($path, $data);

				if($doc){
					$document = array_shift($doc); 
				}

				$file = $this->RmCommon->filterEmptyField($document, 'Document', 'file');
				$file = $this->RmCommon->filterEmptyField($documentCategori, 'Document', 'file', $file);

				if($file){
					$documentCategori['Document']['file'] = $file;
					$documentCategori['Document']['save_path'] = 'documents';
					$this->controller->request->data['KprApplication'][0]['Document'][$document_category_id]['file'] = $file;
					$this->controller->request->data['KprApplication'][0]['Document'][$document_category_id]['file_hide'] = $file;
				}
				$document_categories[$key] = $documentCategori;

			}
		}

		if(!empty($document_category_spouses)){
			foreach ($document_category_spouses as $key => $document_category_spouse) {
				$document_category_id = $this->RmCommon->filterEmptyField($document_category_spouse, 'DocumentCategory', 'id');
				$document_type = 'kpr_spouse_particular';
				$path = sprintf('/Document[document_category_id=%s][document_type=%s]', $document_category_id, $document_type);

				$doc = Set::extract($path, $data);

				if($doc){
					$document = array_shift($doc); 
				}

				$file = $this->RmCommon->filterEmptyField($document, 'Document', 'file');
				$file = $this->RmCommon->filterEmptyField($document_category_spouse, 'Document', 'file', $file);

				if($file){
					$document_category_spouse['Document']['file'] = $file;
					$document_category_spouse['Document']['save_path'] = 'documents';
					$this->controller->request->data['KprApplication'][1]['Document'][$document_category_id]['file'] = $file;
					$this->controller->request->data['KprApplication'][1]['Document'][$document_category_id]['file_hide'] = $file;
				}
				$document_category_spouses[$key] = $document_category_spouse;
			}
		}

		return array(
			'document_categories' => $document_categories,
			'document_category_spouses' => $document_category_spouses,
		);

    }
}
?>