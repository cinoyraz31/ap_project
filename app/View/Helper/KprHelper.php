<?php
class KprHelper extends AppHelper {
	var $helpers = array(
		'Rumahku', 'Html'
	);


    function configure($case, $admin, $pending){
        $result = array();
        $site_name = Configure::read('__Site.site_name');
        
        switch($case){
            case 'label-status':
                if($pending){
                    $result = array(
                        'pending' => __('Pengajuan'),
                        'approved_admin' => __('Setujui %s', $site_name),
                    );
                }else{
                    $result = array(
                        'approved_admin' => __('Pengajuan'),
                    );
                }
                $result = array_merge($result, array(
                    'rejected_proposal' => __('Menolak Referral'),
                    'approved_proposal' => __('Menyetujui Referral'),
                    'cancel' => __('Batal/Cancel'),
                    'proposal_without_comiission' => __('Menyetujui Referral Tanpa Provisi'),
                    'rejected_admin' => __('Ditolak %s', $site_name),
                    'approved_bank' => __('Menyetujui Aplikasi'),
                    'rejected_bank' => __('Menolak Aplikasi'),
                    'credit_process' => __('Agen Proses Akad Kredit'),
                    'rejected_credit' => __('Tolak Akad Kredit'),
                    'approved_credit' => __('Menyetujui Akad Kredit'),
                ));
                break;
        }
        return $result;
    } 

    function getDateList($data){
        if(!empty($data)){
            $from_web = $this->Rumahku->filterEmptyField($data, 'KprBank', 'from_web');

            $created = $this->Rumahku->filterEmptyField($data, 'KprBank', 'created');
            $approved_admin_date = $this->Rumahku->filterEmptyField($data, 'KprBank', 'approved_admin_date');

            // $date_pending_arr = $this->Rumahku->filterEmptyField($data, 'pending');
            // $pending_date = $this->Rumahku->filterEmptyField($date_pending_arr, 'KprBankDate', 'action_date', $created);

            // $date_admin_arr = $this->Rumahku->filterEmptyField($data, 'approved_admin');
            // $approved_admin_date = $approved_admin_excel = $this->Rumahku->filterEmptyField($date_admin_arr, 'KprBankDate', 'action_date', $created);

            // if($from_web == 'rumahku'){
            //    $approved_admin_date = $created;
            // }else{
            //     $created = $approved_admin_date;
            // }

            $data['KprBank'] = array_merge($data['KprBank'], array(
                'created' => $created,
                'approved_admin_date' => $created,
                'approved_admin_excel' => $approved_admin_date,
            ));
        }
        return $data;
    }

    function _getStatus ( $data = array(), $tagHtml = false ) {
        $site_name = Configure::read('__Site.site_name');
        $color = $alert = $allow_edit = $wrapper =  $wrapperClass = $action_bottom = $noteCustom = $status = false;
        $id = $this->Rumahku->filterEmptyField($data, 'KprBank', 'id');
        $prime_kpr_id = $this->Rumahku->filterEmptyField($data, 'KprBank', 'prime_kpr_id');
        $prime_kpr_bank_id = $this->Rumahku->filterEmptyField($data, 'KprBank', 'prime_kpr_bank_id');
        $document_status = $this->Rumahku->filterEmptyField($data, 'KprBank', 'document_status');
        $application_status = $this->Rumahku->filterEmptyField($data, 'KprBank', 'application_status');
        $application_snyc = $this->Rumahku->filterEmptyField($data, 'KprBank', 'application_snyc');
        $from_kpr = $this->Rumahku->filterEmptyField($data, 'KprBank', 'from_kpr');
        $from_web = $this->Rumahku->filterEmptyField($data, 'KprBank', 'from_web');
        $company_name = $this->Rumahku->filterEmptyField($data, 'UserCompany', 'name');
        $kpr_application = $this->Rumahku->filterEmptyField($data, 'KprBank', 'KprApplication', 'full_name');
        $full_name = $this->Rumahku->filterEmptyField($kpr_application, 'full_name');

        $kprBankDate = $this->Rumahku->filterEmptyField($data, $document_status);
        $note = $this->Rumahku->filterEmptyField($kprBankDate, 'KprBankDate', 'note');
        $action_date = $this->Rumahku->filterEmptyField($kprBankDate, 'KprBankDate', 'action_date');
        $customActionDate = $this->Rumahku->customDate($action_date, 'd F Y H:i');

        $agent_name = $this->Rumahku->filterEmptyField($data, 'User', 'full_name');

        $bank_name = $this->Rumahku->filterEmptyField($data, 'Bank', 'name');

        if(!empty($note) && !empty($action_date)){
            $noted = $this->Html->tag('p', sprintf('%s %s', $this->Html->tag('strong', __('Keterangan : ')), $note));
            $actionDateCustom = $this->Html->tag('p', sprintf('%s : %s', $this->Html->tag('strong', __('Pada tanggal')), $customActionDate));
            $noteCustom = sprintf('%s %s', $noted, $actionDateCustom);
        }


        $status_application = $this->_callStatusDocument($data);

        if($from_web == 'primesystem'){
            if($document_status == 'approved_credit'){
                $ulContent = null;

                $process_akad_date = $this->Rumahku->filterEmptyField($data, 'KprBankCreditAgreement', 'action_date');
                $description_akad = $this->Rumahku->filterEmptyField($data, 'KprBankCreditAgreement', 'note', false, false, false);
                $description_akad = $this->Rumahku->_callGetDescription($description_akad, ', ');

                $name_marketing = $this->Rumahku->filterEmptyField($data, 'KprBankCreditAgreement', 'staff_name');
                $contact_marketing = $this->Rumahku->filterEmptyField($data, 'KprBankCreditAgreement', 'staff_phone');
                $contact_email = $this->Rumahku->filterEmptyField($data, 'KprBankCreditAgreement', 'staff_email');
                $name_client = $this->Rumahku->filterEmptyField($data, 'KprBank', 'client_name');
                $contact_client = $this->Rumahku->filterEmptyField($data, 'KprBank', 'client_hp');

                $status = 'Akad Kredit Disetujui';
                $color = 'color-green label approved';

                $customContactClient = $this->Html->tag('strong', $contact_client);
                $customNameClient = $this->Html->tag('strong', $name_client);

                if(!empty($process_akad_date)){
                    $customCreditDate = $this->Html->tag('strong', $this->Rumahku->formatDate( $process_akad_date, 'd M Y, H:i:s'));
                    $ulContent = $this->Html->tag('li', sprintf(__('Tanggal Akad Kredit : %s'), $customCreditDate));
                }

                if(!empty($description_akad)){
                    $customLocation = $this->Html->tag('strong', $description_akad);
                    $ulContent .= $this->Html->tag('li', sprintf(__('Lokasi : %s'), $customLocation));
                }

                if(!empty($name_marketing)){
                    $customNameMarketing = $this->Html->tag('strong', $name_marketing);
                    $ulContent .= $this->Html->tag('li', sprintf(__('Bertemu dengan : %s '), $customNameMarketing));
                }

                if(!empty($contact_marketing)){
                    $customContactMarketing = $this->Html->tag('strong', $contact_marketing);
                    $ulContent .= $this->Html->tag('li', sprintf(__('Kontak : %s'), $customContactMarketing));
                }

                if(!empty($contact_email)){
                    $customContactEmail = $this->Html->tag('strong', $contact_email);
                    $ulContent .= $this->Html->tag('li', sprintf(__('Email : %s'), $customContactEmail));
                }
                

                $wrapper = $this->Html->tag('label', __('Pemberitahuan'));
                $wrapper .= $this->Html->tag('p', sprintf(__('%s Proses KPR telah Selesai, berikut jadwal dan lokasi Akad Kredit :'), $this->Html->tag('strong', __('Selamat'))));
                $wrapper .= $this->Html->tag('ul', $ulContent);
                $wrapper .= $this->Html->tag('p', __('Klik dibawah ini untuk lihat informasi Provisi:'));
                $action_bottom = $this->Html->tag('div', $this->Html->link(__('Lihat'), '#agent-information', array(
                    'class'=> 'btn blue',
                )), array(
                    'class' => 'action-button',
                ));
            }else if( $document_status == 'credit_process' ){

                $status = 'Agen Setujui KPR';
                $color = 'color-green label contract';

                $wrapper = $this->Html->tag('label', __('Tips & Panduan'));
                $wrapper .= $this->Html->tag('p', sprintf(__('%s, Klien/Pembeli memilih Anda dalam Proses KPR'), $this->Html->tag('strong', __('Selamat'))));
                $wrapper .= $this->Html->tag('p', __('Anda dapat mengatur Jadwal Akad Kredit disini:'));
                $action_bottom = $this->Html->tag('div', $this->Html->link(__('Proses Akad Kredit'), array(
                    'controller' => 'kpr',
                    'action' => 'process_akad',
                    $id,
                    'admin' => true,
                ), array(
                    'class'=> 'btn green ajaxModal',
                    'title' => __('PROSES AKAD'),
                    'data-subtitle' => __('Mohon isi Form dibawah ini untuk Kami informasikan kepada Agen dan Calon Pembeli'),
                )), array(
                    'class' => 'action-button',
                ));
            } else if( $document_status == 'rejected_credit' ){

                $status = 'Tolak Akad Kredit';
                $color = 'color-green label rejected';
                $wrapper = $this->Html->tag('label', __('Menolak Akad Kredit'));
                $wrapper .= $this->Html->tag('p', __('Anda telah menolak proses Akad Kredit, Terima kasih sudah memakai system Bank kami.'));
                $wrapper .= $noteCustom;
            }else if( $document_status == 'cancel' ){

                $status = 'Cancel / Batal';
                $color = 'color-green label cancel';
                $wrapperClass = 'black';

                $wrapper = $this->Html->tag('label', __('Agen Menolak/Cancel'));
                $wrapper .= $this->Html->tag('p', __('Agen telah menolak aplikasi KPR ini, Terima kasih sudah memakai system Bank kami.'));
                $wrapper .= $noteCustom;
            }else if( $document_status == 'approved_bank' ) {

                $status = 'Disetujui';
                $color = 'color-green label send';
                $wrapperClass = 'orange';

                $wrapper = $this->Html->tag('label', __('Tips & Panduan'));
                $wrapper .= $this->Html->tag('p', __('Anda telah menyetujui aplikasi KPR, %s konfirmasi Agen', $this->Html->tag('strong', __('harap menunggu'))));
                $wrapper .= $this->Html->tag('p', __('Agen dapat memilih Bank KPR yang diinginkan oleh Pembeli. Bank yang tidak dipilih oleh Pembeli akan di "Cancel" secara otomatis oleh sistem'));
                $wrapper .= $this->Html->tag('p', sprintf('%s %s pada tanggal %s', $this->Html->tag('strong', __('Keterangan : ')), $note, $customActionDate));
            } else if( $document_status == 'rejected_bank' ) {

                $status = 'Ditolak';
                $color = 'color-red label rejected';

                $wrapper = $this->Html->tag('label', __('Bank Menolak'));
                $wrapper .= $this->Html->tag('p', __('Bank telah menolak aplikasi KPR ini, dengan alasan :'));
                $wrapper .= $noteCustom;
                $wrapperClass = 'red';
            }else if( $document_status == 'rejected_admin' ) {

                $status = sprintf('Ditolak %s', $site_name);
                $color = 'color-red label rejected';

                $wrapper = $this->Html->tag('label', __('Ditolak %s', $site_name));
                $wrapper .= $this->Html->tag('p', __('Rumahku telah menolak aplikasi KPR ini, dengan alasan :'));
                $wrapper .= $noteCustom;
                $wrapperClass = 'red';
            }else if( $document_status == 'approved_proposal' ){

                $status = 'Referral Disetujui';
                $color = 'color-red label proposal';
                $allow_edit = true;

                $btnAction = $this->Html->link(__('Edit Pembeli'), array(
                    'controller' => 'kpr',
                    'action' => 'edit_application',
                    $id,
                    'admin' => true,
                ), array(
                    'class' => 'btn default',
                ));

                $btnAction .= $this->Html->link(__('Proses'), '#proccess-action-kpr', array(
                    'class' => 'btn default',
                ));

                if( $status_application == 'Lengkap' ) {
                    $wrapper = $this->Html->tag('label', __('Pemberitahuan'));
                    $wrapper .= $this->Html->tag('p', __('Agen telah melengkapi Aplikasi klien/pembeli'));
                    $wrapper .= $this->Html->tag('p', __('Pastikan kelengkapan data klien/pembeli. Anda dapat mengubah, menyetujui maupun menolak Aplikasi yang diajukan Agen'));

                    $btnAction .= $this->Html->link(__('Lihat Pembeli'), '#buyer-information', array(
                        'class' => 'btn blue',
                    ));
                    
                } else {
                    $wrapper = $this->Html->tag('label', __('Tips & Panduan'));
                    $wrapper .= $this->Html->tag('p', __('Referral telah disetujui'));
                    $wrapper .= $this->Html->tag('p', __('Anda dapat langsung memproses Aplikasi atau menunggu Agen melengkapi Aplikasi KPR dari Klien/Pembeli'));
                    $wrapper .= $noteCustom;

                }

                 $action_bottom = $this->Html->tag('div', $btnAction, array(
                    'class' => 'action-button',
                ));
            }else if($document_status == 'rejected_proposal'){

                $status = 'Referral Ditolak';
                $color = 'color-red label rejected';
                $rejected_proposal = $this->Rumahku->filterEmptyField($data, 'rejected_proposal');
                $note = $this->Rumahku->filterEmptyField($rejected_proposal, 'KprBankDate', 'note');
                $wrapper = $this->Html->tag('label', __('Referral Ditolak'));
                $wrapper .= $this->Html->tag('p', __('Anda telah menolak Referral Pengajuan KPR, dengan alasan:'));
                $wrapper .= $noteCustom;

                $wrapperClass = 'red';
            }else if($document_status == 'proposal_without_comiission'){

                $status = 'Referral Disetujui & Provisi Ditolak';
                $color = 'color-red label proposal';

                if( $application_status == 'completed' && !empty($application_snyc)) {
                    $allow_edit = true;
                    $wrapper = $this->Html->tag('label', __('Pemberitahuan'));
                    $wrapper .= $this->Html->tag('p', __('Referral telah disetujui, & Provisi ditolak'));
                    $wrapper .= $this->Html->tag('p', __('Agen telah melengkapi Aplikasi KPR dari Klien/Pembeli'));
                    $wrapper .= $this->Html->tag('p', __('Harap mengecek kelengkapan data Pembeli, Anda dapat mengubah, menyetujui dan menolak Aplikasi yang diajukan Agen'));

                    $btnAction = $this->Html->link(__('Lihat Pembeli'), '#buyer-information', array(
                        'class' => 'btn blue',
                    ));
                    $btnAction .= $this->Html->link(__('Edit Pembeli'), array(
                        'controller' => 'kpr',
                        'action' => 'edit_application',
                        $id,
                        'admin' => true,
                    ), array(
                        'class' => 'btn default',
                    ));
                    // $btnAction .= $this->Html->link(__('Setuju'), array(
                    //     'controller' => 'kpr',
                    //     'action' => 'approved_bank',
                    //     $id,
                    //     'admin' => true,
                    // ), array(
                    //     'class' => 'btn default ajaxModal',
                    //     'title' => __('Anda bisa merubah data aplikasi KPR'),
                    // ));
                    $btnAction .= $this->Html->link(__('Proses'), '#proccess-action-kpr', array(
                        'class' => 'btn default',
                    ));
                    $action_bottom = $this->Html->tag('div', $btnAction, array(
                        'class' => 'action-button',
                    ));
                } else {

                    if(empty($application_snyc)){
                        $wrapper = $this->Html->tag('label', __('Tips & Panduan'));
                        $wrapper .= $this->Html->tag('p', __('Referral telah disetujui & Provisi ditolak , menunggu Agen memutuskan untuk melanjutkan atau tidak aplikasi ini '));
                        $wrapper .= $noteCustom;
                    }else{
                        $wrapper = $this->Html->tag('label', __('Pemberitahuan'));
                        $wrapper .= $this->Html->tag('p', __('Agen %s telah menyetujui aplikasi KPR ini tidak memberikan provisi, dan anda bisa proses aplikasi KPR dengan klik button "Proses"', $this->Html->tag('b', $agent_name)));

                        $btnAction = $this->Html->link(__('Edit Pembeli'), array(
                            'controller' => 'kpr',
                            'action' => 'edit_application',
                            $id,
                            'admin' => true,
                        ), array(
                            'class' => 'btn default',
                        ));

                        $btnAction .= $this->Html->link(__('Proses'), '#proccess-action-kpr', array(
                            'class' => 'btn default',
                        ));
                        $action_bottom = $this->Html->tag('div', $btnAction, array(
                            'class' => 'action-button',
                        ));
                    }
                }
            }else if($document_status == 'approved_admin'){
                $_isAdmin = Configure::read('User.admin');

                if(!empty($_isAdmin)){
                    $status = sprintf('Setujui %s', $site_name);
                    $color = 'label send';
                    $wrapper = $this->Html->tag('label', __('Setujui %s', $site_name));

                    if(!empty($prime_kpr_bank_id) && !empty($prime_kpr_id)){
                        $wrapper .= $this->Html->tag('p', __('Aplikasi ini berasal dari %s', $site_name));
                    }else{
                        $wrapper .= $this->Html->tag('p', __('Anda telah menyetujui aplikasi KPR ini'));
                    }
                    $wrapper .= $noteCustom;
                }else{
                    $status = 'Pending';
                    $color = 'label pending';

                    $wrapper = $this->Html->tag('label', __('Approval Referral'));
                    $wrapper .= $this->Html->tag('p', __('Anda telah mendapatkan Referral Pengajuan KPR dari %s', $this->Html->tag('strong', $agent_name)));
                    $wrapper .= $this->Html->tag('p', sprintf(__('%s apabila Anda menyetujui Referral KPR ini'), $this->Html->tag('strong', __('Informasi Klien akan tampil'))));
                    $wrapper .= $noteCustom;
                    $action_bottom = $this->Html->tag('div', $this->Html->link(__('Proses Referral'), '#proccess-action-kpr', array(
                        'class' => 'btn blue',
                    )), array(
                        'class' => 'action-button',
                    )); 

                }   
            }elseif($document_status == 'pending'){
                $type_kpr = $this->Rumahku->filterEmptyField($data, 'KprBank', 'type');
                $type_log_kpr = $this->Rumahku->filterEmptyField($data, 'KprBank', 'type_log');

                if($type_kpr == 'logkpr' && $type_log_kpr == 'app-calculate'){

                    $status = 'Log KPR Calculate';
                    $color = 'label log-kpr';

                }else{
                    $status = 'Pending';
                    $color = 'label pending';

                    $wrapper = $this->Html->tag('label', __('Pengajuan Klien'));
                    $wrapper .= $this->Html->tag('p', __('%s telah melakukan pengajuan KPR melalui Agen %s dari %s.', $this->Html->tag('strong', $full_name), $this->Html->tag('strong', $agent_name), sprintf('%s (%s)', $company_name, $from_web)));
                    $wrapper .= $this->Html->tag('p', __('Harap hubungi %s untuk melanjutkan proses pengajuan KPR ini.', $this->Html->tag('strong', __('Agen terkait'))));
                }
            }
        }else{
            switch ($document_status) {
                case 'pending' :
                    $type_kpr = $this->Rumahku->filterEmptyField($data, 'KprBank', 'type');
                    $type_log_kpr = $this->Rumahku->filterEmptyField($data, 'KprBank', 'type_log');

                    if($type_kpr == 'logkpr' && $type_log_kpr == 'app-calculate'){

                        $status = 'Log KPR Calculate';
                        $color = 'label log-kpr';

                    }else{
                        $status = 'Pending';
                        $color = 'label pending';

                        $wrapper = $this->Html->tag('label', __('Pengajuan Klien'));
                        $wrapper .= $this->Html->tag('p', __('%s telah melakukan pengajuan KPR melalui detail properti %s', $full_name, $from_web));
                    }
                break;
                case 'approved_admin':
                    $_isAdmin = Configure::read('User.admin');

                    if(!empty($_isAdmin)){
                        // if($document_status)
                        $status = sprintf('Setujui %s', $site_name);
                        $color = 'label send';
                        $wrapper = $this->Html->tag('label', __('Setujui %s', $site_name));

                        if(!empty($prime_kpr_bank_id) && !empty($prime_kpr_id)){
                            $wrapper .= $this->Html->tag('p', __('Aplikasi ini berasal dari Prime System'));
                        }else{
                            $wrapper .= $this->Html->tag('p', __('Anda telah menyetujui aplikasi KPR ini'));
                        }
                        $wrapper .= $noteCustom;
                    }else{
                        $status = 'Pending';
                        $color = 'label pending';
                        $allow_edit = true;

                        $wrapper = $this->Html->tag('label', __('Pemberitahuan'));
                        $wrapper .= $this->Html->tag('p', __('Klien %s telah melakukan Pengajuan KPR melalui %s', $this->Html->tag('strong', $full_name), $from_web));
                        $wrapper .= $this->Html->tag('p', __('Harap mengecek kelengkapan data Pengajuan KPR. Anda dapat mengubah, menyetujui maupun menolak Aplikasi yang diajukan.'));
                        $wrapper .= $noteCustom;

                        $btnAction = $this->Html->link(__('Lihat Pembeli'), '#buyer-information', array(
                            'class' => 'btn blue',
                        ));
                        $btnAction .= $this->Html->link(__('Edit Pembeli'), array(
                            'controller' => 'kpr',
                            'action' => 'edit_application',
                            $id,
                            'admin' => true,
                        ), array(
                            'class' => 'btn default',
                        ));
                    
                        $btnAction .= $this->Html->link(__('Proses'), '#proccess-action-kpr', array(
                            'class' => 'btn default',
                        ));
                        $action_bottom = $this->Html->tag('div', $btnAction, array(
                            'class' => 'action-button',
                        ));

                    }   
                    break;

                case 'rejected_admin' : 
                    $status = sprintf('Ditolak %s', $site_name);
                    $color = 'color-red label rejected';

                    $wrapper = $this->Html->tag('label', __('Ditolak %s', $site_name));
                    $wrapper .= $this->Html->tag('p', __('Rumahku telah menolak aplikasi KPR ini, dengan alasan :'));
                    $wrapper .= $noteCustom;
                    $wrapperClass = 'red';
                    break;
                
                case 'approved_bank':
                    $status = 'Disetujui';
                    $color = 'color-green label send';
                    $wrapperClass = 'orange';

                    $wrapper = $this->Html->tag('label', __('Tips & Panduan'));
                    $wrapper .= $this->Html->tag('p', __('Anda telah menyetujui aplikasi KPR ini.'));
                    $wrapper .= $this->Html->tag('p', __('Anda dapat informasikan klien secara manual bahwa aplikasi KPR ini telah disetujui oleh bank'));
                    $wrapper .= $noteCustom;
                    break;

                case 'rejected_bank' :
                    $status = 'Ditolak';
                    $color = 'color-red label rejected';

                    $wrapper = $this->Html->tag('label', __('Bank Menolak'));
                    $wrapper .= $this->Html->tag('p', __('Bank telah menolak aplikasi KPR ini, dengan alasan :'));
                    $wrapper .= $noteCustom;
                    $wrapperClass = 'red';
                    break;
            }
        }

        if( !empty($wrapper) ) {
            $alert = $this->Html->tag('div', $wrapper, array(
                'class' => 'wrapper-alert',
            )).$action_bottom;
            $alert = $this->Html->tag('div', $alert, array(
                'class' => 'crm-tips hidden-print '.$wrapperClass,
            ));
        }

        return array(
            'status' => $status,
            'color' => $color,
            'alert' => $alert,
            'allow_edit' => $allow_edit,
        );
    }

    function _getStatusAdmin ( $data = array(), $tagHtml = false ) {

        $_isAdmin = Configure::read('User.admin');
        $approved_admin = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'approved_admin');
        $rejected_admin = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'rejected_admin');

        $approved = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'approved');
        $rejected = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'rejected');

        $assign_project = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'assign_project');
        $cancel_project = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'cancel_project');

        $document_status = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'document_status');
        $process_akad_date = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'process_akad_date');

        $aprove_proposal = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'aprove_proposal');
        $reject_proposal = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'reject_proposal');
        $proposal_date = $this->Rumahku->filterEmptyField($data, 'KprApplication', 'proposal_date');

        $color = false;

        if($document_status == 'akad_credit'){

            $status = 'Akad Kredit Disetujui';
            $color = 'color-green label approved';

        }else if(!empty($assign_project)) {

            $status = 'Proses Akad';
            $color = 'color-red label approved';

        } else if(!empty($cancel_project)){

            $status = 'Ditolak Agen';
            $color = 'color-red label rejected';

        }else if( !empty($approved_bank) ) {

            $status = 'Disetujui Bank';
            $color = 'color-green label approved';

        } else if( !empty($rejected) ) {

            $status = 'Ditolak Bank';
            $color = 'color-red label rejected';

        }else if( !empty($aprove_proposal) ){

            $status = 'Referral Setujui';
            $color = 'color-green label approved';

        }else if( !empty($reject_proposal) ){

            $status = 'Referral Ditolak';
            $color = 'color-red label rejected';

        } else if( !empty($approved_admin) ) {
            if( !empty($_isAdmin) ) {
                $status = 'Terkirim';
                $color = 'color-green label send';
            } else {
                $status = 'Pending';
                $color = 'label pending';
            }

        } else if( !empty($rejected) || !empty($rejected_admin) ) {

            $status = 'Ditolak';
            $color = 'color-red label rejected';

        }else {

            $status = 'Pending';
            $color = 'label pending';

        }

        return array(
            'status' => $status,
            'color' => $color,
        );
    }

    // function _callStatusKomisi($data, $tagHtml = false){
    //     $paid_komisi_agen = $this->Rumahku->filterEmptyField($data,'KprApplication','paid_komisi_agen');
    //     $paid_komisi_rku = $this->Rumahku->filterEmptyField($data,'KprApplication','paid_komisi_rku');

    //     // if($paid_komisi_agen == 'dibayarkan'){}

    // }

    function _callStatus ( $data, $tagHtml = false ) {
        $_isAdmin = Configure::read('User.admin');

        $result = $this->_getStatus( $data, $tagHtml );
        return $this->tagHtml($result, $tagHtml);
    }

    function _callStatusDocument( $value, $tagHtml = false){
        $application_status = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'application_status');
        if($application_status == 'completed'){
            $result = array(
                'status' => 'Lengkap',
                'color' => 'color-blue label approved',
            );
        }else{
            $result = array(
                'status' => 'Pending',
                'color' => 'label pending',
            );  
        }

        return $this->tagHtml($result, $tagHtml);
    }

    function tagHtml( $result, $tagHtml){

        if( !empty($tagHtml) ) {
            $status = !empty($result['status'])?$result['status']:false;
            $color = !empty($result['color'])?$result['color']:false;

            return $this->Html->tag('span', $status, array(
                'class' => $color,
            ));
        } else {
            $status = !empty($result['status'])?$result['status']:false;
            return $status;
        }

    }

    function _callStatusHelpApply ( $data ) {
        $followup = $this->Rumahku->filterEmptyField($data, 'HelpApplyKpr', 'is_followup');

        if( !empty($followup) ) {
            return __('Follow Up');
        } else {
            return __('Pending');
        }
    }

    function _callCalcNotaryCost ( $price, $data ) {
        $sale_purchase_certificate = $this->Rumahku->filterEmptyField($data, 'sale_purchase_certificate', false, 0);
        $transfer_title_charge = $this->Rumahku->filterEmptyField($data, 'transfer_title_charge', false, 0);
        $SKMHT = $this->Rumahku->filterEmptyField($data, 'letter_mortgage', false, 0);
        $APHT = $this->Rumahku->filterEmptyField($data, 'mortgage', false, 0);
        $HT = $this->Rumahku->filterEmptyField($data, 'imposition_act_mortgage', false, 0);
        $other_certificate = $this->Rumahku->filterEmptyField($data, 'other_certificate', false, 0);
        $credit_agreement = $this->Rumahku->filterEmptyField($data, 'credit_agreement', false, 0);
        // $sale_purchase_certificate = floatval( $sale_purchase_certificate_persen / 100 ) * $price;
        // $transfer_title_charge = floatval( $transfer_title_charge_persen / 100 ) * $price;
        // $SKMHT = floatval( $SKMHT_persen / 100 ) * $price;
        // $APHT = floatval( $APHT_persen / 100 ) * $price;
        // $HT = floatval( $HT_persen / 100 ) * $price;
        // $other_certificate = floatval( $other_certificate_persen / 100 ) * $price;
        
        return $sale_purchase_certificate + $transfer_title_charge + $credit_agreement + $SKMHT + $APHT + $HT + $other_certificate;
    }

    function _callCalcBankCost ( $loan_price, $data ) {
        $appraisal = $this->Rumahku->filterEmptyField($data, 'appraisal', false, 0);
        $administration = $this->Rumahku->filterEmptyField($data, 'administration', false, 0);
        $provision = $this->Rumahku->filterEmptyField($data, 'provision', false, 0);
        $insurance = $this->Rumahku->filterEmptyField($data, 'insurance', false, 0);
        $commission = $this->Rumahku->filterEmptyField($data, 'commission', false, 0);

        $provision_price = (floatval($provision / 100) * $loan_price);
        $provision_price = !empty($commission)?$commission:$provision_price;
        // $insurance_price = (floatval($insurance / 100) * $loan_price);
        $insurance_price = $insurance;
        $total_bank_charge = $appraisal + $administration + $provision_price + $insurance_price;

        return array(
            'total_bank_charge' => $total_bank_charge,
            'appraisal' => $appraisal,
            'administration' => $administration,
            'provision_price' => $provision_price,
            'insurance_price' => $insurance_price,
        );
    }

    function _callCalcDp ( $price, $rate = false , $down_payment = false) {
        if(!empty($down_payment)){
            return ($price - $down_payment);  
        }else{
            return $price * ($rate / 100 );        
        }
    }

    function _callCalcDpnPrice ( $price, $loan_price ) {
        return ($price - $loan_price );
    }    

    function _callCalcCreditTotal ( $flat, $floating ) {
        return $flat + $floating;
    }

    function _callCalcDpPercent($price, $down_payment){
        return round(@($down_payment / $price) * 100,2);
    }

    function _callCalcCostKpr ( $data, $modelName = 'KprApplication' ) {
        $interest_rate = $this->Rumahku->filterEmptyField($data, $modelName, 'interest_rate');
        $credit_fix = $this->Rumahku->filterEmptyField($data, $modelName, 'credit_fix');
        $credit_float = $this->Rumahku->filterEmptyField($data, $modelName, 'credit_float');
        $property_price = $this->Rumahku->filterEmptyField($data, $modelName, 'property_price',0);
        $persen_loan = $this->Rumahku->filterEmptyField($data, $modelName, 'persen_loan');
        $loan_price = $this->Rumahku->filterEmptyField($data, $modelName, 'loan_price');
        $appraisal = $this->Rumahku->filterEmptyField($data, $modelName, 'appraisal');
        $administration = $this->Rumahku->filterEmptyField($data, $modelName, 'administration');
        $provision = $this->Rumahku->filterEmptyField($data, $modelName, 'provision');
        $commission = $this->Rumahku->filterEmptyField($data, $modelName, 'commission');
        $insurance = $this->Rumahku->filterEmptyField($data, $modelName, 'insurance');
        $sale_purchase_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'sale_purchase_certificate');
        $transfer_title_charge = $this->Rumahku->filterEmptyField($data, $modelName, 'transfer_title_charge');
        $letter_mortgage = $this->Rumahku->filterEmptyField($data, $modelName, 'letter_mortgage');
        $imposition_act_mortgage = $this->Rumahku->filterEmptyField($data, $modelName, 'imposition_act_mortgage');
        $mortgage = $this->Rumahku->filterEmptyField($data, $modelName, 'mortgage');
        $other_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'other_certificate');
        $credit_agreement = $this->Rumahku->filterEmptyField($data, $modelName, 'credit_agreement');
        $credit_total = $this->_callCalcCreditTotal($credit_fix, $credit_float);
        $down_payment = $this->_callCalcDpnPrice($property_price, $loan_price);
        $down_payment_percent = $this->_callCalcDpPercent($property_price,$down_payment);
        $total_charge = $this->_callCalcBankCost($loan_price, array(
            'appraisal' => $appraisal,
            'administration' => $administration,
            'provision' => $provision,
            'commission' => $commission,
            'insurance' => $insurance,
        ));

        $total_notary_charge = $this->_callCalcNotaryCost($property_price, array(
            'sale_purchase_certificate' => $sale_purchase_certificate,
            'transfer_title_charge' => $transfer_title_charge,
            'letter_mortgage' => $letter_mortgage,
            'imposition_act_mortgage' => $imposition_act_mortgage,
            'mortgage' => $mortgage,
            'other_certificate' => $other_certificate,
            'credit_agreement' => $credit_agreement,
        ));
        // debug($credit_total);die();
        $total_first_credit = $this->Rumahku->creditFix($loan_price, $interest_rate, $credit_total);

        $total_bank_charge = $this->Rumahku->filterEmptyField($total_charge, 'total_bank_charge');
        $total = $down_payment + $total_bank_charge + $total_notary_charge + $total_first_credit;

        $appraisal = $this->Rumahku->filterEmptyField($total_charge, 'appraisal');
        $administration = $this->Rumahku->filterEmptyField($total_charge, 'administration');
        $provision_price = $this->Rumahku->filterEmptyField($total_charge, 'provision_price');
        $insurance_price = $this->Rumahku->filterEmptyField($total_charge, 'insurance_price');


        return array(
            'credit_total' => $credit_total,
            'down_payment' => $down_payment,
            'down_payment_percent' => $down_payment_percent,
            'total_bank_charge' => $total_bank_charge,
            'total_notary_charge' => $total_notary_charge,
            'total_first_credit' => $total_first_credit,
            'appraisal' => $appraisal,
            'administration' => $administration,
            'provision_price' => $provision_price,
            'insurance_price' => $insurance_price,
            'total' => $total,
        );
    }


    function _callDateStatus ( $labels, $approved, $approved_date, $rejected = false, $rejected_date = false, $format_type = 'default' ) {
        if( !empty($approved) && !empty($approved_date) ) {
            $label = sprintf(__('Disetujui %s'), $labels);
            $date = $approved_date;
        } else if( !empty($rejected) && !empty($rejected_date) ) {
            $label = sprintf(__('Ditolak %s'), $labels);
            $date = $rejected_date;
        }
        
        if($approved == 'akad_credit'){
            $label = sprintf(__('Disetujui %s'), $labels);
            $date = $approved_date;
        }else if($approved == 'cancel_credit'){
            $label = sprintf(__('Ditolak %s'), $labels);
            $date = null;
        }

        if( !empty($date) ) {
            switch ($format_type) {
                case 'excel':
                    $contentTd = $this->Html->tag('td', $label, array(
                        'style' => 'text-align: left;',
                    ));
                    $contentTd .= $this->Html->tag('td', $date, array(
                        'style' => 'text-align: left;',
                    ));
                    $result = $this->Html->tag('tr', $contentTd);
                    break;
                
                default:
                    $label = $this->Html->tag('label', $label);
                    $val = $this->Html->tag('span', $date, array(
                        'id' => 'app-date',
                    ));

                    $dateContent = $this->Rumahku->icon('rv4-calendar2');
                    $dateContent .= $this->Html->div('app-date-info', $label.$val);

                    $result = $dateContent;
                    $result .= $this->Html->tag('div', '', array(
                        'class' => 'clear'
                    ));
                    break;
            }
        } else {
            $result = false;
        }

        return $result;
    }


    function _callDates ( $label, $date, $note , $pending) {
        $result = false;
        $_isAdmin = Configure::read('User.admin');
        $configure_status = $this->configure('label-status', $_isAdmin, $pending);
        $label = $this->Rumahku->filterEmptyField($configure_status, $label);

        if(!empty($label)){
            $label = $this->Html->tag('label', $label);
            $val = $this->Html->tag('span', $date, array(
                'id' => 'app-date',
            ));

            $dateContent = $this->Rumahku->icon('rv4-calendar2');
            $dateContent .= $this->Html->div('app-date-info', $label.$val);

            if(!empty($note)){
                $dateContent .= $this->Html->tag('div', $this->Html->tag('strong', __('Keterangan : ')).$note, array(
                    'class' => 'block'
                ));
            }

            $result = $dateContent;
            $result .= $this->Html->tag('div', '', array(
                'class' => 'clear'
            ));
        }

        return $result;
    }

    function _callDateStatusAgent ( $label, $credit_process = array(),$rejected_credit = array(), $format_type = 'default' ) {

        $cek_label = explode(' ',$label);
        $credit_process_date = $this->Rumahku->filterEmptyField($credit_process, 'KprBankDate', 'action_date');
        $rejected_credit_date = $this->Rumahku->filterEmptyField($credit_process, 'KprBankDate', 'action_date');

        if($cek_label[0] == 'Provisi'){
            $label = sprintf(__('Dibayarkan %s'), $label);
            $date = $credit_process_date;
        }else if( !empty($credit_process_date)) {
            $label = sprintf(__('Disetujui %s'), $label);
            $date = $credit_process_date;
        } else if( !empty($rejected_credit_date)) {
            $label = sprintf(__('Ditolak %s'), $label);
            $date = $rejected_credit_date;
        }

        if( !empty($date) ) {
            switch ($format_type) {
                case 'excel':
                    $contentTd = $this->Html->tag('td', $label, array(
                        'style' => 'text-align: left;',
                    ));
                    $contentTd .= $this->Html->tag('td', $date, array(
                        'style' => 'text-align: left;',
                    ));
                    $result = $this->Html->tag('tr', $contentTd);
                    break;
                
                default:
                    $label = $this->Html->tag('label', $label);
                    $val = $this->Html->tag('span', $date, array(
                        'id' => 'app-date',
                    ));

                    $dateContent = $this->Rumahku->icon('rv4-calendar2');
                    $dateContent .= $this->Html->div('app-date-info', $label.$val);

                    $result = $dateContent;
                    $result .= $this->Html->tag('div', '', array(
                        'class' => 'clear'
                    ));
                    break;
            }
        } else {
            $result = false;
        }

        return $result;
    }

    function generateViewDetail($label, $value, $mb30 = false, $options = array()){
            $divLabel = $this->Rumahku->filterEmptyField($options, 'divLabel', false,'col-sm-5');
            $divValue = $this->Rumahku->filterEmptyField($options, 'divValue', false, 'col-sm-7');

            $label =  $this->Html->tag('div', $this->Html->tag('span', $label), array(
                'class' => $divLabel,
            ));
            $value =  $this->Html->tag('div', $this->Html->tag('span', $value), array(
                'class' => $divValue,
            ));

            return $this->Html->tag('div', sprintf('%s %s', $label, $value), array(
                'class' => 'row '.$mb30
            ));
    }

    function generateDetailPinjaman($datas = false, $label = false, $modelName, $fieldName = false, $options = array(), $concat = false, $merge = array()){
        $labelClass = $this->Rumahku->filterEmptyField($options, 'labelClass', false, 'col-sm-4');
        $valueClass = $this->Rumahku->filterEmptyField($options, 'valueClass', false, 'col-sm-4');

        $result = $this->Html->tag('div', $this->Html->tag('span', $label), array(
            'class' => $labelClass
        ));

        if($datas){
            foreach ($datas as $key => $data) {
                $class = !empty($key) ? 'color-blue' : false;

                switch ($fieldName) {
                    case 'notary':
                        $property_price = $this->Rumahku->filterEmptyField($data, $modelName, 'property_price');
                        $sale_purchase_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'sale_purchase_certificate');
                        $transfer_title_charge = $this->Rumahku->filterEmptyField($data, $modelName, 'transfer_title_charge');
                        $SKMHT = $this->Rumahku->filterEmptyField($data, $modelName, 'letter_mortgage');
                        $APHT = $this->Rumahku->filterEmptyField($data, $modelName, 'mortgage');
                        $HT = $this->Rumahku->filterEmptyField($data, $modelName, 'imposition_act_mortgage');
                        $other_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'other_certificate');
                        $credit_agreement = $this->Rumahku->filterEmptyField($data, $modelName, 'credit_agreement');

                        $value = $this->_callCalcNotaryCost($property_price, array(
                            'sale_purchase_certificate' => $sale_purchase_certificate,
                            'transfer_title_charge' => $transfer_title_charge,
                            'letter_mortgage' => $SKMHT,
                            'imposition_act_mortgage' => $HT,
                            'mortgage' => $APHT,
                            'other_certificate' => $other_certificate,
                            'credit_agreement' => $credit_agreement,
                        ));
                        break;
                    case 'grandTotal':
                        $property_price = $this->Rumahku->filterEmptyField($data, $modelName, 'property_price');
                        $sale_purchase_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'sale_purchase_certificate');
                        $transfer_title_charge = $this->Rumahku->filterEmptyField($data, $modelName, 'transfer_title_charge');
                        $SKMHT = $this->Rumahku->filterEmptyField($data, $modelName, 'letter_mortgage');
                        $APHT = $this->Rumahku->filterEmptyField($data, $modelName, 'mortgage');
                        $HT = $this->Rumahku->filterEmptyField($data, $modelName, 'imposition_act_mortgage');
                        $other_certificate = $this->Rumahku->filterEmptyField($data, $modelName, 'other_certificate');
                        $credit_agreement = $this->Rumahku->filterEmptyField($data, $modelName, 'credit_agreement');

                        $down_payment = $this->Rumahku->filterEmptyField($data, $modelName, 'down_payment');
                        $insurance = $this->Rumahku->filterEmptyField($data, $modelName, 'insurance');
                        $administration = $this->Rumahku->filterEmptyField($data, $modelName, 'administration');
                        $appraisal = $this->Rumahku->filterEmptyField($data, $modelName, 'appraisal');
                        $total_first_credit = $this->Rumahku->filterEmptyField($data, $modelName, 'total_first_credit');

                        $notary = $this->_callCalcNotaryCost($property_price, array(
                            'sale_purchase_certificate' => $sale_purchase_certificate,
                            'transfer_title_charge' => $transfer_title_charge,
                            'letter_mortgage' => $SKMHT,
                            'imposition_act_mortgage' => $HT,
                            'mortgage' => $APHT,
                            'other_certificate' => $other_certificate,
                            'credit_agreement' => $credit_agreement,
                        ));

                        $value = $down_payment + ($insurance + $administration + $appraisal) + $notary + $total_first_credit;
                        break;
                    default:
                        $value = $this->Rumahku->filterEmptyField($data, $modelName, $fieldName, 0);
                        break;
                }



                if(is_numeric($value) && empty($concat)){
                    $value = $this->Rumahku->getCurrencyPrice($value, 'N/A');
                }else{
                    $value .= $concat;
                }

                if(!empty($merge)){
                    $mergeFieldName = $this->Rumahku->filterEmptyField($merge, 'fieldName');
                    $mergeConcat = $this->Rumahku->filterEmptyField($merge, 'concat');
                    $value2 = $this->Rumahku->filterEmptyField($data, $modelName, $mergeFieldName);

                    if(is_numeric($value2) && empty($mergeConcat)){
                        $value2 = $this->Rumahku->getCurrencyPrice($value2, 'N/A');
                    }else{
                        $value2 .= ' '.$mergeConcat;
                    }
                    $value .= sprintf(', %s',$value2);
                }


                $result .= $this->Html->tag('div', $this->Html->tag('strong', $value, array(
                    'class' => $class,
                )), array(
                    'class' => $valueClass,
                ));
            }
        }

        return $this->Html->tag('div', $result, array(
            'class' => 'row ml5 mb10',
        ));
    }

    function _callStepNotice($value){
        $result = $this->_getStatus( $value); 
        $alert = $this->Rumahku->filterEmptyField( $result, 'alert', false, false, false);
        return $alert;
    }

    function assignCommission($data = array()){
        $values = array();
        
        if(!empty($data)){
            foreach($data AS $key => $val){
                $commission = $this->Rumahku->filterEmptyField($val, 'KprCommissionPayment');
                $commission = $this->Rumahku->filterEmptyField($val, 'KprCommissionPaymentConfirm', false, $commission);

                if(!empty($commission)){
                    $values[$key]['commission'] = $commission;
                }
            }
        }

        return $values;
    }

    function getDateKPR($datas){
        if(!empty($datas)){
            foreach($datas AS $key => $data){
                $slug = $this->Rumahku->filterEmptyField($data, 'KprBankDate', 'slug');
                $action_date = $this->Rumahku->filterEmptyField($data, 'KprBankDate', 'action_date');
                $arr[sprintf('%s_date',$slug)] = $action_date;
                $arr[$slug] = TRUE;
            }
        }

        return $arr;
    }
    
    function unsetListExcel($value, $_isAdmin = false){

        $document_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'document_status');
        $application_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_status');
        $application_snyc = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_snyc');
        $flag = ((!in_array( $document_status, array('pending', 'approved_admin', 'proposal_without_comiission', 'process', 'rejected_admin', 'rejected_proposal')) || ('proposal_without_comiission' == $document_status && !empty($application_snyc))) && $application_status == 'completed');

        if( !$flag && !$_isAdmin){
            $value = $this->Rumahku->_callUnset(array(
                'KprApplication',
            ), $value);
        }
        return $value;
    }

    function documentcheckList($documents = array(), $fields, $valign){
            $docs = array(
                'kpr_application', 
                'kpr_spouse_particular',
            );
            $tagHTML = array();

            foreach($docs AS $key => $doc){
                $data_type = Set::Extract($documents, sprintf('/Document[document_type=%s]', $doc));

                if(!empty($fields)){
                    $tag = array();
                    foreach($fields AS $slug => $field){
                        if($doc == 'kpr_spouse_particular'){
                            $slug = sprintf('%s-pasangan', $slug);
                        }
                        $document = Set::Extract($documents, sprintf('/Document[slug=%s]', $slug));

                        if(!empty($document)){
                            $tag[] = array(
                                '&#8730;',
                                array(
                                    'style' => sprintf('text-align:center;%s', $valign),
                                ),
                            );
                        }else{
                             $tag[] = array(
                                'x',
                                array(
                                    'style' => sprintf('text-align:center;%s', $valign),
                                ),
                            );
                        }
                    }
                }
                $tagHTML[$key] = $tag;
            }

            return $tagHTML;
    }
}