<?php
	## GET PARAMS ELEMENT
	$document = !empty($document)?$document:false;
	##
	$cnt_document = count($document);
	$divided = round($cnt_document/2);
?>
<div class="mt30">
	<div class="row">
		<div class="col-sm-12">
			<?php
					echo $this->Html->tag('h3', __('Kelengkapan Dokumen'), array(
						'class' => 'mb20'
					));
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<ul class="document">
		<?php
				for($i=0; $i < $divided; $i++){
					$name = $this->Rumahku->filterEmptyField($document[$i], 'DocumentCategory', 'name');
					$cek_document = $this->Rumahku->filterEmptyField($document[$i], 'Document');
					$document_category_id = $this->Rumahku->filterEmptyField($cek_document, 'document_category_id');
					$owner_id = $this->Rumahku->filterEmptyField($cek_document, 'owner_id');
					$document_type = $this->Rumahku->filterEmptyField($cek_document, 'document_type');
					$lihatfile = null;
					if(!empty($cek_document)){

						$lihatfile = $this->Html->link(__('Download'), array(
				            'controller' => 'kpr',
				            'action' => 'view_thumbnail',
				            $owner_id,
				            $document_category_id,
				            $document_type,
				            'admin' => true,
				        ), array(
				            'class' => 'ml10',
				            'title' => $name,
				        ));

						$icon = $this->Rumahku->icon('rv4-bold-check', false, 'i', 'color-green');
					}else{
						$icon = $this->Rumahku->icon('rv4-bold-cross', false, 'i', 'color-red');
					}
					echo $this->Html->tag('li',sprintf('%s %s - %s', $icon, $name, $lihatfile), array(
						'class' => 'mb15'
					));
				}
		?>
		</ul>
	</div>
	<div class="col-sm-6">
		<ul class="document">
		<?php
			for($i=$divided; $i < $cnt_document; $i++){
				$name = $this->Rumahku->filterEmptyField($document[$i], 'DocumentCategory', 'name');
				$cek_document = $this->Rumahku->filterEmptyField($document[$i], 'Document');
				$document_category_id = $this->Rumahku->filterEmptyField($cek_document, 'document_category_id');
				$owner_id = $this->Rumahku->filterEmptyField($cek_document, 'owner_id');
				$document_type = $this->Rumahku->filterEmptyField($cek_document, 'document_type');
				$lihatfile = null;
				
				if(!empty($cek_document)){
					$lihatfile = $this->Html->link(__('Download'), array(
			            'controller' => 'kpr',
			            'action' => 'view_thumbnail',
			            $owner_id,
			            $document_category_id,
			            $document_type,
			            'admin' => true,
			        ), array(
			            'class' => 'ml10',
			            'title' => $name,
			        ));

					$icon = $this->Rumahku->icon('rv4-bold-check', false, 'i', 'color-green');
				}else{
					$icon = $this->Rumahku->icon('rv4-bold-cross', false, 'i', 'color-red');
				}
				echo $this->Html->tag('li',sprintf('%s %s - %s', $icon, $name, $lihatfile), array(
					'class' => 'mb15'
				));
			}
		?>
		</ul>
	</div>
</div>