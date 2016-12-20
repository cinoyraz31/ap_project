<?php

App::uses('AppModel', 'Model');

class Group extends AppModel {
    public $name = 'Group';
    public $useTable = "groups";
    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode() {
	    return null;
	}

    var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nama group harap diisi'
			)
		)
	);

    /**
	* 	@param string $find - all, list, paginate, count
	*		string all - Pick semua field berupa array
	*		string list - Pick semua field berupa key dan value array
	*		string count - Pick jumah data
	*		string paginate - Pick opsi query
	* 	@param array $options - Menampung semua opsi-opsi yang dibutuhkan dalam melakukan query
	* 	@return result
	*/
    function getData( $find = 'all', $options = array() ){
		$default_options = array(
			'conditions'=> array(
				'Group.status' => 1, 
			),
			'order'=> array(
				'Group.name' => 'ASC',
			),
		);

		if(!empty($options)){
			$default_options = array_merge($default_options, $options);
		}

		if( $find == 'paginate' ) {
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
        return $result;
	}

    function getMerge( $data, $group_id, $is_root = false ){
    	if( empty($data['Group']) ) {
			$group = $this->getData('first', array(
				'conditions'=> array(
					'Group.id' => $group_id, 
				),
			));

			if( !empty($group) && !$is_root ){
				$data = array_merge($data, $group);
			}
		}

        return $data;
	}
}
