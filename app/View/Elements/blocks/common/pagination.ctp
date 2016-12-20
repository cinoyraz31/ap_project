<?php
		$options = !empty($options)?$options:array();
		$class = false;

		if( !empty($url) ) {
			$options['url'] = $url;
		}

		if( !empty($options['class']) ) {
			$class = $options['class'];
		}

		$options['show_count'] = isset($show_count)?$show_count:true;
		$options['escape'] = false;
		$optionFirst = array_merge($options, array('class' =>'first'));
		$optionPrev = array_merge($options, array('class' =>'prev'));
		$optionNumber = array_merge(array(
			'separator'=>'',
			'tag'=>'li',
			'modulus' => 4,
			'class' => 'page',
		), $options);

		$optionNext = $options;
		$optionLast = array_merge($options, array('class' =>'last'));
		$classpaginate = 'col-sm-9';

		if( empty($options['show_count']) ) {
			$classpaginate = 'col-sm-12';

			if( !empty($classTxt) ) {
				$classpaginate .= ' '.$classTxt;
			} else {
				$classpaginate .= ' text-center';
			}
		}
?>
<div class="pagination-content clear">
	<ul class="pagination">
		<?php 
				if($this->Paginator->hasPrev()):
					echo $this->Html->tag('li', $this->Paginator->first('&laquo;',$optionFirst), array(
						'class' => $class,
						'data-toggle' => 'tooltip',
						'title' => __('Pertama'),
					));
					echo $this->Html->tag('li', $this->Paginator->prev('&lsaquo;',$optionPrev), array(
						'class' => $class,
						'data-toggle' => 'tooltip',
						'title' => __('Sebelumnya'),
					));
				endif;

				echo $this->Paginator->numbers($optionNumber);

				if($this->Paginator->hasNext()):
					echo $this->Html->tag('li', $this->Paginator->next('&rsaquo;',$optionNext), array(
						'class' => $class,
						'data-toggle' => 'tooltip',
						'title' => __('Selanjutnya'),
					));
					echo $this->Html->tag('li', $this->Paginator->last('&raquo;', $optionLast), array(
						'class' => $class,
						'data-toggle' => 'tooltip',
						'title' => __('Terakhir'),
					));
				endif;
		?>
	</ul>
</div>