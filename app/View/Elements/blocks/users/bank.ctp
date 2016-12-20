<?php 
        echo $this->Rumahku->buildInputForm('bank_id', array_merge($options, array(
            'label' => __('User Bank *'),
            'empty' => __('Pilih Bank'),
            'inputClass' => 'triggerChange',
            'attributes' => array(
                'data-target' => '.targetChange',
                'data-url' => $this->Html->url(array(
                    'controller' => 'ajax',
                    'action' => 'get_bank_branches',
                    'admin' => false,
                )),
            ),
        )));
        echo $this->Rumahku->buildInputForm('bank_branch_id', array_merge($options, array(
            'label' => __('Cabang *'),
            'empty' => __('Pilih Cabang'),
            'inputClass' => 'targetChange',
        )));
?>