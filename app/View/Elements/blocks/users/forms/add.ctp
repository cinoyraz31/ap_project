<?php 
        echo $this->Rumahku->buildInputForm('username', array_merge($options, array(
            'label' => __('Username *'),
        )));
        echo $this->Rumahku->buildInputForm('email', array_merge($options, array(
            'label' => __('Email *'),
            'autocomplete' => 'off',
        )));
        echo $this->Rumahku->buildInputForm('password', array_merge($options, array(
            'type' => 'password',
            'label' => __('Password *'),
            'autocomplete' => 'off',
        )));
        echo $this->Rumahku->buildInputForm('password_confirmation', array_merge($options, array(
            'type' => 'password',
            'label' => __('Konfirmasi Password *'),
            'autocomplete' => 'off',
        )));
?>