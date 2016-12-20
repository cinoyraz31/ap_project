<?php
class PasswordReset extends AppModel {
	var $name = 'PasswordReset';
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	function getData( $find='all', $options = array(), $is_merge = true, $elements = array() ){
        $status = isset($elements['status'])?$elements['status']:'active';
		$default_options = array(
			'conditions'=> array(),
			'contain' => array(),
            'fields'=> array(),
            'group'=> array(),
		);

        switch ($status) {
            case 'all':
                $default_options['conditions'] = array();
                break;

            case 'non-active':
                $default_options['conditions'] = array(
					'PasswordReset.status'=> 1, 
            	);
                break;
            
            default:
                $default_options['conditions'] = array(
					'PasswordReset.status'=> 0, 
            	);
                break;
        }

		if($is_merge){
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
		}else{
			$default_options = $options;
		}

		if( $find == 'paginate' ) {
			$result = $default_options;
		} else {
			$result = $this->find($find, $default_options);
		}
		
        return $result;
	}
}
?>