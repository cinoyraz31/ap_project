<?php
		$searchUrl = !empty($searchUrl)?$searchUrl:array();
		$urlEdit = !empty($urlEdit)?$urlEdit:array();
		$urlAdd = !empty($urlAdd)?$urlAdd:array();

		echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari berdasarkan Nama User dan Divisi'),
        	'url' => $searchUrl,
        	'sorting' => array(
        		'buttonDelete' => array(
		            'text' => $text.$this->Html->tag('span', '', array(
		            	'class' => 'check-count-target',
	            	)),
		            'url' => array(
		            	'controller' => 'users',
			            'action' => 'delete_multiple_admin',
			            'admin' => true,
	            	),
	            	'options' => array(
	            		'class' => 'check-multiple-delete',
	            		'data-alert' => __('Anda yakin ingin menghapus user ini?'),
	        		),
		        ),
		        'overflowDelete' => true,
		        'buttonAdd' => array(
		            'text' => $textAdd,
		            'url' => $urlAdd,
		        ),
		        'options' => array(
		        	'options' => array(
		        		'User.fullname-asc' => __('Nama User ( A - Z )'),
		        		'User.fullname-desc' => __('Nama User ( Z - A )'),
		        		'User.email-asc' => __('Email ( A - Z )'),
		        		'User.email-desc' => __('Email ( Z - A )'),
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
		            'user' => array(
		                'name' => __('Nama'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'email' => array(
		                'name' => __('Email'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'activity' => array(
		                'name' => __('Divisi'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'status' => array(
		                'name' => __('Aktif'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'last_login' => array(
		                'name' => __('Terakhir Login'),
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
		            'craeted' => array(
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
	      				$id = $this->Rumahku->filterEmptyField($value, 'User', 'id');
	      				$name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
	      				$email = $this->Rumahku->filterEmptyField($value, 'User', 'email');
	      				$active = $this->Rumahku->filterEmptyField($value, 'User', 'active');
	      				$status = $this->Rumahku->filterEmptyField($value, 'User', 'status');
	      				$last_login = $this->Rumahku->filterEmptyField($value, 'User', 'last_login');
	      				$created = $this->Rumahku->filterEmptyField($value, 'User', 'created');
	      				$modified = $this->Rumahku->filterEmptyField($value, 'User', 'modified');
	      				$group_name = $this->Rumahku->filterEmptyField($value, 'Group', 'name');

	      				$customCreated = $this->Rumahku->formatDate($created, 'd M Y H:i:s');
	      				$lastLogin = $this->Rumahku->formatDate($last_login, 'd M Y H:i:s');
	      				$customModified = $this->Rumahku->formatDate($modified, 'd M Y H:i:s');
	      				$customError = $this->Rumahku->_callStatusChecked($active);

	      				$action = $this->Html->link(__('Edit'), array_merge($urlEdit, array($id)));

	      				echo $this->Html->tableCells(array(
				            array(
				            	array($this->Rumahku->buildCheckOption('User', $id, 'default'), array('class' => 'tacenter')), 
					            $name,
					            $email,
					            $group_name,
						        array(
					         		$customError,
						            array(
						            	'class' => 'tacenter actions',
					            	),
						        ),
						        array(
					         		$lastLogin,
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