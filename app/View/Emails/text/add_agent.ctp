<?php
		$_site_name = !empty($_site_name)?$_site_name:false;
		$parent_fullname = !empty($params['parent_fullname'])?$params['parent_fullname']:false;

		printf(__('Anda telah terdaftar sebagai agen '.$parent_fullname.' di %s.'), $_site_name);
		echo "\n\n";
?>
