<?php
App::uses('ModelBehavior', 'Model');

/**
 * Common Behavior.
 *
 * Enables a model to access some common function for manipulate data
 */
class CommonBehavior extends ModelBehavior {

	function toSlug(Model $model, $data, $fields = false, $glue = '-') {
		if( !empty($data) ) {
			if( !is_array($data) ) {
				$data = strtolower(Inflector::slug($data, $glue));
			} else {
				foreach ($fields as $key => $value) {
					if( is_array($value) ) {
						foreach ($value as $idx => $fieldName) {
							if( !empty($data[$key][$fieldName]) ) {
								$data[$key][$fieldName] = strtolower(Inflector::slug($data[$key][$fieldName], $glue));
							}
						}
					} else {
						$data[$value] = strtolower(Inflector::slug($data[$value], $glue));
					}
				}
			}
		}

		return $data;
	}

	function filterEmptyField(Model $model, $value, $modelName, $fieldName = false, $empty = false, $options = false){
		$type = !empty($options['type'])?$options['type']:'empty';
		$trim = isset($options['trim'])?$options['trim']:true;
		$addslashes = isset($options['addslashes'])?$options['addslashes']:false;
		$result = false;

		switch($type){
			case 'isset':
				if(empty($fieldName) && isset($value[$modelName])){
					$result = $value[$modelName];
				} else {
					$result = isset($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
				}
				break;
			
			default:
				if(empty($fieldName) && !empty($value[$modelName])){
					$result = $value[$modelName];
				} else {
					$result = !empty($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
				}
				break;
		}

		if( !empty($result) && is_string($result) ) {
			$result = urldecode($result);

			if( !empty($trim) ) {
				$result = trim($result);
			}

			if( !empty($addslashes) ) {
				$result = addslashes($result);
			}
		}

		return $result;
	}

	function filterIssetField (Model $model, $value, $modelName, $fieldName = false, $empty = false ) {
		$result = '';
		
		if( empty($modelName) && !is_numeric($modelName) ) {
			$result = isset($value)?$value:$empty;
		} else if( empty($fieldName) && !is_numeric($fieldName) ) {
			$result = isset($value[$modelName])?$value[$modelName]:$empty;
		} else {
			$result = isset($value[$modelName][$fieldName])?$value[$modelName][$fieldName]:$empty;
		}

		return $result;
	}

	function containString(Model $model, $needle, $haystack){

		if(is_array($needle)){
			$result = false;
			foreach ($needle as $key => $value) {
				$result = strpos($haystack, $value) !== false;
				
				if($result){
					break;
				}
			}

			return $result;
		}else{
			return strpos($haystack, $needle) !== false;
		}
	}

//	convert non sql formatted date / datetime to sql formatted date / datetime
	public function convertSQLDatetime(Model $model, $datetime = NULL, $default = NULL, $delimiter = '-'){
		$datetime = trim($datetime);

		if($datetime){
			$datetime	= explode(' ', $datetime);
			$date		= empty($datetime[0]) ? NULL : $datetime[0];
			$time		= empty($datetime[1]) ? NULL : $datetime[1];

			$date = preg_split("/(\/|-)/", $date);

			if(count($date) == 3){
				if(strlen($date[0]) == 4){
				//	YYYY-MM-DD
					$date = sprintf('%s%s%s%s%s', $date[0], $delimiter, $date[1], $delimiter, $date[2]);
				}
				else{
				//	DD-MM-YYYY
					$date = sprintf('%s%s%s%s%s', $date[2], $delimiter, $date[1], $delimiter, $date[0]);
				}

				$datetime = sprintf('%s %s', $date, $time);
			}
			else{
			//	invalid date
				$datetime = $default;
			}
		}
		else{
			$datetime = $default;
		}

		$datetime = trim($datetime);
		return $datetime;
	}

	// public function getMerge(model $model, $value, $id, $optionsParams){	
	// 	debug('masuk');die();
	// 	$fieldName = $this->filterEmptyField($model, '');

	// }

	public function callSet( Model $model, $data, $fieldArr ) {
		if( !empty($fieldArr) && !empty($data) ) {
			$data = array_intersect_key($data, array_flip($fieldArr));
		}
		return $data;
	}

	public function callUnset( Model $model, $data = false, $fieldArr = false ) {
		if( !empty($fieldArr) ) {
			foreach ($fieldArr as $key => $value) {

				if( is_array($value) ) {
					foreach ($value as $idx => $fieldNames) {

						if(is_array($fieldNames)){
							foreach ($fieldNames as $i => $fieldName) {
								$flag = isset($data[$key][$idx][$fieldName]);
								
								if( $flag || ($flag == null)) {
									unset($data[$key][$idx][$fieldName]);
								}	
							}
						}else{
							$flag = isset($data[$key][$fieldNames]);

							if( $flag || ($flag == null)) {
								unset($data[$key][$fieldNames]);
							}	
						}
					}
				} else {
					unset($data[$value]);
				}
			}
		}

		return $data;
	}

	public function getMerge( model $model, $data, $modelName,  $id = false, $options = array() ) {
		// $conditions = !empty($options['conditions'])?$options['conditions']:array();
		$options = !empty($options)?$options:array();
		$elements = !empty($options['elements'])?$options['elements']:array();
		$cache = !empty($options['cache'])?$options['cache']:array();
		$alias = $this->filterEmptyField($model, $options, 'uses');
		$uses = $this->filterEmptyField($model, $options, 'uses', false, $modelName);
		$foreignKey = !empty($options['foreignKey'])?$options['foreignKey']:'id';
		$primaryKey = !empty($options['primaryKey'])?$options['primaryKey']:$foreignKey;
		$position = !empty($options['position'])?$options['position']:'outside';
		$type = !empty($options['type'])?$options['type']:'first';
		$parentModelName = $model->name;

		$optionsModel = $this->callSet($model, $options, array(
			'conditions',
			// 'contain',
			'fields',
			'group',
			'limit',
			'order',
		));

		if(empty($data[$modelName])){
			if(!empty($uses)){
				if( $uses == $model->name ) {
					$model = $model;
				} else {
					$model = $model->$uses;
				}
			}else{
				$model = $model->$modelName;
			}

			$optionsModel['conditions'][sprintf('%s.%s', $uses, $primaryKey)] = $id;

			if( !empty($cache) ) {
				$cache_name = $this->filterEmptyField($model, $cache, 'name', false, $modelName);
				$cache_config = $this->filterEmptyField($model, $cache, 'config', false, 'default');
				
				$cache_name = __('%s.%s', $cache_name, $id);
				$value = Cache::read($cache_name, $cache_config);
				// debug($cache_name);

				if( empty($value) ) {
					$value = $model->getData($type, $optionsModel, $elements);
					
					Cache::write($cache_name, $value, $cache_config);
				}
			} else {
				$value = $model->getData($type, $optionsModel, $elements);
			}

			if(!empty($value)){
				switch ($type) {
					case 'count':
						$data[$modelName] = $value;
						break;
					case 'list':
						$data[$modelName] = $value;
						break;
					
					default:
						if(!empty($alias) ){
							if( !empty($value[$alias]) ) {
								$data[$modelName] = $value[$alias];
							} else if(!empty($value[0])){
								$data[$modelName] = $value;
							}
						}else{
							if(!empty($value[0])){
								$data[$modelName] = $value;
							}else{
								switch ($position) {
									case 'inside':
										if( !empty($parentModelName) ) {
											$parentDataModel = !empty($data[$parentModelName])?$data[$parentModelName]:array();
											$data[$parentModelName] = array_merge($parentDataModel, $value);
										} else {
											$data = array_merge($data, $value);
										}
										break;
									
									default:
										$data = array_merge($data, $value);
										break;
								}
							}
						}
						break;
				}
			}
		}	

		return $data;
	}

	function _callMergeData ( Model $model, $value, $element, $options, $modelName ) {
		$mergeParents = $this->filterEmptyField($model, $element, 'mergeParent', false, array());
		$generateMultiples = $this->filterEmptyField($model, $element, 'generateMultiple', false, array());

		if( !is_array($options) ) {
			$modelName = $uses = $options;
			$optionsParams = array();
		} else {
			$mergeParent = $this->filterEmptyField( $model, $options, 'modelParent');

			$options = $this->callUnset($model, $options, array(
				'modelParent',
			));

			$optionsParams = $options; ## CONDITIONS, ELEMENTS for getData 
			$uses = $this->filterEmptyField($model, $options, 'uses', false, $modelName);

			if( !empty($options) ) {
				$containRecursive = $this->filterEmptyField( $model, $options, 'contain');

				if( empty($containRecursive) && !empty($options[0]) ) {
					$containRecursive = $options;
				}
			}
		}

		$type = $this->filterEmptyField($model, $optionsParams, 'type');
		$forceMerge = $this->filterEmptyField($model, $optionsParams, 'forceMerge');

		if( !empty($mergeParent) ) {
			$modelParent = $this->filterEmptyField($model, $mergeParent, 0);
			$foreignKey = $this->filterEmptyField($model, $mergeParent, 1);
		} else {
			$modelParent = $model->name;
			$foreignKey = 'id';

			if( !empty($options['foreignKey']) && is_array($options) ) {
				$foreignKey = $options['foreignKey'];
			} else if( !empty($model->belongsTo[$uses]['foreignKey']) ) {
				$foreignKey = $model->belongsTo[$uses]['foreignKey'];
				$optionsParams = array_merge($optionsParams, array(
					'foreignKey' => $foreignKey,
					'primaryKey' => 'id',
				));
			} else if( !empty($model->hasOne[$uses]['foreignKey']) ) {
				$foreignKey = 'id';
				$optionsParams = array_merge($optionsParams, array(
					'primaryKey' => $model->hasOne[$uses]['foreignKey'],
					'foreignKey' => $foreignKey,
				));
			} else if( !empty($model->hasMany[$uses]['foreignKey']) ) {
				$primaryKey = $model->hasMany[$uses]['foreignKey'];
				$optionsParams = array_merge($optionsParams, array(
					'foreignKey' => $foreignKey,
					'primaryKey' => $primaryKey,
				));
				$type_custom = 'all';
			}

			if( empty($type) && !empty($type_custom) ) {
				$optionsParams['type'] = $type_custom;
			}
		}

		if( empty($value[$modelName]) || !empty($forceMerge) ){
			if( !empty($value[$modelName]) ) {
				$value = $this->callUnset($model, $value, array(
					$modelName,
				));
			}

			$id = $this->filterEmptyField( $model, $value, $modelParent, $foreignKey);

			if( empty($id) ) {
				$id = $this->filterEmptyField( $model, $value, $foreignKey);
			}

			if( !empty($id) ) {
				## MERGEDATA JIKA DATA YANG INGIN DI MERGE BERSIFAT JAMAK/MULTIPLE 
				## FUNGSI GETMERGE DI MODEL TERSEBUT HARUS DITAMBAHKAN PARAMETER KETIGA FIND = 'ALL/FIRST/ DLL'
				$value = $this->getMerge( $model, $value, $modelName, $id, $optionsParams);
				## KETIKA SUDAH DI BUILD DENGAN FUNGSI GETMERGE UNTUK DATA JAMAK HARUS
				## MODEL => INDEX => MODEL => VALUE, ANDA BISA UBAH DATA DENGAN generateMultiples ATAU mergeParents

				## KETIKA DATA MULTIPlE SUDAH DIBUILD dengan GENERATEMULTIPLE DIBAWAH INI, MENJADI MODEL => IDX => VALUE
				if(in_array( $modelName, $generateMultiples)){
					if(!empty($value[$modelName])){
						if(!empty($value[$modelName][0])){
							$temp_model = array();
							foreach($value[$modelName] AS $key_multiple => $modelParams){
								$temp_model[$key_multiple] = $modelParams[$modelName];
							}
							$value[$modelName] = $temp_model;
						}
						
					}
				## KETIKA DATA MULTIPlE SUDAH DIBUILD dengan MERGEPARENT DIBAWAH INI, MENJADI PARENTMODEL => MODEL => IDX => VALUE
				}elseif(in_array($modelName,$mergeParents)){

					if(!empty($value[$modelName])){

						if(!empty($value[$modelName][0])){
							$temp_model = array();
							foreach($value[$modelName] AS $key_merge => $modelParams){
								$temp_model[$key_merge] = $modelParams[$contain];
							}
							$value[$this->name][$modelName] = $temp_model;
							unset($value[$modelName]);
						}else{
							$value[$this->name][$modelName] = $value[$contain];
							unset($value[$modelName]);
						}
					}
				}
			}
		}

		if(!empty($containRecursive)){
			$valueTemps = array();
			
			if(!empty($value[$modelName])){
				$valueTemps = $this->getMergeList($model->$uses, $value[$modelName], array(
					'contain' => $containRecursive,
				));

				if(!empty($valueTemps)){
					$value = $this->callUnset($model->$uses, $value, array(
						$modelName
					));
					$value[$modelName] = $valueTemps;
				}
			}
		}

		return $value;
	}

	public function getMergeList( Model $model, $values, $options, $element = false){
		$contains = $this->filterEmptyField($model, $options, 'contain');

		if(!empty($values)){
			if(!empty($values[0])){
				foreach($values AS $key => $value){
					foreach($contains AS $modelName => $options){
						$value = $this->_callMergeData($model, $value, $element, $options, $modelName);
					}
					$values[$key] = $value;
				}

			}else{
				foreach($contains AS $modelName => $options){
					$values = $this->_callMergeData($model, $values, $element, $options, $modelName);
				}
			}
		}
		return $values;
	}

	function callCreateDataList(Model $model, $data, $id, $options = array() ){

		if( !empty($options) ) {
			foreach ($options as $modelName => $opt) {
				$parentName = $this->filterEmptyField($model, $opt, 'parentField');
				$foreignName = $this->filterEmptyField($model, $opt, 'foreignField');

				$other_id = $this->filterEmptyField($model, $data, $modelName, 'other_id');
				$other_text = $this->filterEmptyField($model, $data, $modelName, 'other_text');

				$values = $this->filterEmptyField($model, $data, $modelName, $foreignName, array());
		        $values = array_filter($values);
		        
	            $data = $this->callUnset($model, $data, array(
	                $modelName,
	            ));

		        if( !empty($values) ) {
		            foreach ($values as $foreign_id => $value) {
		                $data[$modelName][] = array(
		                    $parentName => $id,
		                    $foreignName => $foreign_id,
		                );
		            }
		        }

		        if( !empty($other_id) && !empty($other_text) ) {
		        	$data[$modelName][] = array(
	                    $parentName => $id,
	                    $foreignName => '-1',
	                    'other_text' => $other_text,
	                );
		        }
		    }
	    }

        return $data;
	}

	function deleteAllModel (  Model $model, $datas ) {
		if( !empty($datas) ) {
			foreach ($datas as $modelName => $conditions) {
                $model->$modelName->deleteAll($conditions);
		    }
	    }
	}

	/*
		arr_set : validasi yang ingin di tampilkan
		- ketentuan 
			* jika ingin mengambil validasi di model yang sama, cukup tambahkan nama fieldnya saja
			* 
	*/
	public function prettyErrorDetail( Model $model, $all_arr_error, $arr_set){
		$temp_arr_set = array();
		foreach ($arr_set as $key => $value) {
			$set = explode('.', $value);

			if(count($set) > 1){
				$_model = $set[0];
				$_field = $set[1];

				$temp_arr_set[$_model][] = $_field;
			}else{
				$temp_arr_set[] = $set[0];
			}
		}
		
		$list = array();
		foreach ($temp_arr_set as $model => $field) {
			if(is_numeric($model) && !empty($all_arr_error[$field])){
				$list = array_merge($list, $all_arr_error[$field]);
			}else if(!empty($all_arr_error[$model])){
				foreach ($all_arr_error[$model] as $sub_field => $value) {
					if(!empty($all_arr_error[$model][$sub_field])){
						$list = array_merge($list, $all_arr_error[$model][$sub_field]);
					}
				}
			}
		}
		
		return $list;
	}

	function _setListPrettyError($arr_list){
		$result = array();
		foreach ($arr_list as $key => $value) {
			$result[] = $value;
		}

		return $result;
	}

	function array_random($arr, $num = 1){
		shuffle($arr);
		
		$r = array();
		for($i = 0; $i < $num; $i++){
			$r[] = $arr[$i];
		}
		return $num == 1 ? $r[0] : $r;
	}

  	function createRandomNumber(Model $model, $default= 4, $variable = 'bcdfghjklmnprstvwxyz', $modRndm = 20){
		$chars = $variable;
		srand((double)microtime()*1000000);
		$pass = array() ;

		$i = 1;
		while($i != $default){
			$num = rand() % $modRndm;
			$tmp = substr($chars, $num, 1);
			$pass[] = $tmp;
			$i++;
		}
		
		$pass[] = rand(1, 9);
		$rand_code = $this->array_random($pass, count($variable));

		return $pass;
	}

	public static function auth_password(Model $model, $password) {
		return Security::hash($password, null, true);
	}

	public static function dataConverter(Model $model, $data, $fields, $reverse = false, $round = 0 ) {
		if( !empty($data) && !empty($fields) ) {
			foreach ($fields as $type => $models) {
				$data = $model->converterLists($type, $data, $models, $reverse, $round);
			}
		}
		return $data;
	}

	public static function converterLists(Model $model, $type, $data, $models, $reverse = false, $round = 0){
    	if(!empty($type) && !empty($data) && !empty($models)){
    		if(is_array($models)){
    			foreach($models AS $loop => $modelName){
 	   				if(!empty($modelName)){
	 	   				if(is_array($modelName)){
	 	   					$data[$loop] = $model->converterLists($type, $data[$loop], $models[$loop], $reverse, $round);
	 	   				}else{
	 	   					if(in_array($type, array('unset', 'array_filter'))){
	 	   						if($type == 'array_filter'){
	 	   							$data[$modelName] = array_filter($data[$modelName]);
	 	   							if(empty($data[$modelName])){
	 	   								unset($data[$modelName]);
	 	   							}
	 	   						}else{
	 	   							unset($data[$modelName]);
	 	   						}

	 	   					} else if( !empty($data[$modelName]) ) {
	 	   						$data[$modelName] = $model->generateType($type, $data[$modelName], $reverse, $round);
	 	   					}
	 	   				}
	 	   			}
	    		}
    		}else{
    			if(in_array($type, array('unset', 'array_filter'))){
    				if($type == 'array_filter'){
						$data[$models] = array_filter($data[$models]);
						if(empty($data[$models])){
							unset($data[$models]);
						}
					}else{
						unset($data[$models]);
					}
    			}else{
    				$data[$models] = $model->generateType($type, $data[$models], $reverse, $round);
    			}
    		}
    	}
    	
    	return $data;
    }

    public static function generateType(Model $model, $type, $data, $reverse, $round){
    	switch($type){
			case 'auth_password' : 
				$data = $model->auth_password($data);
			break;
			case 'date' : 
				$data = $model->getDate($data, $reverse);
			break;
		## ADA CASE BARU TAMBAHKAN DISINI, ANDA HANYA MEMBUAT $this->FUNCTION yang anda inginkan tanpa merubah flow dari
		## function dataConverter dan _converterLists
		}
		return $data;
    }

	function callLoopChart (Model $model, $values, $is_multiple = false ) {
		$result = array();

		if( !empty($values) ) {
			if( $is_multiple ) {
				$total_arr = count($values)+1;
				foreach( $values as $pkey => $pvalue ) {
					foreach ($pvalue as $key => $value) {
						$cnt = !empty($value[0]['cnt'])?(int)$value[0]['cnt']:0;
						$created = !empty($value[0]['created'])?date('d M Y', strtotime($value[0]['created'])):false;
						$formatCreated = !empty($value[0]['created'])?date('Y-m-d', strtotime($value[0]['created'])):false;

						$found = false;
						foreach( $result as $check_key => $check_value ){
							if( $check_value[0] == $created ) {
								$found = true;
								$result_index = $formatCreated;
							}
						}

						if( $found ) {
							$result[$result_index][$pkey+1] = $cnt;
						} else {
							$arr = array_fill(0, $total_arr, 0);
							$arr[0] = $created;
							$arr[$pkey+1] = $cnt;
							$result[$formatCreated] = $arr;
						}
					}
				}
			} else {
				foreach ($values as $key => $value) {

					$cnt = !empty($value[0]['cnt'])?(int)$value[0]['cnt']:0;
					$created = !empty($value[0]['created'])?date('d M Y', strtotime($value[0]['created'])):false;
					$formatCreated = !empty($value[0]['created'])?date('Y-m-d', strtotime($value[0]['created'])):false;

					$result[$formatCreated] = array(
						$created,
						$cnt,
					);
				}
			}
		}

		return $result;
	}

	function toChartFormat ( Model $model, $values, $modelName = false, $is_multiple = false ) {
		$result = array();

		if( !empty($values) && !empty($modelName) ) {
			if( $is_multiple ) {
				$total_arr = count($values)+1;
				foreach( $values as $pkey => $pvalue ) {
					foreach ($pvalue as $key => $value) {
						$cnt = !empty($value[$modelName]['cnt'])?(int)$value[$modelName]['cnt']:0;
						$created = !empty($value[$modelName]['created'])?date('d M Y', strtotime($value[$modelName]['created'])):false;
						$found = false;
						foreach( $result as $check_key => $check_value ){
							if( $check_value[0] == $created ) {
								$found = true;
								$result_index = $check_key;
							}
						}

						if( $found ) {
							$result[$result_index][$pkey+1] = $cnt;
						} else {
							$arr = array_fill(0, $total_arr, 0);
							$arr[0] = $created;
							$arr[$pkey+1] = $cnt;
							$result[] = $arr;
						}
					}
				}
			} else {
				foreach ($values as $key => $value) {
					$cnt = !empty($value[$modelName]['cnt'])?(int)$value[$modelName]['cnt']:0;
					$created = !empty($value[$modelName]['created'])?date('d M Y', strtotime($value[$modelName]['created'])):false;
					$result[] = array(
						$created,
						$cnt,
					);
				}
			}
		}

		return $result;
	}

	/*
		- range berisi jangka waktu yang di perlukan, misal : +2 month, +1 days (sesuai format strtotime PHP)
		- date : acuan tanggal

		*note : bila tanggal kosong, acuan akan tanggal akan mengacu pada tanggal ketika fungsi ini akses
	*/
	function getDateAfter( Model $model, $range, $date = false){

		if(!empty($date)){
			$date = date('Y-m-d');
		}

		return date('Y-m-d', strtotime('+2 month', strtotime($date)));
	}

	function setting_site(Model $model, $slug) {
		$setting_site = Configure::read('__Site.Setting');

		if(isset($setting_site[$slug])){
			return $setting_site[$slug];
		}else{
			return false;
		}
	}

	function extensionUploadList(model $model, $errors, $datas){
		if(!empty($datas) && !empty($errors)){
			if(!empty($datas[0])){
				foreach($datas AS $key => $data){
					if(!empty($errors[$key])){
						$errors[$key] = $this->extensionUpload($model, $errors[$key], $data);
					}
				}
			}else{
				$errors = $this->extensionUpload($model, $errors, $datas);
			}
		}
		return $errors;
	}

	function extensionUpload(model $model, $errors, $data){
		if(!empty($data)){
			$uploads = $this->filterEmptyField($model, $data, 'Upload');

			if(!empty($uploads)){
				foreach($uploads AS $fieldName => $status_arr){
					$error = !empty($errors[$fieldName])?$errors[$fieldName]:false;
					$status = $this->filterEmptyField($model, $status_arr, 'error');

					if(!empty($error) && !empty($status)){
						$message = $this->filterEmptyField($model, $status_arr, 'message');
						$errors[$fieldName] = array(
							$message
						);
					}
				}
			}
		}
		return $errors;
	}

	function callSetDataCache ( Model $model, $cacheConfig, $data, $cacheName, $add_param = false ) {
		if( !empty($add_param) ) {
			$cacheName = sprintf('%s.%s', $cacheName, $add_param);
		}

		$cacheData = array(
			'result' => $data
		);

		Cache::write($cacheName, $cacheData, $cacheConfig);
	}

	function callGetDataCache ( Model $model, $cacheConfig, $cacheName, $add_param = false, $options = array() ) {
		if( !empty($add_param) ) {
			$cacheName = sprintf('%s.%s', $cacheName, $add_param);
		}

		$cacheData = Cache::read($cacheName, $cacheConfig);

		if( !empty($cacheData['result']) ){
			return $cacheData['result'];
		} else {
			return false;
		}
	}

	function getDate(Model $model, $date, $reverse = false){
		$dtString = false;
		$date = urldecode($date);
		$date = trim($date);

		if(!empty($date) && $date != '0000-00-00'){
			if($reverse){
				$dtString = date('d/m/Y', strtotime($date));
			}else{
				$mergeDate = explode(' ', $date);

				if($mergeDate > 1){
					$date = !empty($mergeDate[0])?$mergeDate[0]:false;
					$time = !empty($mergeDate[1])?$mergeDate[1]:false;
				}

				$dtArr = explode('/', $date);

				if(count($dtArr) == 3){
					$dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
				} else {
					$dtArr = explode('-', $date);

					if(count($dtArr) == 3){
						$dtString = date('Y-m-d', strtotime(sprintf('%s-%s-%s', $dtArr[2], $dtArr[1], $dtArr[0])));
					}
				}

				if(!empty($time)){
					$dtString = sprintf('%s %s', $dtString, $time);
				}
			}
		}

		return $dtString;
	}

	function getToken(){
		$security_key = 'rumahku_is_cool';
		$security_secret = md5($security_key);
		$security_time = strtotime(date('Y-m-d H:i:s'));
	    if (function_exists('getToken')){
	        return getToken();
	    }else{
	    	$uuid = md5($security_secret.$security_time.uniqid("", true));
	        return $uuid;
	    }
	}

	/* ini digunakan ketika  array "OR" tidak selevel*/
	function collectionORConditions(Model $model, $options = array()){
		$temp_or = array();
		
		if(!empty($options)){
			$temp_or = $this->filterEmptyField($model, $options, 'OR', false, array());

			foreach ($options as $key => $value) {
				if( is_numeric($key) && is_array($value)){
					foreach ($value as $key_val => $val) {
						if($key == 'OR'){
							$temp_or = array_merge($temp_or, $val);

							unset($options[$key]);
						}
					}
				}
			}

			$options['OR'] = $temp_or;
		}

		return $options;
	}

	function buildCache ( Model $model, $cache = array(), $options = array() ) {
        if( !empty($cache) ) {
            $cache_name = $this->filterEmptyField($model, $cache, 'name', false, $model->alias);
            $cache_config = $this->filterEmptyField($model, $cache, 'config', false, 'default');

            $options['cache'] = $cache_name;
            $options['cacheConfig'] = $cache_config;
        }

        return $options;
	}
}
