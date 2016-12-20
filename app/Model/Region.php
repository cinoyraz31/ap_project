<?php
class Region extends AppModel {
	var $name = 'Region';
	var $order = array(
		"Region.default" => "DESC", 
		"Region.order" => "ASC", 
		"Region.name" => "ASC"
	);

	/**
	* Get region
	*
	* @param string $find - all, list, paginate, revision
	*		string all - Pick semua field berupa array
	*		string list - Pick semua field berupa key dan value array
	*		string paginate - Pick opsi query
	*		string revision - Pick data revision
	* @param array $options - Menampung semua opsi-opsi yang dibutuhkan dalam melakukan query
	* @param boolean $is_merge - True merge default opsi dengan opsi yang diparsing, False gunakan hanya opsi yang diparsing
	* @return array - hasil query atau opsi
	*/
	function getData($find = 'all', $options = array(), $is_merge = true) {
		$default_options = array(
			'order' => array(
				'Region.order' => 'ASC',
				'Region.name' => 'ASC'
			),
			'conditions' => array(
				'Region.active' => 1,
			),
		);

		if( !empty($options) && $is_merge ){
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
            if(!empty($options['limit'])){
                $default_options['limit'] = $options['limit'];
            }
            if(!empty($options['fields'])){
                $default_options['fields'] = $options['fields'];
            }
        } else if( !empty($options) ) {
            $default_options = $options;
        }

		if( $find == 'paginate' ) {
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}

		return $result;
	}

	function getMerge ( $data, $bank_id ) {
		if( empty($data['Region']) ) {
			$bank = $this->getData('first', array(
				'conditions' => array(
					'Region.id' => $bank_id,
				),
			));

			if( !empty($bank) ) {
				$data = array_merge($data, $bank);
			}
		}

		return $data;
	}
}
?>