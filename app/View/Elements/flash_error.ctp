<div class="error-full alert">
	<?php 
			echo $this->Html->tag('p', sprintf('%s %s', $this->Html->tag('strong', __('Uppss!')), $message), array(
				'id' => 'msg-text',
			));
			echo $this->Html->tag('div', 'error', array(
				'id' => 'msg-status',
				'class' => 'hide',
			));
	?>
</div>