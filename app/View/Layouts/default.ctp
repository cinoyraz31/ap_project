<?php  
		$general_path = Configure::read('__Site.general_folder');
		$site_name = Configure::read('__Site.site_name');
		$_global_variable = Configure::read('_global_variable');
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php 
			$default_css = array(
				'jquery',
				'style',
			);

			echo $this->Html->tag('title', $site_name) . PHP_EOL;
			echo $this->Rumahku->initializeMeta( $_global_variable );
	        echo $this->Html->css($default_css).PHP_EOL;

			if(isset($layout_css) && !empty($layout_css)){
				foreach ($layout_css as $key => $value) {
					echo $this->Html->css($value).PHP_EOL;
				}
			}

	        echo $this->Html->css(array(
	        	'custom',
        	)).PHP_EOL;

			echo $this->Html->meta($site_name, false, array(
				'type' => 'icon'
			)) . PHP_EOL;
	?>
</head>
<body>
	<div id="big-wrapper">
		<?php 
				echo $this->element('sidebars/left_menus').PHP_EOL;
        ?>
		<div id="content-wrapper">
			<?php 
					echo $this->element('headers/header').PHP_EOL;
					echo $this->element('headers/breadcrumb').PHP_EOL;
					echo $this->element('blocks/common/flash');
	        ?>
			<div id="content">
				<?php 
						echo $this->Html->tag('div', $this->fetch('content'));
		        ?>
			</div>
		</div>
	</div>
	<?php
			echo $this->element('footer/footer').PHP_EOL;

			$default_js = array(
				'jquery.library',
				'https://s3-ap-southeast-1.amazonaws.com/rmhstatic/js/location_home.js',
			);
	        echo $this->Html->script($default_js).PHP_EOL;

			if(isset($layout_js) && !empty($layout_js)){
				foreach ($layout_js as $key => $value) {
					echo $this->Html->script($value).PHP_EOL;
				}
			}

	        echo $this->Html->script(array(
				'customs.library',
	        	'functions',
        	)).PHP_EOL;
        	echo $this->element('blocks/common/modal').PHP_EOL;

    ?>
</body>
</html>
