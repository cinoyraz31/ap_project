<?php
class RmCommonComponent extends Component {
	var $components = array(
		'Email', 'Session', 'RequestHandler',
		'RmUser'
	);
	
	/**
	*	@param object $controller - inisialisasi class controller
	*/
	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
	}
	
	/**
	*
	*	mendapatkan nilai tanggal sekarang
	*
	*	@return string tanggal
	*/
	function currentDate( $formatDate = 'Y-m-d H:i:s' ){
		return date($formatDate);
	}

	/**
	*
	*	function untuk mengirim email
	*
	*	@param string $name - nama user
	*	@param string $email - email user
	*	@param string $template - template content email 
	*	@param string $subject - subjek email email 
	*	@param string $params
	*		- string name : nama user
	*		- string id : id dari kontent yang dikirim
	*		- boolen browser : untuk menandakan apakah bisa di buka di selain email provider apa tidak
	*		- boolen MailChimp : true untuk melihat full content di dalam sebuah variable email render, untuk keperluan mailchimp
	*		- string url : url tambahan untuk keperluan parameter email
	*	@param boolean $debug
	*	@return boolean true or false
	*/

	/**
	*
	*	set nilai pesan setelah melakukan suatu action pada controller
	*
	*	@param string $message - info dari pesan
	*	@param string $type 
	*		- string success - menyatakan pesan berhasil
	*		- string error - menyatakan pesan gagal
	*		- string info - menyatakan info dari suatu action
	*	@param array $params - parameter pendukung untuk pesan
	*	@param boolean $ajaxMsg - true jika di jalankan ketika event ajax, false jika tidak
	*	@param boolean $flash 
	*/
	function setCustomFlash($message, $type = 'success', $params=array(), $ajaxMsg = false, $flash = true) {
		$flashType = 'flash_'.$type;

		if( !$ajaxMsg ) {
			if( $flash ){
				$this->Session->setFlash($message, $flashType, $params, $type);
			}

			if( $type == 'success' ) {
				$status = 1;
			} else if( $type == 'error' ) {
				$status = 0;
			} else {
				$status = $type;
			}

			$this->controller->set('msg', $message);
			$this->controller->set('status', $status);
		}
	}

	function validateEmail($options = false) {
		if( !empty($options['SendEmail']) ) {
			$dataEmail = $this->filterEmptyField($options, 'SendEmail');
			$flagSendMail = true;

			if( empty($dataEmail[0]) ) {
				$dataEmails[0] = $dataEmail;
			} else {
				$dataEmails = $dataEmail;
			}
			foreach( $dataEmails as $value ) {
				$bank_id = $this->filterEmptyField($value, 'bank_id');
				$admin = $this->filterEmptyField($value, 'admin');
				$to_name = $this->filterEmptyField($value, 'to_name');
				$to_email = $this->filterEmptyField($value, 'to_email');
				$subject = $this->filterEmptyField($value, 'subject');
				$temp = $this->filterEmptyField($value, 'template');
				$data = $this->filterEmptyField($value, 'data');
				$debug = $this->filterEmptyField($value, 'debug');

				if( !empty($bank_id) ) {
					$bank = $this->controller->User->Bank->getMerge(array(), $bank_id);
					$bank_data = $this->filterEmptyField($bank, 'Bank');
					$bank_name = $this->filterEmptyField($bank_data, 'name');
					$sub_domain = $this->filterEmptyField($bank_data, 'sub_domain');

					$bank_emails = $this->controller->User->getData('list', array(
						'fields' => array(
							'User.id', 'User.email',
						),
					), array(
						'bank' => $bank_id,
					));
					
					$data['BankDomain'] = $bank_data;

					if( empty($to_email) ) {
						$to_email = $bank_emails;
						$to_name = $bank_name;
					}
				}

				// DEVELOPMENT
				if(!empty($admin)){
					$admin_name = Configure::read('__Site._superadmin');
					$admin_emails = Configure::read('__Site.send_email_from');

					if( empty($to_email) ) {
						$to_email = $admin_emails;
						$to_name = $admin_name;	
					}
				}

				// LIVE 
				// if(!empty($admin)){
				// 	$admin_name = Configure::read('__Site.site_name');
				// 	$admin_emails = $this->controller->User->getData('list', array(
				// 		'fields' => array(
				// 			'User.id', 'User.email',
				// 		),
				// 	), array(
				// 		'admin' => $admin,
				// 	));

				// 	if( empty($to_email) ) {
				// 		$to_email = $admin_emails;
				// 		$to_name = $admin_name;	
				// 	}
				// }



				if( !empty($debug) ) {
					$data['debug'] = $debug;
				}

				if( !$this->sendEmail(
					$to_name,
					$to_email,
					$temp,
					$subject,
					$data
				) ) {
					$flagSendMail = false;
				}
			}

			return $flagSendMail;
		} else {
			return true;
		}
	}

	function sendEmail( $name = null, $email = null, $template='general', $subject=null, $params=null, $debug=false) {
		if( !empty($email) ) {
			$email_tipe = 'outside';
			$_layout = !empty($params['layout'])?$params['layout']:'layout_btn';

			$params['_email'] = $email;
			$params['name'] = $name;
			
			if(isset($params['MailChimp']) && $params['MailChimp']){
				return $this->renderViewToVariable($template, $params);
			}else{
				$this->Email->reset();
		        $this->Email->to = $email;
				$this->Email->from = Configure::read('__Site.send_email_from');
				$this->Email->subject = $subject;
				$this->Email->template = $template;
				$this->Email->sendAs = 'both';

				if(isset($params['replyTo']) && $params['replyTo']) {
					$this->Email->replyTo = $params['replyTo'];
				}

				if($debug) {
					$this->Email->delivery = 'debug';
				} else {
					$this->Email->delivery = 'mail';
				}

				if( !empty($_layout) ){
					$this->Email->layout = $_layout;
				}

				$this->controller->set(compact(
					'name', 'params', 'template', 
					'email_tipe'
				));

		        if ($this->Email->send()) {
					return true;
		        } else {
					return false;
		        }
				exit();
			}
		} else {
			return false;
		}
	}

	function _set_global_variable ($site_name) {
		$name_site 		= $site_name;
		return array(
			'meta' => array(
				'title_for_layout' => $name_site,
				'description_for_layout' => $name_site,
				'keywords_for_layout' => $name_site,
			),
			'gender_options' => array(
				'1' => __('Laki-laki'),
				'2' => __('Perempuan'),
			),
			'status_marital' => array(
				'single' => __('Belum Menikah'),
				'marital' => __('Menikah'),
			),
			// 'favicon' => array(
			// 	'logo' => $logo
 		// 	)
		);
	}

	function _setConfigVariable () {
		// Image Path
		Configure::write('__Site.upload_path', APP.'Uploads');
		Configure::write('__Site.thumbnail_view_path', APP.'webroot'.DS.'img'.DS.'view');
		Configure::write('__Site.cache_view_path', '/img/view');

		// Options Image
		Configure::write('__Site.allowed_ext', array('jpg', 'jpeg', 'png', 'gif'));
		Configure::write('__Site.max_image_size', 10242880);
		Configure::write('__Site.max_image_width', 900);
		Configure::write('__Site.max_image_height', 600);

		// Path Folder
		Configure::write('__Site.property_photo_folder', 'properties');
		Configure::write('__Site.profile_photo_folder', 'users');
		Configure::write('__Site.logo_photo_folder', 'logos');	
		Configure::write('__Site.document_folder', 'documents');
		Configure::write('__Site.general_logo_photo', 'general');
		Configure::write('__Site.crm_folder', 'crms');
		Configure::write('__Site.fullsize', 'fullsize');

		Configure::write('__Site.kpr_credit_fix', 20);
		Configure::write('__Site.bunga_kpr', 20);
		Configure::write('__Site.config_currency_code', 'IDR ');
		Configure::write('__Site.config_currency_symbol', 'Rp. ');

		Configure::write('__Site.config_pagination', 10);
		Configure::write('__Site.config_admin_pagination', 20);

		Configure::write('__Site.Admin.List.id', array( 20 ));
		Configure::write('__Site.official_website', 'http://v4.pasiris.com');
		Configure::write('__Site.kpr_site_name', 'http://kpr.pasiris.com');

		Configure::write('__Site.company_profile', array(
			'name_premiere' => 'Primesystem.id',
			'name' => 'PT. NAGA LANGIT',
			'address' => 'Wisma Slipi, Jl. S. Parman Kav. 12, Suite #318',
			'phone' => '(021) 5332555',
			'phone2' => '0822 505 77777',
			// 'email' => 'support@primesystem.id', live
			'email' => 'supportprimesystem@yopmail.com',
			'link' => 'http://www.primesystem.id',
			'bank_account' => array(
				'no_account' => '467 3977 777',
				'bank_name' => 'BCA', 
				'npwp' => '03.093.658.7-031.000',
			),
		));

		Configure::write('__Site.rumahku', 'Rumahku.com');

		Configure::write('__Selectbox.price', array(
			'nominal' => __('Nominal'),
			'percent' => __('Persentase'),
		));

		Configure::write('__Selectbox.paramsPrice', array(
			'price' => __('Harga properti'),
			'loan_price' => __('Plafon'),
		));

		// LIMIT CRONTAB
		Configure::write('__Site.config_limit_crontab', 15);

		// rumahku Info
		Configure::write('__Site.site_wa', '0822 505 77777');
		Configure::write('__Site.site_phone', '(021) 5332555');
	}

	function _layout ( $params = false, $layout = 'default' ) {
		if ( !empty($params) && $params == 'admin' ) {
            $layout = 'admin';
        }

        return $layout;
	}

	function getMessageToAdmin ( $msg ) {
		$msg = rtrim($msg, '.');

		return sprintf(__('%s. Silahkan hubungi Admin Kami untuk informasi lebih lanjut.'), $msg);
	}

	/**
	*
	*	set nilai validasi dari suatu form
	*
	*	@param boolean $errors
	*/
	function setValidationError( $errors = false ) {
		$tempErrors = array();
		if( !empty($errors) ) {
			$validationErrors = array();
			foreach ($errors as $key => $error) {
				$validationErrors[$key] = $error;

				foreach ($error as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$tempErrors[] = $value2;
					}
				}
			}
			$this->controller->set('validationErrors', $validationErrors);
		}

		return $tempErrors;
	}

	/**
	*
	*	function untuk meminta variabel yang diset secara global
	*
	*	@param string $action
	*		- array alert_type_options : nilai periode email alert
	*		- array recommendation-option : nilai dari recommendation
	*		- array client_types : tipe user
	*		- array view_property : nilai dari pandangan properti
	*		- array property_period : nilai dari periode properti disewakan
	*		- array lot_options : nilai satuan dimensi tanah
	*		- array alert_type_options : nilai periode email alert
	*		- array room_options : nilai dari pilian ruangan
	*		- array range_price_sell_m : nilai range harga properti di jual
	*		- array range_price_rent_m : nilai range harga properti disewakan
	*		- array range_price_rent : nilai range harga properti disewakan
	*		- array range_price_sell : nilai range harga properti di jual
	*		- array size_ranges : ukuran tanah
	*		- array sales_developer_email : email sales rumahku
	*		- array size_options_refine : ukuran tanah di pencarian
	*		- array size_options_refine : ukuran tanah di pencarian
	*		- array recommendation-option : pilihan rekomendasi
	*		- array view_property : pilihan hadap
	*		- array specialist_option : pilihan spesialis
	*		- array languages_option : pilihan bahasa
	*		- array property_period_option : pilihan satuan waktu properti disewakan
	*		- array color_banner_option : pilihan warna klaim banner
	*		- array agent_certificates_option : pilihan certificate
	*/
	function getGlobalVariable ( $action ) {
    	switch ($action) {
    		case 'client_types':
    			return array(
					'Pembeli Properti',
					'Penyewa',
					'Investor',
					'Perusahaan'
				);
    			break;

			case 'specialist_option':
    			return array(
					'Jual Rumah/Apartemen',
					'Jual Rumah Mewah',
					'Jual Tanah Komersial',
					'Negosiasi',
					'Lelang',
					'Manajemen Properti',
					'Relokasi',
					'Sewa Menyewa',
					'Pengurusan KPR',
					'Pengurusan Serfitikat',
					'Pengurusan Pajak'
				);
    			break;

			case 'languages_option':
    			return array(
					'Indonesia',
					'English',
					'Mandarin',
					'Malaysia',
					'Arabic',
					'Deutsch',
					'Espanol',
					'Prancis'
				);
    			break;

			case 'agent_certificates_option':
    			return array(
					'AREBI'
				);
    			break;

    		default:
    			return false;
    			break;
    	}
    }

	function getUrlReferer( $params, $is_admin ) {
		
		$referer = array(
			'controller' => $params->controller,
			'action' => $params->action, 
			'admin' => $is_admin
		);
		return $referer;
	}

	function redirectReferer ( $msg, $status = 'error', $urlRedirect = false, $options = array() ) {
		$flash = $this->filterEmptyField($options, 'flash', false, true, array(
			'type' => 'isset',
		));
		$this->setCustomFlash($msg, $status, array(), false, $flash);

		if( !empty($urlRedirect) ) {
			$this->controller->redirect($urlRedirect);
		} else {
			$this->controller->redirect($this->controller->referer());
		}
	}

	function _saveNotification( $data = NULL ){
		$flag = true;

		if( !empty($data['Notification']) ) {
			$dataNotif = $this->filterEmptyField($data, 'Notification');

			if( !empty($dataNotif['name']) ) {
				$notifs = array(
					array(
						'Notification' => $dataNotif,
					),
				);
			} else {
				$notifs = $dataNotif;
			}
			
			if( !empty($notifs) ) {
				foreach ($notifs as $key => $notif) {
					$data['Notification'] = $this->filterEmptyField($notif, 'Notification');

					if( !$this->controller->User->Notification->doSave($data) ) {
						$flag = false;
					}
				}
			}
		}

		return $flag;
	}

	// function setProcessParams ( $data, $urlRedirect = false, $redirectError = false, $noRedirect = false ) {
	function setProcessParams ( $data, $urlRedirect = false, $options = array() ) {
		$redirectError = $this->filterEmptyField($options, 'redirectError');
		$noRedirect = $this->filterEmptyField($options, 'noRedirect');
	 	$flash = $this->filterIssetField($options, 'flash', false, true);

		$this->validateEmail($data);
		$this->_saveNotification($data);

		if ( !empty($data['msg']) && !empty($data['status']) ) {
			if ( !empty( $data['Log'] ) ) {
				$activity = $this->filterEmptyField($data, 'Log', 'activity');
				$old_data = $this->filterEmptyField($data, 'Log', 'old_data');
				$document_id = $this->filterEmptyField($data, 'Log', 'document_id');
				$error = $this->filterEmptyField($data, 'Log', 'error');

				$this->_saveLog( $activity, $old_data, $document_id, $error );
			}

			if ( !empty( $data['RefreshAuth'] ) ) {
				$user_id = $this->filterEmptyField($data, 'RefreshAuth', 'id');
				$this->RmUser->refreshAuth($user_id);
			}

			if ( ( $data['status'] == 'success' || !empty($redirectError) ) && !$noRedirect ) {
				$this->redirectReferer($data['msg'], $data['status'], $urlRedirect, array(
					'flash' => $flash,
				));
			} else {
				$this->setCustomFlash($data['msg'], $data['status']);
			}
		}

		if ( !empty( $data['data'] ) ) {
			$this->controller->request->data = $data['data'];
		}
	}

	function filterEmptyField ( $value, $modelName, $fieldName = false, $empty = false, $options = false ) {
		$type = !empty($options['type'])?$options['type']:'empty';

		switch ($type) {
			case 'isset':
				if( empty($fieldName) && isset($value[$modelName]) ) {
					return $value[$modelName];
				} else {
					return isset($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
				}
				break;
			
			default:
				if( empty($fieldName) && !empty($value[$modelName]) ) {
					return $value[$modelName];
				} else {
					return !empty($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
				}
				break;
		}
	}

	function filterIssetField ( $value, $modelName, $fieldName = false, $empty = false ) {
		$result = '';
		if( empty($modelName) && !is_numeric($modelName) ) {
			$result = isset($value)?$value:$empty;
		} else if( empty($fieldName) && !is_numeric($fieldName) ) {
			$result = isset($value[$modelName])?$value[$modelName]:$empty;
		} else {
			$result = isset($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
		}

		return $result;
	}

    function _layout_js ( $type ) {
    	$layout_js = false;

    	switch ($type) {
    		case 'map':
    			$layout_js = array(
					'//maps.google.com/maps/api/js?sensor=false',
					'//s3-ap-southeast-1.amazonaws.com/rmhstatic/js/jquery/gmap.js',
				);
    			break;
    		case 'fileupload':
    			$layout_js = array(
					'/file_upload/js/vendor/jquery.ui.widget.js',
					'/file_upload/js/tmpl.min.js',
					'/file_upload/js/load-image.min.js',
					'/file_upload/js/canvas-to-blob.min.js',
					'/file_upload/js/jquery.iframe-transport.js',
					'/file_upload/js/jquery.fileupload.js',
					'/file_upload/js/jquery.fileupload-fp.js',
					'/file_upload/js/jquery.fileupload-ui.js',
					'/file_upload/js/locale.js',
					'/file_upload/js/main.js',
				);
    			break;
    		case 'datepicker':
    			$layout_js = array(
					'daterangepicker',
				);
    			break;
    	}
    	return $layout_js;
    }

    function _layout_css ( $type ) {
    	$layout_css = false;

    	switch ($type) {
    		case 'fileupload':
    			$layout_css = array(
					'/file_upload/css/file_upload_style.css',
					'/file_upload/css/jquery.fileupload-ui.css',
				);
    			break;
    	}
    	return $layout_css;
    }

    function get_result_upload($options = false){
  		$file = new stdClass();

  		if( !empty($options['error']) ){
  			$file->message = $options['message'];
			$file->error = 1;
  		}else{
  			if(!empty($options['imagePath'])){
	  			$file->thumbnail_url = $options['imagePath'];
  			}

  			if(!empty($options['name'])){
	  			$file->name = $options['name'];
  			}
  		}

  		return $file;
  	}

  	function array_random($arr, $num = 1) {
	    shuffle($arr);
	    
	    $r = array();
	    for ($i = 0; $i < $num; $i++) {
	        $r[] = $arr[$i];
	    }
	    return $num == 1 ? $r[0] : $r;
	}

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
		$rand_code = $this->array_random($pass, count($variable));

        return $pass;
    }

    function toSlug($string, $glue = '-') {
		return strtolower(Inflector::slug($string, $glue));
	}

	/**
	*
	*	filterisasi content tag
	*
	*	@param string string : string
	*	@return string
	*/
	function safeTagPrint($string){
		if( is_string($string) ) {
			return strip_tags($string);
		} else {
			return $string;
		}
	}

	function getRefine( $refine = false, $fieldName = false ) {
		$keyword = $this->filterEmptyField($refine, 'named', $fieldName);
		$this->controller->request->data['Search'][$fieldName] = $keyword;

		return $keyword;
	}

	function convertDataAutocomplete( $data ) {
		$result = array();

		if( !empty($data) ) {
			foreach ($data as $id => $value) {
				array_push($result, $value);
			}
		}

		return $result;
	}

    function _layout_file ( $type ) {
    	$layout_js = array();
    	$layout_css = array();
    	$contents = array();

    	if( !is_array($type) ) {
    		$contents[] = $type;
    	} else {
    		$contents = $type;
    	}

    	if( !empty($contents) ) {
    		foreach ($contents as $key => $type) {
		    	switch ($type) {
		    		case 'map':
		    			$layout_js = array_merge($layout_js, array(
							'//maps.google.com/maps/api/js?sensor=false',
							'//s3-ap-southeast-1.amazonaws.com/rmhstatic/js/jquery/gmap.js',
						));
		    			break;
		    		case 'fileupload':
		    			$layout_js = array_merge($layout_js, array(
							'/file_upload/js/vendor/jquery.ui.widget.js',
							'/file_upload/js/tmpl.min.js',
							'/file_upload/js/load-image.min.js',
							'/file_upload/js/canvas-to-blob.min.js',
							'/file_upload/js/jquery.iframe-transport.js',
							'/file_upload/js/jquery.fileupload.js',
							'/file_upload/js/jquery.fileupload-fp.js',
							'/file_upload/js/jquery.fileupload-ui.js',
							'/file_upload/js/locale.js',
							'/file_upload/js/main.js',
						));
		    			$layout_css = array_merge($layout_css, array(
							'/file_upload/css/file_upload_style.css',
							'/file_upload/css/jquery.fileupload-ui.css',
						));
		    			break;
		    		case 'ckeditor':
		    			$layout_js = array_merge($layout_js, array(
							'ckeditor/ckeditor',
						));
		    			break;
		    		case 'gchart':
		    			$layout_js = array_merge($layout_js, array(
							'jquery.gchart.js',
						));
		    			break;
		    	}
		    }
	    }
    	
    	$this->controller->set(compact(
    		'layout_js', 'layout_css'
		));
    }

    function _isAdmin ( $group_id = false ) {
    	$admin_id = Configure::read('__Site.Admin.List.id');

    	if( empty($group_id) ) {
    		$group_id = Configure::read('User.group_id');
    	}

    	if( in_array($group_id, $admin_id) ) {
    		return true;
    	} else {
    		return false;
    	}
    }

    function _callAuthUserId ( $user_id = false ) {
		$user_login_id = Configure::read('User.id');

    	if( $this->_isAdmin() && !empty($user_id)  ) {
			$user_id = $user_id;
		} else {
			$user_id = $user_login_id;
		}

		return $user_id;
    }

	// function _callRequestSubarea ( $values, $extraOptions = false ) {
	// 	if( empty($values) ) {
	// 		$values = array(
	// 			'Subarea' => 'UserProfile',
	// 		);
	// 	} else if( !is_array($values) ) {
	// 		$values = array(
	// 			'Subarea' => $values,
	// 		);
	// 	}

	// 	if( !empty($values) ) {
	// 		foreach ($values as $variableName => $modelName) {

	// 			switch ($variableName) {
	// 				case 'BankBranch':
	// 					if( !empty($this->controller->request->data[$modelName]['bank_id']) ) {
	// 						$id = $this->controller->request->data[$modelName]['bank_id'];
	// 						$bankBranches = $this->controller->Bank->BankBranch->getData('list', array(
	// 							'conditions' => array(
	// 								'BankBranch.bank_id' => $id,
	// 							),
	// 						));

	// 						$this->controller->set(compact(
	// 							'bankBranches'
	// 						));
	// 					}
	// 					break;
					
	// 				default:
	// 					if( !empty($this->controller->request->data[$modelName]['city_id']) ) {

	// 						$city_id = $this->controller->request->data[$modelName]['city_id'];	
	// 						$subareas = $this->controller->User->UserProfile->Subarea->getSubareas('list', false, $city_id);
	// 						if($extraOptions){
	// 							$this->optionsLocation($modelName, $subareas);
	// 						}else{
	// 							$this->controller->set(compact(
	// 								'subareas'
	// 							));	
	// 						}
							
	// 					}
	// 					break;
	// 			}
	// 		}
	// 	}
	// }

	function optionsLocation( $modelName, $values, $key = 0){
		if(!empty($modelName) && !empty($values)){
			$subarea_list = sprintf('subareas_%s', $key);
			$this->controller->set($subarea_list, $values);	
		}
	}

    function _callRequestSubarea ( $values, $customField = false ) {
		if( empty($values) ) {
			$values = array(
				'Subarea' => 'UserProfile',
			);
		} else if( !is_array($values) ) {
			$values = array(
				'Subarea' => $values,
			);
		}

		if( !empty($values) ) {
			foreach ($values as $variableName => $modelName) {
				if(!empty($this->controller->request->data[$modelName][0])){
					foreach($this->controller->request->data[$modelName] AS $key => $model){
						if( !empty($model['city_id']) ) {
							$city_id = $model['city_id'];
						} else if( !empty($model['city']) ) {
							$city_id = $model['city'];
						}

						if( !empty($city_id) ) {
							$subareas = $this->controller->User->UserProfile->Subarea->getSubareas('list', false, $city_id);
							if($customField){
								$this->optionsLocation($modelName, $subareas, $key);
							}else{
								$this->controller->set(compact(
									'subareas'
								));	
							}
						}
					}
				}else{
					if( !empty($this->controller->request->data[$modelName]['city_id']) ) {
						$city_id = $this->controller->request->data[$modelName]['city_id'];
					} else if( !empty($this->controller->request->data[$modelName]['city']) ) {
						$city_id = $this->controller->request->data[$modelName]['city'];
					}

					if( !empty($city_id) ) {
						$subareas = $this->controller->User->UserProfile->Subarea->getSubareas('list', false, $city_id);
						$this->controller->set(compact(
							'subareas'
						));	
					}
				}
				
			}
		}
	}

	function _callConvertDateRange($params, $date, $options = array()){
		$startField = $this->filterEmptyField($options, 'date_from', false, 'date_from');
		$endField = $this->filterEmptyField($options, 'date_to', false, 'date_to');

		$date = urldecode($date);
		$dateArr = explode(' - ', $date);

		if(!empty($dateArr) && count($dateArr) == 2){
			$fromDate = !empty($dateArr[0])?$this->getDate($dateArr[0]):false;
			$toDate = !empty($dateArr[1])?$this->getDate($dateArr[1]):false;

			$params[$startField] = $fromDate;
			$params[$endField] = $toDate;
		}

		return $params;
	}

	function _callReverseDateRange ( $fromDate, $toDate, $format = 'Y-m-d' ) {
        $fromDate = urldecode($fromDate);
        $toDate = urldecode($toDate);

        if(!empty($format)){
        	$fromDate = date($format, strtotime($fromDate) );
        	$toDate = date($format, strtotime($toDate) );
        }

        if( !empty($fromDate) ) {
            $fromDate = str_replace('-', '/', $fromDate);
	    }
	    if( !empty($toDate) ) {
            $toDate = str_replace( '-', '/', $toDate);
	    }

	    if(!empty($fromDate) && !empty($toDate)){
	    	$date = sprintf('%s - %s', $fromDate, $toDate);
	    }

	    return $date;
	}

	function processSorting ( $params, $data, $with_param_id = true, $param_id_only = false, $redirect = true ) {
		$filter = $this->filterEmptyField($data, 'Search', 'filter');
		$sort = $this->filterEmptyField($data, 'Search', 'sort');
		$date = $this->filterEmptyField($data, 'Search', 'date');
		$excel = $this->filterEmptyField($data, 'Search', 'excel');
		$min_price = $this->filterEmptyField($data, 'Search', 'min_price', 0);
		$max_price = $this->filterEmptyField($data, 'Search', 'max_price', 0);

		$named = $this->filterEmptyField($this->controller->params, 'named');

		if( !empty($with_param_id) ) {
			$param_id = $this->filterEmptyField($named, 'param_id');
			if( is_array($param_id) ) {
				$params = array_merge($params, $param_id);
			} else {
				$params[] = $param_id;
			}
		}

		if( !empty($param_id_only) ) {
			return $params;
		}

		if( empty($date) ) {
			$date_from = $this->filterEmptyField($data, 'Search', 'date_from');
			$date_to = $this->filterEmptyField($data, 'Search', 'date_to');

			if( !empty($date_from) && !empty($date_to) ) {
				$date = sprintf('%s - %s', $date_from, $date_to);
			}
		}

		if(!empty($data['Search']['change_url'])){
			unset($data['Search']['change_url']);
		}

		$data = $this->_callUnset(array(
			'Search' => array(
				'sort',
				'direction',
				'date',
				'excel',
				'action',
				'min_price',
				'max_price',
			),
		), $data);
		$dataSearch = $this->filterEmptyField($data, 'Search');
		if( isset($dataSearch['keyword']) ) {
			$dataSearch['keyword'] = trim($dataSearch['keyword']);
		}
		
		if( !empty($dataSearch) ) {
			foreach ($dataSearch as $fieldName => $value) {
				if( is_array($value) ) {
					$value = array_filter($value);

					if( !empty($value) ) {
						$result = array();

						foreach ($value as $id => $boolean) {
							if( !empty($id) ) {
								$result[] = $id;
							}
						}

						$value = implode(',', $result);
					}
				}

				if( !empty($value) ) {
					if( !is_array($value) ) {
						$params[$fieldName] = urlencode($value);
					} else {
						$params[$fieldName] = $value;
					}
				}
			}
		}
		if( !empty($filter) ) {
			$filterArr = strpos($filter, '.');

			if( !empty($filterArr) ) {
				$sort = $filter;
			}
		}

		if( !empty($sort) ) {
			$dataArr = explode('-', $sort);

			if( !empty($dataArr) && count($dataArr) == 2 ) {
				$sort = !empty($dataArr[0])?$dataArr[0]:false;
				$direction = !empty($dataArr[1])?$dataArr[1]:false;

				$sortLower = strtolower($sort);
				$directionLower = strtolower($direction);

				if( !in_array($direction, array( 'asc', 'desc' )) ) {
					$params[$sort] = $direction;
				} else {
					$params['sort'] = $sort;
					$params['direction'] = $direction;
				}
			}
		}

		if( !empty($date) ) {
			$params = $this->_callConvertDateRange($params, $date);
		}
		if( !empty($excel) ) {
			$params['export'] = 'excel';
		}
		if( !empty($min_price) || !empty($max_price) ) {
			$min_price = $this->_callPriceConverter($min_price);
			$max_price = $this->_callPriceConverter($max_price);

			if( empty($max_price) ) {
				$price = $min_price;
			} else {
				$price = sprintf('%s-%s', $min_price, $max_price);
			}

			$params['price'] = $price;
		}

		if( !empty($redirect) ) {
			$this->controller->redirect($params);
		} else {
			return $params;
		}
	}

	function _callSet( $fieldArr, $data ) {
		if( !empty($fieldArr) && !empty($data) ) {
			$data = array_intersect_key($data, array_flip($fieldArr));
		}
		return $data;
	}

	function _callRefineParams ( $data, $default_options = false ) {
		$default_status = $this->filterEmptyField($default_options, 'status');
		$default_document = $this->filterEmptyField($default_options, 'document_status');

		$dataSearch = $this->filterEmptyField($data, 'named');
		$sort = $this->filterEmptyField($data, 'named', 'sort');
		$filter = $this->filterEmptyField($data, 'named', 'filter');
		$status = $this->filterEmptyField($data, 'named', 'status', $default_status);
		$document_status = $this->filterEmptyField($data, 'named', 'document_status', $default_document);
		$date_from = $this->filterEmptyField($data, 'named', 'date_from');
		$date_to = $this->filterEmptyField($data, 'named', 'date_to');
		$keyword = $this->filterEmptyField($data, 'named', 'keyword');
		$type = $this->filterEmptyField($data, 'named', 'type');
		$add_type = $this->filterEmptyField($data, 'named', 'add_type');
		$subareas = $this->filterEmptyField($data, 'named', 'subareas');
		$options = array();

		$dataString = $this->_callUnset(array(
			'sort',
			'direction',
			'date_from',
			'date_to',
			'document_status',
			'status',
			'subareas',
			'type',
		), $dataSearch);

		if( !empty($dataString) ) {
			foreach ($dataString as $fieldName => $value) {
				$this->controller->request->data['Search'][$fieldName] = urldecode($value);
			}
		}
		$dataArr = $this->_callSet(array(
			'subareas',
			'type',
		), $dataSearch);

		if( !empty($dataArr) ) {
			foreach ($dataArr as $fieldName => $value) {
				$value = urldecode($value);
				$valueArr = explode(',', $value);

				if( !empty($valueArr) ) {
					foreach ($valueArr as $key => $id) {
						$this->controller->request->data['Search'][$fieldName][$id] = true;
					}
				}
			}
		}

		if( !empty($sort) ) {
			$direction = $this->filterEmptyField($data, 'named', 'direction', 'asc');
			$direction = strtolower($direction);
			$sortName = $sort;

			if( !empty($direction) ) {
				$sortName = sprintf('%s-%s', $sort, $direction);
			}

			$this->controller->request->data['Search']['sort'] = $sortName;
			$this->controller->request->data['Search']['filter'] = $sortName;
		}
		if( !empty($filter) ) {
			$this->controller->request->data['Search']['filter'] = $filter;
		}
		if( !empty($status) ) {
			$options['status'] = $status;
			$this->controller->request->data['Search']['status'] = $status;
		}

		if( !empty($document_status) ) {
			$options['document_status'] = $document_status;
			$this->controller->request->data['Search']['document_status'] = $document_status;
		}

		if( !empty($date_from) ) {

			$date = $this->_callReverseDateRange($date_from, $date_to, 'd/m/Y');
			$this->controller->request->data['Search']['date'] = $date;
			
			$this->controller->request->data['Search']['date_from'] = date('d-m-Y', strtotime($this->filterEmptyField($data, 'named', 'date_from')));
			$this->controller->request->data['Search']['date_to'] = date('d-m-Y', strtotime($this->filterEmptyField($data, 'named', 'date_to')));
		}

		if(!empty($add_type)){
			$this->controller->set('add_type', $add_type);
		}

		return $options;
	}

	function defaultSearch ( $params, $data ) {
		if( !empty($data) ) {
			foreach ($data as $fieldName => $value) {
				$this->controller->request->data['named'][$fieldName] = $value;

				if( is_array($params['named']) ) {
					if( !array_key_exists($fieldName, $params['named']) ) {
						$params['named'][$fieldName] = $value;
					}
				} else {
					$params['named'][$fieldName] = $value;
				}
			}
		}

		return $params;
	}

	function _saveLog( $activity = NULL, $old_data = false, $document_id = false, $error = 0 ){
		$this->Log = ClassRegistry::init('Log'); 

		$log = array();
		$user_id = Configure::read('User.id');
		$_admin = Configure::read('User.admin');

		$controllerName = $this->controller->params['controller'];
		$actionName = $this->controller->params['action'];
		$data = serialize($this->controller->params['data']);
		$named = serialize( $this->controller->params['named'] );

		$is_admin = !empty($_admin)?true:false;
		$old_data = serialize( $old_data );
		$url = $this->RequestHandler->getReferer();

		$ip_address = $this->RequestHandler->getClientIP();
		$browser = !empty($user_agents['browser'])?implode(' ', array($user_agents['browser'], $user_agents['version'])):'';
		$os = !empty($user_agents['platform'])?$user_agents['platform']:'';
		
		$log['Log']['user_id'] = $user_id;
		$log['Log']['document_id'] = $document_id;
		$log['Log']['name'] = $activity;
		$log['Log']['model'] = $controllerName;
		$log['Log']['action'] = $actionName;
		$log['Log']['old_data'] = $old_data;
		$log['Log']['data'] = $data;
		$log['Log']['ip'] = $ip_address;
		$log['Log']['user_agent'] = env('HTTP_USER_AGENT');
		$log['Log']['from'] = $url;
		$log['Log']['named'] = $named;
		$log['Log']['error'] = !empty($error)?$error:0;
		$log['Log']['admin'] = $is_admin;
		$log['Log']['bank_activity'] = 1;

		if( $this->Log->doSave($log) ) {
			return true;	
		} else {
			return false;
		}
	}

	// function _callUnsetArr($data, $fieldArr){
	// 	if(!empty($fieldArr)){
	// 		foreach ($fieldArr as $loop => $field) {
	// 			if(is_array($field)){
	// 				debug($fieldArr);
	// 				$data[$loop] = $this->_callUnsetArr($data[$loop], $fieldArr[$loop]);
	// 			}else{
	// 				unset($data[$field]);
	// 			}
	// 		}
	// 	}
	// }

	function _callUnset( $fieldArr, $data ) {
		if( !empty($fieldArr) ) {
			foreach ($fieldArr as $key => $value) {

				if( is_array($value) ) {
					foreach ($value as $idx => $fieldNames) {

						if(is_array($fieldNames)){
							foreach ($fieldNames as $i => $fieldName) {
								$flag = isset($data[$key][$idx][$fieldName]);
								
								if( $flag || ($flag == null)) {
									unset($data[$key][$idx][$fieldName]);
								}	
							}
						}else{
							$flag = isset($data[$key][$fieldNames]);

							if( $flag || ($flag == null)) {
								unset($data[$key][$fieldNames]);
							}	
						}
					}
				} else {
					unset($data[$value]);
				}
			}
		}

		return $data;
	}

	public function getCurlSite($site){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $site
		)); 
		$q = curl_exec($curl); 
		$result = json_decode($q, true);
		return $result;
	}

    function getDate ( $date, $reverse = false ) {
    	$dtString = false;
        $date = trim($date);

    	if( !empty($date) && $date != '0000-00-00' ) {
            if($reverse){
                $dtString = date('d/m/Y', strtotime($date));
            }else{
                $dtArr = explode('/', $date);
                if( count($dtArr) == 3 ) {
                    $dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
                } else {
                    $dtArr = explode('-', $date);

                    if( count($dtArr) == 3 ) {
                        $dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
                    }
                }
            }
    	}

    	return $dtString;
    }

    // function _callPriceConverter ($price, $empty = false) {
    // 	$price = str_replace(array( ',' ), array( '' ), $price);
    // 	$price = trim($price);

    // 	if( !empty($price) ) {
    // 		return $price;
    // 	} else {
    // 		return $empty;
    // 	}
    // }

    // function _callRoundPrice($price, $empty = false){
    // 	if(isset($price)){
    // 		return round($price, 0);
    // 	}else{
    // 		return $empty;
    // 	}
    // }

    function access_api($data){
		if(!empty($data)){
			if( !empty($data['apikey']) && !empty($data['apipass']) ) {
				$apikey = $data['apikey'];
				$api_secret = $data['apipass'];
				
				$this->ApiUser = ClassRegistry::init('ApiUser'); 

				$cek_api_registration = $this->ApiUser->get_access($apikey, $api_secret);
				
				if($cek_api_registration){
					return true;
				}else{
					$this->setCustomFlash(__('Anda tidak mempunyai otorisasi'), 'error');
				}
			}else{
				$this->setCustomFlash(__('Anda tidak mempunyai otorisasi'), 'error');
			}
		}
	}

    function _callSeperateName ( $name ) {
    	$name = explode(' ', $name);
    	$first_name = !empty($name[0])?$name[0]:false;
		$last_name = false;

    	if( !empty($name[1]) ) {
    		unset($name[0]);
    		$last_name = implode(' ', $name);
    	}

    	return array(
    		'first_name' => $first_name,
    		'last_name' => $last_name,
		);
    }

    function getDate2($date, $reverse = false){
		$dtString = false;
		$date = urldecode($date);
		$date = trim($date);

		if(!empty($date) && $date != '0000-00-00'){
			if($reverse){
				$dtString = date('d/m/Y', strtotime($date));
			}else{
				$mergeDate = explode(' ', $date);

				if($mergeDate > 1){
					$date = !empty($mergeDate[0])?$mergeDate[0]:false;
					$time = !empty($mergeDate[1])?$mergeDate[1]:false;
				}

				$dtArr = explode('/', $date);

				if(count($dtArr) == 3){
					$dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
				} else {
					$dtArr = explode('-', $date);

					if(count($dtArr) == 3){
						$dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
					}
				}

				if(!empty($time)){
					$dtString = sprintf('%s %s', $dtString, $time);
				}
			}
		}
		return $dtString;
	}

	function wrapWithHttpLink($url, $removeHttp = false){
	//	jangan pake strtolower, silakan test 2 url dibawah
	//	https://play.google.com/store/apps/details?id=com.gapuraprima.group.AOVJCELAUFGAGGCN	// valid URL
	//	https://play.google.com/store/apps/details?id=com.gapuraprima.group.aovjcelaufgaggcn	// invalid URL

	//	$result		= strtolower($url);
		$result		= trim($url);
		$textUrl	= 'http://';
		$textUrls	= 'https://';

		if(!empty($url)){
			if( !empty($removeHttp) ) {
				$result = str_replace(array( $textUrl, $textUrls ), array( '', '' ), $url);
			} else {
				$flag = array();

				if(strpos($url, $textUrl) === false && substr($url, 0, 7) != $textUrl){
					$flag[] = true;
				}
				if(strpos($url, $textUrls) === false && substr($url, 0, 8) != $textUrls){
					$flag[] = true;
				}

				if(count($flag) == 2){
					$result = sprintf("%s%s", $textUrl, $url);
				}
			}
		}

		return $result;
	}

    function _callDateRangeConverter ( $daterange, $fieldName = 'date', $fromName = 'start_date', $toName = 'end_date' ) {
    	$result = array();

        if( !empty($daterange) ) {
            $dateStr = urldecode($daterange);
            $daterange = explode('-', $dateStr);

            if( !empty($daterange) ) {
                $daterange[0] = urldecode($daterange[0]);
                $daterange[1] = urldecode($daterange[1]);
                $dateFrom = $this->getDate2($daterange[0]);
                $dateTo = $this->getDate2($daterange[1]);
                $result[$fromName] = $dateFrom;
                $result[$toName] = $dateTo;
            }
        }

        return $result;
    }

    function _callDateRangeMerge ( $data, $modelName = 'Search' ) {
		$date = $this->filterEmptyField($data, $modelName, 'date', array());

        if( !empty($date) ) {
        	$data = $this->_callUnset($data, array(
				$modelName => array(
					'date', 
				), 
			));

			$data[$modelName] = array_merge($data[$modelName], $date);
        }

        return $data;
    }

    function _callPriceConverter($price, $empty = false) {
    	$price = str_replace(array( ',' ), array( '' ), $price);
    	$price = trim($price);

    	if( !empty($price) ) {
    		return $price;
    	} else {
    		return $empty;
    	}
    }

	public function getFormatPrice( $amount, $default = 0, $decimalPlaces = 0, $currency = false ){
		App::import('Helper', 'Number'); 
		$this->Number = new NumberHelper(new View(null));

		if( !empty($amount) ) {
			if(intval($amount) != floatval($amount) && is_null($decimalPlaces)){
				$decimalPlaces = 2;
			}

			if( !empty($currency) ) {
				if( is_bool($currency) ) {
					$currency = Configure::read('__Site.config_currency_symbol');
				}
			}

			$amount = $this->Number->currency($amount, $currency, array('places' => $decimalPlaces));

			if( !empty($amount) ) {
				return $amount;
			} else {
				return $default;
			}
		} else {
			return $default;
		}
	}

    function _callRoundPrice($price, $round = 0){
    	if(isset($price)){
    		return round($price, $round);
    	}else{
    		return $empty;
    	}
    }

	function dataConverter2( $data, $fields, $reverse = false, $round = 0 ) {
		if( !empty($data) && !empty($fields) ) {
			foreach ($fields as $type => $models) {
				$data = $this->_converterLists($type, $data, $models, $reverse, $round);
			}
		}
		return $data;
	}

	function _converterLists($type, $data, $models, $reverse = false, $round = 0){
    	if(!empty($type) && !empty($data) && !empty($models)){
    		if(is_array($models)){
    			foreach($models AS $loop => $model){
 	   				if(!empty($model) || $model === 0){
	 	   				if( is_array($model) && !empty($data[$loop]) ){
	 	   					if(is_numeric($loop)){
	 	   						foreach($data AS $key => $dat){
	 	   							if(is_array($model) && !empty($dat)){
	 	   								$data[$key] = $this->_converterLists($type, $data[$key], $model, $reverse, $round);
	 	   							}
	 	   						}
	 	   					}else{
	 	   						$data[$loop] = $this->_converterLists($type, $data[$loop], $models[$loop], $reverse, $round);
	 	   					}
	 	   				} else if( !is_array($model) ) {
	 	   					if(in_array($type, array('unset', 'array_filter'))){
	 	   						if($type == 'array_filter'){
	 	   							$data[$model] = array_filter($data[$model]);
	 	   							if(empty($data[$model])){
	 	   								unset($data[$model]);
	 	   							}
	 	   						}else{
	 	   							if(isset($data[$model])){
		 	   							unset($data[$model]);	   								
	 	   							}
	 	   						}

	 	   					} else if( !empty($data[$model]) ) {
	 	   						$data[$model] = $this->_generateType($type, $data[$model], $reverse, $round);
	 	   					}
	 	   				}
	 	   			}
	    		}
    		}else{
    			if(in_array($type, array('unset', 'array_filter'))){
    				if($type == 'array_filter'){
						$data[$models] = array_filter($data[$models]);
						if(empty($data[$models])){
							unset($data[$models]);
						}
					}else{
						unset($data[$models]);
					}
    			}else{
    				$data[$models] = $this->_generateType($type, $data[$models], $reverse, $round);
    			}
    		}
    	}
    	return $data;
    }

    function _generateType($type, $data, $reverse, $round){
    	switch($type){
			case 'date' : 
			$data = $this->getData2($data, $reverse);
			break;
		case 'price' : 
			$data = $this->_callPriceConverter($data, $reverse);
			break;
		case 'round' : 
			$data = $this->_callRoundPrice($data, $round);
			break;
		case 'url' : 
			$data = $this->wrapWithHttpLink($data, $reverse);
			break;
		case 'auth_password' : 
			$data = $this->Auth->password($data);
			break;
        case 'daterange':
			$data = $this->_callDateRangeConverter($data);
            break;
        case 'year':
            $data = intval($data);
            $data = !empty($data)?$data:false;
            break;
		## ADA CASE BARU TAMBAHKAN DISINI, ANDA HANYA MEMBUAT $this->FUNCTION yang anda inginkan tanpa merubah flow dari
		## function dataConverter dan _converterLists
		}
		return $data;
    }

	function dataConverter ( $data, $fields, $reverse = false ) {
		if( !empty($fields) ) {
			foreach ($fields as $type => $models) {
				switch ($type) {
					case 'date':
						if( !empty($models) ) {
							if( is_array($models) ) {
								foreach ($models as $modelName => $model) {
									if( !empty($model) ) {
										if( is_array($model) ) {
											foreach ($model as $key => $fieldName) {
												if( !empty($data[$modelName][$fieldName]) ) {
													$data[$modelName][$fieldName] = $this->getDate($data[$modelName][$fieldName], $reverse);
												}
											}
										} else {
											if( !empty($data[$model]) ) {
												$data[$model] = $this->getDate($data[$model], $reverse);
											}
										}
									}
								}
							} else {
								if( !empty($data[$models]) ) {
									$data[$models] = $this->getDate($data[$models], $reverse);
								}
							}
						}
						break;
					case 'price':
						if( !empty($models) ) {
							if( is_array($models) ) {
								foreach ($models as $modelName => $model) {
									if( !empty($model) ) {
										if( is_array($model) ) {
											foreach ($model as $key => $fieldName) {
												if( !empty($data[$modelName][$fieldName]) ) {
													$data[$modelName][$fieldName] = $this->_callPriceConverter($data[$modelName][$fieldName], $reverse);
												}
											}
										} else {
											if( !empty($data[$model]) ) {
												$data[$model] = $this->_callPriceConverter($data[$model], $reverse);
											}
										}
									}
								}
							} else {
								if( !empty($data[$models]) ) {
									$data[$models] = $this->_callPriceConverter($data[$models], $reverse);
								}
							}
						}
						break;
					case 'round':
						if( !empty($models) ) {
							if( is_array($models) ) {
								foreach ($models as $modelName => $model) {
									if( !empty($model) ) {
										if( is_array($model) ) {
											foreach ($model as $key => $fieldName) {
												if( !empty($data[$modelName][$fieldName]) ) {
													$data[$modelName][$fieldName] = $this->_callRoundPrice($data[$modelName][$fieldName], $reverse);
												}
											}
										} else {
											if( !empty($data[$model]) ) {
												$data[$model] = $this->_callRoundPrice($data[$model], $reverse);
											}
										}
									}
								}
							} else {
								if( !empty($data[$models]) ) {
									$data[$models] = $this->_callRoundPrice($data[$models], $reverse);
								}
							}
						}
						break;
				}
			}
		}

		return $data;
	}

	

	function _callBankSetting () {
		if( $this->controller->group_id == 19 ){
			$banks = $this->controller->Bank->getData('list',array(
			    'conditions' => array(
					array(
						'sub_domain <>' => array('',0),
					),
					array(
						'sub_domain NOT' => null,
					),
		    	),
				'fields' => array(
					'Bank.id', 'Bank.name',
				)
			));
			$banks['admin'] = 'All';
			ksort($banks);
		}else{
			$banks = false;
			$this->sub_domain = $this->filterEmptyField($this->controller->data_bank,'Bank','sub_domain');
		}

		Configure::write('getBankAll', $banks);
	}

	function getCombineDate ( $startDate, $endDate, $empty = false, $emptyEndDate = ' - ..' ) {
		$customDate = false;

		if( !empty($startDate) && !empty($endDate) ) {
			$startDate = strtotime($startDate);
			$endDate = strtotime($endDate);

			if( $startDate == $endDate ) {
				$customDate = date('d M Y', $startDate);
			} else if( date('M Y', $startDate) == date('M Y', $endDate) ) {
				$customDate = sprintf('%s - %s', date('d', $startDate), date('d M Y', $endDate));
			} else if( date('Y', $startDate) == date('Y', $endDate) ) {
				$customDate = sprintf('%s - %s', date('d M', $startDate), date('d M Y', $endDate));
			} else {
				$customDate = sprintf('%s - %s', date('d M Y', $startDate), date('d M Y', $endDate));
			}
		} else if( !empty($startDate) ) {
			$startDate = strtotime($startDate);
			$customDate = sprintf('%s%s', date('d M Y', $startDate), $emptyEndDate);
		} else if( !empty($empty) ) {
			$customDate = $empty;
		}

		return $customDate;
	}


	function _callPathKprApplication ( $is_id_company = false ) {
		if( !empty($is_id_company) ) {
            $save_path = Configure::read('__Site.crm_folder');
        } else {
            $save_path = Configure::read('__Site.general_logo_photo');
        }

        return $save_path;
	}

	function _saveLogDocument($result){
		$log_msg = $this->filterEmptyField($result, 'Log', 'activity');
		$old_data = $this->filterEmptyField($result, 'Log', 'old_data');
		$document_id = $this->filterEmptyField($result, 'Log', 'document_id');
		$error = $this->filterEmptyField($result, 'Log', 'error');
		$code_error = $this->filterEmptyField($result, 'Log', 'code_error');
		$validation_data = $this->filterEmptyField($result, 'Log', 'validation_data');

		$this->_saveLog($log_msg, $old_data, $document_id, $error, $code_error, $validation_data);
	}

    function _callRestValidateAccess () {
		$version = Configure::read('Rest.validate');
		$status = $this->filterEmptyField($version, 'status');
		$msg = $this->filterEmptyField($version, 'msg');

		if( !empty($version) ) {
			$this->setCustomFlash($msg, $status, false, false);
		} else {
			$this->setCustomFlash(__('Unaccepted User'), 'error', false, false);
		}
	}

    function _callCheckAPI($data){
		$this->controller->loadModel('Setting');
		$token = $this->filterEmptyField($data, 'token');
		$slug = $this->filterEmptyField($data, 'slug');
		if( !empty($token) ) {
			$this->controller->loadModel('Setting');

			$setting = $this->controller->Setting->find('first', array(
				'conditions' => array(
					'token' => $token,
					'slug' => $slug,
				),
			));

			$access_id = $this->filterEmptyField($setting, 'Setting', 'id');
			$version['id'] = $access_id;
			$version['passkey'] = $token;
			$version['slug'] = $slug;

			if( empty($setting) ) {
				$version['status'] = 0;
				$version['msg'] = __('Anda tidak memiliki hak untuk mengakses halaman ini.');
			} else {
				Configure::write('Rest.token', $token);

				$version['status'] = 1;
				$version['msg'] = __('User accepted');
			}
		} else {
			$version['status'] = 0;
			$version['msg'] = __('Anda tidak memiliki hak untuk mengakses halaman ini.');
		}

		$data = array_merge($data, $version);

		Configure::write('Rest.validate', $version);
		$this->_callRestValidateAccess();
		$this->controller->request->data = $data;
	}

	function getMergePost($posts) {
		$data = array();
		
		if( !empty($posts) ) {
			foreach ($posts as $key => $post) {
				$postArr = ucwords(str_replace('_', ' ', $key));
				$model = str_replace(' ', '', $postArr);
				
				if( is_array($post) ) {
					foreach ($post as $key => $field) {
						$data[$model][strtolower($key)] = $field;
					}
				} else {
					$data[strtolower($model)] = $post;
				}
			}
		}

		if( !empty($_FILES) ) {
			foreach ($_FILES as $key => $img) {
				$postArr = ucwords(str_replace('_', ' ', $key));
				$model = str_replace(' ', '', $postArr);

				if( is_array($img) ) {
					foreach ($img as $key => $field) {
						if( is_array($field) ) {
							foreach ($field as $index => $value) {
								$data[$model][strtolower($index)][$key] = $value;
							}
						}
					}
				} else {
					$data[strtolower($model)] = $img;
				}
			}
		}
		
		return $data;
	}

    function tokenCheck(){
		if(!empty($_REQUEST['token']) && $this->controller->Rest->isActive()){
			$data = $this->getMergePost($_REQUEST);
			$this->_callCheckAPI($data);
		}
    }

	function _callDataForAPI ( $data ) {
		$rest_api = Configure::read('Rest.token');
		if( !empty($rest_api) ) {
			$this->controller->set('data', $data);
		}
	}

	function _callDomainBank( $group_id, $domain, $base_url ) {
		if(in_array($group_id,array(19, 20))){
			$domain = $base_url;
		}

		$last_char = substr($domain, -1);

		if( $last_char == '/' ) {
			$domain = rtrim($domain, '/');
		}

		return $domain;
	}

	function _callGetDataAPI ( $url ) {
		App::uses('HttpSocket','Network/Http');
        App::uses('Xml','Utility');
        $this->layout = null;
        $this->autorender = false;
        $HttpSocket = new HttpSocket();
        $response = $HttpSocket->get($url);
        $request = $HttpSocket->request; 
        $xmlString = $response['body']; 
		return json_decode($xmlString, TRUE);
	}
}
?>