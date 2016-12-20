<?php
    $data = $this->request->data;
    $modelName = !empty($modelName)?$modelName:'KprApplication';
    $idx = isset($idx)?$idx:0;
    $_global_variable = !empty($_global_variable)?$_global_variable:false;
    $gender_options = $this->Rumahku->filterEmptyField($_global_variable, 'gender_options');
    $class_marital = !empty($class_marital)?$class_marital:false;
    $disabled_marital = !empty($disabled_marital)?$disabled_marital:false;
    $statusMaritals = $this->Rumahku->filterEmptyField( $_global_variable, 'status_marital');
    $optionAttributes = !empty($optionAttributes)?$optionAttributes:array();
    $marital = !empty($data[$modelName][$idx]['status_marital']) ? $data[$modelName][$idx]['status_marital'] : false;


    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.first_name', array_merge($options, array(
        'label' => __('Nama Depan *'),
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.last_name', array_merge($options, array(
        'label' => __('Nama Belakang *'),
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.birthplace', array_merge($options, array(
        'label' => __('Tempat Lahir *'),
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.gender_id', array_merge($options, array(
        'label' => __('Jenis Kelamin *'),
        'options' => $gender_options,
    )));
    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.birthday', array_merge($options, array(
        'label' => __('Tanggal Lahir *'),
        'type' => 'text',
        'inputClass' => 'datepicker',
    )));
?>

<?php

    if(empty($disabled_marital)){
        $merge_options =  array_merge( $options, array(
            'label' => __('Status Nikah *'),
            'inputClass' => $class_marital,
            'options' => array(
                $statusMaritals,
            ),
        ));
        if(!empty($optionAttributes)){
            $merge_options = array_merge($merge_options, $optionAttributes);
        }
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.status_marital', $merge_options);
    }
    // echo $this->Form->hidden($modelName.'.'.$idx.'.status_marital', array(
    //     'value' => $marital
    // ));

    echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.ktp', array_merge($options, array(
        'label' => __('No. KTP *'),
    )));
?>