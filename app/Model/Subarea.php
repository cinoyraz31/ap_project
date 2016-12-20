<?php
class Subarea extends AppModel {
	var $name = 'Subarea';
	var $belongsTo = array(
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'country_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Region' => array(
			'className' => 'Region',
			'foreignKey' => 'region_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'City' => array(
			'className' => 'City',
			'foreignKey' => 'city_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	function getSubareas( $find = 'list', $region_id = false, $city_id = false ) {
		$conditions = array();

		if( !empty($region_id) ) {
			$conditions['Subarea.region_id'] = $region_id;
		}
		if( !empty($city_id) ) {
			$conditions['Subarea.city_id'] = $city_id;
		}

		return $this->getData($find, array(
			'conditions' => $conditions,
		));
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
	public function getData( $find = 'all', $options = array() ){
		$default_options = array(
			'order' => array(
				'Subarea.order' => 'ASC',
				'Subarea.name' => 'ASC'
			),
			'conditions' => array(
				'Subarea.status' => 1,
			),
		);
	
		if( !empty($options) ){
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
        }

		if( $find == 'paginate' ) {
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
        return $result;
	}

	function getMerge ( $data, $id ) {
		if( empty($data['Subarea']) ) {
			$value = $this->getData('first', array(
				'conditions' => array(
					'Subarea.id' => $id,
				),
			));

			if( !empty($value) ) {
				$data = array_merge($data, $value);
			}
		}

		return $data;
	}
}
?>