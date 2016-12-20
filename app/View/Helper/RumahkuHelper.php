<?php
class RumahkuHelper extends AppHelper {
	var $helpers = array(
		'Time', 'Html', 'Form',
        'Paginator', 'Text', 'Number'
	);

	/**
	*
	*	filterisasi content tag
	*
	*	@param string string : string
	*	@return string
	*/
	function safeTagPrint($string){
		if( is_string($string) ) {
			return strip_tags($string);
		} else {
			return $string;
		}
	}

	/**
	*
	*	function format tanggal
	*	@param string $dateString : tanggal
	*	@param string $format : format tanggal
	*	@return string tanggal
	*/
	function formatDate($dateString, $format = false, $empty = '-') {
		if( empty($dateString) || $dateString == '0000-00-00' || $dateString == '0000-00-00 00:00:00') {
			return $empty;
		} else {
			if( !empty($format) ) {
				return date($format, strtotime($dateString));
			} else {
				return $this->Time->niceShort(strtotime($dateString));
			}
		}
	}

    function wrapTag( $tag = false, $content = false, $options = array() ) {
        $result = $content;
        if( !empty($tag) && !empty($content) ){
            $result = $this->Html->tag($tag, $content, $options);
        }
        return $result;
    }

    function default_status($status,$tagHtml = true){

        switch ($status) {

            case 'Pending' :
                $color = 'label pending';
                break;
            case 'Dibayarkan' :
                $color = 'color-green label approved';
                break;
            case 'Ditolak' :
                $color = 'color-green label rejected';
                break;

        }

        if($tagHtml){

            $status = $this->Html->tag('span', $status, array(
                    'class' => $color,
            ));

        }

        return $status;

    }

	function initializeMeta( $params ) {
		$title         = !empty($params['meta']['title_for_layout'])?$params['meta']['title_for_layout']:false;
		$description   = !empty($params['meta']['description_for_layout'])?$params['meta']['description_for_layout']:false;
		$keywords      = !empty($params['meta']['keywords_for_layout'])?$params['meta']['keywords_for_layout']:false;
        $favicon       = !empty($params['favicon']['logo'])?$params['favicon']['logo']:false;
        
		echo $this->Html->tag('title', $title).PHP_EOL;
		echo $this->Html->meta('description', $description).PHP_EOL;
		echo $this->Html->meta('keywords', $keywords).PHP_EOL;
        // echo $this->Html->meta('favicon.ico',$favicon,array('type' => 'icon'));
	}

	function icon($icon, $content = false, $tag = 'i', $addClass = false) {
		return $this->Html->tag($tag, $content, array(
			'class' => sprintf('%s %s', $icon, $addClass),
		));
	}

	/**
	*
	*	function membuat form radio button sesuai bootstrap
	*	@param string $name : name form input
	*	@param array $options : options form input
	*	@param string $add_options : class tambahan (optional)
	*	@param array $default_options : opsi tambahan
	*	@return string
	*/
	function bootstrap_radio( $name, $options = array(), $default = 0, $add_options = array(), $default_options = array() ) {
        if (isset($options['options']) && !empty($options['options'])) {
            $rc = "";
            $options_default = array(
            	'legend'=> false,
            	'label'=> false,
            	'required'=> false,
            	'value'=> $default,
            	'data-role' => 'none'
        	);

            if( !empty($default_options) ) {
            	$options_default = array_merge($options_default, $default_options);
            }

            foreach ($options['options'] as $key => $value) {
            	$checked = ($default == $key)?'checked':'';

            	$with_radio = 'radio';
            	if(isset($add_options['no_class_radio']) && $add_options['no_class_radio']){
            		$with_radio = '';
            	}

            	if(!empty($add_options['class'])){
            		$rc .= "<div class='".$with_radio." ".$add_options['class']."'><label class='radio-inline ".$checked."'>";
            	}else{
            		$rc .= "<div class='".$with_radio."'><label class='radio-inline ".$checked."'>";	
            	}
                
                $rc .= $this->Form->radio($name, array(
                	$key=>__($value),
            	), $options_default); 

                $rc .= "</label></div>";
            }
            
		    return($rc);
        }
        return(false);
    }

    function validDateBirth(){
    	$day = $year = array();
    	$monthname = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    	$yearcounter = date("Y") - 5;
        
    	for($i = $yearcounter; $i > 1900; $i--){
    		$year[$i] = $i;
    	}
    	for($i = 1; $i <= 12; $i++){
    		$month[$i] = $monthname[$i-1];
    	}
    	for($i = 1; $i <= 31; $i++){
    		$day[$i] = $i;
    	}

    	return array(
    		'day' => $day,
    		'month' => $month,
    		'year' => $year
    	);
    }

    function getGenerateAddress( $address, $option = array(), $separator = ', ', $empty = false ) {
        $fulladdress = '';

        if(!empty($option)){
            $rt = $this->filterEmptyField($option, 'rt');
            $rw = $this->filterEmptyField($option, 'rw');
            $region = $this->filterEmptyField($option, 'region');
            $city = $this->filterEmptyField($option, 'city');
            $subarea = $this->filterEmptyField($option, 'subarea');
            $zip = $this->filterEmptyField($option, 'zip');

        }

        if( !empty($address) ) {
            $fulladdress = $address;
        }

        if(!empty($rt) || !empty($rw)){
            $fulladdress .= $separator.sprintf(__('RT:%s RW:%s'), $rt, $rw);
        }

        if( !empty($subarea) ) {
            if( !empty($fulladdress) ) {
                $fulladdress .= $separator. $subarea;
            } else {
                $fulladdress .= $subarea;
            }
        }
        if( !empty($city) ) {
            $fulladdress .= $separator. $city;
        }
        if( !empty($region) ) {
            $fulladdress .= $separator . $region . ' ' . $zip;
        }

        $fulladdress = trim($fulladdress);

        if( !empty($fulladdress) ) {
            return $fulladdress;
        } else {
            return $empty;
        }
    }

    function filterEmptyField ( $value, $modelName, $fieldName = false, $empty = false, $removeHtml = true, $format = false ) {
        $result = '';
        
        if( empty($modelName) ) {
            $result = $empty;
        } else if( empty($fieldName) ) {
            $result = !empty($value[$modelName])?$value[$modelName]:$empty;
        } else {
            $result = !empty($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
        }

        if( !empty($removeHtml) && !is_array($result) ) {
            $result = $this->safeTagPrint($result);
        }

        if( !empty($result) && $result != $empty ) {
            if( is_array($format) ) {
                if( !empty($format['date']) ) {
                    $format = $format['date'];
                    $result = $this->formatDate($result, $format);
                } else if( !empty($format['wa']) ) {
                    $format = $format['wa'];
                    
                    if( !empty($format) ) {
                        $result = __('%s %s', $result, $this->Html->tag('small', __('(WA)'), array(
                            'title' => __('WhatsApp'),
                        )));
                    }
                } else if( !empty($format['text']) ) {
                    $format = $format['text'];
                    
                    if( !empty($format['limit']) ) {
                        $result = $this->truncate($result, $format['limit'], '...', false);
                    }
                    if( !empty($format['type']) ) {
                        switch ($format['type']) {
                            case 'EOL':
                                $result = $this->_callGetDescription($result);
                                break;
                        }
                    }
                }
            } else {
                switch ($format) {
                    case 'EOL':
                        $result = $this->_callGetDescription($result);
                        break;
                    case 'remove_space':
                        $result = str_replace(' ', '', $result);
                        break;
                    case 'year':
                        if( $result == '0000' ) {
                            $result = $empty;
                        }
                        break;
                    case 'json_encode':
                        $result = json_encode($result);
                        break;
                }
            }
        }

        if( is_string($result) ) {
            $result = trim($result);
        }

        return $result;
    }

    function buildInputMultiple ($fieldName1, $fieldName2, $options = false) {
        $default_options = array(
            'fieldName1' => $fieldName1,
            'fieldName2' => $fieldName2,
            'label' => false,
            'inputClass' => false,
            'inputClass2' => false,
            'labelDivClass' => 'col-sm-4 col-xl-2 taright',
            'class' => 'col-xs-5 col-sm-3 col-xl-2',
            'divider' => 'rv4-cross fs085',
            'dividerClass' => 'col-xs-1 col-sm-1',
            'frameClass' => 'col-sm-8',
            'textGroup' => false,
            'div' => false,
            'required' => false,
        );

        if( !empty($options) ) {
            $default_options = array_merge($default_options, $options);
        }

        return $this->_View->element('blocks/common/forms/input_multiple', $default_options);
    }

    function buildForm ( $fieldName, $fieldLabel, $options = array(), $position = 'vertical' ) {
    	$result = '';
    	$labelText = false;
    	$fieldDiv = false;
    	$id_form = $this->filterEmptyField($options, 'id');
    	$size = $this->filterEmptyField($options, 'size');
    	$type = $this->filterEmptyField($options, 'type');
    	$error = $this->filterEmptyField($options, 'error', false, true);
    	$_options = $this->filterEmptyField($options, 'options');
    	$description = $this->filterEmptyField($options, 'description');
    	$empty = $this->filterEmptyField($options, 'empty');
        $readonly = $this->filterEmptyField($options, 'readonly');
        $placeholder = $this->filterEmptyField($options, 'placeholder');
        $addClass = $this->filterEmptyField($options, 'class');
        $frameSize = $this->filterEmptyField($options, 'frame-size');
        $overflowText = $this->filterEmptyField($options, 'overflow-text');

        switch ($frameSize) {
            case 'large':
                $frameClass = $this->filterEmptyField($options, 'frame-class', false, 'col-sm-12');
                $frameLabelClass = $this->filterEmptyField($options, 'frame-label-class', false, 'col-sm-3');
                break;

            case 'large':
                $frameClass = $this->filterEmptyField($options, 'frame-class', false, 'col-sm-6');
                $frameLabelClass = $this->filterEmptyField($options, 'frame-label-class', false, 'col-sm-3');
                break;
            
            default:
                $frameClass = $this->filterEmptyField($options, 'frame-class', false, 'col-sm-8');
                $frameLabelClass = $this->filterEmptyField($options, 'frame-label-class', false, 'col-sm-4');
                break;
        }

    	$classSize = false;

        switch ($size) {
        	case 'small':
        		$classSize = 'input-group col-sm-3 col-xl-3';
        		break;

        	case 'medium':
        		$classSize = ' col-sm-5 col-xl-3';
        		break;
        }

    	switch ($position) {
    		case 'horizontal':
    			$result .= $this->Form->label($fieldName, $fieldLabel, array(
		            'class' => 'col-xl-1 taright '.$frameLabelClass,
		        ));
    			$classSize = $this->filterEmptyField($classSize, false, false, 'col-sm-7 col-xl-3');

    			$fieldDiv = array(
		            'class' => 'relative '.$classSize,
		        );
    			break;
    		
    		default:
    			$labelText = $fieldLabel;
    			break;
    	}

		$default_options = array(
	        'id' => $id_form,
	        'label' => $labelText,
            'required' => false,
	        'div' => $fieldDiv,
	        'empty' => $empty,
            'readonly' => $readonly,
            'placeholder' => $placeholder,
	        'class' => 'form-control '.$addClass,
	    );

	    if( !empty($type) ) {
	    	if( $type == 'checkbox' ) {
                $default_options['class'] = '';
	    		$default_options['div'] = false;
	    	}

	    	$default_options['type'] = $type;
	    }

    	if( !is_array($options) ) {
    		$default_options = array_merge_recursive($default_options, $options);
    	}

        if( !empty($description) ) {
            $description = $this->Html->tag('small', $description, array(
                'class' => 'extra-text',
            ));
        }

        if( !empty($overflowText) ) {
            $overflowText = $this->Html->tag('div', $overflowText, array(
                'class' => 'overflow-text',
            ));
        }

    	switch ($type) {
    		case 'radio':
    			$inputContent = $this->_View->element('blocks/common/forms/multiple_radio', array(
                    'options' => $_options,
                    'fieldName' => $fieldName,
                    'error' => $error,
                    'label' => $labelText,
                ));

        		if( $position == 'horizontal' ) {
	    			$result =  $this->Html->tag('div', $result.$this->Html->tag('div', $inputContent, array(
			    		'class' => 'relative '.$classSize,
					)), array(
			    		'class' => 'form-group',
					));
	    		} else {
	    			$result =  $this->Html->tag('div', $inputContent, array(
			    		'class' => 'form-group',
					));
	    		}
    			break;
    		
    		default:
                if( !empty($_options) ) {
                    $default_options['options'] = $_options;
                }

    			if( !empty($fieldDiv) && !empty($description) ) {
    				$default_options['div'] = false;
    				$inputContent = $this->Html->tag('div', $this->Form->input($fieldName, $default_options).$description.$overflowText, array(
						'class' => $fieldDiv,
					));
    			} else {
    				$inputContent = $this->Form->input($fieldName, $default_options).$description.$overflowText;
    			}

                if( $type == 'checkbox' && !empty($fieldDiv['class']) ) {
                    $inputContent = $this->Html->tag('div', $this->Html->tag('div', $inputContent.$this->Html->tag('div', '', array(
                        'class' => 'rku-checkbox',
                    )), array(
                        'class' => 'cb-custom cb-checkbox cb-checkmark relative',
                    )), array(
                        'class' => $fieldDiv['class'],
                    ));
                }

                switch ($position) {
                    case 'horizontal':
                        $result =  $this->Html->tag('div', $this->Html->tag('div', $this->Html->tag('div', $this->Html->tag('div', $result.$inputContent, array(
                            'class' => 'row',
                        )), array(
                            'class' => $frameClass,
                        )), array(
                            'class' => 'row',
                        )), array(
                            'class' => 'form-group',
                        ));
                        break;
                    
                    default:
                        $result =  $this->Html->tag('div', $result.$inputContent, array(
                            'class' => 'form-group',
                        ));
                        break;
                }
    			break;
    	}

        if( $position == 'vertical' && !empty($classSize) ) {
        	$result = $this->Html->tag('div', $this->Html->tag('div', $result, array(
        		'class' => 'relative '.$classSize,
    		)), array(
        		'class' => 'row',
    		));
        }

        return $result;
    }

    function buildInputToggle ($fieldName, $options = false) {
        $default_options = array(
            'fieldName' => $fieldName,
            'label' => false,
            'labelClass' => 'col-xl-2 taright col-sm-2',
            'class' => 'col-sm-7 col-xl-4',
            'div' => false,
            'required' => false,
            'frameClass' => 'col-sm-12',
            'data_toggle' => 'toggle',
            'data_width' => '100%',
            'data_height' => '30px',
            'attributes' => array(),
            'infoText' => ''
        );

        if( !empty($options) ) {
            $default_options = array_merge($default_options, $options);
        }

        return $this->_View->element('blocks/common/forms/input_toggle', $default_options);
    }

    function _setFormAddressArr($modelName, $options = array()){
        $type = $this->filterEmptyField($options, 'type');
        $idx = isset($options['key'])?$options['key']:null;
        $aditionals = $this->filterEmptyField($options, 'aditionals');
        $currRegionID = $this->filterEmptyField($options, 'currRegionID');
        $currCityID = $this->filterEmptyField($options, 'currCityID');
        $result = '';

        if( !empty($this->request->data[$modelName][$idx]['region_id']) ) {
            $currRegionID = $this->safeTagPrint($this->request->data[$modelName][$idx]['region_id']);
        } else if( !empty($this->request->data[$modelName][$idx]['region']) ) {
            $currRegionID = $this->safeTagPrint($this->request->data[$modelName][$idx]['region']);
        }

        if( !empty($this->request->data[$modelName][$idx]['city_id']) ) {
            $currCityID = $this->safeTagPrint($this->request->data[$modelName][$idx]['city_id']);
        } else if( !empty($this->request->data[$modelName][$idx]['city']) ) {
            $currCityID = $this->safeTagPrint($this->request->data[$modelName][$idx]['city']);
        }

        if( $type == 'all' || $type == 'areas' ) {
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'current_region_id'), array(
                'id' => sprintf('currRegionID%s', $aditionals),
                'value' => $currRegionID
            ));
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'current_city_id'), array(
                'id' => sprintf('currCityID%s', $aditionals),
                'value' => $currCityID
            ));
        }

        if( $type == 'all' || $type == 'locations' ) {
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'latitude'), array(
                'id'=>'rku-latitude', 
            ));
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'longitude'), array(
                'id'=>'rku-longitude', 
            ));
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'location'), array(
                'id'=>'rku-location', 
            ));
            $result .= $this->Form->hidden(sprintf('%s.%s.%s',$modelName, $idx, 'dragend'), array(
                'id'=>'rku-dragend', 
            ));
        }
        return $result;
    }

    function _setFormAddress($modelName, $options = array()){
        $type = $this->filterEmptyField($options, 'type');
        $aditionals = $this->filterEmptyField($options, 'aditionals');
        $currRegionID = $this->filterEmptyField($options, 'currRegionID');
        $currCityID = $this->filterEmptyField($options, 'currCityID');
        $result = '';

        if( !empty($this->request->data[$modelName]['region_id']) ) {
            $currRegionID = $this->safeTagPrint($this->request->data[$modelName]['region_id']);
        } else if( !empty($this->request->data[$modelName]['region']) ) {
            $currRegionID = $this->safeTagPrint($this->request->data[$modelName]['region']);
        }

        if( !empty($this->request->data[$modelName]['city_id']) ) {
            $currCityID = $this->safeTagPrint($this->request->data[$modelName]['city_id']);
        } else if( !empty($this->request->data[$modelName]['city']) ) {
            $currCityID = $this->safeTagPrint($this->request->data[$modelName]['city']);
        }

        if( $type == 'all' || $type == 'areas' ) {
            $result .= $this->Form->hidden($modelName.'.current_region_id', array(
                'id' => sprintf('currRegionID%s', $aditionals),
                'value' => $currRegionID
            ));
            $result .= $this->Form->hidden($modelName.'.current_city_id', array(
                'id' => sprintf('currCityID%s', $aditionals),
                'value' => $currCityID
            ));
        }
        if( $type == 'all' || $type == 'locations' ) {
            $result .= $this->Form->hidden($modelName.'.latitude', array(
                'id'=>'rku-latitude', 
            ));
            $result .= $this->Form->hidden($modelName.'.longitude', array(
                'id'=>'rku-longitude', 
            ));
            $result .= $this->Form->hidden($modelName.'.location', array(
                'id'=>'rku-location', 
            ));
            $result .= $this->Form->hidden($modelName.'.dragend', array(
                'id'=>'rku-dragend', 
            ));
        }
        return $result;
    }

    function setFormAddress ( $modelName = false, $type = 'all' , $options = array()) {
        $currRegionID = false;
        $currCityID = false;
        $result = '';
        $aditionals = $this->filterEmptyField($options, 'aditionals');

        if(!empty($this->data[$modelName][0])){
            foreach($this->data[$modelName] AS $key => $value){
                if($key > 0){
                    $aditionals = $key;
                }
                $result .= $this->_setFormAddressArr($modelName, array(
                    'type' => $type,
                    'key' => $key,
                    'aditionals' => $aditionals,
                    'currRegionID' => $currRegionID,
                    'currCityID' => $currCityID,
                ));
            }
        }else{
            $result = $this->_setFormAddress($modelName, array(
                'type' => $type,
                'aditionals' => $aditionals,
                'currRegionID' => $currRegionID,
                'currCityID' => $currCityID,
            ));
        }
        return $result;
    }

    function year($fieldName, $minYear = null, $maxYear = null, $selected = null, $attributes = array(), $tipe = '') {
        $attributes += array('empty' => true);
        if ((empty($selected) || $selected === true) && $value = $this->Form->value($fieldName)) {
            if (is_array($value)) {
                extract($value);
                $selected = $year;
            } else {
                if (empty($value)) {
                    if (!$attributes['empty'] && !$maxYear) {
                        $selected = 'now';

                    } elseif (!$attributes['empty'] && $maxYear && !$selected) {
                        $selected = $maxYear;
                    }
                } else {
                    $selected = $value;
                }
            }
        }

        if (strlen($selected) > 4 || $selected === 'now') {
            $selected = date('Y', strtotime($selected));
        } elseif ($selected === false) {
            $selected = null;
        }
        $yearOptions = array('min' => $minYear, 'max' => $maxYear, 'order' => 'desc');
        if (isset($attributes['orderYear'])) {
            $yearOptions['order'] = $attributes['orderYear'];
            unset($attributes['orderYear']);
        }
        $attributes['data-role'] = 'none';
        return $this->Form->select($fieldName.( ($tipe == 'year_built') ? '' : '.year'), $this->Form->_generateOptions('year', $yearOptions) ,
            $attributes, $selected
        );
    }

    function getSorting ( $model = false,  $label = false, $is_print = false ) {
        $named = $this->params['named'];
        
        if( !empty($model) && $this->Paginator->hasPage() && empty($is_print) ) {
            return $this->Paginator->sort($model, $label, array(
                'escape' => false
            ));
        } else {
            return $label;
        }
    }

    function _allowShowColumn ( $modelName, $fieldName ) {
        $_allowShow = isset($this->request->data[$modelName][$fieldName])?$this->request->data[$modelName][$fieldName]:true;
        $result = true;

        if( empty($_allowShow) ) {
            $result = false;
        }

        return $result;
    }

    function _generateShowHideColumn ( $dataColumns, $data_type, $is_print = false, $options = false ) {
        $result = false;
        // Global Attribut
        $_class = !empty($options['class'])?$options['class']:false;
        $_style = !empty($options['style'])?$options['style']:false;

        if( !empty($dataColumns) ) {
            $childArr = array();

            foreach ($dataColumns as $key_field => $dataColumn) {
                $field_model = !empty($dataColumn['field_model'])?$dataColumn['field_model']:false;
                // Get Data Model
                $data_model = explode('.', $field_model);
                $data_model = array_filter($data_model);
                if( !empty($data_model) ) {
                    list($modelName, $fieldName) = $data_model;
                } else {
                    $modelName = false;
                    $fieldName = false;
                }

                $style = !empty($dataColumn['style'])?$dataColumn['style']:false;
                $name = !empty($dataColumn['name'])?$dataColumn['name']:false;
                $display = isset($dataColumn['display'])?$dataColumn['display']:true;
                $child = !empty($dataColumn['child'])?$dataColumn['child']:false;
                $rowspan = !empty($dataColumn['rowspan'])?$dataColumn['rowspan']:false;
                $class = !empty($dataColumn['class'])?$dataColumn['class']:false;
                $fix_column = !empty($dataColumn['fix_column'])?$dataColumn['fix_column']:false;
                $data_options = !empty($dataColumn['data-options'])?$dataColumn['data-options']:false;
                $align = !empty($dataColumn['align'])?$dataColumn['align']:false;
                $content = false;

                if( !empty($display) ) {
                    $checked = true;
                    $addClass = '';
                } else {
                    $checked = false;
                    $addClass = 'hide';
                }

                switch ($data_type) {
                    case 'show-hide':
                        $checkbox = $this->Form->checkbox($field_model, array(
                            'data-field' => $key_field,
                            'checked' => $checked,
                        ));
                        $content = $this->Html->tag('li', $this->Html->tag('div', $this->Html->tag('label', $checkbox.$name), array(
                            'class' => 'checkbox',
                        )));
                        break;
                    
                    default:
                        // Set Allow Show Column
                        $allowShow = $this->_allowShowColumn($modelName, $fieldName);

                        if( !empty($allowShow) ) {
                            // Colspan
                            if( !empty($child) ) {
                                $colspan = count($child);
                            } else {
                                $colspan = false;
                            }

                            if( !empty($is_print) ) {
                                $data_options = false;
                            }

                            $content = $this->Html->tag('th', $this->getSorting($field_model, $name, $is_print), array(
                                'class' => sprintf('%s %s %s %s', $addClass, $key_field, $class, $_class),
                                'style' => $style,
                                'colspan' => $colspan,
                                'rowspan' => $rowspan,
                                'data-options' => $data_options,
                                'align' => $align,
                            ));

                            if( $fix_column && empty($is_print) ) {
                                $content .= '</tr></thead><thead><tr>';
                            }

                            // Append Child
                            if( !empty($child) ) {
                                $childArr[] = $this->_generateShowHideColumn( $child, $data_type, $is_print, $options );
                            }
                        }

                        break;
                }

                if( !empty($content) ) {
                    $result[] = $content;
                }
            }
        }

        if( is_array($result) ) {
            if( !empty($childArr) && is_array($childArr) ) {
                $result_child = implode('', $childArr);
                $result_child = '</tr><tr style="'.$_style.'">'.$result_child;
                $result[] = $result_child;
            }

            $result = implode('', $result);
        }

        $result = is_array($result)?implode('', $result):$result;

        return $result;
    }

    function _callGetExt ( $file = false ) {
        $fileArr = explode('.', $file);
        return end($fileArr);
    }

    function _getContentType ( $ext = false ) {
        $default_mime = array(
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg', 
            'jpeg' => 'image/jpeg', 
            'png' => 'image/png',
            'pjpeg' => 'image/pjpeg',
            'x-png' => 'image/x-png',
            'pdf' => 'application/pdf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        if( !empty($ext) ) {
            if( !empty($default_mime[$ext]) ) {
                return $default_mime[$ext];
            } else {
                return 'application/octet-stream';
            }
        } else {
            return $default_mime;
        }
    }

    public function photo_thumbnail($options = array(), $parameters = array() ) {
        if( !empty($options['user_path']) && $options['user_path'] == true ) {
            $defaultSize = 'ps';
        } else {
            $defaultSize = 's';
        }

        $thumb = isset($options['thumb'])?$options['thumb']:true;
        $url = isset($options['url'])?$options['url']:false;
        $dimension  = isset($options['size'])?$options['size']:$defaultSize;
        $save_path = isset($options['save_path'])?$options['save_path']:false;
        $src = !empty($options['src'])?$options['src']:false;
        $fullbase = !empty($options['fullbase'])?$options['fullbase']:false;

        $src = ( substr($src, 0, 1) != '/' )?'/'.$src:$src;
        $src = !empty($options['project_path'])?sprintf('/%s%s',$options['project_path'], $src):$options['src'];

        $cache_view_path = Configure::read('__Site.cache_view_path');
        $fullsize = Configure::read('__Site.fullsize');
        $thumbnailPath = sprintf('/%s/%s%s', $save_path, $dimension, $src);
        $fullPath = sprintf('/%s/%s%s', $save_path, $fullsize, $src);

        $extension = $this->_callGetExt($src);

        if( $thumb ) {
            $srcImg = Configure::read('__Site.images_view').$thumbnailPath;
        } else {
            $srcImg = Configure::read('__Site.images_view').$fullPath;
            $dimension = $fullsize;
        }

        if( in_array($extension, array('pdf', 'xls', 'xlsx')) ) {
            if( $extension == 'pdf' ) {
                $url_photo = '/img/pdf.png';
            } else {
                $url_photo = '/img/excel.png';
            }
        } else if( !empty($src) ) {
            if( substr($src, 0, 4) != 'http' ) {
                if( !empty($src) && $src != '/' ) {
                    $thumbnail = array(
                        'src' => sprintf('%s%s', $cache_view_path, $srcImg),
                    );
                } else {
                    $thumbnail['src'] = sprintf('%s/errors/error_%s.jpg', $cache_view_path, $dimension);
                }
            } else {
                $thumbnail = array(
                    'src' => $src,
                );
            }
           
            $photoname = $thumbnail['src'];
            $url_photo = Configure::read('__Site.img_path_http').$photoname;
        } else {
            $url_photo = sprintf('%s/errors/error_%s.jpg', $cache_view_path, $dimension);
        }

        if( !empty($fullbase) ) {
            if( $fullbase == true ) {
                $url_photo = FULL_BASE_URL.$url_photo;
            } else {
                $url_photo = $fullbase.$url_photo;
            }
        }

        if( $url ) {
            return $url_photo;
        } else {            
            return $this->Html->image($url_photo, $parameters);
        }
    }

    function setFormBirthdate ( $modelName = 'UserProfile', $options = array('day','month', 'year') ) {

        $default_attribute = array(
            'label' => false,
            'div' => array(
                'class' => 'col-md-4'
            ),
            'required' => false,
            'error' => false,
            'class' => 'form-control',
        );

        $optionBirth = $this->validDateBirth();
        $result = '';

        if( in_array("day", $options) ) {
            $default_attribute['empty'] = 'Tanggal';
            $default_attribute['options'] = $optionBirth['day'];
            $result .= $this->Form->input($modelName.'.day_birth', $default_attribute);
        }

        if( in_array("month", $options) ) {
            $default_attribute['empty'] = 'Bulan';
            $default_attribute['options'] = $optionBirth['month'];
            $result .= $this->Form->input($modelName.'.month_birth', $default_attribute);
        }

        if( in_array("year", $options) ) {
            $default_attribute['empty'] = 'Tahun';
            $default_attribute['options'] = $optionBirth['year'];
            $result .= $this->Form->input($modelName.'.year_birth', $default_attribute);
        }

        return $result;
    }

    function customDate($dateString, $format = 'd F Y') {
        return date($format, strtotime($dateString));
    }

    function _callActiveMenu ( $lblActive, $lblTag ) {
        $lblTag = strtolower($lblTag);

        if( $lblActive == $lblTag ) {
            return false;
        } else {
            return 'collapse';
        }
    }

    function link($text, $url, $options = false, $alert = false) {
        $_icon = $this->filterEmptyField($options, 'data-icon');
        $_wrapper = $this->filterEmptyField($options, 'data-wrapper');
        $_wrapper_options = $this->filterEmptyField($options, 'data-wrapper-options');
        $_lbl_active = $this->filterEmptyField($options, 'data-active');
        $_caret = $this->filterEmptyField($options, 'data-caret');

        $_add_class = !empty($options['class'])?$options['class']:false;
        $_tolower_text = strtolower($text);

        if( !empty($_wrapper) ) {
            $default_wrapper_options = false;

            if( !empty($_wrapper_options) ) {
                $default_wrapper_options = $_wrapper_options;
            }

            $text = $this->Html->tag($_wrapper, $text, $default_wrapper_options);
        }
        if( !empty($_icon) ) {
            $text = sprintf('%s %s', $this->icon($_icon), $text);
            $options['escape'] = false;

            unset($options['data-icon']);
        }
        if( $_lbl_active == $_tolower_text ) {
            $_add_class .= ' active';
            $options['class'] = $_add_class;

            if( isset($options['aria-expanded']) ) {
                $options['aria-expanded'] = 'true';
            }
            if( isset($options['class']) ) {
                $options['class'] = str_replace('collapsed', '', $options['class']);
            }
        }

        if( !empty($_caret) ) {
            $text .= $this->Html->tag('span', '', array(
                'class' => 'caret',
            ));
        }

        return $this->Html->link($text, $url, $options, $alert);
    }

    function truncate( $str, $len, $ending = '...', $stripTag = true ) {
        $str = trim($str);

        if( !empty($stripTag) ) {
            $str = $this->safeTagPrint($str);
        }

        return $this->Text->truncate($str, $len, array(
            'ending' => $ending,
            'exact' => false
        ));
    }

    function _callGreetingDate () {
        $hour = date("H"); 

        if ( $hour > 00 && $hour < 10 ){ 
            $lblDay = 'Pagi'; 
        }else if ($hour >= 10  && $hour < 15 ){ 
            $lblDay = 'Siang'; 
        }else if ($hour >= 15 &&  $hour < 18 ){ 
            $lblDay = 'Sore'; 
        }else if ($hour >= 18 &&  $hour <= 24 ){ 
            $lblDay = 'Malam'; 
        }else { 
            $lblDay = ''; 
        } 
        
        return $lblDay;
    }

    function get_greeting_by_time(){
        $lblDay = $this->_callGreetingDate();
        return sprintf('Selamat %s!', $lblDay);
    }

    function buildInputRadio( $name, $options = array(), $attributes = array(), $default = 0 ) {
        $default_attributes = array(
            'fieldName' => $name,
            'options' => $options,
            'labelClass' => 'col-xl-1 col-sm-3',
            'class' => 'col-sm-8 col-xl-7',
            'styling' => 'line',
            'frameClass' => 'col-sm-7',
        );

        if( !empty($attributes) ) {
            $default_attributes = array_merge($default_attributes, $attributes);
        }

        return $this->_View->element('blocks/common/forms/input_radio', $default_attributes);
    }

    function buildInputForm ($fieldName, $options = false) {
        $default_options = array(
            'fieldName' => $fieldName,
            'label' => false,
            'frameClass' => 'col-sm-8',
            'labelClass' => 'col-sm-4 col-xl-1 taright',
            'class' => 'input-group col-sm-3 col-xl-1',
            'div' => false,
            'required' => false,
            'placeholder' => false,
            'textGroup' => false,
            'options' => false,
            'disabled' => false,
        );

        if( !empty($options) ) {
            $default_options = array_merge($default_options, $options);
        }

        return $this->_View->element('blocks/common/forms/input_form', $default_options);
    }

    function noticeInfo($desc, $title = false, $options = array(), $icon = 'rv4-shortip'){
        $icon = $this->icon($icon);

        $options['data-placement'] = $this->filterEmptyField($options, 'data-placement', false, 'right');

        $content = '';
        if(!empty($desc)){
            $options = array_merge(array(
                'class' => 'static-modal',
                'data-toggle' => 'popover',
                'data-content' => $desc,
                'title' => $title,
                'escape' => false
            ), $options);

            $content = $this->Html->tag('span', $this->Html->link($icon, 'javascript:void(0)', $options), array(
                'class' => 'notice-static-modal'
            ));
        }

        return $content;
    }

    function buildText ($options = false) {
        $default_options = array(
            'label' => false,
            'frameClass' => 'col-sm-8',
            'labelClass' => 'col-sm-4 col-xl-1 taright',
            'class' => 'input-group col-sm-3 col-xl-1',
            'disabled' => false,
        );

        if( !empty($options) ) {
            $default_options = array_merge($default_options, $options);
        }

        return $this->_View->element('blocks/common/forms/format_text', $default_options);
    }

    function buildButton ( $data, $frameClass = false, $btnClass = false ) {
        $text = $this->filterEmptyField($data, 'text', false, false, false);
        $url = $this->filterEmptyField($data, 'url');
        $alert = $this->filterEmptyField($data, 'alert');

        $options = $this->filterEmptyField($data, 'options');
        $options['escape'] = false;

        if( !empty($btnClass) ) {
            $options['class'] = !empty($options['class'])?$options['class']:false;
            $options['class'] .= ' '.$btnClass;
        }

        return $this->Html->tag('div', $this->Html->link($text, $url, $options, $alert), array(
            'class' => $frameClass,
        ));
    }

    function getPathPhoto ( $path, $size, $save_path, $filename ){
        $file = $path.DS.$save_path.DS.$size.$filename;
        $file = str_replace('/', DS, $file);

        return $file;
    }

    function _isAdmin ( $group_id = false ) {
        $admin_id = Configure::read('__Site.Admin.List.id');
        
        if( empty($group_id) ) {
            $group_id = Configure::read('User.group_id');
        }

        if( in_array($group_id, $admin_id) ) {
            return true;
        } else {
            return false;
        }
    }

    function _callUserFullName($user, $link = true, $options = false) {
        $user = $this->mergeUser($user);

        $full_name = $this->filterEmptyField($user, 'full_name');

        if( empty($full_name) ) {
            $full_name = $this->filterEmptyField($user, 'first_name');
            $last_name = $this->filterEmptyField($user, 'last_name');

            if( !empty($last_name) ) {
                $full_name = sprintf('%s %s', $full_name, $last_name);
                $full_name = trim($full_name);
            }
        }

        $full_name = ucwords($full_name);

        if( !empty($link) ) {
            $id = $this->filterEmptyField($user, 'id');
            $username = $this->filterEmptyField($user, 'username');

            return $this->Html->link($full_name, array(
                'controller'=>'users', 
                'action'=>'profile', 
                $id, 
                $username, 
                'admin' => true
            ), $options);
        } else {
            return $full_name;   
        }
    }

    function _callStatusChecked ( $status, $text = false ) {
        if( !empty($status) ) {
            if( !empty($text) ) {
                $labelIcon = $this->Html->tag('span', __('Ya'), array(
                    'class' => 'color-green',
                ));
            } else {
                $labelIcon = $this->icon('rv4-check no-margin', false, 'i', 'color-green');
            }
        } else {
            if( !empty($text) ) {
                $labelIcon = $this->Html->tag('span', __('Tidak'), array(
                    'class' => 'color-red',
                ));
            } else {
                $labelIcon = $this->icon('rv4-cross no-margin', false, 'i', 'color-red');
            }
        }

        return $this->Html->tag('span', $labelIcon, array(
            'class' => 'status-label-checked',
        ));
    }

    function toSlug($string) {
        if( is_string($string) ) {
            return strtolower(Inflector::slug($string, '-'));
        } else {
            return $string;
        }
    }

    function getIndoDate($date, $type = 'day'){
        if($type == 'day'){
            $day_arr = array('minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu');
            $day = date('w', strtotime($date));

            return $day_arr[$day];
        }else{
            $day_month = array('januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember');
        
            $month = date('n', strtotime($date));

            return $day_month[$month-1];
        }
    }

    function _generateMenuSide ( $labelMenu, $icon, $caret, $active_menu, $tag, $contentLi ) {
        $classChild = $this->_callActiveMenu($active_menu, $labelMenu);
        $upperTag = strtoupper($tag);
        $tag = $this->toSlug($tag);

        $content = $this->Html->tag('div', $this->link($labelMenu, '#'.$tag, array(
            'data-active' => $active_menu,
            'data-wrapper' => 'span',
            'data-icon' => $icon,
            'data-caret' => $caret,
            'data-toggle' => 'collapse',
            'data-parent' => '#accordion',
            'aria-expanded' => 'false',
            'aria-controls' => $tag,
            'role' => 'button',
            'class' => 'tab collapsed',
        )), array(
            'id' => 'head'.$upperTag,
            'role' => 'tab',
        ));

        $optionsUl = array(
            'id' => $tag,
            'class' => $classChild,
            'role' => 'tabpanel',
            'aria-labelledby' => 'head'.$upperTag,
        );

        if( empty($classChild) ) {
            $optionsUl['aria-expanded'] = 'true';
            $optionsUl['class'] .= 'in';
        }

        $content .= $this->Html->tag('ul', $contentLi, $optionsUl);

        return $this->Html->tag('li', $content);
    }

    function _generateMenuTop ( $labelMenu, $icon, $caret, $contentLi ) {
        $content = $this->Html->tag('div', $this->link($labelMenu, '#', array(
            'data-wrapper' => 'span',
            'data-icon' => $icon,
            'data-caret' => $caret,
            'aria-expanded' => 'false',
            'aria-hashpopup' => 'true',
            'data-toggle' => 'dropdown',
            'class' => 'dropdown-toggle',
        )).$this->Html->tag('ul', $contentLi, array(
            'class' => 'dropdown-menu',
        )), array(
            'class' => 'btn-group',
        ));
        return $this->Html->tag('li', $content);
    }

    function _callBankBranchName($value) {
        $bank = $this->filterEmptyField($value, 'Bank', 'code');
        $branch = $this->filterEmptyField($value, 'BankBranch', 'name');

        return sprintf('(%s) %s', $bank, $branch);
    }

    function _rulesDimensionImage($directory_name, $data_type = false, $size_type = 'label'){
        $result = array();
        
        if( in_array($directory_name, array( 'logos', 'general_logo_photo' )) ) {
            if( $data_type == 'thumb' ) {
                if( $size_type == 'size' ) {
                    $result = '100x40';
                } else {
                    $result = 'xsm';
                }
            } else if( $data_type == 'large' ) {
                if( $size_type == 'size' ) {
                    $result = '240x96';
                } else {
                    $result = 'xxsm';
                }
            } else {
                $result = array(
                    'xsm' => '100x40',
                    'xm' => '165x165',
                    'xxsm' => '240x96'
                );
            }
        } else if( in_array($directory_name, array( 'users' )) ) {
            if( $data_type == 'thumb' ) {
                if( $size_type == 'size' ) {
                    $result = '100x100';
                } else {
                    $result = 'pm';
                }
            } else if( $data_type == 'large' ) {
                if( $size_type == 'size' ) {
                    $result = '300x300';
                } else {
                    $result = 'pxl';
                }
            } else {
                $result = array(
                    'ps' => '50x50',
                    'pm' => '100x100',
                    'pl' => '150x150',
                    'pxl' => '300x300',
                );
            }
        } else {
            if( $data_type == 'thumb' ) {
                if( $size_type == 'size' ) {
                    $result = '300x169';
                } else {
                    $result = 'm';
                }
            } else if( $data_type == 'large' ) {
                if( $size_type == 'size' ) {
                    $result = '855x481';
                } else {
                    $result = 'l';
                }
            } else {
                $result = array(
                    's' => '150x84',
                    'm' => '300x169',
                    'l' => '855x481'
                );
            }
        }

        return $result;
    }

    function getActiveStep ( $step, $current, $data = false, $id = false ) {
        $addClass = '';

        if( !empty($data) ) {
            $addClass = 'done';
        }

        if( $step == $current ) {
            $addClass = ' active';
        }

        return $addClass;
    }

    function getUrlStep ( $url, $current, $data, $id = false ) {

        if( !empty($data) || !empty($id) ) {
            return $url;
        } else {
            return '#';
        }
    }

    function creditFix($amount, $rate, $year=20){
        if(empty($rate)){
            return 0;
        }else{
            if( $rate != 0 ) {
                $rate = ($rate/100)/12;
            }

            $rateYear = pow((1+$rate), ($year*12));
            $rateMin = (pow((1+$rate), ($year*12))-1);

            if( $rateMin != 0 ) {
                $rateYear = $rateYear / $rateMin;
            }

            $mortgage = $rateYear*$amount*$rate;
            return $mortgage;   
    
        }
    }

    function getFormatPrice ($price, $empty = 0, $symbol = false) {
        if( !empty($price) ) {
            return $this->Number->currency($price, $symbol, array('places' => 0));
        } else {
            return $empty;
        }
    }

    function getCurrencyPrice ($price, $empty = false, $currency = false) {
        if( empty($currency) ) {
            $currency = Configure::read('__Site.config_currency_symbol');
            $currency = trim($currency);
        }

        if( !empty($empty) && empty($price) ) {
            return $empty;
        } else {
            return $this->Number->currency($price, $currency.' ', array('places' => 0));
        }
    }

    function getCombineDate ( $startDate, $endDate ) {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        if( !empty($startDate) && !empty($endDate) ) {
            if( $startDate == $endDate ) {
                $customDate = date('d M Y', $startDate);
            } else if( date('M Y', $startDate) == date('M Y', $endDate) ) {
                $customDate = sprintf('%s - %s', date('d', $startDate), date('d M Y', $endDate));
            } else if( date('Y', $startDate) == date('Y', $endDate) ) {
                $customDate = sprintf('%s - %s', date('d M', $startDate), date('d M Y', $endDate));
            } else {
                $customDate = sprintf('%s - %s', date('d M Y', $startDate), date('d M Y', $endDate));
            }
            return $customDate;
        }
        return false;
    }

    function mergeUser ( $user ) {
        if( !empty($user['User']) ) {
            $user = array_merge($user, $user['User']);
            unset($user['User']);
        }

        return $user;
    }

    function _getBungaKPRPersen ( $bunga_kpr = false ) {
        $bunga_kpr = !empty($bunga_kpr)?$bunga_kpr:Configure::read('__Site.bunga_kpr');
        return ( 100 - $bunga_kpr ) / 100;
    }

    function calcLoan ( $price, $bunga_kpr = false ) {
        $bunga_kpr_persen = $this->_getBungaKPRPersen( $bunga_kpr );

        return $price * $bunga_kpr_persen;
    }

    public function _callFormatPhoneNumber($phoneNumber) {

        if(strlen($phoneNumber) > 8) {
            $areaCode = substr($phoneNumber, 0, 4);
            $nextThree = substr($phoneNumber, 4, 4);
            $lastFour = substr($phoneNumber, 8, strlen($phoneNumber));
            $phoneNumber = $areaCode.' '.$nextThree.' '.$lastFour;
        }

        return $phoneNumber;
    }

    function urlExport ( $url, $type ) {
        $lastChar = substr($url, -1);

        if( $lastChar != '/' ) {
            $url .= '/';
        }

        $url .= 'export:'.$type;
        return $url;
    }

    function _callGetDescription ( $str, $replace = '<br>' ) {
        return str_replace(PHP_EOL, $replace, $str);
    }

    function _callPathKprApplication ( $is_id_company = false ) {
        if( !empty($is_id_company) ) {
            $save_path = Configure::read('__Site.crm_folder');
        } else {
            $save_path = Configure::read('__Site.general_logo_photo');
        }

        return $save_path;
    }

    function _callLbl ( $type, $label, $value, $value2 = false, $status = false) {
        $result = false;
        $content = '';

        $val = $this->Html->tag('span', $value);
        $val2 = null;
        if($status){
            $val2 = $this->Html->tag('span', $value2);
            $val2 = $this->Html->tag('div', $val2, array(
                    'class' => 'col-sm-3 color-blue',
            ));
        }


        switch ($type) {
            case 'row':
                $lbl = $this->Html->tag('label', $label);

                $content = $this->Html->tag('div', $lbl, array(
                    'class' => 'col-sm-4',
                ));
                $content .= $this->Html->tag('div', $val, array(
                    'class' => 'col-sm-3',
                ));
                $content .= $val2;

                $result = $this->Html->tag('div', $content, array(
                    'class' => 'row',
                ));
                break;
            
            case 'form-group':
                $lbl = $this->Html->tag('label', $label, array(
                    'class' => 'normal',
                ));
                
                $result = $this->Html->tag('div', $lbl.$val, array(
                    'class' => 'form-group',
                ));
                break;
            
            case 'table':
                $result = $this->Html->tag('td', $label, array(
                    'width' => '200',
                ));
                $result .= $this->Html->tag('td', $val);
                
                $result = $this->Html->tag('tr', $result);
                break;
        }

        return $result;
    }

    function setGroupField($fieldName, $options = array()){
        $category = $this->filterEmptyField( $options, 'category');
        $currency = $this->filterEmptyField( $options, 'currency');
        $showParams = ($category == 'percent')?"show":"hide";
        if(empty($currency)){
            $currency = Configure::read('__Site.config_currency_code');
        }

        if(!empty($category)){
            switch ($category) {
                case 'percent':
                    return $options = array(
                        'positionGroup' => 'right ',
                        'classGroup' => $fieldName,
                        'textGroup' => '%',
                        'classPrice' => 'input_number',
                        'showParams' => $showParams,
                    );
                    break;
                default :
                    return $options = array(
                        'positionGroup' => 'left ',
                        'classGroup' => $fieldName,
                        'textGroup' => $currency,
                        'classPrice' => 'input_price',
                        'showParams' => $showParams,
                    );
                    break;
            }
        }else{
            return array(
                'positionGroup' => 'left',
                'classGroup' => $fieldName,
                'textGroup' => $currency,
                'showParams' => $showParams,
            );
        }
    }

    function _callPeriodeYear ( $maxPeriode = 50, $text = 'Thn' ) {
        $year = array();

        for ($i=1; $i <= $maxPeriode; $i++) { 
            $label_text = $i.' '.$text;
            $label_text = trim($label_text);

            $year[$i] = $label_text;
        }

        return $year;
    }

    function wrapWithHttpLink( $url, $link = true, $options = array() ){
        $result = strtolower($url);
        $textUrl = 'http://';
        $textUrls = 'https://';
        $empty = $this->filterEmptyField($options, 'empty');

        if( !empty($url) ) {
            $flag = array();

            if( strpos($url, $textUrl) === false && substr($url, 0, 7) != $textUrl ) {
                $flag[] = true;
            }
            if( strpos($url, $textUrls) === false && substr($url, 0, 8) != $textUrls ) {
                $flag[] = true;
            }

            if( count($flag) == 2 ) {
                $url = sprintf("%s%s", $textUrl, $url);
            }
        } else {
            $url = $empty;
        }

        if( !empty($link) && !empty($url) ) {
            $result = $this->Html->link($url, $url, array(
                'target' => '_blank',
            ));
        } else {
            $result = $url;
        }

        return $result;
    }

    function _callUnset( $fieldArr, $data ) {
        if( !empty($fieldArr) ) {
            foreach ($fieldArr as $key => $value) {
                if( is_array($value) ) {
                    foreach ($value as $idx => $fieldName) {
                        if( isset($data[$key][$fieldName]) ) {
                            unset($data[$key][$fieldName]);
                        }
                    }
                } else {
                    unset($data[$value]);
                }
            }
        }

        return $data;
    }

    function buildCheckOption( $modelName, $id = false, $type = 'all', $is_checked = false, $default_class = 'check-option',$disabled = false ,$attribute = false, $options = array(), $label_options = array()) {
        if( empty($options) ) {
            $options = array();
        }
        if( empty($label_options) ) {
            $label_options = array();
        }

        if( !empty($is_checked) ) {
            $options['checked'] = true;
        }

        switch ($type) {
            case 'all':
                return $this->Html->tag('div', $this->Html->tag('div', $this->Form->checkbox($modelName.'.checkbox_all', array_merge($options, array(
                    'label' => false,
                    'class' => 'checkAll',
                    'div' => false,
                    'hiddenField' => false,
                ))).$this->Form->label($modelName.'.checkbox_all', '&nbsp;', $label_options), array(
                    'class' => 'cb-checkmark',
                )), array(
                    'class' => 'cb-custom mt0',
                ));
                break;
            
            default:
                return $this->Html->tag('div', $this->Html->tag('div', $this->Form->checkbox($modelName.'.id.'.$id, array_merge($options, array(
                    'class' => $default_class,
                    'value' => $id,
                    'div' => false,
                    'hiddenField' => false,
                    'disabled' => $disabled,
                    'add-attribute' => $attribute
                ))).$this->Form->label($modelName.'.id.'.$id, '&nbsp;', $label_options), array(
                    'class' => 'cb-checkmark',
                )), array(
                    'class' => 'cb-custom mt0',
                ));
                break;
        }
    }
}