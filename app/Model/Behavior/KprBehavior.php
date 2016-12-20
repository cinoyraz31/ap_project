<?php
App::uses('CommonBehavior', 'Model');

	
class KprBehavior extends CommonBehavior {

    function generateCode(model $model, $bank_id = false, $code = false) {
    	$new_code = null;
    	$modelName = $model->name;

		$bank = $model->Bank->getData('first',array(
			'conditions' => array(
				'Bank.id' => $bank_id,
			),
		), array(
			'status' => 'all',
		));		

		if(!empty($bank)){
			$code = $this->filterEmptyField($model, $bank, 'Bank', 'code');
			$status_kpr = 'apply_kpr';
		}else{
			$status_kpr = 'kpr_calculate';
		}
		
		$codeDate = $code.date('Ymd');
		$length_code = strlen($codeDate);
		$code_start = $length_code+1;
		// $model->virtualFields['code'] = sprintf('SUBSTRING(KprBank.code,%s, 5', $code_start);

		$last_order = $model->getData('first', array(
			'fields'=> array(
				sprintf('SUBSTRING(%s.code,'.$code_start.', 5) as code', $modelName)
			),
			'conditions'=> array(
				sprintf('LEFT(%s.code, '.$length_code.')', $modelName) => $codeDate,
			),
			'order'=> array(
				sprintf('%s.code', $modelName) => 'DESC',			
			),
		), array(
			'status_snyc' => 'crontab',
			'status_kpr' => $status_kpr,
		));

		$new_code = $codeDate;

		if(!empty($last_order[0]['code'])) {
			$new_code .= str_pad((int)$last_order[0]['code']+1, 5, '0', STR_PAD_LEFT);
		} else {
			$new_code .= str_pad(1, 5, '0', STR_PAD_LEFT);
		}

		return $new_code;
	}

	function mergeApplication(model $model, $value, $id, $index = false){
		if(isset($value)){
			if(empty($index)){
				$value = $model->getMerge( $value, $id, array(
					'optionConditions' => array(
						'KprApplication.parent_id' => NULL,
					),
					'fieldName' => 'kpr_bank_id',
				));
				$value = $this->mergeOptionApplication($model, $value, 'KprApplication');
				$kpr_application_id  = $model->filterEmptyField($value, 'KprApplication', 'id');
				## GET SPOUSE PARTICULAR
				$value = $model->getMerge( $value, $kpr_application_id, array(
					'virtualModel' => 'KprApplicationParticular',
					'fieldName' => 'KprApplication.parent_id', 
				));	
				$value = $this->mergeOptionApplication($model, $value, 'KprApplicationParticular');
			}else{
				$vals = $model->getData('all', array(
					'conditions' => array(
						'KprApplication.kpr_bank_id' => $id,
					),
					'order' => array(
						'KprApplication.parent_id' => 'ASC',
					),
				));
				if(!empty($vals)){
					foreach($vals AS $key => $val){
						$dataApp['KprApplication'][] = $val['KprApplication'];
					}
					$value = array_merge($value, $dataApp);
				}
			}
			
		}
		return $value;
	}

	function mergeOptionApplication(model $model, $data, $modelName){
		$value = array();
		$value_model = $this->filterEmptyField($model, $data, $modelName);

		if(!empty($value_model) && !empty($modelName)){
			$region_id = $this->filterEmptyField($model, $data, $modelName, 'region_id');
			$city_id = $this->filterEmptyField($model, $data, $modelName, 'city_id');
			$subarea_id = $this->filterEmptyField($model, $data, $modelName, 'subarea_id');
			$job_type_id = $this->filterEmptyField($model, $data, $modelName, 'job_type_id');

			$value = $model->JobType->getMerge($value, $job_type_id);
			$value = $model->Region->getMerge($value, $region_id);
			$value = $model->City->getMerge($value, $city_id);
			$value = $model->Subarea->getMerge($value, $subarea_id);
			if(!empty($value)){
				$data[$modelName] = array_merge($data[$modelName],$value);
			}
		}
		
		return $data;
	}

	function getDocumentEditApplication(model $model, $application_id){
		$document_categories = $model->getData( 'all', array(
       		'conditions' => array(
 	      		'DocumentCategory.id' => array(
 	      			1, 2, 
 	      			4, 6,
 	      			15, 16,
 	      			17,
 	      		),
       		)
       	));
       	$document_categories = $model->getDataList($document_categories, $application_id);
       	## DOCUMENT SPOUSE
       	$document_category_spouses = $model->getData( 'all', array(
       		'conditions' => array(
 	      		'DocumentCategory.id' => array(
 	      			19, 20,
 	      		),
       		)
       	));
       	$document_category_spouses = $model->getDataList( $document_category_spouses, $application_id, 'kpr_spouse_particular');

       	return array(
       		'document_categories' => $document_categories,
       		'document_category_spouses' => $document_category_spouses,
       	);
	}

	function getCntStatus(model $model, $conditionOptions, $is_admin){
		
		if(!empty($is_admin)){ // LOGIN SEBAGAI ADMIN

			$cnt_pending = $model->getData('count', $conditionOptions, array(
				'document_status' => 'pending',
			));

			$cnt_approved_admin = $model->getData('count', $conditionOptions, array(
				'document_status' => 'approved_admin',
			));

			$cnt_rejected_admin = $model->getData('count', $conditionOptions, array(
				'document_status' => 'rejected_admin',
			));

		}else{ // LOGIN SEBAGAI BANK
			$cnt_pending = $model->getData('count', $conditionOptions, array(
				'document_status' => 'approved_admin',
			));
		}

		$cnt_cancel = $model->getData('count', $conditionOptions, array(
			'document_status' => 'cancel',
		));

		$cnt_rejected_proposal = $model->getData('count', $conditionOptions, array(
			'document_status' => 'rejected_proposal',
		));

		$cnt_proposal_without_comiission = $model->getData('count', $conditionOptions, array(
			'document_status' => 'proposal_without_comiission',
		));

		$cnt_approved_proposal = $model->getData('count', $conditionOptions, array(
			'document_status' => 'approved_proposal',
		));

		$cnt_approved_bank = $model->getData('count', $conditionOptions, array(
			'document_status' => 'approved_bank',
		));

		$cnt_rejected_bank = $model->getData('count', $conditionOptions, array(
			'document_status' => 'rejected_bank',
		));

		$cnt_credit_process = $model->getData('count', $conditionOptions, array(
			'document_status' => 'credit_process',
		));

		$cnt_rejected_credit = $model->getData('count', $conditionOptions, array(
			'document_status' => 'rejected_credit',
		));

		$cnt_approved_credit = $model->getData('count', $conditionOptions, array(
			'document_status' => 'approved_credit',
		));

		$cnt_completed = $model->getData('count', $conditionOptions, array(
			'document_status' => 'completed',
		));

		$cnt_data = array(
			'cnt_pending' => !empty($cnt_pending)?$cnt_pending:0,
			'cnt_approved_admin' => !empty($cnt_approved_admin)?$cnt_approved_admin:0,
			'cnt_rejected_admin' => !empty($cnt_rejected_admin)?$cnt_rejected_admin:0,
			'cnt_cancel' => !empty($cnt_cancel)?$cnt_cancel:0,
			'cnt_approved_proposal' => !empty($cnt_approved_proposal)?$cnt_approved_proposal:0,
			'cnt_rejected_proposal' => !empty($cnt_rejected_proposal)?$cnt_rejected_proposal:0,
			'cnt_proposal_without_comiission' => !empty($cnt_proposal_without_comiission)?$cnt_proposal_without_comiission:0,
			'cnt_approved_bank' => !empty($cnt_approved_bank)?$cnt_approved_bank:0,
			'cnt_rejected_bank' => !empty($cnt_rejected_bank)?$cnt_rejected_bank:0,
			'cnt_credit_process' => !empty($cnt_credit_process)?$cnt_credit_process:0,
			'cnt_rejected_credit' => !empty($cnt_rejected_credit)?$cnt_rejected_credit:0,
			'cnt_approved_credit' => !empty($cnt_approved_credit)?$cnt_approved_credit:0,
			'cnt_completed' => !empty($cnt_completed)?$cnt_completed:0,
		);

		return $cnt_data;
	}

}
?>