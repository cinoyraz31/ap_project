<?php
        $idx = isset($idx)?$idx:0;
        $labelHeader = !empty($labelHeader)?$labelHeader:false;
        $modelName = !empty($modelName)?$modelName:'KprApplication';

        echo $this->Html->tag('h2', sprintf(__('Kontak Pembeli %s'), $labelHeader), array(
            'class' => 'sub-heading'
        ));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.email', array_merge($options, array(
            'label' => __('Email *'),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.phone', array_merge($options, array(
            'label' => __('No. Telp *'),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.no_hp', array_merge($options, array(
            'label' => __('No. Handphone *'),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.no_hp_2', array_merge($options, array(
            'label' => __('No. Handphone 2'),
        )));
?>