<?php

        echo $this->element('blocks/kpr/ajax_filter_status');
        
        if( $result['status'] == 'error' ) {
            echo $this->element('blocks/common/modal/rejected_proposal', array(
                'value' => $value
            ));
        }
?>
