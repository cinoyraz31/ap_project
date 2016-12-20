<?php
		$searchUrl = array(
			'controller' => 'logs',
			'action' => 'search',
			'histories',
			'admin' => true,
		);
		
        echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari pemohon berdasarkan Nama atau Email'),
        	'url' => $searchUrl,
        	'sorting' => array(
		        'options' => array(
		        	'options' => array(
		        		'LogKpr.created-desc' => __('Baru - Lama'),
		        		'LogKpr.created-asc' => __('Lama - Baru'),
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
		            'created' => array(
		                'name' => __('Tanggal'),
		                'field_model' => 'KprBank.created',
		                'display' => true,
		            ),
		            'from' => array(
		                'name' => __('from Web'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'name' => array(
		                'name' => __('Nama'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'email' => array(
		                'name' => __('Email'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'bank' => array(
		                'name' => __('Bank'),
		                'field_model' => false,
		                'display' => true,
		            ),
		            'interest_rate' => array(
		                'name' => __('Suku Bunga (%)'),
		                'field_model' => 'LogKpr.interest_rate',
		                'class' => 'tacenter',
		                'display' => true,
		            ),
		            'credit_total' => array(
		                'name' => __('Lama Pinjaman (Thn)'),
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
	      				$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
	      				$created = $this->Rumahku->filterEmptyField($value, 'KprBank', 'created');
	      				$from_web = $this->Rumahku->filterEmptyField($value, 'KprBank', 'from_web');
	      				$interest_rate = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'interest_rate_fix');
	      				$credit_fix = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'periode_fix');
	      				$credit_total = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'credit_total');
	      				
	      				$user_fullname = $this->Rumahku->filterEmptyField($value, 'KprBank', 'client_name', '-');
	      				$user_email = $this->Rumahku->filterEmptyField($value, 'KprBank', 'client_email', '-');
	      				$bank = $this->Rumahku->filterEmptyField($value, 'Bank', 'name');

	      				$customCreated = $this->Rumahku->formatDate($created, 'd M Y H:i');
	      				$credit_float = $credit_total - $credit_fix;

	      				// Set Action
      					$action = $this->Html->link($this->Rumahku->icon('rv4-angle-right'), array(
	      					'controller' => 'logs',
	      					'action' => 'history_detail',
	      					$id,
	      					'admin' => true,
      					), array(
      						'escape' => false,
      						'class' => 'icon-more',
      					));

	      				echo $this->Html->tableCells(array(
			            	array(
			         			$customCreated,
			         			$from_web,
					            $user_fullname,
					            $user_email,
		      					$bank,
						        array(
		      						$interest_rate,
						            array(
						            	'class' => 'tacenter',
					            	),
						        ),
						        array(
		      						$credit_total,
						            array(
						            	'class' => 'tacenter',
					            	),
						        ),
						        array(
					         		$action,
						            array(
						            	'class' => 'actions tacenter',
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
    ?>
</div>
<?php 	
		echo $this->element('blocks/common/pagination');
?>