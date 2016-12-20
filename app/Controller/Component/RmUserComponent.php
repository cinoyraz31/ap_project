<?php
class RmUserComponent extends Component {
	var $components = array('Session', 'Auth', 'RmCommon'); 

	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
		$this->User = ClassRegistry::init('User');
	}

	/**
	*
	*	membuat random karakter
	*	
	*	@param int $default : jumlah karakter yang ingin di random
	*	@param string $variable : value karakter
	*	@param int $modRndm : modulus
	*	@return string $pass
	*/
	function createRandomNumber( $default= 4, $variable = 'bcdfghjklmnprstvwxyz', $modRndm = 20 ) {
        $chars = $variable;
        srand((double)microtime()*1000000);
        $pass = array() ;

        $i = 1;
        while ($i != $default) {
            $num = rand() % $modRndm;
            $tmp = substr($chars, $num, 1);
            $pass[] = $tmp;
            $i++;
        }
        $pass[] = rand(1,9);

        return $pass;
    }

    /**
	*
	*	random array
	*	
	*	@param array $arr : data array
	*	@param int $num : jumlah shuffle
	*	@return array $hasil
	*/
    function array_random($arr, $num = 1) {
	    shuffle($arr);
	    
	    $r = array();
	    for ($i = 0; $i < $num; $i++) {
	        $r[] = $arr[$i];
	    }
	    return $num == 1 ? $r[0] : $r;
	}

	/**
	*
	*	generate activation code
	*	
	*	@return string code
	*/
	function _generateCode( $type = 'activation', $string = false ) {
		switch ($type) {
			case 'reset':
				$result = md5(time().Configure::read('Security.salt').String::uuid());
				break;

			case 'username':
				$stringArr = explode('@', $string);
				$result = !empty($stringArr[0])?$stringArr[0]:false;
				break;

			case 'user_code':
				$new_code = '';
				$flag = true;

				while ($flag) {
					$new_code = $this->createRandomNumber();
					$rand_code = $this->array_random($new_code, count($new_code));
					$str_code = strtoupper(implode('', $rand_code));
					$check_user = $this->User->getdata('count', array(
						'conditions'=> array(
							'User.code'=> $str_code,
						),
					), array(
						'status' => false,
					));
					
					if( empty($check_user) ) {
						$flag = false;
					}
				}

				$result = $str_code;
				break;
			
			default:
				$result = md5(date('mdY').String::uuid());
				break;
		}

		return $result;
	}

	function _checkExpiredResetPassword ( $data ) {
		$reminder_time = !empty($data['PasswordReset']['reminder_time'])?$data['PasswordReset']['reminder_time']:0;
		$expired_time = !empty($data['PasswordReset']['expired_time']) ? $data['PasswordReset']['expired_time']:8640;
		$expired = time() - $expired_time;

		if ($reminder_time > $expired) {
			return true;
		} else {
			return false;
		}
	}

	function _callDataRegister ( $data ) {
		if( !empty($data['User']['password']) ) {
			$data['User']['auth_password'] = $this->Auth->password($data['User']['password']);
			$data['User']['code'] = $this->_generateCode('user_code');

			$data['UserConfig']['activation_code'] = $this->_generateCode();
		} else if( isset($data['User']['current_password']) ) {
			if( !empty($data['User']['current_password']) ) {
				$data['User']['current_password'] = $this->Auth->password($data['User']['current_password']);
			}
			if( !empty($data['User']['new_password']) ) {
				$data['User']['new_password_ori'] = $data['User']['new_password'];
				$data['User']['new_password'] = $this->Auth->password($data['User']['new_password']);
			}
			if( !empty($data['User']['new_password_confirmation']) ) {
				$data['User']['new_password_confirmation'] = $this->Auth->password($data['User']['new_password_confirmation']);
			}
		} else if( isset($data['User']['new_password']) ){
			if( !empty($data['User']['new_password']) ) {
				$data['User']['new_password_ori'] = $data['User']['new_password'];
				$data['User']['new_password'] = $this->Auth->password($data['User']['new_password']);
			}
			if( !empty($data['User']['new_password_confirmation']) ) {
				$data['User']['new_password_confirmation'] = $this->Auth->password($data['User']['new_password_confirmation']);
			}
		}

		return $data;
	}

	function refreshAuth ( $user_id ) {
		$user_login_id = Configure::read('User.id');

		if( $user_id == $user_login_id ) {
			$user = $this->User->getMerge(array(), $user_id);

			$this->Auth->login($user, false, true);
		}
	}

	function getAdminList($options){
		$this->controller->paginate = $this->controller->User->getData('paginate', $options, array(
			'status' => 'semi-active',
		));

		$values = $this->controller->paginate('User');

		if( !empty($values) ){
			foreach( $values as $key => $value ) {
				$user_id = $this->RmCommon->filterEmptyField($value, 'User', 'id');
				$bank_id = $this->RmCommon->filterEmptyField($value, 'User', 'bank_id');
				$bank_branch_id = $this->RmCommon->filterEmptyField($value, 'User', 'bank_branch_id');

				$value = $this->controller->User->UserProfile->getMerge( $value, $user_id);
				$value = $this->controller->Bank->getMerge( $value, $bank_id);
				$value = $this->controller->Bank->BankBranch->getMerge( $value, $bank_branch_id);

				$values[$key] = $value;
			}
		}
		return $values;
	}

	function UserExist($datas){
		if(!empty($datas)){
			foreach($datas AS $key => $data){
				$user = $this->RmCommon->filterEmptyField($data, 'User');

				if(!empty($user)){
					$email = $this->RmCommon->filterEmptyField($user, 'email');
					$data = $this->getDataEmail($data, $email, array(
						'KprBank' => 'agent_id',
						'Property' => 'user_id',
					));
				}
				$datas[$key] = $data;
			}
		}
		return $datas;
	}

	function getDataEmail($data, $email, $changes = array()){

		if(!empty($data) && !empty($email)){
			$value = $this->User->find('first', array(
				'conditions' => array(
					'User.email'=> $email,
				),
			));

			// $data = $this->controller->User->UserCompany->CompanyExist($data, array(
			// 	'User' => 'parent_id'
			// ));

			$dataSave['User'] = $this->RmCommon->filterEmptyField($data, 'User');
			$dataSave = $this->RmCommon->dataConverter2($dataSave, array(
				'unset' => array(
					'User' => array(
						'id',
						'UserProfile' => array(
							'id',
							'user_id',
						),
					),
				),
			));

			if(!empty($value)){
				$dataSave['User']['id'] = $this->RmCommon->filterEmptyField($value, 'User', 'id');
			}

			$dataSave['UserProfile'] = $this->RmCommon->filterEmptyField($dataSave, 'User', 'UserProfile');
			$dataSave = $this->RmCommon->dataConverter2($dataSave, array(
				'unset' => array(
					'User' => array(
						'UserProfile'
					),
				),
			));

			$result = $this->User->apiSaveAll($dataSave);
			$id = $this->RmCommon->filterEmptyField($result, 'id');
			$this->RmCommon->setProcessParams($result, false, array(
				'noRedirect' => true,
			));

			if(!empty($id)){
				if(!empty($changes)){
					foreach($changes AS $modelName => $field){
						$data[$modelName][$field] = $id;
					}
				}
			}
		}
		return $data;
	}

}
?>