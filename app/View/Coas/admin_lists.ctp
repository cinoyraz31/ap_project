<?php
		$searchUrl = !empty($searchUrl)?$searchUrl:array();
		$urlEdit = !empty($urlEdit)?$urlEdit:array();
		$urlAdd = !empty($urlAdd)?$urlAdd:array();
		$save_path  = Configure::read('__Site.document_folder');

		echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari berdasarkan Nama Coa'),
        	'url' => $searchUrl,
        	'sorting' => array(
        		'buttonDelete' => array(
		            'text' => $text.$this->Html->tag('span', '', array(
		            	'class' => 'check-count-target',
	            	)),
		            'url' => array(
		            	'controller' => 'coas',
			            'action' => 'delete_multiple_admin',
			            'admin' => true,
	            	),
	            	'options' => array(
	            		'class' => 'check-multiple-delete',
	            		'data-alert' => __('Anda yakin ingin menghapus coa ini?'),
	        		),
		        ),
		        'overflowDelete' => true,
		        'buttonAdd' => array(
		            'text' => $textAdd,
		            'url' => $urlAdd,
		        ),
		        'options' => array(
		        	'options' => array(
		        		'coas.name-asc' => __('Nama Coa ( A - Z )'),
		        		'coas.name-desc' => __('Nama Coa ( Z - A )'),
	        		),
	        		'url' => $searchUrl,
	        	),
    		),
    	));
    	echo $this->Form->create('User', array(
    		'class' => 'form-target',
		));
?>
<div class="table-responsive">
	<?php
		if( !empty($values) ) {
			$dataColumns = array(
					'checkall'=> array('name' => $this->Rumahku->buildCheckOption('User'), 'class' => 'tacenter'), 
		            'photo' => array(
		                'name' => __('Photo'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'type' => array(
		                'name' => __('Tipe'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'name' => array(
		                'name' => __('Nama'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'status' => array(
		                'name' => __('Aktif'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'modified' => array(
		                'name' => __('Diubah'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'created' => array(
		                'name' => __('Dibuat'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'action' => array(
		                'name' => __('Action'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		        );
		        $fieldColumn = $this->Rumahku->_generateShowHideColumn( $dataColumns, 'field-table' );
	?>
	<table class="table green">
	<?php
                if( !empty($fieldColumn) ) {
                    echo $this->Html->tag('thead', $this->Html->tag('tr', $fieldColumn));
                }
        ?>
      	<tbody>
      		<?php
	      			foreach( $values as $key => $value ) {
	      				$id = $this->Rumahku->filterEmptyField($value, 'Coa', 'id');
	      				$modified = $this->Rumahku->filterEmptyField($value, 'Coa', 'modified');
	      				$created = $this->Rumahku->filterEmptyField($value, 'Coa', 'created');
	      				$photo = $this->Rumahku->filterEmptyField($value, 'Coa', 'photo');
	      				$type = $this->Rumahku->filterEmptyField($value, 'Coa', 'type');
	      				$name = $this->Rumahku->filterEmptyField($value, 'Coa', 'name');
	      				$status = $this->Rumahku->filterEmptyField($value, 'Coa', 'status');

	      				$userPhoto = $this->Rumahku->photo_thumbnail(array(
				            'save_path' => $save_path, 
				            'src' => $photo, 
				            'size' => 's',
				        ), array(
				            'class' => 'default-thumbnail',
				        ));

	      				$customModified = $this->Rumahku->formatDate($modified, 'd M Y H:i:s');
	      				$customCreated = $this->Rumahku->formatDate($created, 'd M Y H:i:s');
	      				$customError = $this->Rumahku->_callStatusChecked($status);

	      				$action = $this->Html->link(__('Edit'), array_merge($urlEdit, array($id)));

	      				echo $this->Html->tableCells(array(
				            array(
				            	array($this->Rumahku->buildCheckOption('User', $id, 'default'), array('class' => 'tacenter')), 
					            $userPhoto,
					            $type,
					            $name,
						        array(
					         		$customError,
						            array(
						            	'class' => 'tacenter actions',
					            	),
						        ),
						        array(
					         		$customModified,
						            array(
						            	'class' => 'tacenter',
					            	),
						        ),
		      					array(
					         		$customCreated,
						            array(
						            	'class' => 'tacenter',
					            	),
						        ),
						        array(
					         		$action,
						            array(
						            	'class' => 'tacenter',
					            	),
						        ),
					        ),
				        ));
					}
      		?>
      	</tbody>
    </table>
    <?php 
    		} else {
    			echo $this->Html->tag('p', __('Data belum tersedia'), array(
    				'class' => 'alert alert-warning'
				));
    		}

        	echo $this->Form->end(); 
    ?>
</div>
<?php 	
		echo $this->Form->end(); 
		echo $this->element('blocks/common/pagination');
?>