<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta content="en-us" http-equiv="Content-Language">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<title><?php echo $title_for_layout;?></title>
		<style type="text/css">
			body {
				margin:0;
				padding:0;
				background-color:#cccccc;
				background:#cccccc;
			}
		</style>
		<?php 
				if(isset($layout_js) && !empty($layout_js)){
					foreach ($layout_js as $key => $value) {
						echo $this->Html->script($value);
					}
				}

				if(isset($layout_css) && !empty($layout_css)){
					foreach ($layout_css as $key => $value) {
						echo $this->Html->css($value);
					}
				}
		?>
	</head>
	<?php 
			$facebook = !empty($_global_variable['facebook'])?$_global_variable['facebook']:false;
			$twitter = !empty($_global_variable['twitter'])?$_global_variable['twitter']:false;
			$googleplus = !empty($_global_variable['googleplus'])?$_global_variable['googleplus']:false;
	?>
	<body style="background: #cccccc;" link="#1EB81E" vlink="#1EB81E" bgcolor="#cccccc">
		<div style="width: 640px; margin: 0 auto; padding: 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px; background: #ffffff;">
			<header>
				<div style="background: #5eab1f; padding: 10px 20px;">
					<ul style="margin: 0; padding: 5px 0px;; float: left;">
						<?php 
								$link = FULL_BASE_URL.$this->Html->url(array(
									'controller'=>'properties', 
									'action'=>'find',
									'admin'=>false
								));
								echo $this->Html->tag('li', $this->Html->link(__('Cari Rumah'), $link, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;'
								)), array(
									'style' => 'list-style: none; float: left; margin: 0 25px 0 0;'
								));

								$link = FULL_BASE_URL.$this->Html->url(array(
									'controller'=>'expo', 
									'action'=>'index',
									'admin'=>false
								));
								echo $this->Html->tag('li', $this->Html->link(__('Properti Baru'), $link, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;'
								)), array(
									'style' => 'list-style-type: none; float: left; margin: 0 25px 0 0;'
								));

								$link = FULL_BASE_URL.$this->Html->url(array(
									'controller'=>'advices', 
									'action'=>'index',
									'admin' => false
								));
								echo $this->Html->tag('li', $this->Html->link('Berita Properti', $link, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;'
								)), array(
									'style' => 'list-style: none; float: left; margin: 0 25px 0 0;'
								));

								$link = FULL_BASE_URL.$this->Html->url(array(
									'controller'=>'professional', 
									'action'=>'index',
									'admin'=>false
								));
								echo $this->Html->tag('li', $this->Html->link(__('Cari Agen'), $link, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;'
								)), array(
									'style' => 'list-style: none; float: left; margin: 0;'
								));
						?>
						<div style="clear: both; display: block;"></div>
					</ul>
					<ul style="margin: 0; padding: 0; float: right;">
						<?php 
								echo $this->Html->tag('li', $this->Html->link($this->Html->image('/img/email/socmed-twitter.png', array(
									'fullBase' => true,
									'style' => 'width:20px'
								)), $twitter, array(
									'style' => 'color: #ffffff;width:61px;',
									'escape' => false
								)), array(
									'style' => 'list-style: none; float: left; border-radius: 50%;background: #FFFFFF;padding: 3px 5px;margin-right:15px;'
								));

								echo $this->Html->tag('li', $this->Html->link($this->Html->image('/img/email/socmed-fb.png', array(
									'fullBase' => true,
									'style' => 'width:20px'
								)), $facebook, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;',
									'escape' => false
								)), array(
									'style' => 'list-style: none; float:left; border-radius: 50%;background: #FFFFFF;padding: 3px 5px;margin-right:15px;'
								));

								echo $this->Html->tag('li', $this->Html->link($this->Html->image('/img/email/socmed-gplus.png', array(
									'fullBase' => true,
									'style' => 'width:20px'
								)), $googleplus, array(
									'style' => 'color: #ffffff; font-size: 14px; text-decoration: none;',
									'escape' => false
								)), array(
									'style' => 'list-style: none; float:left; border-radius: 50%;background: #FFFFFF;padding: 3px 5px;'
								));
						?>
						<div style="clear: both; display: block;"></div>
					</ul>
					<div style="clear: both; display: block;"></div>
				</div>
				<div style="padding: 10px 20px; margin: 10px 0 0;">
					<div>
						<div style="float: left">
							<?php 
									echo $this->Html->image('/img/rumahku-logo.png', array(
										'fullBase' => true,
										'url' => FULL_BASE_URL,
									));

									if( !empty($params['title_email']) ) {
										echo $this->Html->tag('h1', $params['title_email'], array(
											'style' => 'font-size: 22px; color: #7e7e7e; font-weight: bold;margin: 0;padding: 0;text-align: right;'
										));
									}
							?>
						</div>
						<div style="float: right; font-size: 18px; color: #909090;">
							<?php 
									echo date('d F Y');
							?>
						</div>
						<div style="clear: both; display: block;"></div>
					</div>
				</div>
			</header>
			<?php
				$_other_banner = isset($params['_other_banner'])?$params['_other_banner']:false;

				if($_other_banner){
			?>
			<div style="background-image: url(<?php echo FULL_BASE_URL;?>/img/email/hd-background.jpg);height:151px;padding:20px;color:#fff;font-family: helvetica;">
				<div style="float:left; width:440px;">
					<h2 style="font-size: 21px;font-weight: 700;margin-bottom: 10px;margin-top: 0px;">PROPERTI INI MILIK ANDA?</h2>
					<p style="font-size: 14px;margin-bottom: 20px;"><?php printf('klik "<b>KLAIM SEKARANG</b>" agar Anda dapat terhubung dengan kami: <b style="color: #5EAB1F;">%s</b>, situs properti terlengkap di Indonesia yang dapat mempermudah  pemasaran  dan meningkatkan penjualan properti Anda.', Configure::read('__Site.site_name'))?></p>
					<?php
						echo $this->Html->link(__('KLAIM SEKARANG'), 'javascript:', array(
							'style' => 'font-size: 14px;color: #fff;background: #468017;border-bottom: 2px solid #056537;border-radius: 3px;padding: 10px 20px;text-decoration: none;'
						));
					?>
				</div>
				<div style="float:right">
					<?php
						echo $this->Html->image('/img/email/property-icon-white.png', array(
							'fullBase' => true
						));
					?>
				</div>
				<div class="clear:both;"></div>
			</div>
			<?php
				}

				$_full_size_content = isset($params['_full_size_content'])?$params['_full_size_content']:false;
				$size = 'padding: 10px 20px;';

				if($_full_size_content){
					$size = 'padding: 10px 0px;';
				}
			?>
			<div style="<?php echo $size;?> margin: 0;">
				<?php
						$_header = isset($params['_header'])?$params['_header']:true;

						if( $_header ) {
							echo $this->element('header/email/header');
						}
						echo $content_for_layout;
				?>
			</div>
			<?php 
					echo $this->element('footer/email/footer');
			?>
		</div>
	</body>
</html>
<?php 
	if( isset($params['debug']) && $params['debug'] == 'view' ){
		die();
	}
?>