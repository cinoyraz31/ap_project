<?php
App::uses('AppController', 'Controller');

class UnitsController  extends AppController {

	public $uses = array(
		'Unit',
	);

	public $components = array(
		'RmUser', 'RmImage'
	);

	public function beforeFilter(){
		parent::beforeFilter();

	//	ga maenan dl acl
		$this->Auth->allow();
	}

	function admin_search ( $action ) {
		$data = $this->request->data;
		$params = array(
			'action' => $action,
		);
		$this->RmCommon->processSorting($params, $data);
	}

	function admin_sub_lists(){
		$this->loadModel('UnitList');
		$default_conditons = array(
			'conditions' => array(),
		);

		$options = $this->Unit->UnitList->_callRefineParams($this->params, $default_conditons);
		$this->paginate = $this->Unit->UnitList->getData('paginate', $options);
		$values = $this->paginate('UnitList');	

		$title = __('Daftar Sub Unit');
		$this->set(array(
			'values' => $values,
			'index' => 'sub_unit',
			'current' => 'sub_unit',
			'title_layout' =>$title,
			'title' => $title,
			'url_view' => array(
				'add' => array(
					'controller' => 'units',
					'action' => 'sub_add',
				),
				'edit' => array(
					'controller' => 'units',
					'action' => 'sub_edit',
				),
				'delete' => array(
					'controller' => 'units',
					'action' => 'sub_delete',
				),
			),
		));

		$this->render('admin_unit_list');

	}	

	function admin_lists(){

		$default_conditons = array(
			'conditions' => array(),
		);

		$options = $this->Unit->_callRefineParams($this->params, $default_conditons);
		$this->paginate = $this->Unit->getData('paginate', $options);
		$values = $this->paginate('Unit');	

		$values = $this->Unit->getMergeList($values, array(
			'contain' => array(
				'Coa',
				'User',
			),
		));

		$this->set(array(
			'values' => $values,
			'active_menu' => 'master',
			'module_title' => 'Daftar Unit',
			'urlAdd' => array(
	            	'controller' => 'units',
		            'action' => 'admin_add',
		            'admin' => true,
	        ),
        	'urlEdit' => array(
        		'controller' => 'units',
        		'action' => 'admin_edit',
        		'admin' => true,
        	),
        	'searchUrl' => array(
        		'controller' => 'units',
				'action' => 'search',
				'lists',
				'admin' => true,
        	),
        	'text' => __('Hapus Unit'),
        	'textAdd' => __('Tambah Unit'),
		));
	}

	function admin_sub_add(){
		$data = $this->request->data;

		if(!empty($data)){
			$data = $this->RmCommon->dataConverter($data, array(
				'date' => array(
					'Unit' => array(
						'assign_date'
					),
				),
			));

			$result = $this->Unit->doSave($data);
			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'units',
				'action' => 'list',
				'admin' => true,
			));
		}

		$units = $this->Unit->getData('list', array(
			'fields' => array(
				'id', 'name'
			),
		));

		$title = __('Tambah Unit');
		$this->set(array(
			'index' => 'unit',
			'title_layout' => $title,
			'units' => $units,
			'title' => $title,
		));
	}

	function admin_add(){
		$data = $this->request->data;

		if(!empty($data)){
			$data = $this->RmCommon->dataConverter($data, array(
				'date' => array(
					'Unit' => array(
						'assign_date'
					),
				),
			));

			$result = $this->Unit->doSave($data);
			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'units',
				'action' => 'lists',
				'admin' => true,
			));
		}

		$coas = $this->Unit->Coa->getData('list', array(
			'fields' => array(
				'id', 'name'
			),
		));

		$leaders = $this->Unit->User->getData('list', array(
			'conditions' => array(
				'group_id' => '5'
			),
			'fields' => array(
				'id', 'full_name'
			),
		), array(
			'status' => 'active',
		));

		$title = __('Tambah Unit');

		$this->set(array(
			'active_menu' => 'master',
			'module_title' => $title,
        	// 'manualUploadPhoto' => true,
        	'coas' => $coas,
        	'leaders' => $leaders,
		));


	}

	public function admin_delete_multiple_admin() {
		$data = $this->request->data;
		$id = $this->RmCommon->filterEmptyField($data, 'User', 'id');

    	$result = $this->Unit->doRemove( $id );
		$this->RmCommon->setProcessParams($result, false, array(
			'redirectError' => true,
		));
    }

	function admin_edit($id = false){
		$value = $this->Unit->getData('first', array(
			'conditions' => array(
				'Unit.id' => $id
			),
		));

		if(!empty($value)){
			$data = $this->request->data;

			if(!empty($data)){
				$data = $this->RmCommon->dataConverter($data, array(
					'date' => array(
						'Unit' => array(
							'assign_date'
						),
					),
				));

				$result = $this->Unit->doSave($data, $id);
				$this->RmCommon->setProcessParams($result, array(
					'controller' => 'units',
					'action' => 'lists',
					'admin' => true,
				));
			}else{
				$value = $this->RmCommon->dataConverter($value, array(
					'date' => array(
						'Unit' => array(
							'assign_date'
						),
					),
				), true);
				$this->request->data = $value;
			}

			$coas = $this->Unit->Coa->getData('list', array(
				'fields' => array(
					'id', 'type'
				),
			));

			$leaders = $this->Unit->User->getData('list', array(
				'conditions' => array(
					'group_id' => '5'
				),
				'fields' => array(
					'id', 'full_name'
				),
			), array(
				'status' => 'active',
			));

			$title = __('Ubah Unit');
			$this->set(array(
				'index' => 'unit',
				'title_layout' => $title,
				'leaders' => $leaders,
				'coas' => $coas,
				'title' => $title,
			));

			$this->render('admin_add');
		}else{
			$this->RmCommon->redirectReferer(__('Data tidak ditemukan'));
		}
	}

	function admin_delete($id = false){
		$value = $this->Unit->getData('first', array(
			'conditions' => array(
				'Unit.id' => $id,
			),
		));

		if(!empty($value)){
			$result = $this->Unit->doDelete($id);
			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'units',
				'action' => 'list',
				'admin' => true,
			));

		}else{
			$this->RmCommon->redirectReferer(__('Data tidak ditemukan'));
		}
	}

}
?>