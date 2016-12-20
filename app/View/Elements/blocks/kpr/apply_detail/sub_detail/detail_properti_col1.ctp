<?php
        $label = $this->Property->getNameCustom($value);
        $price = $this->Property->getPrice($value, __('(Harga belum ditentukan)'));
        $specs = $this->Property->getSpec($value);

        echo $this->Html->tag('div', $this->Html->link($label, $url, array(
            'escape' => false,
        )), array(
            'class' => 'label',
        ));
        echo $this->Html->tag('div', $this->Html->link($title, $url, array(
            'escape' => false,
        )), array(
            'class' => 'title',
        ));
        
        if( !empty($mls_id) ) {
            $customMlsId = sprintf(__('ID Properti: %s'), $this->Html->tag('strong', $mls_id));
            echo $this->Html->tag('div', $customMlsId);
        }

        echo $this->Html->tag('div', $specs, array(
            'class' => 'specs',
        ));
?>
