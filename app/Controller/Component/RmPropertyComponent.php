<?php
class RmPropertyComponent extends Component {

	var $components = array(
		'RmCommon'
	);

	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
		$this->User = ClassRegistry::init('Kpr');
	}
	
	function getMeasurePrice ( $property ) {
		
		$allow_multiplication = array(2, 5, 6);
		$propertyPrice = !empty($property['Property']['price']) ? $property['Property']['price']:0;
		$lot_unit = !empty($property['Property']['lot_unit']) ? $property['Property']['lot_unit']:1;

		if( in_array($property['Property']['property_type_id'], $allow_multiplication) && $lot_unit != 1 ){
			$measure_size = $property['Property']['lot_size'];

			if(in_array($property['Property']['property_type_id'], array(5,6) )){
				$measure_size = $property['PropertyAsset']['building_size'];
			}

			$propertyPrice = $propertyPrice * $measure_size;
		}
		
		return $propertyPrice;
    }

    function checkMlsID($mls_id, $length, $char){
    	$len_mls_id = strlen($mls_id);
    	
    	if($len_mls_id == $length){
    		$substr = substr($mls_id, 0, 1);

    		if($substr == $char){
    			$mls_id = substr($mls_id, 1);
    			return $mls_id;
    		}else{
    			return $mls_id;
    		}
    	}else{
    		return $mls_id;
    	}
    }

    function PropertyExist($datas, $model){ // model for replace property_id
    	if(!empty($datas)){

    		if(is_array($datas) && !empty($datas[0])){
    			foreach($datas AS $key => $data){
    				$mls_id = $this->RmCommon->filterEmptyField($data, 'Property', 'mls_id');
    				$mls_id = $this->checkMlsID($mls_id, 9, 'C');

                    $value = $this->controller->User->Property->find('first', array(
                        'conditions' => array(
                            'Property.mls_id' => $mls_id,
                        ),
                    ));

                    $data['Property']['mls_id'] = $mls_id;
                    $data = $this->RmCommon->dataConverter2($data, array(
                        'unset' => array(
                            'Property' => array(
                                'id',
                                'PropertyAddress' => array(
                                    'id',
                                    'property_id',
                                ),
                                'PropertyAsset' => array(
                                    'id',
                                    'property_id',
                                ),
                                'PropertyMedias' => array(
                                    0 => array(
                                        'PropertyMedias' => array(
                                            'id',
                                            'property_id',
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ));
                    if(!empty($value)){
                        $data['Property']['id'] = $this->RmCommon->filterEmptyField($value, 'Property', 'id');
                    }

                    $result = $this->controller->User->Property->doSaveAll($data, false);       
                    $this->RmCommon->setProcessParams($result, false, array(
                        'noRedirect' => true,
                        'flash' => false,
                    ));

                    $property_id = $this->RmCommon->filterEmptyField($result, 'id');

                    $data[$model]['property_id'] = $property_id;
                    $data[$model]['mls_id'] = $mls_id;
                    unset($data['Property']);
                    $datas[$key] = $data;
    			}
    		}else{
    			$mls_id = $this->RmCommon->filterEmptyField($datas, 'Property', 'mls_id');
    			$mls_id = $this->checkMlsID($mls_id, 9, 'C');

				$value = $this->controller->User->Property->getData('first', array(
					'conditions' => array(
						'Property.mls_id' => $mls_id,
					),
				));

                $datas['Property']['mls_id'] = $mls_id;
                $datas = $this->RmCommon->dataConverter2($datas, array(
                    'unset' => array(
                        'Property' => array(
                            'id',
                            'PropertyAddress' => array(
                                'id',
                            ),
                            'PropertyAsset' => array(
                                'id',
                            ),
                            'PropertyMedias' => array(
                                0 => array(
                                    'PropertyMedias' => array(
                                        'id',
                                    ),
                                ),
                            ),
                        ),
                    )
                ));

                if(!empty($value)){
                    $datas['Property']['id'] = $this->RmCommon->filterEmptyField($value, 'Property', 'id');
                }

                $result = $this->controller->User->Property->doSaveAll($datas, false);       
                $this->RmCommon->setProcessParams($result, false, array(
                    'noRedirect' => true,
                    'flash' => false,
                ));
                $property_id = $this->RmCommon->filterEmptyField($result, 'id');

                $datas[$model]['property_id'] = $property_id;
                $datas[$model]['mls_id'] = $mls_id;
                unset($datas['Property']);
    		}
    		return $datas;
    	}else{
    		return false;
    	}
    }
}
?>