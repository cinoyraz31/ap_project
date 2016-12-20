<?php
class Unit extends AppModel {
	var $name = 'Unit';
	var $useTable = 'units';

	var $belongsTo = array(
		'Coa' => array(
			'className' => 'Coa',
			'foreignKey' => 'coa_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'leader_id',
		),
	);

	var $hasMany = array(
		'UnitList' => array(
			'className' => 'UnitList',
			'foreignKey' => 'unit_id',
		),
	);

	var $validate = array(
		'assign_date' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Tanggal harap diisi',
			),
		),
		'coa_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Coa harap dipilih',
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nama harap diisi',
			),
			'minLength' => array(
				'allowEmpty'=> true,
				'rule' => array('minLength', 2),
				'message' => 'Panjang Nama minimal 2 karakter',
			),
		),
		'leader_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Leader harap dipilih',
			),
		),	
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
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

	function doRemove( $ids ) {
		$result = false;
		$default_msg = __('menghapus unit');

		$values = $this->getData('all', array(
        	'conditions' => array(
				'Unit.id' => $ids,
			),
		));

		if ( !empty($values) ) {
			$full_name = Set::extract('/Unit/name', $values);
			$full_name = implode(', ', $full_name);

			$flag = $this->updateAll(array(
				'Unit.status' => 0,
			), array(
				'Unit.id' => $ids,
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
				'msg' => __('Gagal menghapus unit. Data tidak ditemukan'),
				'status' => 'error',
			);
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