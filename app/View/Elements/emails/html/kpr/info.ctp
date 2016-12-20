<?php
		$id = $this->Rumahku->filterEmptyField($params, 'KprBank', 'id');
		$dp = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'down_payment');
		$loan_price = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'loan_price');
		$credit_total = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'credit_total');
		$mls_id = $this->Rumahku->filterEmptyField($params, 'Property', 'mls_id');
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain');

        $interest_rate = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'interest_rate_fix');
        $total_first_credit = $this->Rumahku->creditFix($loan_price, $interest_rate, $credit_total);

		$total_first_credit = $this->Rumahku->getCurrencyPrice($total_first_credit);
		$dp = $this->Rumahku->getCurrencyPrice($dp);
		$loan_price = $this->Rumahku->getCurrencyPrice($loan_price);
   	 	$price = $this->Rumahku->filterEmptyField($params, 'KprBankInstallment', 'property_price');

   	 	if( !empty($price) ) {
        	$price = $this->Rumahku->getCurrencyPrice($price);
        } else {
        	$price = $this->Property->getPrice($params);
        }

		$link = $domain.$this->Html->url(array(
			'controller' => 'properties', 
			'action' => 'detail',
            $mls_id,
            'application' => $id,
			'admin' => true,
		));

		echo $this->Html->tag('h1', __('Rincian Informasi KPR'), array(
			'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #000;margin: 20px 0;'
		));
?>
<table cellpadding="0" cellspacing="0" border="0">
	<tbody>
	<tr>
			<td>
            <div>
              	<table style="line-height: 1.5em;">
              		<tbody>
	          			<?php  
	          					echo $this->Rumahku->_callLbl('table', __('Properti ID'), sprintf(__(': %s - %s'), $mls_id, $this->Html->link(__('Lihat Properti'), $link, array(
	          						'target' => '_blank',
									'style' => 'text-decoration: none; cursor: pointer;'
								))));
	          					echo $this->Rumahku->_callLbl('table', __('Harga Properti'), sprintf(__(': %s'), $price));
	          					echo $this->Rumahku->_callLbl('table', __('Uang Muka'), sprintf(__(': %s'), $dp));
	          					echo $this->Rumahku->_callLbl('table', __('Jumlah Pinjaman'), sprintf(__(': %s'), $loan_price));
	          					echo $this->Rumahku->_callLbl('table', __('Jangka Waktu'), sprintf(__(': %s Tahun'), $credit_total));
	          					echo $this->Rumahku->_callLbl('table', __('Angsuran Per Bulan'), sprintf(__(': %s'), $total_first_credit));
	          			?>
	                </tbody>
              	</table>
            </div>
        </td>
    </tr>
</tbody>
</table>