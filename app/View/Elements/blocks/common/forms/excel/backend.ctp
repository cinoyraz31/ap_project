<div class="col-sm-2">
	<div class="excel-type excel-style-1">
        <div class="form-group">
        	<?php 
                    $urlExport = $this->Rumahku->urlExport($this->here, 'excel');
                    
        			echo $this->Html->link($this->Rumahku->icon('rv4-doc').__('Excel'), $urlExport, array(
                        'escape' => false,
                        'id' => 'kpr-export-excel',
        				'class' => 'btn green',
    				));
        	?>
		</div>
    </div>
</div>