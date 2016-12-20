<?php
        echo $this->Html->tag('h2', __('Bunga dan Periode'), array(
            'class' => 'sub-heading'
        ));
        echo $this->element('blocks/common/multiple_forms', array(
            'divClassTop' => 'col-sm-6',
            'fieldName' => 'interest_rate',
            'modelName' => 'BankSettingRate',
            'labelName' => false,
            'limit' => 1,
            'element' => 'blocks/bank/forms/interest_rate_items',
        ));
?>