<?php 
		$User = $this->Rumahku->mergeUser($User);

		$userPhoto = $this->Rumahku->filterEmptyField($User, 'photo');
		$userFullName = $this->Rumahku->filterEmptyField($User, 'full_name');
		$userEmail = $this->Rumahku->filterEmptyField($User, 'email');

		$userGroup = $this->Rumahku->filterEmptyField($User, 'Group', 'name');
		$userLastLogin = $this->Rumahku->filterEmptyField($User, 'last_login');
		$cabang = $this->Rumahku->filterEmptyField($User, 'BankBranch', 'name');

		$loginDate = $this->Rumahku->formatDate($userLastLogin, 'd M Y', false);
?>
<div class="user-information">
	<?php 
			$changePhoto = $this->Html->link($this->Rumahku->icon('rv4-cam-2'), '#', array(
				'escape' => false,
				'class' => 'change-photo pick-file',
			));
			echo $this->Html->tag('span', $this->Rumahku->photo_thumbnail(array(
                'save_path' => Configure::read('__Site.profile_photo_folder'), 
                'src'=> $userPhoto, 
                'size' => 'pl',
            ), array(
            	'title' => $userFullName,
            	'alt' => $userFullName,
            )).$changePhoto, array(
            	'class' => 'user-photo relative',
            ));
	?>
	<div class="user-info">
		<?php 
	            echo $this->Html->tag('div', $userFullName, array(
	            	'class' => 'user-name',
	            ));
	            echo $this->Html->tag('div', $userEmail, array(
	            	'class' => 'user-email fs085',
	            ));

	            if( !empty($cabang) ) {
		            echo $this->Html->tag('div', sprintf(__('Cabang: %s'), $this->Html->tag('span', $cabang, array(
		            	'class' => 'color-red fbold',
		            ))), array(
		            	'class' => 'user-status fs085',
		            ));
		        }

	            if( !empty($loginDate) ) {
		            echo $this->Html->tag('div', sprintf(__('Terakhir login: %s'), $this->Html->tag('span', $loginDate, array(
		            	'class' => 'color-green fbold',
		            ))), array(
		            	'class' => 'user-last-login fs085',
		            ));
		        }
		?>
	</div>
</div>