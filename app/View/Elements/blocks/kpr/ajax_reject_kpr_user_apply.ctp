<?php

		echo $this->element('blocks/kpr/ajax_filter_status');
		
		if( $result['status'] == 'error' ) {
			echo $this->element('blocks/common/modal/reject_kpr', array(
				'value' => $value
			));
		}
?>