<div class="actions pull-right">
	<ul class="list-action-acl">
		<li>
			<?php 
				echo $this->Html->link(__('<i class="fa fa-exchange"></i> Manage permissions'), array('action' => 'permissions'), 
					array(
						'class' => 'btn btn-app',
						'escape' => false
					)
				); 
			?>
		</li>
		<li>
			<?php 
				echo $this->Html->link(__('<i class="fa fa-pencil-square-o"></i> Update ACOs'), array('action' => 'update_acos'),
					array(
						'class' => 'btn btn-app',
						'escape' => false
					)
				); 
			?>
		</li>
		<li>
			<?php 
				echo $this->Html->link(__('<i class="fa fa-pencil-square-o"></i> Update AROs'), array('action' => 'update_aros'),
					array(
						'class' => 'btn btn-app',
						'escape' => false
					)); 
			?>
		</li>
		<li>
			<?php 
				echo $this->Html->link(__('<i class="fa fa-times-circle"></i> Drop ACOs/AROs'), array('action' => 'drop'), array(
						'class' => 'btn btn-app',
						'escape' => false
					), __("Do you want to drop all ACOs and AROs?")); 
			?>
		</li>
		<li>
			<?php 
				echo $this->Html->link(__('<i class="fa fa-times-circle"></i> Drop permissions'), array('action' => 'drop_perms'), array(
						'class' => 'btn btn-app',
						'escape' => false
					), __("Do you want to drop all the permissions?")
					); 
			?>
		</li>
	</ul>
	<div class='clear'></div>
</div>
<div class='clear'></div>
