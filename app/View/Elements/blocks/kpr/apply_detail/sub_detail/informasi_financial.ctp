<?php 
        echo $this->Html->tag('label', __('Informasi Financial'));
        $options =  array(
            'divLabel' => 'col-sm-5',
            'divValue' => 'col-sm-7',
        );

        if(!empty($customIncome)){
            echo $this->Kpr->generateViewDetail(__('Penghasilan per Bulan'), sprintf( '%s', $customIncome), false, $options);
        }
        if(!empty($customHouseholdFee)){
            echo $this->Kpr->generateViewDetail(__('Pengeluaran per Bulan'), $customHouseholdFee, false, $options);
        }
        if(!empty($customOtherInstallment)){
            echo $this->Kpr->generateViewDetail(__('Angsuran Lain'), $customOtherInstallment, 'mb30', $options);
        }

        
?>