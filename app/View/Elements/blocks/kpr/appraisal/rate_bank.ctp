<?php
	$mandatory = !empty($mandatory)?$mandatory:false;
	$data = $this->request->data;
	$bankProduct = $this->Rumahku->filterEmptyField($data, 'BankProduct');
	$periode_fix = $this->Rumahku->filterEmptyField($data, 'KprApplicationConfirm', 'periode_fix');
	$credit_fix = $this->Rumahku->filterEmptyField($data, 'KprApplicationConfirm', 'credit_fix');
?>

<div class="row">
	<div class="col-sm-6">
		<?php 
				echo $this->Rumahku->buildInputForm('interest_rate_fix', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Suku Bunga Fix %s'), $mandatory),
	                'inputClass' => 'interest_rate',
                	'textGroup' => __('%'),
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
                	'attributes' => array(
			            'action_type' => 'interest_rate',
			        ),
	            )));
		?>
	</div>
	<div class="col-sm-6">
		<?php 
	            echo $this->Rumahku->buildInputForm('periode_fix', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Periode Bunga Fix %s'), $mandatory),
	                'inputClass' => 'credit_fix',
                	'textGroup' => __('Tahun'),
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
					'attributes' => array(
			            'action_type' => 'credit_fix',
			            'data-default' => $credit_fix,
			            'label-name' => __('periode Fix'),

			        ),
	            )));
		?>
	</div>
</div>
	<?php
			if($bankProduct){
	?>
<div class="row">
	<div class="col-sm-6">
		<?php 
				echo $this->Rumahku->buildInputForm('interest_rate_cabs', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Suku Bunga Cap %s'), $mandatory),
	                'inputClass' => 'interest_cap',
                	'textGroup' => __('%'),
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
	            )));
		?>
	</div>
	<div class="col-sm-6">
		<?php 
	            echo $this->Rumahku->buildInputForm('periode_cab', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Periode Bunga Cap %s'), $mandatory),
	                'inputClass' => 'credit_cap',
                	'textGroup' => __('Tahun'),
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
	            )));
		?>
	</div>
</div>
	<?php
			}
	?>
<div class="row">
	<div class="col-sm-6">
		<?php 
	            echo $this->Rumahku->buildInputForm('interest_rate_float', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Suku Bunga Floating %s'), $mandatory),
	                'inputClass' => 'floating_rate',
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
                	'textGroup' => __('%'),
	            )));
		?>
	</div>
	<div class="col-sm-6">
		<?php 
				echo $this->Form->hidden('periode_installment');
	            echo $this->Rumahku->buildInputForm('credit_total', array_merge($options, array(
	            	'type' => 'text',
	                'label' => sprintf(__('Lama Pinjaman Cicilan %s'), $mandatory),
            		'inputClass' => 'periode_fix',
                	'textGroup' => __('Tahun'),
					'labelClass' => 'col-sm-6 control-label',
					'class' => 'relative col-sm-4',
                	'attributes' => array(
			            'action_type' => 'periode_fix',
			            'data-default' => $periode_fix,
			            'label-name' => __('periode cicilan'),
			        ),
	            )));
		?>
	</div>
</div>