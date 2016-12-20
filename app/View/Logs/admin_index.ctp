<?php
		$searchUrl = array(
			'controller' => 'logs',
			'action' => 'search',
			'index',
			'admin' => true,
		);
		
        echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari berdasarkan Nama User dan aktifitas'),
        	'url' => $searchUrl,
        	'sorting' => array(
        		'options' => array(
		        	'options' => array(
		        		'Log.created-desc' => __('Baru - Lama'),
		        		'Log.created-asc' => __('Lama - Baru'),
	        		),
	        		'url' => $searchUrl,
	        	),
    		),
    	));
?>
<div class="table-responsive">
	<?php
			if( !empty($values) ) {
				$dataColumns = array(
		            'user' => array(
		                'name' => __('Nama User'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'activity' => array(
		                'name' => __('Aktivitas'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'admin' => array(
		                'name' => __('Admin Rumahku?'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'status' => array(
		                'name' => __('Status'),
		                'field_model' => false,
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'created' => array(
		                'name' => __('Tgl Aktivitas'),
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
	      				$id = $this->Rumahku->filterEmptyField($value, 'Log', 'id');
	      				$name = $this->Rumahku->filterEmptyField($value, 'User', 'full_name');
	      				$activity = $this->Rumahku->filterEmptyField($value, 'Log', 'name');
	      				$admin = $this->Rumahku->filterEmptyField($value, 'Log', 'admin');
	      				$status = !$this->Rumahku->filterEmptyField($value, 'Log', 'error');
	      				$created = $this->Rumahku->filterEmptyField($value, 'Log', 'created');

	      				$customCreated = $this->Rumahku->formatDate($created, 'd M Y H:i:s');
	      				$customAdmin = $this->Rumahku->_callStatusChecked($admin);
	      				$customError = $this->Rumahku->_callStatusChecked($status);

	      				echo $this->Html->tableCells(array(
				            array(
					            $name,
					            $activity,
						        array(
					         		$customAdmin,
						            array(
						            	'class' => 'tacenter actions',
					            	),
						        ),
						        array(
					         		$customError,
						            array(
						            	'class' => 'tacenter actions',
					            	),
						        ),
		      					array(
					         		$customCreated,
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
		echo $this->element('blocks/common/pagination');
?>