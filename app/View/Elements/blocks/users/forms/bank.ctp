<?php 
        echo $this->Rumahku->buildInputForm('group_id', array_merge($options, array(
            'label' => __('Divisi *'),
            'empty' => __('Pilih Divisi'),
        )));
?>