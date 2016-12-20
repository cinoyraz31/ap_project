<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
			$default_css = array(
				'login/jquery',
				'login/style',
				'login/customs',
			);
			$bankLogo = $this->Rumahku->filterEmptyField($active_bank, 'Bank', 'logo');
			$bankName = $_site_name;

			echo $this->Rumahku->initializeMeta( $_global_variable );
	        echo $this->Html->css($default_css).PHP_EOL;

	        if(isset($layout_css) && !empty($layout_css)){
				foreach ($layout_css as $key => $value) {
					echo $this->Html->css($value).PHP_EOL;
				}
			}
	?>
</head>
<body class="login">
	<div class="bg_body">
		<div id="header">
			<div class="container">
				<div class="row">
					<div class="col-md-2 col-xs-6">
						<?php
							echo $this->Html->image('/img/angkasa_pura.png');
						?>
					</div>
					<div class="col-md-2 col-xs-6 pull-right text-center">
						<?php
							if(!empty($bankLogo)){
								echo $this->Html->link($this->Rumahku->photo_thumbnail(array(
									'save_path' => Configure::read('__Site.logo_photo_folder'), 
									'src' => $bankLogo, 
									'size' => 'xxsm',
								), array(
									'alt' => $bankName,
									'title' => $bankName,
								)), array(
									'controller' => 'users',
									'action' => 'login',
									'admin' => true,
								), array(
									'escape' => false,
									'class' => 'navbar-brand logo',
								));
							}
							
						?>
					</div>
				</div>
			</div>
		</div>
    	<div class="login-box">
		<?php
			echo $this->Html->tag('div', $this->element('blocks/common/flash'), array(
				'class' => 'alert-container'
			));
			echo $this->Html->tag('div', $this->fetch('content'));
		?>
		</div>
    </div>
</body>
</html>
