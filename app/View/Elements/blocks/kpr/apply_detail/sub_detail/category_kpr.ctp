<?php 
        $name_category = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'name');
        $code_category = $this->Rumahku->filterEmptyField($value, 'BankApplyCategory', 'code');
?>
<div class="row">
    <div class=	"col-sm-12">
        <?php
        		$label = $this->Html->tag('label', __('Jenis Permohonan'));
        		echo $label;
        ?>
    </div>
</div>
<div class="row mb30">
    <div class="col-sm-12">
    	<?php
        		$value = $this->Html->tag('span', sprintf('%s - %s', $name_category, $code_category));
        		echo $value;
        ?>
    </div>
</div>