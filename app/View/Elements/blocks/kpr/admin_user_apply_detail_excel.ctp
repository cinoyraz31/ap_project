<?php
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=aplikasi-kpr.xls');
        $code = !empty($code)?$code:false;
        $created = !empty($created)?$created:false;
        $approved_admin = !empty($approved_admin)?$approved_admin:false;
        $approved_admin_date = !empty($approved_admin_date)?$approved_admin_date:false;
        $rejected_admin = !empty($rejected_admin)?$rejected_admin:false;
        $rejected_admin_date = !empty($rejected_admin_date)?$rejected_admin_date:false;
        $approved = !empty($approved)?$approved:false;
        $approved_date = !empty($approved_date)?$approved_date:false;
        $rejected = !empty($rejected)?$rejected:false;
        $rejected_date = !empty($rejected_date)?$rejected_date:false;
        $customStatus = !empty($customStatus)?$customStatus:false;
        $help_apply = !empty($help_apply)?$help_apply:false;
        $log_kpr = !empty($log_kpr)?$log_kpr:false;
        $note = !empty($note)?$note:false;
        $full_name = !empty($full_name)?$full_name:false;
        $gender = !empty($gender)?$gender:false;
        $birthday = !empty($birthday)?$birthday:false;
        $email = !empty($email)?$email:false;
        $no_kpt = !empty($no_kpt)?$no_kpt:false;
        $phone = !empty($phone)?$phone:false;
        $no_hp = !empty($no_hp)?$no_hp:false;
        $no_hp_2 = !empty($no_hp_2)?$no_hp_2:false;
        $address = !empty($address)?$address:false;
        $address_2 = !empty($address_2)?$address_2:false;
        $location = !empty($location)?$location:false;
        $customIncome = !empty($customIncome)?$customIncome:false;
        $customOtherInstallment = !empty($customOtherInstallment)?$customOtherInstallment:false;
        $company = !empty($company)?$company:false;
        $job_type = !empty($job_type)?$job_type:false;
        $customHouseholdFee = !empty($customHouseholdFee)?$customHouseholdFee:false;
        $customMlsId = !empty($customMlsId)?$customMlsId:false;
        $bank_name = !empty($bank_name)?$bank_name:false;
        $bank_code = !empty($bank_code)?$bank_codebank_code:false;
        $customPropertyPrice = !empty($customPropertyPrice)?$customPropertyPrice:false;
        $customLoanPrice = !empty($customLoanPrice)?$customLoanPrice:false;
        $customLoanTime = !empty($customLoanTime)?$customLoanTime:false;
        $customRate = !empty($customRate)?$customRate:false;
        $customFloatRate = !empty($customFloatRate)?$customFloatRate:false;
        $customDp = !empty($customDp)?$customDp:false;
        $customDpPercent = !empty($customDpPercent)?$customDpPercent:false;
        $customBankCharge = !empty($customBankCharge)?$customBankCharge:false;
        $customNotaryCharge = !empty($customNotaryCharge)?$customNotaryCharge:false;
        $customFirstCredit = !empty($customFirstCredit)?$customFirstCredit:false;
        $customCreditTotal = !empty($customCreditTotal)?$customCreditTotal:false;
        $application_form = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'application_form');
?>
<table>
    <?php 
            if( !empty($code) ) {
                $contentTd = $this->Html->tag('td',__('Kode Pengajuan'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $code, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
            }

            $contentTd = $this->Html->tag('td',__('Tanggal Pengajuan'), array(
                'style' => 'text-align: left;',
            ));
            $contentTd .= $this->Html->tag('td', $this->Rumahku->formatDate($created, 'd M Y'), array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            echo $this->Kpr->_callDateStatus( __('Rumahku.com'), $approved_admin, $approved_admin_date, $rejected_admin, $rejected_admin_date, 'excel' );
            echo $this->Kpr->_callDateStatus( __('Bank'), $approved, $approved_date, $rejected, $rejected_date, 'excel' );

            $contentTd = $this->Html->tag('td',__('Status'), array(
                'style' => 'text-align: left;',
            ));
            $contentTd .= $this->Html->tag('td', $customStatus, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            if( empty($help_apply) && empty($log_kpr) && !empty($note) ) {
                $note = str_replace(PHP_EOL, '<br>', $note);
                $contentTd = $this->Html->tag('td',__('Alasan ditotak'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $note, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
            }
            
            $contentTd = $this->Html->tag('td', '&nbsp;', array(
                'style' => 'text-align: left;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);
            
            $contentTd = $this->Html->tag('td', __('DETAIL INFORMASI PROPERTI'), array(
                'style' => 'text-align: left;font-weight: bold;font-size: 18px;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);
            
            $contentTd = $this->Html->tag('td', '&nbsp;', array(
                'style' => 'text-align: left;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);

            $contentTd = $this->Html->tag('td', $full_name, array(
                'style' => 'text-align: left;font-weight: bold;font-size: 14px;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);

            if($application_form <> 'none'){
                $contentTd = $this->Html->tag('td', $gender, array(
                    'style' => 'text-align: left;',
                    'colspan' => 2,
                ));
                echo $this->Html->tag('tr', $contentTd);

                if( !empty($birthday) ) {
                    $contentTd = $this->Html->tag('td', $birthday, array(
                        'style' => 'text-align: left;',
                        'colspan' => 2,
                    ));
                    echo $this->Html->tag('tr', $contentTd);
                }

                $contentTd = $this->Html->tag('td', $email, array(
                    'style' => 'text-align: left;',
                    'colspan' => 2,
                ));
                echo $this->Html->tag('tr', $contentTd);
                
                $contentTd = $this->Html->tag('td', '&nbsp;', array(
                    'style' => 'text-align: left;',
                    'colspan' => 2,
                ));
                echo $this->Html->tag('tr', $contentTd);
                
                $contentTd = $this->Html->tag('td', '&nbsp;', array(
                    'style' => 'text-align: left;',
                    'colspan' => 2,
                ));
                echo $this->Html->tag('tr', $contentTd);

                if( !empty($no_kpt) ) {
                    $contentTd = $this->Html->tag('td',__('No. KTP'), array(
                        'style' => 'text-align: left;',
                    ));
                    $contentTd .= $this->Html->tag('td', '# '.$no_kpt, array(
                        'style' => 'text-align: left;',
                    ));
                    echo $this->Html->tag('tr', $contentTd);
                }

                if( !empty($phone) ) {
                    $contentTd = $this->Html->tag('td',__('No. Telp'), array(
                        'style' => 'text-align: left;',
                    ));
                    $contentTd .= $this->Html->tag('td', $phone, array(
                        'style' => 'text-align: left;',
                    ));
                    echo $this->Html->tag('tr', $contentTd);
                }

                $contentTd = $this->Html->tag('td',__('No. Handphone'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $no_hp, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                if( !empty($no_hp_2) ) {
                    $contentTd = $this->Html->tag('td',__('No. Handphone 2'), array(
                        'style' => 'text-align: left;',
                    ));
                    $contentTd .= $this->Html->tag('td', $no_hp_2, array(
                        'style' => 'text-align: left;',
                    ));
                    echo $this->Html->tag('tr', $contentTd);
                }

                $contentTd = $this->Html->tag('td',__('Alamat sesuai KTP'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $address, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Alamat Domisili'), array(
                    'style' => 'text-align: left;vertical-align:top;',
                ));
                $contentTd .= $this->Html->tag('td', $address_2.$location, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Penghasilan per bulan'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $customIncome, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Angsuran Lain'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $customOtherInstallment, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Perusahaan'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $company, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Jenis Pekerjaan'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $job_type, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Pengeluaran per Bulan'), array(
                    'style' => 'text-align: left;',
                ));
                    $contentTd .= $this->Html->tag('td', $customHouseholdFee, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
                
                $contentTd = $this->Html->tag('td', '&nbsp;', array(
                    'style' => 'text-align: left;',
                    'colspan' => 2,
                ));
                echo $this->Html->tag('tr', $contentTd);
            }
            
            
            $contentTd = $this->Html->tag('td', __('DETAIL INFORMASI PINJAMAN'), array(
                'style' => 'text-align: left;font-weight: bold;font-size: 18px;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);
            
            $contentTd = $this->Html->tag('td', '&nbsp;', array(
                'style' => 'text-align: left;',
                'colspan' => 2,
            ));
            echo $this->Html->tag('tr', $contentTd);

            if( !empty($customMlsId) ) {
                $contentTd = $this->Html->tag('td',__('Properti ID'), array(
                    'style' => 'text-align: left;',
                ));
                    $contentTd .= $this->Html->tag('td', $customMlsId, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);

                $contentTd = $this->Html->tag('td',__('Bank Pengajuan'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', sprintf('%s (%s)', $bank_name, $bank_code), array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
            }

            $contentTd = $this->Html->tag('td',__('Harga Properti'), array(
                'style' => 'text-align: left;',
            ));
                $contentTd .= $this->Html->tag('td', $customPropertyPrice, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            $contentTd = $this->Html->tag('td',__('Jumlah Pinjaman'), array(
                'style' => 'text-align: left;',
            ));
                $contentTd .= $this->Html->tag('td', $customLoanPrice, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            $contentTd = $this->Html->tag('td',__('Lama pinjaman'), array(
                'style' => 'text-align: left;',
            ));
                $contentTd .= $this->Html->tag('td', $customLoanTime, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            $contentTd = $this->Html->tag('td', __('Suku Bunga'), array(
                'style' => 'text-align: left;',
            ));
            $contentTd .= $this->Html->tag('td', $customRate, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            if( !empty($customFloatRate) ) {
                $contentTd = $this->Html->tag('td',__('Suku Bunga Float'), array(
                    'style' => 'text-align: left;',
                ));
                    $contentTd .= $this->Html->tag('td', $customFloatRate, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
            }

            $contentTd = $this->Html->tag('td',__('Uang Muka (DP)'), array(
                'style' => 'text-align: left;',
            ));
            $contentTd .= $this->Html->tag('td', sprintf('%s %s',$customDp,$customDpPercent), array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            $contentTd = $this->Html->tag('td',__('Total Biaya Bank'), array(
                'style' => 'text-align: left;',
            ));
            $contentTd .= $this->Html->tag('td', $customBankCharge, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);

            if( !empty($total_notary_charge) ) {
                $contentTd = $this->Html->tag('td',__('Total Biaya Notaris'), array(
                    'style' => 'text-align: left;',
                ));
                $contentTd .= $this->Html->tag('td', $customNotaryCharge, array(
                    'style' => 'text-align: left;',
                ));
                echo $this->Html->tag('tr', $contentTd);
            }

            $contentTd = $this->Html->tag('td',__('Angsuran per bulan'), array(
                'style' => 'text-align: left;',
            ));
                $contentTd .= $this->Html->tag('td', $customFirstCredit, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);
            
            $contentTd = $this->Html->tag('td',__('Total Pembayaran Pertama'), array(
                'style' => 'text-align: left;',
            ));
                $contentTd .= $this->Html->tag('td', $customCreditTotal, array(
                'style' => 'text-align: left;',
            ));
            echo $this->Html->tag('tr', $contentTd);
    ?>
</table>