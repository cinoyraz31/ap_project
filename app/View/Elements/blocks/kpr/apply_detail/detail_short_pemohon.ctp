<?php
		$bantuan_kpr = !empty($bantuan_kpr) ? $bantuan_kpr : false;
		$group_id = $this->Rumahku->filterEmptyField($User, 'group_id'); 
        $document_status = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'document_status');
        $application = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'KprApplication');
        $application_snyc = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'application_snyc');

        $log_document_status = Set::classicExtract($value, 'KprBank.KprBankDate.{n}.KprBankDate.slug');
        $log_document_status = !empty($log_document_status) ? $log_document_status : array();

        if( $document_status == 'approved_bank' ){
	        $display = __('block');
	        $icon = $this->Rumahku->icon('rv4-angle-up');
	    }else{
	        $display = __('none');
	        $icon = $this->Rumahku->icon('rv4-angle-down');
	    }

	    $label = $this->Html->tag('label', __('Informasi Pembeli'));

	    $btnSlide = $this->Html->link( $icon, '#', array(
	        'escape' => false,
	        'class' => 'toggle-display floright',
	        'data-display' => "#detail-project-info",
	        'data-type' => 'slide',
	        'data-arrow' => 'true',
	    ));

	    echo $this->Html->tag('div', $label.$btnSlide, array(
	        'class' => 'info-title hidden-print',
	        'id' => 'buyer-information',
	    ));

?>
	<div class="row" id="detail-project-info" class="visible-print" style="display:<?php echo $display; ?>;">
		<div class="row">
			<div class="tab-content">
			    <div class="col-sm-12 ml5">
			        <?php
			                echo $this->Html->tag('label', __('Biodata Pembeli'));
			        ?>
			    </div>
		    </div>
		</div>
		<?php
			 $flags = (in_array('approved_proposal', $log_document_status) || ( in_array('proposal_without_comiission', $log_document_status) &&  !empty($application_snyc) ) )?true:false;

			 if( $flags ||  !empty($bantuan_kpr) || in_array($group_id, array(19,20))){
		?>
			<div class="col-sm-12">
				<div class="tab-content">
		            <div class="row">
		                <div class="col-sm-6">
		                    <?php
		                            echo $this->element('blocks/kpr/apply_detail/sub_detail/biodata_short_pemohon');
		                    ?>
		                </div>
		            </div>
				</div>
			</div>
	        <div class="col-sm-12">
	        	<div class="tab-content">
		            <?php
		                    $document = $this->Rumahku->filterEmptyField($application, 'Document');
		                    if(!empty($document)){
		                        echo $this->element('blocks/kpr/apply_detail/sub_detail/document_detail', array(
		                            'document' => $document,
		                        )); 
		                    }
		            ?>
	            </div>
	        </div>
		<?php
			 }else{
		?>
			<div class="row mb30">
	            <div class="col-sm-12">
	            	<div class="tab-content">
	                <?php
	                        echo $this->Html->image('sssecret-data.jpg');
	                ?>
	                </div>
	            </div>
	        </div>
		<?php
			 }
		?>
	</div>