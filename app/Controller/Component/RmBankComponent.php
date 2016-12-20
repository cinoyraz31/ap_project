<?php
class RmBankComponent extends Component {
	var $components = array(
		'RmCommon'
	);
	
	/**
	*	@param object $controller - inisialisasi class controller
	*/
	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
	}

	function _callDataGlobal ( $bank_id = false, $step = false ) {
		$dataCommission = $this->controller->Bank->BankCommissionSetting->getData('first', array(
			'conditions' => array(
				'BankCommissionSetting.bank_id' => $bank_id
			),
		));

		$dataKpr = $this->controller->Bank->BankSetting->getData('first', array(
			'conditions' => array(
				'BankSetting.bank_id' => $bank_id,
			),
		));
		$dataProduct = $this->controller->Bank->BankProduct->getData('all', array(
			'conditions' => array(
				'BankProduct.bank_id' => $bank_id,
			),
		));

		switch ($step) {

			case 'Bank':
				$this->controller->set(compact(
					'dataCommission', 'dataKpr', 'dataProduct'
				));
				break;
			case 'Commission':
				$this->controller->set(compact(
					'dataKpr', 'dataProduct'
				));
				break;
			case 'KPR':
				$this->controller->set(compact(
					'dataCommission', 'dataProduct'
				));
				break;
			case 'Product':
				$this->controller->set(compact(
					'dataCommission', 'dataKpr'
				));
				break;
		}

		if( $this->RmCommon->_isAdmin() ) {
			$this->controller->set('active_menu', 'bank');
		} else {
			$this->controller->set('active_menu', 'pengaturan');
		}
	}

	function getDataBank ($bank_id) {
		$action = $this->controller->action;
		$controller = $this->controller->params['controller'];
		$allowFunction = ($controller == 'users' && in_array($action, array( 'admin_login', 'admin_logout' )));

		if( $allowFunction || $this->RmCommon->_isAdmin() ) {
			$status = false;
		} else {
			$status = 'active';
		}

		$dataBank = $this->controller->Bank->getDataBank( $bank_id, $status );

		if( !empty($dataBank) ) {
			return $dataBank;
		} else if( !empty($this->controller->bank_id) ) {
			$this->controller->redirect(array(
				'controller' => 'users',
				'action' => 'logout',
				'admin' => true,
			));
		} else {
			return false;
		}
	}

}
?>