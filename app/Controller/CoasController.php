<?php
App::uses('AppController', 'Controller');

class CoasController extends AppController {

	public $uses = array(
		'Coa',
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

	function admin_lists(){

		$default_conditons = array(
			'conditions' => array(),
		);

		$options = $this->Coa->_callRefineParams($this->params, $default_conditons);
		$this->paginate = $this->Coa->getData('paginate', $options);
		$values = $this->paginate('Coa');	

		$this->set(array(
			'values' => $values,
			'active_menu' => 'master',
			'module_title' => 'Daftar COA',
			'urlAdd' => array(
	            	'controller' => 'coas',
		            'action' => 'admin_add',
		            'admin' => true,
	        	),
        	'urlEdit' => array(
        		'controller' => 'coas',
        		'action' => 'admin_edit',
        		'admin' => true,
        	),
        	'searchUrl' => array(
        		'controller' => 'coas',
				'action' => 'search',
				'lists',
				'admin' => true,
        	),
        	'text' => __('Hapus Coa'),
        	'textAdd' => __('Tambah Coa'),
		));
	}

	public function admin_delete_multiple_admin() {
		$data = $this->request->data;
		$id = $this->RmCommon->filterEmptyField($data, 'User', 'id');

    	$result = $this->Coa->doRemove( $id );
		$this->RmCommon->setProcessParams($result, false, array(
			'redirectError' => true,
		));
    }

	function admin_add(){
		$data = $this->request->data;

		if(!empty($data)){
			$save_path = Configure::read('__Site.document_folder');
			$data = $this->request->data = $this->RmImage->_uploadPhoto( $data, 'Coa', 'photo', $save_path );

			$result = $this->Coa->doSave($data);
			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'coas',
				'action' => 'lists',
				'admin' => true,
			));
		}

		$title = __('Tambah COA');
		$this->set(array(
			'active_menu' => 'master',
			'module_title' => $title,
			'title' => $title,
			'manualUploadPhoto' => true,
		));
	}

	function admin_edit($id = false){
		$value = $this->Coa->getData('first', array(
			'conditions' => array(
				'Coa.id' => $id
			),
		));

		if(!empty($value)){
			$data = $this->request->data;

			if(!empty($data)){
				$save_path = Configure::read('__Site.document_folder');
				$data = $this->request->data = $this->RmImage->_uploadPhoto( $data, 'Coa', 'photo', $save_path );

				$result = $this->Coa->doSave($data, $id);
				$this->RmCommon->setProcessParams($result, array(
					'controller' => 'coas',
					'action' => 'lists',
					'admin' => true,
				));
			}else{
				$photo = $this->RmCommon->filterEmptyField($value, 'Coa', 'photo');
				$this->request->data = $value;
				$this->request->data['Coa']['photo_hide'] = $photo;
			}

			$title = __('Ubah COA');
			$this->set(array(
				'active_menu' => 'master',
				'module_title' => $title,
				'title' => $title,
				'manualUploadPhoto' => true,
			));

			$this->render('admin_add');
		}else{
			$this->RmCommon->redirectReferer(__('Data tidak ditemukan'));
		}
	}

	function admin_delete($id = false){
		$value = $this->Coa->getData('first', array(
			'conditions' => array(
				'Coa.id' => $id,
			),
		));

		if(!empty($value)){
			$result = $this->Coa->doDelete($id);
			$this->RmCommon->setProcessParams($result, array(
				'controller' => 'coas',
				'action' => 'list',
				'admin' => true,
			));

		}else{
			$this->RmCommon->redirectReferer(__('Data tidak ditemukan'));
		}
	}
}
?>