<div class="hidden-print">
	<?php 
			if( !empty($module_title) ) {
	        	$pageCount = $this->Paginator->counter(array('format' => '%count%'));

				if( !empty($pageCount) ) {
			        $pageCount = $this->Rumahku->getFormatPrice($pageCount);
			        $pageCount = $this->Html->tag('label', sprintf('%s %s Data', $this->Rumahku->icon('rv4-list'), $pageCount), array(
			            'class' => 'datatable-count'
			        ));
			    } else {
			    	$pageCount = false;
			    }

				echo $this->Html->tag('div', $this->Html->tag('h2', $module_title).$pageCount, array(
					'id' => 'crumbtitle',
					'class' => 'clear',
				));
			}
	?>
</div>