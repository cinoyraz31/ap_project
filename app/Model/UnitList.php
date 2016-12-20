<?php
class UnitList extends AppModel {
	var $name = 'UnitList';
	var $useTable = 'unit_lists';

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
		'unit_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Unit harap dipilih',
			),
		),
		'coa_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Coa harap dipilih',
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
			$default_options['conditions']['OR']['UnitList.name LIKE'] = '%'.$keyword.'%';
		}
		
		return $default_options;
	}

	function getData( $find = 'all', $options = array(), $elements = array()){
		$status = $this->filterEmptyField($elements, 'status', false, 'active');

		$default_options = array(
			'conditions'=> array(),
			'order'=> array(
				'UnitList.created' => 'ASC',
			),
			'fields' => array(),
			'contain' => array(),
			'group' => array(),
		);

		switch($status){
			case 'active':
				$statusConditions = array(
					'UnitList.status'=> 1, 
				);
				break;
			case 'disabled':
				$statusConditions = array(
					'UnitList.status'=> 0, 
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
		$default_msg = __('melakukan %s data Sub Unit');

		if(!empty($id)){
			$data['UnitList']['id'] = $id;
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
		$msg = __('melakukan hapus data Sub Unit');

		$value = $this->getData('first', array(
			'conditions' => array(
				'UnitList.id' => $id,
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