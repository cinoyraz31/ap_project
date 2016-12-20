<?php
		$first_name = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'first_name');
		$last_name = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'last_name');
		$full_name = sprintf('%s %s', $first_name, $last_name);

		$email = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'email');
		$no_hp = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'no_hp');
		$no_hp_2 = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'no_hp_2');
		$pin_bb = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'pin_bb');
		$gender_id = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'gender_id');
		$address = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'address');
		$region = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'Region');
		$region_name = $this->Rumahku->filterEmptyField($region, 'name');
		$city = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'City');
		$city_name = $this->Rumahku->filterEmptyField($city, 'name');
		$subarea = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'Subarea');
		$subarea_name = $this->Rumahku->filterEmptyField($subarea, 'name');

		$location = $this->Rumahku->getGenerateAddress( $address, array(
	        'region' => $region_name,
	        'city' => $city_name,
	        'subarea' => $subarea_name,
	    ), ', ', '-');

		$gender = !empty($gender_id) ? $this->Rumahku->filterEmptyField($_global_variable, 'gender_options', $gender_id) : false;

		$btnSlide = $this->Html->link( $this->Rumahku->icon('rv4-angle-down'), '#', array(
	        'escape' => false,
	        'class' => 'toggle-display floright',
	        'data-display' => "#detail-owner-property",
	        'data-type' => 'slide',
	        'data-arrow' => 'true',
	    ));
	    $label = $this->Html->tag('label', __('Informasi Pemilik Properti'));

	    echo $this->Html->tag('div', $label.$btnSlide, array(
		    'class' => 'info-title hidden-print',
		    'id' => 'buyer-information',
		));
?>
<div id="detail-owner-property" class="hidden-print" style="display:none">
	<div class="tab-content">
		<div class="row">
		    <div class="col-sm-12">
		        <?php
		            	echo $this->Html->tag('label', __('Biodata Pemilik Properti'));
		        ?>
		    </div>
		    <div class="row">
		    	<div class="col-sm-6">
		    		<?php
					    	if(!empty($full_name)){
					            echo $this->Kpr->generateViewDetail(__('Nama'), $full_name, 'ml0');
					        }

					        if(!empty($no_hp)){
					            echo $this->Kpr->generateViewDetail(__('No. Handphone'), $no_hp, 'ml0');
					        }

					        if(!empty($no_hp_2)){
					            echo $this->Kpr->generateViewDetail(__('No. Handphone 2'), $no_hp_2, 'ml0');
					        }

					        if(!empty($gender)){
					            echo $this->Kpr->generateViewDetail(__('Jenis Kelamin'), $gender, 'ml0');
					        }

					        if(!empty($pin_bb)){
					            echo $this->Kpr->generateViewDetail(__('Pin BBM'), $pin_bb, 'ml0');
					        }
				    ?>
		    	</div>
		    	<div class="col-sm-4">
		    		<?php
			    			$label = $this->Html->tag('label', __('Alamat Pemilik'));
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
		    </div>
	    </div>
	</div>
</div>