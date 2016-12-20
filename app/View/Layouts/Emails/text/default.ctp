<?php
		$office_phone = !empty($_global_variable['office_phone'])?$_global_variable['office_phone']:false;
		$office_fax = !empty($_global_variable['office_fax'])?$_global_variable['office_fax']:false;
		$facebook = !empty($_global_variable['facebook'])?$_global_variable['facebook']:false;
		$twitter = !empty($_global_variable['twitter'])?$_global_variable['twitter']:false;

		echo $this->element('header/email/header_text');
		echo "\n\n";

		echo $content_for_layout;
		echo "\n\n";

		echo $this->element('footer/email/footer_text');
		echo "\n\n";

		echo __('Customer Support');
		echo "\n";

		echo Configure::read('__Site.site_name');
		echo "\n";

		echo Configure::read('__Site.site_description');
		echo "\n";

		echo FULL_BASE_URL;
		echo "\n";

		echo __('Phone');
		echo "\n";

		echo implode("\n", $office_phone);

		echo __('Fax');
		echo "\n";

		echo implode("\n", $office_fax);

		echo $facebook;
		echo "\n";

		echo $twitter;

		if( isset($params['debug']) && $params['debug'] == 'text' ){
			die();
		}
?>
