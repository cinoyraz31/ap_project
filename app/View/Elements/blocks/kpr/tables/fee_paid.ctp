<?php
    	$dataColumns = array(
    		'approved_admin_date' => array(
                'name' => __('Pengajuan'),
            ),
    		'mls_id' => array(
                'name' => __('Id Properti'),
            ),
            'agent' => array(
                'name' => __('Agen'),
            ),
            'code' => array(
                'name' => __('Kode'),
            ),
            'BankApplyCategory.code' => array(
                'name' => __('Tipe Kpr'),
            ),
            'Bank.name' => array(
                'name' => __('Bank'),
            ),
            'loan_price' => array(
                'name' => __('Jumlah Pinjaman'),
            ),
            'credit_total' => array(
                'name' => __('Jangka Waktu'),
            ),
            'commission_agent' => array(
                'name' => __('Provisi Agen'),
            ),
            'commission_company' => array(
                'name' => sprintf('%s %s', __('Provisi'), Configure::read('__Site.site_name')),
            ),
            'status' => array(
                'name' => __('Status'),
                'class' => 'tacenter',
            ),
        );

        $fieldColumn = $this->Rumahku->_generateShowHideColumn( $dataColumns, 'field-table' );

        $searchUrl = array(
    		'controller' => 'kpr',
    		'action' => 'search',
    		'fee_paid',
    		'admin' => true,
    	);

    	echo $this->element('blocks/common/forms/search/backend', array(
        	'placeholder' => __('Cari berdasarkan Nama Klien, Email Klien, Kode Pengajuan, ID Properti, Nama Agen dan Email Agen'),
        	'url' => $searchUrl,
    		'datePicker' => true,
    		'exportExcel' => !empty($values) ? true : false,
    	));

?>

<div class="table-responsive" id="list-user-apply-table">
	<?php
			echo $this->Form->create('KprApplication', array(
	        	'class' => 'form-target',
	    	));

			if( !empty($values) ) {
				$tbody = null;

                if( !empty($fieldColumn) ) {
                    $thead = $this->Html->tag('thead', $this->Html->tag('tr', $fieldColumn));
                }

                foreach ($values as $key => $value) {
                	$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
                	$code = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');
                	$mls_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'mls_id');
                	$property_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'property_id');
                	$approved_admin_date = $this->Rumahku->filterEmptyField($value, 'KprBank', 'approved_admin_date');
                	$agent_name = $this->Rumahku->filterEmptyField($value, 'Agent', 'full_name');
                	$tipe_kpr = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'code');
                	$bank_code = $this->Rumahku->filterEmptyField($value, 'Bank', 'code');
                	$loan_price = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'loan_price');
                	$credit_total = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'credit_total');
                	$unpaid_agent = $this->Rumahku->filterEmptyField($value, 'KprBank', 'unpaid_agent');
                	$commission = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'commission');
                	$unpaid_rumahku = $this->Rumahku->filterEmptyField($value, 'KprBank', 'unpaid_rumahku');
                	$commission_rumahku = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'commission_rumahku');

                	$customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price);
                	$customCommission = $this->Rumahku->getCurrencyPrice($commission, '-');
                	$customCommissionRumahku = $this->Rumahku->getCurrencyPrice($commission_rumahku, '-');
                	$CustomApprovedAdminDate = $this->Rumahku->formatDate($approved_admin_date, 'd M y');

                	$default_status = $this->Html->tag('b', __('sudah dibayarkan'), array(
                		'class' => 'color-green'
                	));

                    $customCommission = ($unpaid_agent == 'approved') ?  $default_status : $this->Html->tag('b', $customCommission);
                    $customCommissionRumahku = ($unpaid_rumahku == 'approved') ?  $default_status : $this->Html->tag('b', $customCommissionRumahku);

                	$urlProperty = array(
			            'controller' => 'properties',
			            'action' => 'detail',
			            $mls_id,
			            'application' => $property_id,
			            'id' => $id,
			            'admin' => true,
			        );

			        $customMlsID = $this->Html->link($mls_id, $urlProperty, array(
			        	'target' => '_blank',
			        ));

			        $action = $this->Html->link($this->Rumahku->icon('rv4-angle-right'), array(
  						'controller' => 'kpr',
      					'action' => 'user_apply_detail',
      					$id,
      					'admin' => true,
  					), array(
  						'escape' => false,
  						'class' => 'icon-more',
  					));


                	$default_arr = array(
                		array(
			         		$CustomApprovedAdminDate,
				            array(
				            	'class' => 'tacenter',
			            	),
				        ),
                		array(
			         		$customMlsID,
				            array(
				            	'class' => 'tacenter',
			            	),
				        ),
				        $agent_name,
				        $code,
				        $tipe_kpr,
				        $bank_code,
				        $customLoanPrice,
				        sprintf('%s Thn', $credit_total),
				        $customCommission,
				        $customCommissionRumahku,
				        $action,
                	);

                	$tbody .= $this->Html->tableCells(array(
		            	$default_arr
			        ));
                }

   				echo $this->Html->tag('table', sprintf('%s %s', $thead, $tbody), array(
   					'class' => 'table green'
   				));

			} else {
    			echo $this->Html->tag('p', __('Data belum tersedia'), array(
    				'class' => 'alert alert-warning'
				));
			}

			echo $this->end();
	?>
</div>
	<?php 	
			echo $this->element('blocks/common/pagination');
	?>