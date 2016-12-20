<?php
	$label_header = !empty($label_header)?$label_header:false;
    $label = sprintf(__('Alamat Pembeli %s (Sesuai KTP)'), $label_header);
    $label2 = sprintf(__('Alamat Domisili %s'), $label_header);
	$address_2 = !empty($same_as_address_ktp)?__('Sama dengan KTP'):$address_2;
?>

<div class="row mb30">
	<div class="col-sm-6">
		<?php 
		        $label = $this->Html->tag('label', $label);
		        echo $label;

		        if(!empty($location)){
		        	$value =  $location;
		            $value = $this->Html->tag('span', $value);
		            echo  $this->Html->tag('div', $value, array(
		                'class' => 'clearfix'
		            ));
		        }
       	?>
	</div>
	<div class="col-sm-6">
		<?php
		        $label = $this->Html->tag('label', $label2);
		        echo $label;
		        $value =  $address_2;
	            $value = $this->Html->tag('span', $value);
	            echo  $this->Html->tag('div', $value, array(
                	'class' => 'clearfix'
	            ));
		        
       	?>
	</div>
</div>

