<?php
class Log extends AppModel {
	var $name = 'Log';
	var $validate = array(
		'admin' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'error' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'status' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
	);

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function getData( $find = 'all', $options = array() ){
		$default_options = array(
			'conditions'=> array(
				'Log.status'=> 1,
				'Log.bank_activity'  => 1,
			),
			'order'=> array(
				'Log.created' => 'DESC',
				'Log.id' => 'DESC',
			),
			'contain' => array(),
			'fields' => array(),
			'group' => array(),
		);

		if(!empty($options['conditions'])){
			$default_options['conditions'] = array_merge($default_options['conditions'], $options['conditions']);
		}
        if(!empty($options['order'])){
            $default_options['order'] = $options['order'];
        }
        if( isset($options['contain']) && empty($options['contain']) ) {
            $default_options['contain'] = false;
        } else if(!empty($options['contain'])){
            $default_options['contain'] = array_merge($default_options['contain'], $options['contain']);
        }
        if(!empty($options['fields'])){
            $default_options['fields'] = $options['fields'];
        }
        if(!empty($options['limit'])){
            $default_options['limit'] = $options['limit'];
        }
        if(!empty($options['group'])){
            $default_options['group'] = $options['group'];
        }

		if( $find == 'paginate' ) {
			if( empty($default_options['limit']) ) {
				$default_options['limit'] = Configure::read('__Site.config_admin_pagination');
			}

			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
        return $result;
	}

	function doSave ( $data ) {
		$this->create();

		if($this->save($data)) {
			return true;	
		} else {
			return false;
		}
	}

	public function _callRefineParams( $data = '', $default_options = false ) {
		$keyword = !empty($data['named']['keyword'])?$data['named']['keyword']:false;

		if( !empty($keyword) ) {
			$default_options['contain'][] = 'User';
			$default_options['conditions']['OR'] = array(
				'Log.name LIKE' => '%'.$keyword.'%',
				'CONCAT(User.first_name,\' \',User.last_name) LIKE' => '%'.$keyword.'%',
			);
		}

		return $default_options;
	}

	function logActivity( $info = NULL, $user = false, $requestHandler = false, $params = fase, $error = 0, $options = false ){
		$log = array();

		if( !empty($options) ) {
			$log = array_merge($log, $options);
		}

		if( !empty($user['User']['id']) ) {
			$log['Log']['user_id'] = $user['User']['id'];
		}

		if( !empty($user['User']['email']) ) {
			$info = sprintf('( %s ) %s', $user['User']['email'], $info);
		}
		
		$log['Log']['name'] = $info;
		$log['Log']['model'] = $params['controller'];
		$log['Log']['action'] = $params['action'];

		if( !empty($requestHandler) ) {
			$ip_address = $requestHandler->getClientIP();
			$log['Log']['ip'] = $ip_address;

			$log['Log']['user_agent'] = env('HTTP_USER_AGENT');
			
			if( !empty($log['Log']['user_agent']) ) {
				// $user_agents = get_browser($log['Log']['user_agent'], true);
				$log['Log']['browser'] = !empty($user_agents['browser'])?implode(' ', array($user_agents['browser'], $user_agents['version'])):'';
				$log['Log']['os'] = !empty($user_agents['platform'])?$user_agents['platform']:'';
			} else {
				$user_agents = '';
				$log['Log']['browser'] = '';
				$log['Log']['os'] = '';
			}
			$log['Log']['from'] = $requestHandler->getReferer();
		}

		$log['Log']['data'] = serialize( $params['data'] );
		$log['Log']['named'] = serialize( $params['named'] );
		$log['Log']['admin'] = !empty($params['admin'])?1:0;
		$log['Log']['error'] = $error;

		$admin_id = Configure::read('Auth.Admin.id');
		if( !empty($admin_id) ) {
			$log['Log']['admin_id'] = $admin_id;
		}
		
		$this->create();
		if($this->save($log)) {
			return true;	
		} else {
			return false;
		}
	}
}
?>