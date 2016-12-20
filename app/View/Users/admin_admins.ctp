<?php
		$group_id = Configure::read('User.group_id');
		$Action = $this->params->action;
		$level = ($Action == 'admin_admins')?1:2;
		$urlAdd = !empty($urlAdd)?$urlAdd:array();
		$urlEdit = !empty($urlEdit)?$urlEdit:array();
		$searchUrl = !empty($searchUrl)?$searchUrl:array();
		
        echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari cabang berdasarkan Nama, Email dan Handphone'),
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

		if( !empty($values) ) {
			$dataColumns = array(
				'checkall'=> array('name' => $this->Rumahku->buildCheckOption('User'), 'class' => 'tacenter'), 
	            'name' => array(
	                'name' => __('Nama'),
	                'field_model' => 'User.full_name',
	                'display' => true,
	            ),
	            'branch' => array(
	                'name' => __('Cabang'),
	                'field_model' => false,
	                'display' => true,
	            ),
	            'email' => array(
	                'name' => __('E-mail'),
	                'field_model' => 'User.email',
	                'display' => true,
	            ),
	            'no_hp' => array(
	                'name' => __('Handphone'),
	                'field_model' => 'User.no_hp',
	                'display' => true,
	            ),
	            'last_login' => array(
	                'name' => __('Login Terakhir'),
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

			if($Action == 'admin_admins'){
				$dataColumns = $this->Rumahku->_callUnset(array(
					'branch'
				), $dataColumns);
			}

	        $fieldColumn = $this->Rumahku->_generateShowHideColumn( $dataColumns, 'field-table' );


        	echo $this->Form->create('User', array(
        		'class' => 'form-target',
    		));
?>
<div class="table-responsive">
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
	      				$photo = $this->Rumahku->filterEmptyField($value, 'User', 'photo');
	      				$name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
	      				$email = $this->Rumahku->filterEmptyField($value, 'User', 'email');
	      				$last_login = $this->Rumahku->filterEmptyField($value, 'User', 'last_login');

	      				$no_hp = $this->Rumahku->filterEmptyField($value, 'UserProfile', 'no_hp');

						$customBankBranchName = $this->Rumahku->_callBankBranchName($value);
						$customName = $this->Rumahku->_callUserFullName($value);
	      				$customLastLogin = $this->Rumahku->formatDate($last_login, 'd M Y');
	      				$customPhoto = $this->Html->tag('div', $this->Rumahku->photo_thumbnail(array(
							'save_path' => Configure::read('__Site.profile_photo_folder'), 
							'src' => $photo, 
							'size' => 'ps',
						)), array(
							'class' => 'user-radius-photo',
						));

	      				$action = $this->Html->link(__('Edit'), array_merge($urlEdit, array($id)));
      					$action .= $this->Html->link(__('Ganti Password'), array(
	      					'controller' => 'users',
	      					'action' => 'change_password',
	      					$id,
	      					'admin' => true,
      					), array(
      						'target' => '_blank',
      					));
	      				$action .= $this->Html->link(__('Hapus'), array(
	      					'controller' => 'users',
	      					'action' => 'delete_admin',
	      					$id,
	      					'admin' => true,
      					), false, __('Anda yakin ingin menghapus user Admin ini?'));

      					$nameUser = $customPhoto;
      					$nameUser .= $customName;

      					if($Action == 'admin_admins'){
      						echo $this->Html->tableCells(array(
				            	array(
									array($this->Rumahku->buildCheckOption('User', $id, 'default'), array('class' => 'tacenter')), 
						            $nameUser,
						            $email,
						            $no_hp,
			      					array(
						         		$customLastLogin,
							            array(
							            	'class' => 'tacenter',
						            	),
							        ),
							        array(
						         		$action,
							            array(
							            	'class' => 'tacenter actions',
						            	),
							        ),
						        ),
					        ));
      					}else{
      						echo $this->Html->tableCells(array(
				            	array(
				            		array(
					         			$this->Form->checkbox('id.', array(
				                            'class' => 'check-option',
				                            'value' => $id,
				                        )),
							            array(
							            	'class' => 'tacenter',
						            	),
				         			),
						            $nameUser,
						            $customBankBranchName,
						            $email,
						            $no_hp,
			      					array(
						         		$customLastLogin,
							            array(
							            	'class' => 'tacenter',
						            	),
							        ),
							        array(
						         		$action,
							            array(
							            	'class' => 'tacenter actions',
						            	),
							        ),
						        ),
					        ));
      					}

	      				
					}
      		?>
      	</tbody>
    </table>
</div>
<?php 
        	echo $this->Form->end(); 
			echo $this->element('blocks/common/pagination');
		} else {
			echo $this->Html->tag('p', __('Data belum tersedia'), array(
				'class' => 'alert alert-warning'
			));
		}
?>