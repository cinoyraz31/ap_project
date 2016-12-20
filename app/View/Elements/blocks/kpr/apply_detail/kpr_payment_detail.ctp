<?php
        $logged_group = !empty($logged_group)?$logged_group:false;
		$document_status = !empty($document_status)?$document_status:'pending';

		$kpr_payment_confirms = $this->Rumahku->filterEmptyField($kpr_application_confirm, 'KprCommissionPaymentConfirm');

		if($document_status == 'akad_credit'){
			if(!empty($kpr_payment_confirms)){
?>
<div class="col-sm-8 pr0 print-split-col">
	<div class="split two second-sect">
		<?php
				echo $this->Html->tag('h4', __('Provisi') );

				foreach($kpr_payment_confirms AS $key => $value){
					$payment_id = $this->Rumahku->filterEmptyField($value, 'id');
					$kpr_app_id = $this->Rumahku->filterEmptyField($value, 'kpr_application_confirm_id');
					$type_komisi = $this->Rumahku->filterEmptyField($value, 'type_komisi');
					$type_komisi_v = !empty($type_komisi)?ucfirst($type_komisi):false;
					$rate_komisi = $this->Rumahku->filterEmptyField($value, 'rate_komisi');
					$commission = $this->Rumahku->filterEmptyField($value, 'commission');
					$paid_fee_approved = $this->Rumahku->filterEmptyField($value, 'paid_fee_approved');
					$paid_date = $this->Rumahku->filterEmptyField($value, 'paid_date');

					echo $this->element('blocks/common/modal/paid_komisi', array(
						'type_komisi' => $type_komisi,
						'kpr_app_id' => $kpr_app_id,
						'payment_id' => $payment_id,
					));

					$type_komisi = ($type_komisi == 'rku')?Configure::read('__Site.site_name'):$type_komisi;

					if( $logged_group <= 10 ) {
						$pending_paid = $this->Html->link($this->Html->tag('b', __('Lakukan Pembayaran')), array(
							'controller' => 'kpr',
							'action' => 'paid',
							$kpr_app_id,
							$payment_id,
							'admin' => true
						), array(
			                'class'=> 'color-blue ajaxModal',
			                'title' => sprintf('Melakukan Pembayaran Provisi %s',$type_komisi),
			                'escape' => false,
			            ));
					} else {
						$pending_paid = false;
					}


					// echo $this->Rumahku
					$label = $this->Html->tag('div', $this->Html->tag('label', sprintf('%s %s', __('provisi'), $type_komisi)), array(
		                'class' => 'col-sm-4',
		            ));

		            $rate_komisi = sprintf('%s %%',$rate_komisi);
		            $commission = $this->Rumahku->getCurrencyPrice($commission);

		            $approved_paid = $this->Html->tag('b', __('Sudah Dibayarkan'), array(
		            	'class' => 'color-green',
		            ));
		            
		            $status = !empty($paid_fee_approved)?$approved_paid:$pending_paid;

		            $val = $this->Html->tag('div', sprintf(': %s - %s - %s', $rate_komisi, $commission,$status), array(
		                'class' => 'col-sm-6',
		            ));

		            echo $this->Html->div('row',$label.$val);

				}
		?>
	</div>
</div>
<?php	
			}
		}
?>