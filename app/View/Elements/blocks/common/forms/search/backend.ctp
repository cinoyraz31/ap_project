<?php 
		$url = !empty($url)?$url:false;
		$placeholder = !empty($placeholder)?$placeholder:false;
		$_advanced = !empty($_advanced)?$_advanced:false;
		$addClass = '';

		$sorting = !empty($sorting)?$sorting:false;
		$buttonDelete = !empty($sorting['buttonDelete'])?$sorting['buttonDelete']:false;
		$overflowDelete = !empty($sorting['overflowDelete'])?$sorting['overflowDelete']:false;
		$buttonAdd = !empty($sorting['buttonAdd'])?$sorting['buttonAdd']:false;
		$options = !empty($sorting['options'])?$sorting['options']:false;
		$search = isset($search)?$search:true;

		$datePicker = !empty($datePicker)?$datePicker:false;

		if( empty($_advanced) ) {
			$addClass = 'no-side-left';
		}

		echo $this->Form->create('Search', array(
    		'url' => $url,
    		'id' => 'SearchForm',
		));

if($search){
?>
<div class="search-style-1">
	<div class="row">
		<div class="col-sm-12">
		    <div class="input-group <?php echo $addClass; ?>">
				<?php 
					

						echo $this->Html->link(__('Pencarian'), 'javascript:', array(
							'escape' => false,
                            'class'=> 'input-group-addon at-left',
                            'role' => 'button',
                        ));
						echo $this->Form->input('keyword', array(
                            'type' => 'text', 
							'label' => false,
                            'div' => false,
                            'class'=> 'form-control has-side-control at-left',
                            'placeholder' => $placeholder,
                        ));
						echo $this->Form->button($this->Rumahku->icon('rv4-magnify'), array(
                            'type' => 'submit', 
                            'class'=> 'btn-search',
                        ));

                        if( !empty($_advanced) ) {
							echo $this->Html->link(__('Detail Pencarian').$this->Rumahku->icon('caret'), 'javascript:', array(
								'escape' => false,
	                            'class'=> 'input-group-addon at-right',
	                            'role' => 'button',
	                        ));
						}
				?>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
<div class="form-type">
	<div class="row">
		<?php 
				if( !empty($header_title) ) {
			        echo $this->Html->tag('div', $this->Html->tag('h2', $header_title), array(
			        	'class' => 'col-sm-4',
		        	));
				} else if( !empty($sorting) ) {
			        echo $this->element('blocks/common/forms/sorting/backend', array(
				        'sorting' => $options,
			    	));
				}
				if( !empty($buttonAdd) ) {
					echo $this->Rumahku->buildButton($buttonAdd, 'col-sm-2 pull-right', 'btn blue');
				}

				if( !empty($buttonDelete) ) {
					$class = isset( $buttonDelete['class'] ) ? $buttonDelete['class'] : 'btn red';
					$btnClass = isset( $buttonDelete['btn_class'] ) ? $buttonDelete['btn_class'] : false;
					echo $this->Rumahku->buildButton($buttonDelete, 'col-sm-2 button-type button-style-1 '.$btnClass, $class.' hide');

					if( !empty($overflowDelete) ) {
		?>
		<div class="delete-overflow clear">
			<div class="counter floleft">
				<?php 
						echo $this->Html->tag('span', 0);
						echo __(' Data dihapus');
				?>
			</div>
			<div class="action-delete floright">
				<?php 
						$buttonDelete['text'] = $this->Rumahku->icon('rv4-cross').__('Hapus');
						echo $this->Rumahku->buildButton($buttonDelete);
				?>
			</div>
		</div>
		<?php
					}
				}

				if( !empty($exportExcel) ) {
			        echo $this->element('blocks/common/forms/excel/backend');
				}

				if( !empty($datePicker) ) {
			        echo $this->element('blocks/common/forms/datepicker/backend');
				}

				echo $this->Form->end(); 
		?>
	</div>
</div>