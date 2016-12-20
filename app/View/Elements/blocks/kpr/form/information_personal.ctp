<?php
    $modelName = !empty($modelName)?$modelName:'KprApplication';
    $idx = isset($idx)?$idx:0;
    $labelHeader = !empty($labelHeader)?$labelHeader:false;
    echo $this->Html->tag('h2', sprintf(__('Informasi Tambahan %s'), $labelHeader), array(
        'class' => 'sub-heading'
    ));

    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.company', array_merge($options, array(
        'label' => __('Perusahaan *'),
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.job_type_id', array_merge($options, array(
        'type' => 'select',
        'label' => __('Jenis Pekerjaan *'),
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.income', array_merge($options, array(
        'type' => 'text',
        'label' => __('Penghasilan per Bulan *'),
        'inputClass' => 'input_price',
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.household_fee' , array_merge($options, array(
        'type' => 'text',
        'label' => __('Pengeluaran per Bulan *'),
        'inputClass' => 'input_price',
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.other_installment', array_merge($options, array(
        'type' => 'text',
        'label' => __('Angsuran Lain'),
        'inputClass' => 'input_price',
    )));
?>