<?php
class SubUnit extends AppModel {
	var $name = 'SubUnit';
	var $useTable = 'sub_units';

	var $belongsTo = array(
		'Unit' => array(
			'className' => 'Unit',
			'foreignKey' => 'unit_id',
		),
		'Coa' => array(
			'className' => 'Coa',
			'foreignKey' => 'coa_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'leader_id',
		),
	);

	var $validate = array(
		'assign_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Tanggal harap diisi',
			),
		),
		'coa_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Coa harap dipilih',
			),
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Nama harap diisi',
			),
			'minLength' => array(
				'allowEmpty'=> true,
				'rule' => array('minLength', 2),
				'message' => 'Panjang Nama minimal 2 karakter',
			),
		),
		'leader_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Leader harap dipilih',
			),
		),	
		'user_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'User harap dipilih',
			),
		),	

	);

	public function _callRefineParams( $data = '', $default_options = false ) {
		$keyword = !empty($data['named']['keyword'])?trim($data['named']['keyword']):false;

		if( !empty($keyword) ) {
			$default_options['conditions']['OR']['Unit.name LIKE'] = '%'.$keyword.'%';
		}
		
		return $default_options;
	}

	function getData( $find = 'all', $options = array(), $elements = array()){
		$status = $this->filterEmptyField($elements, 'status', false, 'active');

		$default_options = array(
			'conditions'=> array(),
			'order'=> array(
				'Unit.created' => 'ASC',
			),
			'fields' => array(),
			'contain' => array(),
			'group' => array(),
		);

		switch($status){
			case 'active':
				$statusConditions = array(
					'Unit.status'=> 1, 
				);
				break;
			case 'disabled':
				$statusConditions = array(
					'Unit.status'=> 0, 
				);
				break;
			case 'all':
				$statusConditions = array();
				break;
		}

		if(isset($statusConditions)){
			$default_options['conditions'] = array_merge($default_options['conditions'], $statusConditions);
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
		if( $find == 'paginate' ) {
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
        return $result;
	}

	function doSave($data, $id = false){
		$default_msg = __('melakukan %s data Unit');

		if(!empty($id)){
			$data['Unit']['id'] = $id;
			$default_msg = sprintf($default_msg, __('perubahan'));
		}else{
			$this->create();
			$default_msg = sprintf($default_msg, __('penambahan'));
		}

		$this->set($data);
		if($this->validates()){
			if($this->save()){
				$result = array(
					'msg' => sprintf('Berhasil %s', $default_msg),
					'status' => 'success',
				);
			}else{
				$result = array(
					'msg' => sprintf('Gagal %s', $default_msg),
					'status' => 'error',
				);
			}
		}else{
			$result = array(
				'msg' => sprintf('Gagal %s', $default_msg),
				'status' => 'error',
				'validationErrors' => $this->validationErrors,
			);
		}
		return $result;
	}

	function doDelete($id = false){
		$msg = __('melakukan hapus data Unit');

		$value = $this->getData('first', array(
			'conditions' => array(
				'Unit.id' => $id,
			),
		));

		if(!empty($value)){
			$this->id = $id;
			$this->set('status', FALSE);

			if($this->save()){
				$result = array(
					'msg' => sprintf('Berhasil %s', $msg),
					'status' => 'success',
				);
			}else{
				$result = array(
					'msg' => sprintf('Gagal %s', $msg),
					'status' => 'error',
				);
			}
		}else{
			$result = array(
				'msg' => sprintf('Gagal %s', $msg),
				'status' => 'error',
			); 
		}
		return $result;
	}
}
?>