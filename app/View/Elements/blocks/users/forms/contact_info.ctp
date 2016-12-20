<?php 
        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative col-sm-4 col-xl-3',
        );

        echo $this->Rumahku->buildInputForm('UserProfile.phone', array_merge($options, array(
            'label' => __('No. Telepon'),
            'class' => 'relative col-sm-3 col-xl-2',
            'infoText' => __('Harap gunakan kode area untuk nomor telepon. Contoh: 0215332555'),
        )));

        echo $this->Rumahku->buildInputForm('UserProfile.no_hp', array_merge($options, array(
            'label' => __('No. handphone #1 *'),
            'class' => 'relative col-sm-3 col-xl-2',
            'infoText' => __('Harap masukkan nomor handphone dengan benar. Contoh: 0822121212'),
        )));

        echo $this->Rumahku->buildInputForm('UserProfile.no_hp_2', array_merge($options, array(
            'label' => __('No. handphone #2'),
            'class' => 'relative col-sm-3 col-xl-2',
            'infoText' => __('Harap masukkan nomor handphone dengan benar. Contoh: 0822121212'),
        )));

        echo $this->Rumahku->buildInputForm('UserProfile.pin_bb', array_merge($options, array(
            'label' => __('Pin Blackberry'),
            'class' => 'relative col-sm-3 col-xl-2',
        )));

        echo $this->Rumahku->buildInputForm('UserProfile.line', array_merge($options, array(
            'label' => __('ID Line Messenger'),
            'class' => 'relative col-sm-3 col-xl-2',
        )));
?>