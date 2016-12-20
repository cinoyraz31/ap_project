<?php
class UserProfile extends AppModel {
	var $name = 'UserProfile';
	var $validate = array(
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Mohon masukkan alamat Anda',
			),
		),
		'country_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Mohon pilih negara Anda',
			),
		),
		'region_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Mohon pilih provinsi Anda',
			),
		),
		'city_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Mohon pilih kota Anda',
			),
		),
		'subarea_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Mohon pilih area Anda',
			),
		),
		'zip' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Mohon masukkan kode pos',
			),
		),
		'phone' => array(
			'notMatch' => array(
				'rule' => array('validatePhoneNumber'),
				'allowEmpty'=> true,
				'message' => 'Format No. Telepon e.g. +6281234567 or 0812345678'
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'allowEmpty'=> true,
				'message' => 'Minimal 6 digit',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 20),
				'allowEmpty'=> true,
				'message' => 'Maksimal 20 digit',
			),
		),
		'no_hp' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Mohon masukkan nomor handphone Anda',
			),
			'notMatch' => array(
				'rule' => array('validatePhoneNumber'),
				'message' => 'Format No. handphone e.g. +6281234567 or 0812345678'
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'message' => 'Minimal 6 digit',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 20),
				'message' => 'Maksimal 20 digit',
			),
		),
		'no_hp_2' => array(
			'notMatch' => array(
				'rule' => array('validatePhoneNumber'),
				'allowEmpty'=> true,
				'message' => 'Format No. handphone 2 e.g. +6281234567 or 0812345678'
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'allowEmpty'=> true,
				'message' => 'Minimal 6 digit',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 20),
				'allowEmpty'=> true,
				'message' => 'Maksimal 20 digit',
			),
		),
		'pin_bb' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				'allowEmpty'=> true,
				'message' => '',
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'allowEmpty'=> true,
				'message' => 'Minimal 6 karakter',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 8),
				'allowEmpty'=> true,
				'message' => 'Maksimal 8 karakter',
			),
		),	
		'website' => array(
			'url' => array(
				'rule' => array('url'),
				'allowEmpty'=> true,
				'message'=> 'Format website salah, contoh : www.primesystem.id',
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
		),
		'Region' => array(
			'className' => 'Region',
			'foreignKey' => 'region_id',
		),
		'City' => array(
			'className' => 'City',
			'foreignKey' => 'city_id',
		),
		'Subarea' => array(
			'className' => 'Subarea',
			'foreignKey' => 'subarea_id',
		)
	);

	function beforeSave( $options = array() ) {

		$day_birth = !empty( $this->data['UserProfile']['day_birth'] ) ? $this->data['UserProfile']['day_birth'] : false;
		$month_birth = !empty( $this->data['UserProfile']['month_birth'] ) ? $this->data['UserProfile']['month_birth'] : false;
		$year_birth = !empty( $this->data['UserProfile']['year_birth'] ) ? $this->data['UserProfile']['year_birth'] : false;

		if ( $day_birth && $month_birth && $year_birth ) {
			$this->data['UserProfile']['birthday'] = $year_birth.'-'.$month_birth.'-'.$day_birth;
		}

		return true;
	}

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->virtualFields['day_birth'] = sprintf('DAY(%s.birthday)', $this->alias, $this->alias);
		$this->virtualFields['month_birth'] = sprintf('MONTH(%s.birthday)', $this->alias, $this->alias);
		$this->virtualFields['year_birth'] = sprintf('YEAR(%s.birthday)', $this->alias, $this->alias);
	}
	
	/**
	* 	@param string $find - all, list, paginate, count
	*		string all - Pick semua field berupa array
	*		string list - Pick semua field berupa key dan value array
	*		string count - Pick jumah data
	*		string paginate - Pick opsi query
	* 	@param array $options - Menampung semua opsi-opsi yang dibutuhkan dalam melakukan query
	* 	@return array - hasil array atau opsi 
	*/
    function getData( $find = 'all', $options = array() ){
		if( $find == 'paginate' ) {
			$result = $options;
		} else {
			$result = $this->find($find, $options);
		}
        return $result;
	}

	/**
	* 	@param array $data - data array "phone", "no_hp", "no_hp_2"
	* 	@return boolean true or false
	*/
	function validatePhoneNumber($data) {
		$phoneNumber = false;
		if( !empty($data['phone']) ) {
			$phoneNumber = $data['phone'];
		} else if( !empty($data['no_hp']) ) {
			$phoneNumber = $data['no_hp'];
		} else if( !empty($data['no_hp_2']) ) {
			$phoneNumber = $data['no_hp_2'];
		}

		if(!empty($phoneNumber)) {
	        if (preg_match('/^[0-9]{1,}$/', $phoneNumber)==1 
	        	|| ( substr($phoneNumber, 0,1)=="+" 
	        	&& preg_match('/^[0-9]{1,}$/', substr($phoneNumber, 1,strlen($phoneNumber)))==1 )) {
	        	return true;
	        }
	    }
        return false;
    }

    function getMerge ( $data, $user_id, $with_contain = false ) {

    	if( empty($data['UserProfile']) && !empty($user_id) ) {
			$userProfile = $this->getData('first', array(
				'conditions' => array(
					'UserProfile.user_id' => $user_id
				),
			));

			if( !empty($userProfile) ) {
				$data = array_merge($data, $userProfile);

				if( !empty($userProfile) ) {
					$data = array_merge($data, $userProfile);

					if( !empty($with_contain) ){
						if( !empty($data['UserProfile']['region_id']) ) {
							$region_id = $data['UserProfile']['region_id'];

							$region = $this->Region->getData('first', array(
								'conditions' => array(
									'Region.id' => $region_id,
								),
								'contain' => false,
							));

							if( !empty($region) ) {
								$data['UserProfile'] = array_merge($data['UserProfile'], $region);
							}
						}

						if( !empty($data['UserProfile']['city_id']) ) {
							$city_id = $data['UserProfile']['city_id'];

							$city = $this->City->getData('first', array(
								'conditions' => array(
									'City.id' => $city_id,
								),
								'contain' => false,
							));

							if( !empty($city) ) {
								$data['UserProfile'] = array_merge($data['UserProfile'], $city);
							}
						}

						if( !empty($data['UserProfile']['subarea_id']) ) {
							$subarea_id = $data['UserProfile']['subarea_id'];

							$subarea = $this->Subarea->find('first', array(
								'conditions' => array(
									'Subarea.id' => $subarea_id,
								),
								'contain' => false,
							));

							if( !empty($subarea) ) {
								$data['UserProfile'] = array_merge($data['UserProfile'], $subarea);
							}
						}
					}
				}
			}
		}

		return $data;
	}

	public function doEditSocialMedia( $user_id, $user, $data ) {

		$result = false;

		if ( !empty($data) ) {

			$this->set($data);

			$user_profile_id = !empty($user['UserProfile']['id'])?$user['UserProfile']['id']:false;
			$save = $this->doSave($data, $user_id, $user_profile_id);

			if ( !empty($save) ) {
				$result = array(
					'msg' => __('Sukses memperbarui data sosial media Anda'),
					'status' => 'success',
				);
			} else {
				$result = array(
					'msg' => __('Gagal memperbarui data sosial media Anda. Silahkan coba lagi'),
					'status' => 'error',
				);
			}
		} else if( !empty($user) ) {
			$result['data'] = $user;
		}

		return $result;
	}

	function doSave( $data, $user_id, $id ) {

		if ( !empty($id) ) {
			$this->id = $id;
		} else {
			$this->create();
			$data['UserProfile']['user_id'] = $user_id;
		}

		if ( $this->save($data) ) {
			return true;
		}

		return false;
	}

	function doSaveSync($data){

		$user_profile = !empty($data['UserProfile'])?$data['UserProfile']:null;
        $result = false;

        if(!empty($user_profile)){

        	$default_msg = __('%s data User profile');
        	$user_id = !empty($user_profile['user_id'])?$user_profile['user_id']:false;
        	$value = $this->getData('first',array(
        		'conditions' => array(
        		 	'UserProfile.user_id' => $user_id
        			)
        		));

        	if(!empty($value)){

        		$id = !empty($value['UserProfile']['id'])?$value['UserProfile']['id']:false;
        		$user_profile['id'] = $id;

        		$this->id = $id;
        		$default_msg = sprintf($default_msg, __('mengubah'));

        	}else{

        		$this->create();
                $default_msg = sprintf($default_msg, __('menambah'));

        	}

        	if($this->save($user_profile, FALSE)){

        		$id = $this->id;
                $msg = sprintf(__('Berhasil %s'), $default_msg);
                $result = array(
                    'msg' => $msg,
                    'status' => 'success',
                    'Log' => array(
                        'activity' => $msg,
                        'old_data' => $value,
                        'document_id' => $id,
                    ),
                    'id' => $id
                );

        	}else{

        		$msg = sprintf(__('Gagal %s'), $default_msg);
                $result = array(
                    'msg' => sprintf(__('Gagal %s'), $default_msg),
                    'status' => 'error',
                    'data' => $user_profile,
                    'Log' => array(
                        'activity' => $msg,
                        'old_data' => $value,
                        'document_id' => $id,
                        'error' => 1,
                    ),
                );

        	}

        }

        return $result;

	}
}
?>