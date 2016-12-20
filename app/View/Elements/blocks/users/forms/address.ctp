<?php 
        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative  col-sm-6 col-xl-6',
        );
        echo $this->Rumahku->setFormAddress( 'UserProfile' );

        echo $this->Rumahku->buildInputForm('UserProfile.address', array_merge($options, array(
            'label' => __('Alamat Tempat Tinggal *'),
        )));
        echo $this->Rumahku->buildInputForm('UserProfile.region_id', array_merge($options, array(
            'id' => 'regionId',
            'label' => __('Provinsi *'),
            'empty' => __('Pilih Provinsi'),
        )));
        echo $this->Rumahku->buildInputForm('UserProfile.city_id', array_merge($options, array(
            'id' => 'cityId',
            'label' => __('Kota *'),
            'empty' => __('Pilih Kota'),
        )));
        echo $this->Rumahku->buildInputForm('UserProfile.subarea_id', array_merge($options, array(
            'id' => 'subareaId',
            'label' => __('Area *'),
            'empty' => __('Pilih Area'),
        )));
        echo $this->Rumahku->buildInputForm('UserProfile.zip', array_merge($options, array(
            'id' => 'rku-zip',
            'label' => __('Kode Pos *'),
            'class' => 'relative  col-sm-3 col-xl-5',
        )));
?>