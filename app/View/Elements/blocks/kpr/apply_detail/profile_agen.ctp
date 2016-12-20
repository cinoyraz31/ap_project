<?php
        $btnIcon = $iconEmail = $konfirmation_comission = null;
        $save_path  = Configure::read('__Site.profile_photo_folder');
        $logo_path = Configure::read('__Site.logo_photo_folder');
        $log_kpr = !empty($log_kpr)?$log_kpr:false;
        $logged_group = !empty($logged_group)?$logged_group:false;
        $assign_project = !empty($assign_project)?$assign_project:false;
        $label = $this->Html->tag('label', __('Informasi Agen'));
        $id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
        $code = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');

        $kpr_bank_installments = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment');
        $kpr_bank_installment = array_pop($kpr_bank_installments);
        $kpr_bank_commissions = $kpr_payments = $this->Rumahku->filterEmptyField($kpr_bank_installment, 'KprBankCommission');
        $agent = $this->Rumahku->filterEmptyField($value, 'User');
        $agentProfile = $this->Rumahku->filterEmptyField($value, 'UserProfile');
        $photo_agen = $this->Rumahku->filterEmptyField($agent, 'photo');
        $full_name_agent = $this->Rumahku->filterEmptyField($agent, 'full_name');
        $no_hp = $this->Rumahku->filterEmptyField($agentProfile, 'no_hp');
        $no_hp_2  = $this->Rumahku->filterEmptyField($agentProfile, 'no_hp_2');
        $phone  = $this->Rumahku->filterEmptyField($agentProfile, 'phone');
        $no_hp_is_whatsapp  = $this->Rumahku->filterEmptyField($agentProfile, 'no_hp_is_whatsapp');
        $no_hp_2_is_whatsapp  = $this->Rumahku->filterEmptyField($agentProfile, 'no_hp_2_is_whatsapp');
        $pin_bb  = $this->Rumahku->filterEmptyField($agentProfile, 'pin_bb');
        $line  = $this->Rumahku->filterEmptyField($agentProfile, 'line');
        $address = $this->Rumahku->filterEmptyField($agentProfile, 'address');
        $kprBankDates = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprBankDate');
        $credit_process = $this->Rumahku->filterEmptyField($value, 'credit_process', false, array());
        $rejected_credit = $this->Rumahku->filterEmptyField($value, 'rejected_credit', false, array());
        $document_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'document_status');
        $from_kpr = $this->Rumahku->filterEmptyField($value, 'KprBank', 'from_kpr');
        $company_name = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'name');
        $logo = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'logo');
        $log_document_status = Set::classicExtract($kprBankDates, '{n}.KprBankDate.slug');
        $log_paid_status = Set::classicExtract($kpr_bank_commissions, '{n}.KprBankCommission.status_confirm');
        $status_confirms = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'status_confirm');
        $kpr_bank_transfer = $this->Rumahku->filterEmptyField($value, 'KprBankTransfer');
        $name_account = $this->Rumahku->filterEmptyField($kpr_bank_transfer, 'name_account');
        $no_account = $this->Rumahku->filterEmptyField($kpr_bank_transfer, 'no_account');
        $bank_name = $this->Rumahku->filterEmptyField($kpr_bank_transfer, 'bank_name');
        $no_npwp = $this->Rumahku->filterEmptyField($kpr_bank_transfer, 'no_npwp');
        $region = $this->Rumahku->filterEmptyField($agentProfile, 'Region', 'name');
        $city = $this->Rumahku->filterEmptyField($agentProfile, 'City', 'name');
        $subarea = $this->Rumahku->filterEmptyField($agentProfile, 'Subarea', 'name');
        $zip = $this->Rumahku->filterEmptyField($agentProfile, 'zip');

        $address = $this->Rumahku->filterEmptyField($agentProfile, 'address');
        $rt = $this->Rumahku->filterEmptyField($agentProfile, 'rt');
        $rw = $this->Rumahku->filterEmptyField($agentProfile, 'rw');

        $location = $this->Rumahku->getGenerateAddress($address, array(
            'rt' => $rt,
            'rw' => $rw,
            'region' => $region,
            'city' => $city,
            'subarea' => $subarea,
            'zip' => $zip,
        ), ', ', '-');

        if( (in_array($document_status, array('pending')) && $from_kpr == 'frontend') || !empty($log_document_status) && array_intersect( $log_document_status, array('approved_bank', 'approved_credit', 'completed'))){
            $display = __('block');
            $icon = $this->Rumahku->icon('rv4-angle-up');
        }else{
            $display = __('none');
            $icon = $this->Rumahku->icon('rv4-angle-down');
        }

        $btnSlide = $this->Html->link( $icon, '#', array(
            'escape' => false,
            'class' => 'toggle-display floright',
            'data-display' => "#detail-project-agen",
            'data-type' => 'slide',
            'data-arrow' => 'true',
        ));
        $userPhoto = $this->Rumahku->photo_thumbnail(array(
            'save_path' => $save_path, 
            'src' => $photo_agen, 
            'size' => 'pm',
        ), array(
            'class' => 'default-thumbnail',
        ));
        $logoCustom = $this->Rumahku->photo_thumbnail(array(
            'save_path' => $logo_path, 
            'src' => $logo, 
            'size' => 'xxsm',
        ), array(
            'class' => 'default-thumbnail',
        ));

        if(!empty($kpr_bank_commissions)){
            $iconEmail = $this->Rumahku->icon('rv4-mail');
            $konfirmation_comission =  !empty($status_confirms)?__('pembayaran'):__('ilustrasi rincian');
            $btnIcon = $this->Html->link(sprintf(__('%s Share'), $iconEmail), array(
                'controller' => 'kpr',
                'action' => 'share_comission',
                $id,
                'admin' => true
            ), array(
                'escape' => false,
                'class'=> 'color-blue ajaxModal action-icon',
                'title' => sprintf('Bagikan informasi %s provisi',$konfirmation_comission),
                'data-subtitle' => sprintf(__('Anda bisa bagikan %s provisi ini kebagian finance agar bisa diurus lebih lanjut'), $konfirmation_comission),
            ));

            $iconEmail = $this->Rumahku->icon('rv4-doc');
            $btnIcon .= $this->Html->link(sprintf(__('%s Unduh'), $iconEmail), array(
                'controller' => 'kpr',
                'action' => 'excel_comission',
                $id,
                'admin' => true
            ), array(
                'escape' => false,
                'class'=> 'color-blue action-icon ml15',
                'target' => 'blank'
            ));
        }
        

        $label = $this->Html->tag('label', __('Informasi Agen'));
        echo $this->Html->tag('div', $label.$btnSlide.$btnIcon, array(
            'class' => 'info-title hidden-print',
            'id' => 'agent-information',
        ));
?>
 <div class="print-split-col borderbottom pd0 hidden-print" id="detail-project-agen" style="display:<?php echo $display;?>;">
    <div class="tab-content">
        <div class="row">
            <div class="col-sm-6 print-split-col">
                <div class="mb30">
                    <?php
                            echo $this->Html->tag('label', __('Biodata Agen'), array(
                                'class' => 'mb15 block',
                            ));
                    ?>
                    <div class="row mtl5">
                        <?php
                                echo $this->Html->tag('div', $userPhoto, array(
                                    'class' => 'col-sm-3',
                                ));
                        ?>
                        <div class="col-sm-8">
                            <?php
                                    if(!empty($full_name_agent)){
                                        echo $this->Kpr->generateViewDetail(__('Nama'), $full_name_agent);
                                    }

                                    if( !empty($address) ){
                                        echo $this->Kpr->generateViewDetail(__('Alamat'), $location);
                                    }

                                    if(!empty($phone)){
                                        echo $this->Kpr->generateViewDetail(__('Phone'), $phone);
                                    }

                                    if($no_hp){
                                        if(!empty($no_hp_is_whatsapp)){
                                            $no_hp .= __(' (whatsapp)'); 
                                        } 

                                        echo $this->Kpr->generateViewDetail(__('Handphone'), $no_hp);
                                    }

                                    if($no_hp_2){
                                        if(!empty($no_hp_2_is_whatsapp)){
                                            $no_hp_2 .= __(' (whatsapp)'); 
                                        } 

                                        echo $this->Kpr->generateViewDetail(__('Handphone 2'), $no_hp_2);
                                    }

                                    if($pin_bb){
                                        echo $this->Kpr->generateViewDetail(__('Pin bbm'), $pin_bb);
                                    }

                                    if($line){
                                        echo $this->Kpr->generateViewDetail(__('Line'), $line);
                                    }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 print-split-col">
                <?php 
                        if( !empty($company_name) ) {
                            $dataCompany = $this->Rumahku->filterEmptyField($value, 'UserCompany');

                            $company_region = $this->Rumahku->filterEmptyField($dataCompany, 'Region', 'name');
                            $company_city = $this->Rumahku->filterEmptyField($dataCompany, 'City', 'name');
                            $company_subarea = $this->Rumahku->filterEmptyField($dataCompany, 'Subarea', 'name');

                            $company_zip = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'zip');
                            $company_address = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'address');
                            $company_phone = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'phone');
                            $company_email = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'contact_email');
                            $company_fax = $this->Rumahku->filterEmptyField($value, 'UserCompany', 'fax');

                            if( !empty($company_subarea) ) {
                                $company_address .= '<br>'.$company_subarea;

                                if( !empty($company_city) ) {
                                    $company_address .= ', '.$company_city;
                                }

                                if( !empty($company_region) ) {
                                    $company_address .= '<br>'.$company_region;

                                    if( !empty($company_zip) ) {
                                        $company_address .= ' '.$company_zip;
                                    }
                                }
                            }
                ?>
                <div class="mb30">
                    <?php
                            echo $this->Html->tag('label', __('Informasi Perusahaan'), array(
                                'class' => 'mb15 block',
                            ));
                    ?>
                    <div class="row">
                        <?php
                                echo $this->Html->tag('div', $logoCustom, array(
                                    'class' => 'col-sm-3',
                                ));
                        ?>
                        <div class="col-sm-8">
                            <?php
                                    echo $this->Kpr->generateViewDetail(__('Perusahaan'), $company_name);

                                    if( !empty($company_phone) ){
                                        echo $this->Kpr->generateViewDetail(__('Phone'), $company_phone);
                                    }

                                    if( !empty($company_fax) ){
                                        echo $this->Kpr->generateViewDetail(__('Fax'), $company_fax);
                                    }

                                    if( !empty($company_email) ){
                                        echo $this->Kpr->generateViewDetail(__('Email'), $company_email);
                                    }

                                    if( !empty($company_address) ){
                                        echo $this->Kpr->generateViewDetail(__('Alamat'), $company_address);
                                    }
                            ?>
                        </div>
                    </div>
                </div>
                <?php 
                        }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="mt30">
                    <?php

                                if(!empty($bank_name)){
                                    echo $this->Kpr->generateViewDetail(__('Nama Bank'), $bank_name, false, array(
                                        'divLabel' => 'col-sm-3 col-md-2',
                                    ));
                                }

                                if(!empty($name_account)){
                                    echo $this->Kpr->generateViewDetail(__('Nama Akun'), $name_account, false, array(
                                        'divLabel' => 'col-sm-3 col-md-2',
                                    ));
                                } 

                                if(!empty($no_account)){
                                    echo $this->Kpr->generateViewDetail(__('No. Rekening'), $no_account, false, array(
                                        'divLabel' => 'col-sm-3 col-md-2',
                                    ));
                                }

                                if(!empty($no_npwp)){
                                    echo $this->Kpr->generateViewDetail(__('No. NPWP'), $no_npwp, false, array(
                                        'divLabel' => 'col-sm-3 col-md-2',
                                    ));
                                }
                            
                            if(empty($log_kpr)){
                                if(!empty($kpr_payments)){
                                    foreach($kpr_payments AS $key => $value){
                                        $payment_id = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'id');
                                        $status_confirm = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'status_confirm');
                                        $kpr_bank_installment_id = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'kpr_bank_installment_id');
                                        $type_komisi = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'type');
                                        $type_komisi_v = !empty($type_komisi)?ucfirst($type_komisi):false;
                                        $rate_komisi = round($this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'percent'), 2);
                                        $commission = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'value');
                                        $paid_date = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'action_date');
                                        $keterangan = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'note_reason');
                                        $paid_status = $this->Rumahku->filterEmptyField($value, 'KprBankCommission', 'paid_status');

                                        $type_komisi = ($type_komisi == 'rumahku')?Configure::read('__Site.site_name'):$type_komisi;
                                        $type_komisi = ucfirst($type_komisi);
                                        if( $logged_group <= 10 ) {
                                            $pending_paid = $this->Html->link($this->Html->tag('b', __('Lakukan Pembayaran')), array(
                                                'controller' => 'kpr',
                                                'action' => 'paid',
                                                $payment_id,
                                                'admin' => true
                                            ), array(
                                                'class'=> 'color-blue ajaxModal',
                                                'title' => sprintf('Konfirmasi Pembayaran Provisi %s Kode KPR #%s' ,$type_komisi, $code),
                                                'escape' => false,
                                            ));
                                        } else {
                                            $pending_paid = false;
                                        }

                                        $approved_paid = $this->Html->tag('b', __('Sudah Dibayarkan'), array(
                                            'class' => 'color-green',
                                        ));

                                        if(in_array($document_status, array('approved_credit', 'completed'))){
                                            $status = ($paid_status == 'approved')?$approved_paid:$pending_paid;
                                        }else{
                                            $status = null;
                                        }

                                        // echo $this->Rumahku
                                        $label = $this->Html->tag('span', sprintf('%s %s', __('Provisi'), $type_komisi));

                                        $rate_komisi = !empty($commission)?$rate_komisi:0;
                                        $rate_komisi = sprintf('(%s%%)', $rate_komisi);

                                        $commission = $this->Rumahku->getCurrencyPrice($commission);
                                        $commission = $this->Html->tag('span', sprintf('%s %s', $commission, $rate_komisi));
                                        $val = $this->Html->tag( 'span', sprintf('%s, %s <br>%s', $commission, $status, $keterangan));
                                        echo $this->Kpr->generateViewDetail( $label, $val, false, array(
                                            'divLabel' => 'col-sm-2'
                                        ));
                                    }
                                    
                                    if(!in_array( TRUE, $log_paid_status)){
                                        echo $this->Html->tag('span', __('* Perhitungan Provisi hanya berupa ilustrasi'), array(
                                            'class' => 'ilustration',
                                        ));
                                    }
                                }else{
                                    $label = $this->Html->tag('span', __('Provisi Agen'));
                                    $val = $this->Html->tag('span', sprintf(__('%s 0 (tidak ada Provisi)'), Configure::read('__Site.config_currency_symbol')));
                                    echo $this->Kpr->generateViewDetail( $label, $val, false, array(
                                            'divLabel' => 'col-sm-2'
                                        ));

                                    $label = $this->Html->tag('span', __('Provisi Rumahku'));
                                    $val = $this->Html->tag('span', sprintf(__('%s 0 (tidak ada Provisi)'), Configure::read('__Site.config_currency_symbol')));
                                    echo $this->Kpr->generateViewDetail( $label, $val, false, array(
                                            'divLabel' => 'col-sm-2'
                                        ));
                                }
                            }
                            
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>