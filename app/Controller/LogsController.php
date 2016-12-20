<?php
App::uses('AppController', 'Controller');

class LogsController extends AppController {
	public $uses = array(
		'Log', 'KprBank'
	);

	public $components = array(
		'RmKpr'
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array(
			'admin_index'
		));
	}

	function admin_search ( $action ) {
		$data = $this->request->data;
		$params = array(
			'action' => $action,
			'admin' => true,
		);
		$this->RmCommon->processSorting($params, $data);
	}

	public function admin_index(){
		$options =  $this->Log->_callRefineParams($this->params);
		$this->RmCommon->_callRefineParams($this->params);

		$this->paginate = $this->Log->getData('paginate', $options);
		$values = $this->paginate('Log');

		if( !empty($values) ){
			foreach( $values as $key => $value ) {
				$user_id = $this->RmCommon->filterEmptyField($value, 'Log', 'user_id');

				$value = $this->User->getMerge( $value, $user_id);
				$values[$key] = $value;
			}
		}

		$this->set('module_title', __('Log Aktivitas'));
		$this->set('active_menu', 'laporan');

		$this->set(compact(
			'values'
		));
	}

	public function admin_histories(){		
		$options = $this->KprBank->_callRefineParams($this->params);
		$this->RmCommon->_callRefineParams($this->params);

        $this->paginate = $this->KprBank->getData('paginate', $options, array(
        	'status_kpr' => 'kpr_calculate'
        ));
		$values = $this->paginate('KprBank');

		if( !empty($values) ) {
			foreach ($values as $key => $value) {
				$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
				$bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id');

				$value = $this->KprBank->KprApplication->getMerge( $value, $kpr_bank_id, array(
					'fieldName' => 'kpr_bank_id'
				));
				$value = $this->KprBank->KprBankInstallment->getMerge( $value, $kpr_bank_id, array(
					'fieldName' => 'kpr_bank_id'
				));

				$value = $this->KprBank->Bank->getMerge($value, $bank_id);
				$values[$key] = $value;
			}
		}

    	$this->set('module_title', __('Histori Perhitungan KPR'));
		$this->set('active_menu', 'laporan');
		$this->set(compact(
			'values'
		));
	}

	public function admin_history_detail( $id ){

        $value = $this->KprBank->getData('first', array(
        	'conditions' => array(
				'KprBank.id' => $id,
			),
		), array(
			'status_kpr' => 'kpr_calculate'
		));

		if( !empty($value) ) {
			$log_kpr = true;
			$value['log_kpr'] = $log_kpr;
			$kpr_bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'id');
			$user_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'user_id');
			$agent_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'agent_id');
			$bank_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_id');
			$property_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'property_id');
			$bank_apply_category_id = $this->RmCommon->filterEmptyField($value, 'KprBank', 'bank_apply_category_id');

			$value = $this->KprBank->BankApplyCategory->getMerge($value, $bank_apply_category_id);
			$value = $this->KprBank->KprApplication->getMerge( $value, $kpr_bank_id, array(
				'fieldName' => 'kpr_bank_id'
			));



			$value = $this->KprBank->KprBankInstallment->getMerge( $value, $kpr_bank_id, array(
				'fieldName' => 'kpr_bank_id'
			));

			$value = $this->KprBank->Bank->getMerge($value, $bank_id);
			$value = $this->KprBank->User->getMerge($value, $user_id, true, 'id', 'Client');
			$value = $this->KprBank->Property->getMerge($value, $property_id);
			$value = $this->KprBank->Property->PropertyAddress->getMerge($value, $property_id, true);

			$value = $this->RmKpr->beforeViewHistoryDetail($value);
			$value = $this->KprBank->User->getMerge($value, $agent_id, true);
			$parent_id = $this->RmCommon->filterEmptyField($value, 'User', 'parent_id');
			$value = $this->User->UserCompany->getMerge($value, $parent_id);
			$this->set('active_menu', 'laporan');
			$this->set('module_title', __('Detail Histori Perhitungan KPR'));
			$this->set(compact(
				'value', 'log_kpr'
			));

			$this->render('/Kpr/admin_user_apply_detail');
		} else {
			$this->RmCommon->redirectReferer(__('Histori perhitungan KPR tidak ditemukan'));
		}
	}
}
