<?php
class AjaxController extends AppController {

	public $components = array(
		'RmImage'
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array(
			'get_subareas', 'get_zip',
		));
		$this->autoLayout = false;
		$this->autoRender = false;
   	}

   	function get_subareas($id = null, $region_id = false, $city_id = false) {
		$title = __('Pilih Area');
		$output = $this->User->UserProfile->Subarea->getData('list', array(
			'conditions'=>array(
				'Subarea.region_id' => $region_id,
				'Subarea.city_id' => $city_id,
			), 
			'fields' => array(
				'Subarea.id', 'Subarea.name'
			),
			'order' => array(
				'Subarea.order' => 'ASC',
				'Subarea.name' => 'ASC'
			),
		));

		$this->set(compact('output', 'title'));
		$this->render('get_options');
	}

	function get_zip( $id = null ) {
		$output = $this->User->UserProfile->Subarea->getData('list', array(
			'conditions' => array(
				'Subarea.id' => $id,
			),
			'fields' => array(
				'Subarea.id', 'Subarea.zip'
			),
			'order' => array(
				'Subarea.order' => 'ASC',
				'Subarea.name' => 'ASC'
			),
		));
		$output = array_values($output);
			
		if( isset($output[0]) ) {
			$output = $output[0];
		} else {
			$output = '';
		}

		$this->set(compact('output'));
		$this->render('get_results');
	}

	public function profile_photo( $user_id = false ) {
		if( !empty($this->request->data['files']) ) {
			$info = array();
        	$userFolder = Configure::read('__Site.profile_photo_folder');
			$prefixImage = String::uuid();
			$files = $this->request->data['files'];
			$user_id = $this->RmCommon->_callAuthUserId($user_id);
			$user = $this->User->getData('first', array(
				'conditions' => array(
					'User.id' => $user_id,
				),
			), array(
				'status' => 'semi-active',
			));

			if( !empty($user) ) {
				foreach ($files as $key => $value) {
					$file_name = $this->RmCommon->filterEmptyField($value, 'name');
					$data = $this->RmImage->upload($value, $userFolder, $prefixImage);
					$photo_name = $this->RmCommon->filterEmptyField($data, 'imageName');

					$data = array_merge($data, array(
						'User' => array(
							'photo' => $photo_name,
						),
					));
					
					$file = $this->User->doSavePhoto($data, $user_id);
					$info[] = $file;
					$this->RmUser->refreshAuth($user_id);
				}

				$this->RmCommon->_saveLog( __('Mengunggah foto profil'), $user, $user_id );
			}

	  		return json_encode($info);
		} else {
			return false;
		}	
	}

	function get_bank_branches () {
		$params = $this->params;
		$title = __('Pilih Cabang');
		$bank_id = $this->RmCommon->filterEmptyField($params, 'named', 'bank_id');
		$output = $this->Bank->BankBranch->getData('list', array(
			'conditions' => array(
				'BankBranch.bank_id' => $bank_id,
			),
		));

		$this->set(compact('output', 'title'));
		$this->render('get_options');
	}

	public function admin_get_kpr( $action_type = false, $fromDate = false, $toDate = false ) {
		$chartApplications = $this->Bank->KprApplication->_callChartApplications( $action_type, $fromDate, $toDate );
		$this->set(compact(
			'chartApplications', 'action_type'
		));
		$this->render('/Elements/blocks/users/dashboards/chart');
	}

	function get_kpr_installment_payment( $property_price = false, $loan_amount = false, $credit_fix = false, $interest_rate = false ) {

		if( !empty($property_price) && !empty($loan_amount) && !empty($credit_fix) && !empty($interest_rate) ) {
			
			$property_price = $this->RmCommon->safeTagPrint($property_price);
			$loan_amount = $this->RmCommon->safeTagPrint($loan_amount);
			$credit_fix = $this->RmCommon->safeTagPrint($credit_fix);
			$interest_rate = $this->RmCommon->safeTagPrint($interest_rate);

			$total_dp =  $property_price - $loan_amount;
			$total_first_credit = $this->RmKpr->creditFix($loan_amount, $interest_rate, $credit_fix );
		} else {
			$total_first_credit = 0;
		}

		$this->set(compact(
			'total_first_credit'
		));
		$this->render('get_kpr_installment_payment');
	}
}
?>