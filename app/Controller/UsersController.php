<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public $helpers = array(
		'FileUpload.UploadForm'
	);

	public $components = array(
		'RmImage'
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array(
			'admin_login', 'admin_forgotpassword',
			'admin_logout', 'admin_password_reset'
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

	public function admin_login() {
	    if ($this->request->is('post')) {
	    	$data = $this->request->data;
	    	$this->User->set($data);		
			if ($this->Auth->login()) {
    			$this->redirect(array(
    				'controller' => 'users',
    				'action' => 'account',
    				'admin' => true,
				));	
	        } 
	    }
    	$this->layout = 'login';
	}

	public function admin_forgotpassword(){
		
        if ( !empty($this->request->data) ) {

			$data = $this->request->data;
			if( !empty($data) ) {
				$data['User']['forgot_email'] = !empty($data['User']['forgot_email']) ? trim($data['User']['forgot_email']) : '';
				$user = $this->User->getData('first', array(
					'conditions' => array(
						'User.email' => $data['User']['forgot_email'],
					),
				));
				if( !empty($user) ) {
					$data['User']['reset_code'] = $this->RmUser->_generateCode('reset');
				}
				$result = $this->User->doForgotPassword( $data, $user, $this->RequestHandler->getClientIP() );
			}

			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'users',
				'action' => 'forgotpassword',
				'admin' => true,
			));
		}
    	$this->layout = 'login';
    }

    public function admin_logout() {
	    return $this->redirect($this->Auth->logout());
	}

    public function admin_password_reset( $reset_code = false ) {
		$this->loadModel('PasswordReset');

		$password_reset = $this->PasswordReset->getData('first', array(
			'conditions' => array(
				'PasswordReset.reset_code' => $reset_code,
			),
		));

		$loginRedirect = array(
			'controller' => 'users',
			'action' => 'login',
			'admin' => true,
		);

		if( !empty($password_reset) ) {
			$check_expired = $this->RmUser->_checkExpiredResetPassword($password_reset);

			if( $check_expired ) {

				$data = $this->request->data;
				$data = $this->RmUser->_callDataRegister($data);
				$id = $password_reset['PasswordReset']['id'];
				$user_id = $password_reset['PasswordReset']['user_id'];
				$user = $this->User->getData('first', array(
					'conditions' => array(
						'User.id' => $user_id
					),
				));
				$result = $this->User->doResetPassword( $data, $user_id, $id, $user );
			} else {
				$result = array(
					'msg' => 'Kode reset sudah expired. Silakan lakukan lupa kata sandi untuk pengiriman kode kembali.',
					'status' => 'error',
				);
			}
		} else {
			$this->RmCommon->redirectReferer(__('Kode reset tidak valid. Kode ini mungkin telah digunakan atau sudah tidak berlaku.'), 'error', $loginRedirect);
		}

		$this->RmCommon->setProcessParams($result, $loginRedirect);
		$this->layout = 'login';
	}

	public function admin_account() {
    	// $conditionsKpr = array(
    	// 	'conditions' => array(),
    	// 	'limit' => 6,
    	// );

		// if( !$this->RmCommon->_isAdmin() ) {	
		// 	$elements = array(
		// 		'document_status' => 'approved_admin',
		// 	);
		// } else {

		// 	if(!empty($this->bank_id)){
		// 		$conditionsKpr = array(
		// 			'conditions' => array(
		// 				'KprBank.bank_id' => $this->bank_id,
	 // 				),
		// 		);
		// 		$elements = array(
		// 			'document_status' => 'approved_admin',
		// 		);
		// 	}else{
		// 		$elements = array(
		// 			'document_status' => 'pending',
		// 		);
		// 	}
		// }
		// $applications = $this->User->KprBank->getData('all', $conditionsKpr, $elements);

		// if(!empty($applications)){
		// 	$applications = $this->User->KprBank->getDataList($applications, array(
		// 		'contain' => array(
		// 			'Property',
		// 			'KprBankInstallment',
		// 		),
		// 	));
		// }

		// $cnt_data = $this->User->KprBank->getCntStatus($conditionsKpr, $this->RmCommon->_isAdmin());
		// $chartApplications = $this->User->KprBank->_callChartApplications();
		// // $chartCommission = $this->User->KprBank->_callChartApplications('commission');
		// // debug($chartCommission);die();
		// $this->RmCommon->_layout_file(array(
		// 	'gchart',
		// ));

		$this->set(array(
			'module_title' => __('Dashboard'),
			// 'cnt_data' => $cnt_data,
			// 'chartCommission' => $chartCommission,
			// 'chartApplications' => $chartApplications,
			// 'applications' => $applications,
			// 'account_elements' => $elements, 
		));
		
    }

	public function admin_edit ( $id = false ){
		$logged_group = Configure::read('User.group_id');

		if($logged_group == 20){
			$id = !empty($id) ? $id : $this->user_id;
		}else{
			$id = $this->id;
		}

		$user = $this->User->getData('first', array(
			'conditions' => array(
				'User.id' => $id,
			),
		));

		if( !empty($user) ) {

			$user = $this->User->UserProfile->getMerge( $user, $id);
			$result = $this->User->doSave( $this->request->data, $user, $id );
			$this->RmCommon->setProcessParams($result);

			$this->RmCommon->_callRequestSubarea('UserProfile');
			$this->RmCommon->_layout_file(array(
				'fileupload',
				'ckeditor',
			));

			$this->set(array(
				'module_title' => __('Biodata Diri'),
				'active_menu' => 'profil',
				'user' => $user,
				'hide_bank' => true,
			));
		} else {
			$this->RmCommon->redirectReferer(__('User tidak ditemukan'));
		}
	}

	public function admin_delete_admin( $admin_id = false ) {
		$group_id = Configure::read('User.group_id');
		$admin_list = Configure::read('__Site.Admin.List.id');

		if(in_array($group_id, $admin_list)){
			$result = $this->User->doRemoveAdmin( $admin_id );
			$this->RmCommon->setProcessParams($result);
		}else{
			$this->RmCommon->redirectReferer(__('Anda Bukan sebagai Administrator'));
		}
	}

	public function admin_delete_multiple_admin() {
		$data = $this->request->data;
		$id = $this->RmCommon->filterEmptyField($data, 'User', 'id');

    	$result = $this->User->doRemoveAdmin( $id );
		$this->RmCommon->setProcessParams($result, false, array(
			'redirectError' => true,
		));
    }

	public function admin_photo_crop( $user_id = false, $refer = 'edit' ) {
		$urlRedirect = array(
            'controller' => 'users',
            'action' => $refer,
            'admin' => true,
        );
		$save_path = Configure::read('__Site.profile_photo_folder');
		$user_id = $this->RmCommon->_callAuthUserId($user_id);

		$user = $this->User->getData('first', array(
			'conditions' => array(
				'User.id' => $user_id,
			),
		));

		if( !empty($user) ) {
			if( !empty($this->request->data) ) {
				$data = $this->request->data;

				$paramPhoto = $this->RmImage->_callDataPosition($data, 'User');
				$photoName = $this->RmImage->cropPhoto($paramPhoto, $save_path);

				$result = $this->User->doCroppedPhoto( $user_id, $data, $photoName );
				$this->RmCommon->setProcessParams($result, $urlRedirect);
				$this->RmUser->refreshAuth($user_id);
			}

    		$this->set('module_title', __('Crop Foto Profil'));
    		$this->set('active_menu', 'profil');
			$this->set(compact(
				'user', 'save_path', 'refer'
			));

		} else {
			$this->RmCommon->redirectReferer(__('User tidak ditemukan'));
		}
	}

	public function admin_change_password ( $id = false ){
		if( empty($id) ) {
			$id = $this->user_id;
		}

		$user = $this->User->getData('first', array(
			'conditions' => array(
				'User.id' => $id
			),
		));

		if( !empty($user) ) {
			$user = $this->User->customMerge($user, array(
				'group_id' => 'Group',
			));

			$data = $this->request->data;
			$data = $this->RmUser->_callDataRegister($data);
			$result = $this->User->doEditPassword( $id, $user, $data);
			$this->RmCommon->setProcessParams($result);
			$this->request->data = $this->RmCommon->_callUnset(array(
				'User' => array(
					'current_password',
					'new_password',
					'new_password_confirmation',
				),
			), $data);

    		$this->set('module_title', __('Ganti Password'));
    		$this->set('active_menu', 'profil');
			$this->set(compact(
				'user', 'id'
			));
		} else {
			$this->RmCommon->redirectReferer(__('User tidak ditemukan'));
		}
	}

	public function admin_change_email (){
		$user = $this->User->getData('first', array(
			'conditions' => array(
				'User.id' => $this->user_id,
			),
		));

		if( !empty($user) ) {
			$user = $this->User->customMerge($user, array(
				'group_id' => 'Group',
			));

			$data = $this->request->data;
			$data = $this->RmUser->_callDataRegister($data);
			$result = $this->User->doEditEmail( $this->user_id, $user, $data);
			$this->RmCommon->setProcessParams($result);
			$this->request->data = $this->RmCommon->_callUnset(array(
				'User' => array(
					'current_password',
				),
			), $data);

    		$this->set('module_title', __('Ganti Email'));
    		$this->set('active_menu', 'profil');
			$this->set(compact(
				'user'
			));
		} else {
			$this->RmCommon->redirectReferer(__('User tidak ditemukan'));
		}
	}

	function admin_notifications(){
		$module_title = $title_for_layout = __('Notifikasi');
		$this->paginate = $this->User->Notification->getData('paginate', array(
			'limit' => 10
		), array(
			'mine' => true,
		));
		
		$data = $values = $this->paginate('Notification');

		$this->set(compact(
			'values', 'module_title', 'title_for_layout', 'data'
		));
	}

	function admin_redirect_notification ( $id = false ) {
		$value = $this->User->Notification->getData('first', array(
			'conditions' => array(
				'Notification.id' => $id,
			),
		));

		if( !empty($value) ) {
            $link = $this->RmCommon->filterEmptyField($value, 'Notification', 'link');
            $url = unserialize($link);
        	$result = $this->User->Notification->doRead($id);

            if( !empty($url) && is_array($url) ) {
				$this->RmCommon->setProcessParams($result, $url, array(
					'flash' => false,
				));
            } else {
            	$this->redirect(array(
					'controller' => 'users',
					'action' => 'notifications',
					'admin' => true,
				));
            }
		} else {
			$this->redirect(array(
				'controller' => 'users',
				'action' => 'notifications',
				'admin' => true,
			));
		}
	}

	function admin_lists(){
		$default_conditons = array(
			'conditions' => array(
				'User.group_id <>' => 20,
			),
		);

		$options = $this->User->_callRefineParams($this->params, $default_conditons);
		$this->paginate = $this->User->getData('paginate', $options, array(
			'status' => 'semi-active'
		));

		$values = $this->paginate('User');
		$values = $this->User->getMergeList($values, array(
			'contain' => array(
				'Group',
			),
		));

		$this->set(array(
			'values' => $values,
			'active_menu' => 'master',
			'module_title' => 'Daftar User',
			'urlAdd' => array(
	            	'controller' => 'users',
		            'action' => 'admin_add',
		            'admin' => true,
	        	),
        	'urlEdit' => array(
        		'controller' => 'users',
        		'action' => 'admin_edit',
        		'admin' => true,
        	),
        	'searchUrl' => array(
        		'controller' => 'users',
				'action' => 'search',
				'lists',
				'admin' => true,
        	),
        	'text' => __('Hapus User'),
        	'textAdd' => __('Tambah User'),
		));
	}

	public function admin_add() {
    	$group_id = Configure::read('User.group_id');
		$admin_list = Configure::read('__Site.Admin.List.id');

		if(in_array($group_id, $admin_list)){
			$user_id = $this->user_id;
			$user = $this->User->getData('first', array(
				'conditions' => array(
					'User.id' => $user_id,
				),
			));

			if( !empty($user) ) {
				$data = $this->request->data;
				$save_path = Configure::read('__Site.profile_photo_folder');

				$data = $this->RmImage->_uploadPhoto( $data, 'User', 'photo', $save_path );
				$data = $this->RmUser->_callDataRegister($data);
				$result = $this->User->doSave( $data );
				$id = $this->RmCommon->filterEmptyField($result, 'id');

				$this->RmCommon->setProcessParams($result, array(
					'controller' => 'users',
					'action' => 'lists',
					'admin' => true,
				));

				$this->RmCommon->_callRequestSubarea(array(
					'Subarea' => 'UserProfile',
					'BankBranch' => 'User',
				));

				$groups = $this->User->Group->getData('list', array(
					'conditions' => array(
						'Group.id <>' => '20'
					),
					'fields' => array('id', 'name'),
				));

				$this->set(array(
					'module_title' => __('Tambah Admin'),
					'active_menu' => 'admin',
					'user' => $user,
					'groups' => $groups,
					'division' => TRUE,
				));

			} else {
				$this->RmCommon->redirectReferer(__('User tidak ditemukan'));
			}
		}else{
			$this->RmCommon->redirectReferer(__('Anda Bukan sebagai Administrator'));
		}
		$this->render('admin_add_admin');
	}
}
