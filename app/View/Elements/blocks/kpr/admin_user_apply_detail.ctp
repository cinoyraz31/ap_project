<?php   
        $_isAdmin = Configure::read('User.admin');
        $property_address = $this->Rumahku->filterEmptyField($value, 'PropertyAddress');
        $property = $this->Rumahku->filterEmptyField($value, 'Property');
        $kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
        $document_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'document_status');
        $application_snyc = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_snyc');
        $bankName = $this->Rumahku->filterEmptyField($value, 'Bank', 'name');

        if(!empty($property_address)){
            $property['PropertyAddress'] = $property_address;
        }

        ## DOCUMENT
        $kpr_bank = $this->Rumahku->filterEmptyField($value, 'KprBank');
        ##
        $id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
        $code = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');
        $urlProperty = array(
            'controller' => 'properties',
            'action' => 'detail',
            'application' => $id,
            'admin' => true,
        );

        $kpr_owner_email = $this->Rumahku->filterEmptyField($value, 'KprOwner', 'email');
        $from_web = $this->Rumahku->filterEmptyField($value, 'KprBank', 'from_web');
        $from_kpr = $this->Rumahku->filterEmptyField($value, 'KprBank', 'from_kpr');
        $application_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_status');
        $log_document_status = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');
        $log_document_status = !empty($log_document_status) ? $log_document_status : array();

        $user = $this->Rumahku->filterEmptyField($value, 'User');
        $user_profile = $this->Rumahku->filterEmptyField($value, 'UserProfile');
        
        $full_name_agent = $this->Rumahku->filterEmptyField( $user, 'full_name');
        $address_agen = $this->Rumahku->filterEmptyField( $user_profile, 'address');
        $address2_agen = $this->Rumahku->filterEmptyField( $user_profile, 'address2');
        $photo_agen = $this->Rumahku->filterEmptyField( $user, 'photo');

        $phone_agen = $this->Rumahku->filterEmptyField( $user_profile, 'phone');
        $no_hp_agen = $this->Rumahku->filterEmptyField( $user_profile, 'no_hp');
        $no_hp_2_agen = $this->Rumahku->filterEmptyField( $user_profile, 'no_hp_2');
        $pin_bb_agen = $this->Rumahku->filterEmptyField( $user_profile, 'pin_bb');

        $log_kpr = !empty($log_kpr)?$log_kpr:false;
        $help_apply = !empty($help_apply)?$help_apply:false;

        if( empty($help_apply) && empty($log_kpr) && !empty($kpr_bank) ) {
            echo $this->Kpr->_callStepNotice($value);
        }

        echo $this->Form->create('KprBank', array(
            'id' => 'frmKPRApplication'
        ));
        echo $this->element('blocks/common/modal/paid_agent');
?>
<div class="mb30">
    <div class="dashbox">
        <?php 
                if( empty($log_kpr) ) {
                    echo $this->element('blocks/kpr/apply_detail/header_title', array(
                        'code' => $code,
                    ));
                }
        ?>
        <div class="boxbody">
            <?php   
                    echo $this->element('blocks/kpr/apply_detail/profile_client');
            ?>
            <div class="app-detail">
                <?php

                        echo $this->element('blocks/kpr/apply_detail/application_detail');

                        if(!empty($documentKPR)){
                            echo $this->Html->tag('div', $this->element('blocks/kpr/apply_detail/sub_detail/document_detail', array(
                                'document' => $documentKPR,
                            )), array(
                                'class' => 'tab-content hidden-print',
                            )); 
                        }
                        
                        $flag_application = ($application_status == 'completed' && (in_array('approved_proposal', $log_document_status)  || (!empty($application_snyc) && in_array('proposal_without_comiission', $log_document_status))) || !empty($log_kpr));

                        if($flag_application || ($from_web == 'rumahku') || ( $_isAdmin && $application_status == 'completed')) {
                            echo $this->element('blocks/kpr/apply_detail/detail_pemohon');
                        }else{
                            echo $this->element('blocks/kpr/apply_detail/detail_short_pemohon');
                        }

                        if(!empty($user)){
                            echo $this->element('blocks/kpr/apply_detail/profile_agen', array(
                                'log_kpr' => $log_kpr,
                            ));
                        }

                        if(!empty($property)){
                            echo $this->element('blocks/kpr/apply_detail/detail_property', array(
                                'urlProperty' => $urlProperty,
                                'fullDisplay' => false,
                            ));
                        }

                        if(!empty($kpr_owner_email)){
                            echo $this->element('blocks/kpr/apply_detail/detail_owner_property');
                        }

                        if( !empty($help_apply) ) {
                            $options = array(
                                'frameClass' => 'col-sm-12',
                                'labelClass' => 'col-xl-1 col-sm-2 control-label',
                                'class' => 'relative col-sm-7 col-xl-6',
                            );
                            
                            if( !empty($followup) && !empty($id) ) {
                                $inputForm = $this->Rumahku->buildText(array_merge($options, array(
                                    'label' => __('Catatan'),
                                    'inputText' => $note, 
                                )));                                        
                            } else {
                                $inputForm = $this->Rumahku->buildInputForm('note', array_merge($options, array(
                                    'type' => 'textarea',
                                    'label' => __('Catatan *'),
                                )));
                            }

                            echo $this->Html->tag('div', $inputForm, array(
                                'class' => 'split',
                            ));
                        }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="mb30 hidden-print" id="proccess-action-kpr">
    <div class="action-group">
        <div class="btn-group floleft">
            <?php   
                    $status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'status_desc', '-');

                    $urlBack = array(
                        'action' => 'list_user_apply',
                        'admin' => true
                    );

                    if( !empty($log_kpr) ) {                        
                        $urlBack = array(
                            'action' => 'histories',
                            'admin' => true
                        );
                    } else if( !empty($help_apply) ) {
                        if( empty($followup) && !empty($id) ) {
                            echo $this->Form->button(__('Follow Up'), array(
                                'type' => 'submit', 
                                'class'=> 'btn blue',
                            ));
                        } else {
                            if( empty($application) ) {
                                echo $this->Html->link(__('Jadikan Aplikasi'), array(
                                    'controller' => 'kpr',
                                    'action' => 'changeto_application',
                                    $id,
                                    'admin' => true
                                ), array(
                                    'class'=> 'btn green',
                                ), __('Anda yakin ingin menjadikan dokumen ini menjadi Aplikasi KPR?'));
                            }
                        }

                        $urlBack = array(
                            'action' => 'help_user_apply',
                            'admin' => true
                        );

                    } else if( $status == 'Pending' ) {

                        if( $this->Rumahku->_isAdmin() ) {
                             echo $this->Html->link(__('Lanjutkan'), array(
                                'controller' => 'kpr',
                                'action' => 'approved_admin',
                                $id,
                                'admin' => true,
                            ), array(
                                'class'=> 'btn blue',
                            ), __('Anda yakin ingin melanjutkan pengajuan KPR ini ke %s ?', $bankName));

                            echo $this->Html->link(__('Tolak'), array(
                                'controller' => 'kpr',
                                'action' => 'rejected_admin',
                                $id,
                                'admin' => true,
                            ), array(
                                'class'=> 'btn red ajaxModal',
                                'title' => __('Anda yakin ingin menolak pengajuan KPR ini'),
                            ));
                        }else{  
                            echo $this->element('blocks/kpr/button_form', array(
                                'application_snyc' => $application_snyc
                            ));
                            
                        }
                    }else if($status == 'Assign'){

                        if( $document_status == 'credit_process'){
                            echo $this->Html->link(__('Proses Akad Kredit'), array(
                                'controller' => 'kpr',
                                'action' => 'process_akad',
                                $id,
                                'admin' => true,
                            ), array(
                                'class'=> 'btn green ajaxModal',
                                'title' => __('PROSES AKAD'),
                                'data-subtitle' => __('Mohon isi form dibawah ini untuk kami informasikan kepada agen dan calon pembeli'),
                            ));

                            echo $this->Html->link(__('Tolak'), array(
                                'controller' => 'kpr',
                                'action' => 'rejected_akad',
                                $id,
                                'admin' => true,
                            ), array(
                                'title' => __('TOTAL PROSES AKAD'),
                                'class'=> 'btn red ajaxModal',
                                'data-subtitle' => __('Berikan alasan mengapa Akad Kredit KPR ditolak.'),
                                'data-color' => 'red',
                            ));

                        }
                    }


                    echo $this->Html->link(__('Kembali'), $urlBack, array(
                        'class'=> 'btn default',
                    ));
            ?>
        </div>
    </div>
</div>
<?php
        echo $this->Form->end(); 
?>