
<?php
    
    $id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
    $document_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'document_status');
    $from_web = $this->Rumahku->filterEmptyField($value, 'KprBank', 'from_web');
    $application_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_status');

    $flag = (($document_status == 'approved_proposal') || ($document_status == 'proposal_without_comiission'  && !empty($application_snyc)));

    if($flag || $from_web == 'rumahku'){
        echo $this->Html->link(__('Proses'), array(
            'action' => 'approved_bank',
            $id,
            'admin' => true
        ), array(
            'class'=> 'btn green ajaxModal',
            'title' => __('Anda bisa merubah data aplikasi KPR'),
        ));

        echo $this->Html->link(__('Tolak'), array(
                'controller' => 'kpr',
                'action' => 'rejected',
                $id,
                'admin' => true
            ), array(
            'class'=> 'btn red ajaxModal',
            'title' => sprintf('Anda yakin ingin menolak aplikasi KPR ini ?'),
            'escape' => false,
            'data-color' => 'red',
        ));

        // echo $this->Html->link(__('Edit Pinjaman'), array(
        //     'controller' => 'kpr',
        //     'action' => 'confirm_appraisal',
        //     $id,
        //     'admin' => true
        // ), array(
        //     'class'=> 'btn green',
        // ));

    }else{

        if(in_array($document_status, array('approved_admin'))){

            echo $this->Html->link(__('Proses Referral'), array(
                'controller' => 'kpr',
                'action' => 'approved',
                $id,
                'admin' => true
            ), array(
                'class'=> 'btn green ajaxModal',
                'title' => __('PROSES REFERRAL'),
                'data-subtitle' => __('Mohon isi Form dibawah ini'),
            ));
        }
    
    }
    

?>