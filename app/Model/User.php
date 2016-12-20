<?php
class User extends AppModel {
	var $name = 'User';

	public $actsAs = array('Acl' => array('type' => 'requester', 'enabled' => false));

	public function bindNode($user) {
	    return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        }
        return array('Group' => array('id' => $groupId));
    }

	var $hasOne = array(
		'UserProfile' => array(
			'className' => 'UserProfile',
			'foreignKey' => 'user_id'
		),
	);

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id'
		),
	);

	var $hasMany = array(
		'KprBank' => array(
			'className' => 'KprBank',
			'foreignKey' => 'user_id'
		),
		'LogKpr' => array(
			'className' => 'LogKpr',
			'foreignKey' => 'user_id'
		),
		'KprLead' => array(
			'className' => 'KprLead',
			'foreignKey' => 'user_id'
		),
		'Property' => array(
			'className' => 'Property',
			'foreignKey' => 'user_id'
		),
		'Notification' => array(
			'className' => 'Notification',
			'foreignKey' => 'user_id'
		),
	);

	var $validate = array(
		'photo' => array(
			'imageupload' => array(
	            'rule' => array('extension',array('jpeg','jpg','png','gif')),
	            'required' => false,
	            'allowEmpty' => false,
	            'message' => 'Mohon mengunggah foto dan berekstensi (jpeg, jpg, png, gif)'
	        ),
		),
		'bank_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Bank harap dipilih',
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Bank harap dipilih',
			),
		),
		// 'bank_branch_id' => array(
		// 	'notempty' => array(
		// 		'rule' => array('notempty'),
		// 		'message' => 'Cabang harap dipilih',
		// 	),
		// 	'numeric' => array(
		// 		'rule' => array('numeric'),
		// 		'message' => 'Cabang harap dipilih',
		// 	),
		// ),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Username harap diisi',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Username telah terdaftar',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 15),
				'message' => 'Panjang username maksimal 15 karakter',
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Panjang username minimal 5 karakter',
			),
			'validateSlug' => array(
				'rule' => array('validateSlug'),
				'message' => 'Karakter yang diijinkan hanya huruf, angka, ".", "-" dan harus diawali serta diakhiri dengan huruf atau angka'
			),
		),
		'full_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nama lengkap harap diisi',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'message' => 'Panjang nama lengkap maksimal 50 karakter',
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Panjang nama lengkap minimal 5 karakter',
			)
		),
		'gender_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Jenis Kelamin harap diisi',
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Format Jenis Kelamin harus angka',
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Email harap diisi',
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Format email salah',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Email telah terdaftar',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 128),
				'message' => 'Panjang email maksimal 128 karakter',
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Panjang email minimal 5 karakter',
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Password harap diisi',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 64),
				'message' => 'Panjang password maksimal 64 karakter',
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'message' => 'Panjang password minimal 6 karakter',
			),
		),
		'current_password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Mohon masukkan password Anda',
			),
			'checkCurrentPassword' => array(
				'rule' => array('checkCurrentPassword'),
				'message' => 'Password lama Anda salah',
			),
		),
		'password_confirmation' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Konfirmasi password harap diisi',
			),
			'notMatch' => array(
				'rule' => array('matchPasswords'),
				'message' => 'Konfirmasi password anda tidak sesuai',
			),
		),
		'new_password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				// 'on' => 'update',
				'message' => 'Password baru harap diisi',
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				// 'on' => 'update',
				'message' => 'Panjang password baru minimal 6 karakter',
			),
		),
		'new_password_confirmation' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Konfirmasi password baru harap diisi',
			),
			'matchNewPasswords' => array(
				'rule' => array('matchNewPasswords'),
				'message' => 'Konfirmasi password anda tidak sesuai',
			),
		),
		'forgot_email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Email harap diisi',
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Format Email salah',
			),
			'forgot_email' => array(
				'rule' => array('validateUserEmail'),
				'message' => 'Email yang Anda masukkan belum terdaftar atau Anda belum mengaktifkan akun ini.',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 128),
				'message' => 'Panjang email maksimal 128 karakter',
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Panjang email minimal 5 karakter',
			),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Mohon pilih tipe divisi Anda.',
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Mohon pilih tipe divisi Anda.',
			),
		),
	);

	/**
	* 	@param array $data['new_password_confirmation'] - password baru
	* 	@return boolean true or false
	*/
	function matchNewPasswords($data) {
		if($this->data['User']['new_password']) {
			if($this->data['User']['new_password'] == $data['new_password_confirmation']) {
				return true;
			}
			return false; 
		} else {
			return false;
		}
	}

	/* 
		VALIDATION FUNCTION 
	*/

	/**
	* 	@param array $data['forgot_email'] - email user
	* 	@return boolean true or false
	*/
	function validateUserEmail($data) {
		if( !empty($data) ){
			$optionUser = array(
				'conditions'=> array(
					'User.email' => $data['forgot_email']
				)
			);
			$user = $this->find('first', $optionUser);
			if(!empty($user)){
				return true;
			}
		}		
		return false;
	}

	/**
	*	@param array $data['username'] - username user
	*	@return boolean true or false
	*/
    function validateSlug($data) {
    	$data['username'] = strtolower($data['username']);

    	if( preg_match('/\s/', $data['username']) ) {
    		return false;
    	} else if( substr($data['username'], 0, 1) == '.' || substr($data['username'], 0, 1) == '-' ) {
    		return false;
    	} else if( substr($data['username'], -1) == '.' || substr($data['username'], -1) == '-' ) {
    		return false;
    	} else if (preg_match('{^_?[a-z0-9_\\.\\- ]+$}i', $data['username'])==1) {
           return true; 
        } else {
        	return false;
        }
    }

    /**
	* 	@param array $data['current_password'] - password active user
	* 	@return boolean true or false
	*/
	function checkCurrentPassword() {
		$data = $this->dataValidation;

		if( !empty($data['password']) && !empty($data['User']) ) {
			$current_password = $data['User']['password'];

			if($data['password'] == $current_password) {
				return true;
			} else {
				return false;
			}
		}
		return false; 
	}

	/**
	* 	@param array $data['password_confirmation'] - password_confirmation
	* 	@return boolean true or false
	*/
	function matchPasswords($data) {
		if($data['password_confirmation']) {
			if( $this->data['User']['password'] == $data['password_confirmation'] ) {
				return true;
			} else {
				return false; 
			}
		} else {
			return true;
		}
	}

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->virtualFields['full_name'] = sprintf('CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias);
	}

	function beforeSave( $options = array() ) {
		
		$full_name = !empty( $this->data['User']['full_name'] ) ? $this->data['User']['full_name'] : false;
		if ( $full_name ) {
			$arr_name = explode(' ', $full_name);
		
			$first_name = $arr_name[0];
			unset($arr_name[0]);
			$last_name = implode(' ', $arr_name);

			$this->data['User']['first_name'] = $first_name;
			$this->data['User']['last_name'] = $last_name;
		}
		return true;
	}
	
	function getData( $find = 'all', $options = array(), $elements = array() ) {
        $status = isset($elements['status']) ? $elements['status']:'active';
        $bank = isset($elements['bank']) ? $elements['bank']:false;
        $admin = isset($elements['admin']) ? $elements['admin']:false;

		$default_options = array(
			'conditions'=> array(),
			'contain' => array(),
            'fields'=> array(),
            'group'=> array(),
		);

        switch ($status) {
            case 'non-activation':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
					'User.active' => 1,
                	'User.status' => 0,
					'User.deleted' => 0,
            	));
                break;

            case 'deleted':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
					'User.deleted' => 1,
            	));
                break;

            case 'non-active':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
                	'User.active' => 0,
					'User.deleted' => 0,
            	));
                break;
            
            case 'active':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
                	'User.status' => 1,
                	'User.active' => 1,
					'User.deleted' => 0,
            	));
                break;
            
            case 'semi-active':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
                	'User.active' => 1,
					'User.deleted' => 0,
            	));
                break;
            case 'semi-register':
                $default_options['conditions'] = array_merge($default_options['conditions'], array(
                	'User.status' => 1,
            	));
                break;
            case 'all' :
            	$default_options['conditions'] = array();
            	break;
        }

        if( !empty($bank) ) {
            $default_options['conditions'] = array_merge($default_options['conditions'], array(
            	'User.group_id' => 6,
            	'User.bank_id' => $bank,
        	));
        }

        if( !empty($admin) ) {
            $default_options['conditions'] = array_merge($default_options['conditions'], array(
            	'User.group_id' => array(19, 20),
        	));
        }

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

		if( $find == 'conditions' && !empty($default_options['conditions']) ) {
			$result = $default_options['conditions'];
		} else if( $find == 'paginate' ) {
			if( empty($default_options['limit']) ) {
				$default_options['limit'] = Configure::read('__Site.config_admin_pagination');
			}
			
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
		
        return $result;
	}

	function apiSaveAll($data){
		$default_msg = __('melakukan %s User');

		if(!empty($data)){

			$id = $this->filterEmptyField($data, 'User', 'id');

			if(!empty($id)){
				$default_msg = sprintf($default_msg, __('mengubah'));
				$this->UserProfile->deleteAll(array(
					'UserProfile.user_id' => $id,
				));
			}else{
				$default_msg = sprintf($default_msg, __('menambah'));
			}

			if($this->saveAll($data, array('validate' => false))){
				$msg = sprintf(__('Berhasil %s'), $default_msg);
				$result = array(
					'msg' => $msg,
					'status' => 'success',
                	'id' => $this->id,
					'Log' => array(
						'activity' => $msg,
						'document_id' => $this->id,
					),
				);
			}else{
				$result = array(
					'msg' => sprintf(__('Gagal %s'), $default_msg),
					'status' => 'error',
					'data' => $data,
				);
			}
		}else{
			$result = array(
				'msg' => sprintf(__('Gagal %s'), $default_msg),
				'status' => 'error',
				'data' => $data,
			);
		}
		return $result;
	}

	function doSave( $data, $value = false, $id = false, $sendEmail = true ) {
		$result = false;
		$groupName = __('user Admin');

		if ( !empty($data) ) {
			if( !empty($id) ) {
				$this->id = $id;
				$default_options = sprintf(__('mengubah %s'), $groupName);
			} else {
				$this->create();
				$default_options = sprintf(__('menambah %s'), $groupName);
			}

			if( isset( $data['User']['username'] ) && $data['User']['username'] != $value['User']['username'] ) {
				$data['User']['username_disabled'] = 1;
			}
			
			$this->set($data);
			$this->UserProfile->set($data);

			$userValidates = $this->validates();
			$userProfileValidates = $this->UserProfile->validates();

			if ( $userValidates && $userProfileValidates ) {
				$full_name = $this->filterEmptyField($data, 'User', 'full_name');
				
				if( empty($id) ) {
					$data['User']['password_ori'] = $this->filterEmptyField($data, 'User', 'password');
					$data['User']['password'] = $this->filterEmptyField($data, 'User', 'auth_password');
					$data['User']['password_confirmation'] = $data['User']['password'];
					$data['User']['status'] = 1;
					$data['User']['active'] = 1;
				}

				$data['User']['sync'] = 0;

                if( $this->save($data) ) {
				
					$user_id = $this->id;
					$user_profile_id = !empty($value['UserProfile']['id'])?$value['UserProfile']['id']:false;
					$save = $this->UserProfile->doSave($data, $user_id, $user_profile_id);

					if ( $save ) {
						$group_id = $this->filterEmptyField($data, 'User', 'group_id');
						$data = $this->Group->getMerge($data, $group_id);
						$msg = sprintf(__('Berhasil %s %s'), $default_options, $full_name);
						$result = array(
							'msg' => $msg,
							'status' => 'success',
                        	'id' => $user_id,
							'Log' => array(
								'activity' => $msg,
								'old_data' => $value,
								'document_id' => $user_id,
							),
						);

						if( !empty($sendEmail) ) {
							$full_name = !empty($data['User']['full_name'])?$data['User']['full_name']:false;
							$email = !empty($data['User']['email'])?$data['User']['email']:false;
							
							$site_name = Configure::read('__Site.site_name');
							$subject = sprintf(__('Selamat datang di %s'), $site_name);

							$email_template = 'add_admin';

							$result['SendEmail'] = array(
	                        	'to_name' => $full_name,
	                        	'to_email' => $email,
	                        	'subject' => $subject,
	                        	'template' => $email_template,
	                        	'data' => $data,
	                        	// 'debug' => 'view',
		                    );
						}
					} else {
						$msg = sprintf(__('Gagal %s %s'), $default_options, $full_name);
						$result = array(
							'msg' => $msg,
							'status' => 'error',
							'data' => $data,
							'Log' => array(
								'activity' => $msg,
								'old_data' => $value,
								'document_id' => $id,
								'error' => 1,
							),
							'validationErrors' => $this->validationErrors,
						);
					}
				} else {
					$result = array(
						'msg' => sprintf(__('Gagal %s. Silahkan coba lagi.'), $default_options),
						'status' => 'error',
						'data' => $data,
						'validationErrors' => $this->validationErrors,
					);
				}
			} else {
				$result = array(
					'msg' => sprintf(__('Gagal %s. Silahkan coba lagi.'), $default_options),
					'status' => 'error',
					'data' => $data,
					'validationErrors' => $this->validationErrors,
				);
			}
		} else if( !empty($value) ) {
			$photoName = !empty($value['User']['photo'])?$value['User']['photo']:false;

			$value['User']['photo_hide'] = $photoName;
			$result['data'] = $value;
		}

		return $result;
	}


	// function doSaveSync($data, $principle_id = false, $switch = false, $changes = false){

	// 	if(!empty($switch)){
	// 		$user = $data;
	// 	}else{
	// 		$user['User'] = $this->filterEmptyField($data, 'Agent');
	// 		$user['UserProfile'] = $this->filterEmptyField($data, 'UserProfile');
	// 	}

 //        $result = false;

 //        if(!empty($user['User'])){

 //        	if(!empty($principle_id)){
 //        		$user['User']['parent_id'] = $principle_id;
 //        	}

 //        	$default_msg = __('%s data Agen');
 //        	$email = $this->filterEmptyField($user, 'User', 'email');
 //        	$value = $this->getData('first',array(
 //        		'conditions' => array(
 //        		 	'User.email' => $email,
 //    			),
 //    		), array(
 //    			'status' => 'status',
 //    		));

 //        	if(!empty($value)){
 //        		$id = $this->filterEmptyField($value, 'User', 'id');
 //        		$id = !empty($value['User']['id'])?$value['User']['id']:false;
 //        		$user['User']['id'] = $id;

 //        		$user = $this->callUnsetV2($user, array(
 //        			'UserProfile' => array(
 //        				'id',
 //        				'user_id',
 //        			),
 //        		));

 //        		$this->UserProfile->deleteAll(array(
 //        			'UserProfile.user_id' => $id
 //        		));

 //        		$default_msg = sprintf($default_msg, __('mengubah'));

 //        	}else{
 //        		$this->create();
 //        		$user = $this->callUnsetV2($user, array(
 //        			'User' => array(
 //        				'id',
 //        			),
 //        			'UserProfile' => array(
 //        				'id',
 //        				'user_id',
 //        			),
 //        		));
 //                $default_msg = sprintf($default_msg, __('menambah'));

 //        	}

 //        	if($this->saveAll($user, array('validate' => false))){

 //                $id = $this->id;
 //                $msg = sprintf(__('Berhasil %s'), $default_msg);
 //                $result = array(
 //                    'msg' => $msg,
 //                    'status' => 'success',
 //                    'Log' => array(
 //                        'activity' => $msg,
 //                        'old_data' => $value,
 //                        'document_id' => $id,
 //                    ),
 //                    'id' => $id
 //                );

 //            }else{
 //                $msg = sprintf(__('Gagal %s'), $default_msg);
 //                $result = array(
 //                    'msg' => sprintf(__('Gagal %s'), $default_msg),
 //                    'status' => 'error',
 //                    'data' => $user,
 //                    'Log' => array(
 //                        'activity' => $msg,
 //                        'old_data' => $value,
 //                        'document_id' => $id,
 //                        'error' => 1,
 //                        'validationErrors' => $this->validationErrors,
 //                    ),
 //                );

 //            }

 //        }

 //        if( !empty($changes) &&!empty($result['status']) && $result['status'] == 'success'){
 //        	foreach ($changes as $modelName => $fieldName) {
	// 			$data[$modelName][$fieldName] = $this->id;
	// 		}
	// 		$result = $data;
 //        }

 //        return $result;

	// }

	public function doEditEmail( $user_id, $user, $data ) {
		$result = false;
		$default_msg = __('memperbarui Email');

		if ( !empty($data) ) {
			$full_name = !empty($user['User']['full_name'])?$user['User']['full_name']:false;
			
			$this->set($data);
			$this->dataValidation = array(
				'User' => $user['User'],
				'password' => $data['User']['current_password'],
			);
			if ( $this->validates() ) {
				$this->id = $user_id;

				if( $this->save($data) ){
					$msg = sprintf(__('Berhasil %s %s'), $default_msg, $full_name);
					$result = array(
						'msg' => $msg,
						'status' => 'success',
						'RefreshAuth' => array(
							'id' => $user_id,
						),
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $user_id,
						),
					);
				} else {
					$msg = sprintf(__('Gagal %s %s'), $default_msg, $full_name);
					$result = array(
						'msg' => $msg,
						'status' => 'error',
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $user_id,
							'error' => 1,
						),
					);
				}
			} else {
				$result = array(
					'msg' => sprintf(__('Gagal %s'), $default_msg),
					'status' => 'error',
				);
			}
		}

		return $result;
	}

	public function doEditPassword( $id, $user, $data ) {
		$result = false;
		$default_msg = __('memperbarui password');

		if ( !empty($data) ) {
			$email = !empty($user['User']['email'])?$user['User']['email']:false;
			$full_name = !empty($user['User']['full_name'])?$user['User']['full_name']:false;
			$new_password = !empty($data['User']['new_password'])?$data['User']['new_password']:false;
			$new_password_ori = !empty($data['User']['new_password_ori'])?$data['User']['new_password_ori']:false;
			$current_password = !empty($data['User']['current_password'])?$data['User']['current_password']:false;

			$this->set($data);
			$this->dataValidation = array(
				'User' => $user['User'],
				'password' => $current_password,
			);
			
			if ( $this->validates() && !empty($new_password) ) {
				$this->id = $id;
				$this->set('password', $new_password);

				if( $this->save() ){
					$msg = sprintf(__('Berhasil %s %s'), $default_msg, $full_name);
					$dataEmail = array_merge_recursive($user, $data);

					$result = array(
						'msg' => sprintf(__('Sukses %s'), $default_msg),
						'status' => 'success',
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $id,
						),
						'SendEmail' => array(
                        	'to_name' => $full_name,
                        	'to_email' => $email,
                        	'subject' => __('Perubahan password Akun'),
                        	'template' => 'change_password',
                        	'data' => $dataEmail,
	                    ),
					);
				} else {
					$msg = sprintf(__('Gagal %s %s'), $default_msg, $full_name);
					$result = array(
						'msg' => $msg,
						'status' => 'error',
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $id,
							'error' => 1,
						),
					);
				}
			} else {
				$result = array(
					'msg' => sprintf(__('Gagal %s'), $default_msg),
					'status' => 'error',
				);
			}
		} else {
			$result['data'] = $user;
		}

		return $result;
	}

	function getMerge( $data, $user_id = false, $with_contain = false, $fieldName = 'id' , $modelName = 'User') {
		
		if( empty($data['User']) ) {

			$user = $this->getData('first', array(
				'conditions' => array(
					$fieldName => $user_id
				),
			), array(
				'status' => 'all',
			));

			if( !empty($user) ) {
				$user_temp = $user;
				unset($user);
				$user[$modelName] = $user_temp['User'];
				$data = array_merge($data, $user);

				if( !empty($with_contain) ) {

					$userProfile = $this->UserProfile->getData('first',array(
						'conditions' => array(
								'UserProfile.user_id' =>  $user_id
							)
					));


					if( !empty($userProfile) ){
						$region_id = $this->filterEmptyField($userProfile, 'UserProfile', 'region_id');
						$city_id = $this->filterEmptyField($userProfile, 'UserProfile', 'city_id');
						$subarea_id = $this->filterEmptyField($userProfile, 'UserProfile', 'subarea_id');

						$userProfile['UserProfile'] = $this->UserProfile->Region->getMerge($userProfile['UserProfile'], $region_id);
						$userProfile['UserProfile'] = $this->UserProfile->City->getMerge($userProfile['UserProfile'], $city_id);
						$userProfile['UserProfile'] = $this->UserProfile->Subarea->getMerge($userProfile['UserProfile'], $subarea_id);
						
						$data = array_merge($data, $userProfile);
					}
				}
			}
		}

		return $data;
	}

	function doRemoveAdmin( $ids ) {
		$result = false;
		$default_msg = __('menghapus user');

		$values = $this->getData('all', array(
        	'conditions' => array(
				'User.id' => $ids,
				'User.group_id <>' => '20',
			),
		), array(
			'status' => 'semi-active',
		));

		if ( !empty($values) ) {
			$full_name = Set::extract('/User/full_name', $values);
			$full_name = implode(', ', $full_name);

			$flag = $this->updateAll(array(
				'User.deleted' => 1,
			), array(
				'User.id' => $ids,
			));

			if( $flag ) {
				$msg = sprintf(__('Berhasil %s %s'), $default_msg, $full_name);
				$result = array(
					'msg' => $msg,
					'status' => 'success',
					'Log' => array(
						'activity' => $msg,
						'old_data' => $values,
					),
				);
			} else {
				$msg = sprintf(__('Gagal %s %s'), $default_msg, $full_name);
				$result = array(
					'msg' => $msg,
					'status' => 'error',
					'Log' => array(
						'activity' => $msg,
						'old_data' => $values,
						'error' => 1,
					),
				);
			}
		} else {
			$result = array(
				'msg' => __('Gagal menghapus user. Data tidak ditemukan'),
				'status' => 'error',
			);
		}

		return $result;
	}

	function doSavePhoto( $data, $user_id ) {
        $result = new stdClass();

        if ( !empty($data) ) {
        	if( !empty($data['error']) ){
	  			$result->error = 1;
	  			$result->message = $data['message'];
	  		}else{
	            $this->id = $user_id;
	            $this->set($data);

	            if( !$this->save() ) {
					$result->error = 1;
	  				$result->message = __('Gagal menyimpan foto profil');
	            } else {
	            	if(!empty($data['imagePath'])){
			  			$result->thumbnail_url = $data['imagePath'];
		  			}

		  			if(!empty($data['name'])){
			  			$result->name = $data['name'];
		  			}

	            	$result->url = sprintf('/admin/users/photo_crop/%s/', $user_id);
	  			}
	  		}
        }

        return $result;
    }

    function doCroppedPhoto( $user_id, $data, $photoName ) {
		$result = false;
		$default_msg = __('crop foto profil Anda');

		if ( !empty($data) ) {
			
			if( !empty($photoName) ) {
				
				$this->id = $user_id;
				$this->set('photo', $photoName);
				
				if($this->save()) {
					$id = $this->id;
					$msg = sprintf(__('Berhasil %s'), $default_msg);
					$result = array(
						'msg' => $msg,
						'status' => 'success',
						'Log' => array(
							'activity' => $msg,
							'document_id' => $user_id,
						),
					);
				} else {
					$msg = sprintf(__('Gagal %s'), $default_msg);
					$result = array(
						'msg' => $msg,
						'status' => 'error',
						'Log' => array(
							'activity' => $msg,
							'document_id' => $user_id,
							'error' => 1,
						),
					);
				}
			} else {
				$result = array(
					'msg' => __('Gagal memperbarui foto profil Anda. Silahkan coba lagi'),
					'status' => 'error',
				);
			}
		}

		return $result;
	}

	function doUpdateLastLogin ( $id ) {
		$lastLogin = date('Y-m-d H:i:s');

		$this->id = $id;
		$this->set(array(
			'last_login' => $lastLogin,
		));

		return $this->save();
	}

	function customMerge ( $data, $dataMerge ) {
		if( !empty($dataMerge) ) {
            foreach ($dataMerge as $fieldName => $value) {
            	$id = !empty($data['User'][$fieldName])?$data['User'][$fieldName]:false;
                $data = $this->$value->getMerge( $data, $id );
            }
        }

		return $data;
	}

	public function _callRefineParams( $data = '', $default_options = false ) {
		$keyword = !empty($data['named']['keyword'])?urldecode($data['named']['keyword']):false;

		if( !empty($keyword) ) {

			$default_options['contain'][] = 'UserProfile';
			$default_options['conditions']['OR'] = array(
				'CONCAT(User.first_name,\' \',User.last_name) LIKE' => '%'.$keyword.'%',
				'User.email LIKE' => '%'.$keyword.'%',
				'UserProfile.no_hp LIKE' => '%'.$keyword.'%',
			);
		}
		
		return $default_options;
	}

	function doForgotPassword( $data, $user, $ip ){
		$result = false;

		if ( !empty($data) ) {

			$this->set($data);
			if ( $this->validates($data) ) {

				$this->PasswordReset = ClassRegistry::init('PasswordReset');
				
				$email = $data['User']['forgot_email'];
				$user_id = $user['User']['id'];
				$full_name = $user['User']['full_name'];
				$reset_code = $data['User']['reset_code'];
				
				$data['PasswordReset'] = array(
					'email' => $email,
					'user_id' => $user_id,
					'reset_code' => $reset_code,
					'reminder_time' => time(),
					'expired_time' => 8640,
				);

				$this->PasswordReset->create();
				$this->PasswordReset->set($data);

				if( $this->PasswordReset->save() ) {
					$inserted_id = $this->PasswordReset->id;
					$this->PasswordReset->updateAll(
					    array('PasswordReset.status' => 1),
					    array(
					    	'PasswordReset.user_id' => $user_id,
					    	'PasswordReset.id <' => $inserted_id,
					    )
					);

					$result = array(
                        'msg' => __('Kami telah mengirimkan Anda link untuk mengubah password, periksa SPAM apabila link tidak diketemukan dalam kotak masuk email Anda.'),
                        'status' => 'success',
                        'SendEmail' => array(
                        	'to_name' => $full_name,
                        	'to_email' => $email,
                        	'subject' => __('Permintaan reset password'),
                        	'template' => 'forgot_password',
                        	'data' => array(
			            		'ip' => $ip,
								'reset_code' => $reset_code,
			            	),
                        	'debug' => 'view',
                    	),
                    );
				} else {
					$result = array(
	                    'msg' => sprintf(__('Gagal mengirimkan link untuk mengubah password. Silahkan hubungi Admin Kami untuk informasi lebih lanjut.')),
	                    'status' => 'error',
	                );
				}

			} else {
				$result = array(
					'msg' => sprintf(__('Gagal melakukan reset password. Silahkan coba lagi.')),
					'status' => 'error',
				);
			}
		}

		return $result;
	}

	public function doResetPassword( $data, $user_id, $id, $user ) {
		$result = false;
		$default_msg = __('memperbarui password');

		if ( !empty($data) ) {

			$this->set($data);
			if ( $this->validates() ) {

				$this->id = $user_id;
				$this->set('password', $data['User']['new_password']);

				if( $this->save() ){

					$this->PasswordReset = ClassRegistry::init('PasswordReset');
					$this->PasswordReset->id = $id;
					$this->PasswordReset->set('status', 1);
					$this->PasswordReset->save();

					$msg = sprintf(__('Berhasil %s user dengan id #%s'), $default_msg, $user_id);
					$result = array(
						'msg' => sprintf(__('Sukses %s'), $default_msg),
						'status' => 'success',
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $id,
						),
					);
				} else {
					$msg = sprintf(__('Gagal %s user dengan id #%s'), $default_msg, $user_id);
					$result = array(
						'msg' => sprintf(__('Gagal %s.'), $default_msg),
						'status' => 'error',
						'Log' => array(
							'activity' => $msg,
							'old_data' => $user,
							'document_id' => $id,
							'error' => 1,
						),
					);
				}
			} else {
				$result = array(
					'msg' => sprintf(__('Gagal %s.'), $default_msg),
					'status' => 'error',
				);
			}
		}

		return $result;
	}

	function data_sync($data){
    	$email = $this->filterEmptyField($data, 'User', 'email');
    	$cek = $this->find('first', array(
    		'conditions' => array(
    			'User.email' => $email,
    		)
    	));
    	$user_id = $this->filterEmptyField($cek, 'User', 'id');
    	$data = $this->callUnset($data, array(
    		'User' => array(
    			'id',
			),
		));

    	$dataSave['User'] = $this->filterEmptyField($data, 'User');
    	$dataSave['User']['id'] = $user_id;

    	$dataSave = $this->callUnset($dataSave, array(
    		'User' => array(
    			'bank_id',
    			'bank_branch_id',
				'UserProfile',
				'Parent',
			),
		));
    	
    	if($this->save($dataSave, false)){
    		$id = $this->id;

    		if( !empty($data['User']['UserProfile']) ){
    			if( !empty($user_id) ) {
	    			$cek = $this->UserProfile->find('first', array(
			    		'conditions' => array(
			    			'UserProfile.user_id' => $user_id,
			    		)
			    	));
    				$user_profile_id = $this->filterEmptyField($cek, 'UserProfile', 'id');
	    		} else {
	    			$user_profile_id = false;
	    		}

	    		$dataSave = array(
	    			'UserProfile' => $this->filterEmptyField($data, 'User', 'UserProfile'),
    			);
		    	$dataSave['UserProfile']['id'] = $user_profile_id;
                $dataSave['UserProfile']['user_id'] = $id;
		    	$dataSave = $this->callUnset($dataSave, array(
		    		'UserProfile' => array(
		    			'Country',
		    			'Region',
						'City',
						'Subarea',
					),
				));

		    	$this->UserProfile->save($dataSave, false);
    		}
    	}
    }

	function getAdmins ( $bank_id = false , $group_id = 6) {

		if($group_id == 6){
			$values = $this->getData('list', array(
				'conditions' => array(
					'User.bank_id' => $bank_id,
					'User.group_id' => $group_id,
				),
				'fields' => array(
					'User.id', 'User.id',
				),
			), array(
				'status' => 'semi-active',
			));
		}else{
			$values = $this->getData('list', array(
				'conditions' => array(
					'User.group_id' => $group_id,
				),
				'fields' => array(
					'User.id', 'User.id',
				),
			), array(
				'status' => 'semi-active',
			));
		}


		return $values;
	}

}
?>