<?php 

		$fee_paid = !empty($fee_paid)?$fee_paid:false;	

		if( !empty($values) ) {
			$export = !empty($export)?$export:false;

			foreach( $values as $key => $value ) {
				$id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id');
				
				$name_agent = $this->Rumahku->filterEmptyField($value,'User','full_name');	

				$mls_id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'mls_id');
				$code = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'code');
				$code_category = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'code');
				$bank_name = $this->Rumahku->filterEmptyField($value, 'Bank', 'code');
				$loan_price = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'loan_price');
				$credit_total = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_total');
				$credit_total = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'credit_total');

				$komisi_agen = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'komisi_agen');
				$paid_agent = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'paid_agen');
				$komisi_rku = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'komisi_rku');
				$paid_rku = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'paid_rku');

				$assign_project_date = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'assign_project_date');

				$assign_project_date = $this->Rumahku->formatDate($assign_project_date, 'd M Y');

				$symbol_currency = $this->Rumahku->filterEmptyField($value,'Currency','symbol');

				$status = $this->Kpr->_callStatusKomisi($value,true);
				$custom_total = sprintf('%s Tahun',$credit_total);

				$custom_loan_price = $this->Rumahku->getCurrencyPrice( $loan_price, false, $symbol_currency); 
				$custom_loan_price = $this->Html->tag('b',$custom_loan_price);

				if(!$paid_agent){
						$custom_commission_agent = $this->Rumahku->getCurrencyPrice( $komisi_agen, false, $symbol_currency);
						$custom_commission_agent = $this->Html->tag('b',$custom_commission_agent);
				}else{
						$custom_commission_agent = 'DONE';
				}

				if(!$paid_rku){
						$custom_commission_company = $this->Rumahku->getCurrencyPrice( $komisi_rku, false, $symbol_currency);
						$custom_commission_company = $this->Html->tag('b',$custom_commission_company);
				}else{
						$custom_commission_company = 'DONE';
				}

				$urlProperty = array(
		            'controller' => 'properties',
		            'action' => 'detail',
		            'admin' => true,
		            'application' => $id,
		            $mls_id
		        );

				$urlProperty = $this->Html->url($urlProperty);
		        $link_mls_id = $this->Html->link($mls_id,$urlProperty,array(
		        		'target' => __('blank')
		        	));

		        if($fee_paid){

		        	$default_arr = array(
						$link_mls_id,
						$name_agent,
			            $code,
						$code_category,
						$bank_name,
						$custom_loan_price,
						$custom_total,
						$custom_commission_agent,
						$custom_commission_company,	
						$status,
		    		);

		        }else{

		        	$default_arr = array(
						$link_mls_id,
						$name_agent,
			            $code,
						$code_category,
						$bank_name,
						$custom_loan_price,
						$custom_total,
						$assign_project_date,
						$status,
		    		);

		        }

	    		echo $this->Html->tableCells(array(
	            	$default_arr
		        ), array(
		        	// 'class' => $class_read,
		       	));
			}
		}
?>