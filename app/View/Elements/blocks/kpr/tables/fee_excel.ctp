<?php
        $data = $this->data;
        $field_bank = array();
        $_admin = Configure::read('User.admin');
        $site_name = Configure::read('__Site.site_name');
        $element = 'blocks/kpr/tables/fee_paid_excel';
        $style = 'text-align:center;background-color: #069E55; color: #FFFFFF;';


        $periodes = $this->Rumahku->filterEmptyField($data, 'Search', 'date');

        if($_admin){
            $field_bank = array(
                'Bank.name' => array(
                    'name' => __('Bank'),
                    'style' => $style
                ),
            );
        }

        $field_banks = array(
            'credit_total' => array(
                'name' => __('Lama Pinjaman'),
                'style' => $style
            ),
            'interest_rate' => array(
                'name' => __('Suku Bunga tetap'),
                'style' => $style
            ),
            'interest_rate_float' => array(
                'name' => __('Suku Bunga floating'),
                'style' => $style
            ),
            'price' => array(
                'name' => __('Harga Properti'),
                'style' => $style
            ),
            'loan_price' => array(
                'name' => __('Total Pinjaman'),
                'style' => $style
            ),
            'down_payment' => array(
                'name' => __('Uang Muka'),
                'style' => $style
            ),
            'down_payment' => array(
                'name' => __('Uang Muka'),
                'style' => $style
            ),
            'fee_bank' => array(
                'name' => __('biaya bank'),
                'style' => $style
            ),
            'fee_notary' => array(
                'name' => __('biaya Notaris'),
                'style' => $style
            ),
            'total_first_credit' => array(
                'name' => __('Cicilan perbulan'),
                'style' => $style
            ),
            'commission_agent' => array(
                'name' => __('Provisi Agen'),
                'style' => $style
            ),
            'unpaid_agent' => array(
                'name' => __('Status Pembayaran Provisi Agen'),
                'style' => $style
            ),
            'commission_company' => array(
                'name' => __('Provisi %s', $site_name),
                'style' => $style
            ),
            'unpaid_rumahku' => array(
                'name' => __('Status Pembayaran Provisi %s', $site_name),
                'style' => $style
            ),
            'assign_project_date' => array(
                'name' => __('Tanggal Akad Kredit'),
                'style' => $style
            ),
        );

        $field_banks = array_merge($field_bank, $field_banks);

		$dataColumns = array(
            'kpr' => array(
                'name' => __('DATA KPR'),
                'style' => $style,
                'child' => array(
                    'no' => array(
                        'name' => __('No'),
                        'style' => $style
                    ),
                    'BankApplyCategory.code' => array(
                        'name' => __('Tipe Pengajuan'),
                        'style' => $style
                    ),
                    'code' => array(
                        'name' => __('Kode KPR'),
                        'style' => $style
                    ),
                    'mls_id' => array(
                        'name' => __('ID Properti'),
                        'style' => $style
                    ),
                    'property_type_id' => array(
                        'name' => __('Tipe Properti'),
                        'style' => $style
                    ),
                ),
            ),
            'agent' => array(
                'name' => __('DATA AGEN'),
                'style' => $style,
                'child' => array(
                    'agent_id' => array(
                        'name' => __('Nama Agen'),
                        'style' => $style
                    ),
                    'email' => array(
                        'name' => __('Email Agen'),
                        'style' => $style
                    ),
                    'address' => array(
                        'name' => __('Alamat'),
                        'style' => $style
                    ),
                    'phone' => array(
                        'name' => __('No Telp'),
                        'style' => $style
                    ),
                    'no_hp' => array(
                        'name' => __('No Handphone 1'),
                        'style' => $style
                    ),
                    'company' => array(
                        'name' => __('Kantor Agen'),
                        'style' => $style
                    ),
                ),
            ),
            'data_bank' => array(
                'name' => __('DATA BANK'),
                'style' => $style,
                'child' => $field_banks,
            ),
	    );	
		
		if(!empty($values)){
			$fieldColumn = $this->Rumahku->_generateShowHideColumn( $dataColumns, 'field-table' );
	        echo $this->element(sprintf('blocks/common/tables/export_%s', $export), array(
	            'tableHead' => $fieldColumn,
	            'tableBody' => $this->element($element),
                'periods' => $periodes,
	        ));
		}else{
			echo $this->Html->tag('p', __('Data belum tersedia'), array(
				'class' => 'alert alert-warning'
			));
		}

?>