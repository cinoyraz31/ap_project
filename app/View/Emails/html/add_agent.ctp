<?php 
		$_site_name = !empty($_site_name)?$_site_name:false;
		$parent_fullname = !empty($params['parent_fullname'])?$params['parent_fullname']:false;
		
?>
<p style="color: #303030; font-size: 14px; margin: 5px 0 0; line-height: 20px;">
	<?php
			printf(__('Anda telah terdaftar sebagai agen '.$parent_fullname.' di %s.'), $_site_name);
	?>
</p>