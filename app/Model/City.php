<?php
class City extends AppModel {
	var $name = 'City';
	var $displayField = 'name';
	var $order = array(
		"City.default" => "DESC", 
		"City.order" => "ASC", 
		"City.name" => "ASC"
	);

	function getData($find = 'all', $options = array(), $is_merge = true) {
		$default_options = array(
			'order' => array(
				'City.order' => 'ASC',
				'City.name' => 'ASC'
			),
			'conditions' => array(
				'City.active' => 1,
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

	function getMerge ( $data, $id ) {
		if( empty($data['City']) ) {
			$value = $this->getData('first', array(
				'conditions' => array(
					'City.id' => $id,
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