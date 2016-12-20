<div class="success-full alert">
	<?php 
			echo $this->Html->tag('p', sprintf('%s %s', $this->Html->tag('strong', __('Selamat!')), $message), array(
				'id' => 'msg-text',
			));
			echo $this->Html->tag('div', 'success', array(
				'id' => 'msg-status',
				'class' => 'hide',
			));
	?>
</div>