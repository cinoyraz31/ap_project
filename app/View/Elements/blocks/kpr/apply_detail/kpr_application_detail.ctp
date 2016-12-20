<?php

    $kpr_application = !empty($kpr_application)?$kpr_application:false;
    $appraisal_date = $this->Rumahku->filterEmptyField($kpr_application, 'appraisal_date');
    
    ## GETDATA KPR APPLICATION CONFIRM
    if(!empty($appraisal_date)){

        $currency = $this->Rumahku->filterEmptyField($value, 'Currency', 'symbol');

        $bank_code = $this->Rumahku->filterEmptyField($kpr_application, 'Bank', 'code');
        $bank_name = $this->Rumahku->filterEmptyField($kpr_application, 'Bank', 'name');
        $property_price = $this->Rumahku->filterEmptyField($kpr_application, 'property_price');
        $loan_price = $this->Rumahku->filterEmptyField($kpr_application, 'loan_plafond');
        $down_payment = $this->Rumahku->filterEmptyField($kpr_application, 'down_payment');
        $persen_loan = $this->Rumahku->filterEmptyField($kpr_application, 'persen_loan');
        $interest_rate = $this->Rumahku->filterEmptyField($kpr_application, 'interest_rate');
        $floating_rate = $this->Rumahku->filterEmptyField($kpr_application, 'floating_rate');
        $credit_fix = $this->Rumahku->filterEmptyField($kpr_application, 'credit_fix');
        $periode_fix = $this->Rumahku->filterEmptyField($kpr_application, 'periode_fix');
        $credit_float = $periode_fix - $credit_fix;
        $appraisal = $this->Rumahku->filterEmptyField($kpr_application, 'appraisal');
        $administration = $this->Rumahku->filterEmptyField($kpr_application, 'administration');
        $provision = $this->Rumahku->filterEmptyField($kpr_application, 'provision');
        $insurance = $this->Rumahku->filterEmptyField($kpr_application, 'insurance');
        $sale_purchase_certificate_persen = $this->Rumahku->filterEmptyField($kpr_application, 'sale_purchase_certificate');
        $transfer_title_charge_persen = $this->Rumahku->filterEmptyField($kpr_application, 'transfer_title_charge');
        $credit_agreement = $this->Rumahku->filterEmptyField($kpr_application, 'credit_agreement');
        $SKMHT_persen = $this->Rumahku->filterEmptyField($kpr_application, 'letter_mortgage');
        $APHT_persen = $this->Rumahku->filterEmptyField($kpr_application, 'imposition_act_mortgage');
        $HT_persen = $this->Rumahku->filterEmptyField($kpr_application, 'mortgage');
        $other_certificate_persen = $this->Rumahku->filterEmptyField($kpr_application, 'other_certificate');
        $same_with_kpr = $this->Rumahku->filterEmptyField($kpr_application, 'same_with_kpr');
        $periode_fix = $this->Rumahku->filterEmptyField($kpr_application, 'periode_fix');

        $costKPR = $this->Kpr->_callCalcCostKpr(array(
            'KprApplication' => array(
                'interest_rate' => $interest_rate,
                'credit_fix' => $credit_fix,
                'credit_float' => $credit_float,
                'property_price' => $property_price,
                'persen_loan' => $persen_loan,
                'loan_price' => $loan_price,
                'appraisal' => $appraisal,
                'administration' => $administration,
                'provision' => $provision,
                'insurance' => $insurance,
                'sale_purchase_certificate' => $sale_purchase_certificate_persen,
                'transfer_title_charge' => $transfer_title_charge_persen,
                'letter_mortgage' => $SKMHT_persen,
                'imposition_act_mortgage' => $APHT_persen,
                'mortgage' => $HT_persen,
                'other_certificate' => $other_certificate_persen,
                'credit_agreement' => $credit_agreement,
            ),
        ));

        $credit_total = $this->Rumahku->filterEmptyField($costKPR, 'credit_total');
        $down_payment = $this->Rumahku->filterEmptyField($costKPR, 'down_payment');
        $down_payment_percent = $this->Rumahku->filterEmptyField($costKPR, 'down_payment_percent');
        $total_bank_charge = $this->Rumahku->filterEmptyField($costKPR, 'total_bank_charge');
        $total_notary_charge = $this->Rumahku->filterEmptyField($costKPR, 'total_notary_charge');
        $total_first_credit = $this->Rumahku->filterEmptyField($costKPR, 'total_first_credit');
        $total = $this->Rumahku->filterEmptyField($costKPR, 'total');

        $customPropertyPrice = $this->Rumahku->getCurrencyPrice($property_price, '-', $currency);
        $customLoanPrice = $this->Rumahku->getCurrencyPrice($loan_price, '-', $currency);
        $customDp = $this->Rumahku->getCurrencyPrice($down_payment, false, $currency);
        $customBankCharge = $this->Rumahku->getCurrencyPrice($total_bank_charge, false, $currency);
        $customNotaryCharge = $this->Rumahku->getCurrencyPrice($total_notary_charge, false, $currency);
        $customFirstCredit = $this->Rumahku->getCurrencyPrice($total_first_credit, false, $currency);
        $customCreditTotal = $this->Rumahku->getCurrencyPrice($total, false, $currency);

        $customLoanTime = sprintf("%s Bulan (%s Tahun)", $credit_total*12, $credit_total);
        $customRate = sprintf("%s%s %s %s tahun", $interest_rate, '%', $this->Html->tag('i', __('effective fixed')), $credit_fix);
        $customFloatRate = sprintf("%s%s %s %s tahun", $floating_rate, '%', $this->Html->tag('i', __('effective float')), $credit_float);
        $customDpPercent = sprintf('(%s%s)',$down_payment_percent,__('%'));

    }

    // if(!empty($kpr_application)){
?>
        <div class="col-sm-6 pr0 print-split-col">
            <div class="split two second-sect">
                <?php
                    echo $this->Html->tag('h4', sprintf('Detail Informasi Pinjaman (%s)',$label));

                ?>
                <ul>
                    <?php

                        if(!empty($customPropertyPrice)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Harga Properti')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customPropertyPrice, array(
                                'id' => 'price',
                            )), array(
                                'class' => 'col-sm-6',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if(!empty($customLoanPrice)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Jumlah Pinjaman')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customLoanPrice, array(
                                'id' => 'price',
                            )), array(
                                'class' => 'col-sm-6',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if(!empty($customDp) && !empty($customDpPercent)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Uang Muka')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', sprintf('%s %s',$customDp,$customDpPercent), array(
                                'id' => 'dp',
                            )), array(
                                'class' => 'col-sm-6',
                            ));
                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if( !empty($customBankCharge) ){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Total Biaya Bank')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customBankCharge, array(
                                'id' => 'dp',
                            )), array(
                                'class' => 'col-sm-6',
                            ));
                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if( !empty($customNotaryCharge) ) {

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Total Biaya Notaris')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customNotaryCharge, array(
                                'id' => 'dp',
                            )), array(
                                'class' => 'col-sm-6',
                            ));
                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if( !empty($customFirstCredit) ) {

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Angsuran per bulan')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customFirstCredit, array(
                                'id' => 'dp',
                            )), array(
                                'class' => 'col-sm-6',
                            ));
                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        
                        
                        if(!empty($customLoanTime)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Lama Pinjaman')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customLoanTime, array(
                                'id' => 'price',
                            )), array(
                                'class' => 'col-sm-6',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if(!empty($customRate)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Suka Bunga')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customRate, array(
                                'id' => 'price',
                            )), array(
                                'class' => 'col-sm-6',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                        if(!empty($customFloatRate)){

                            $label = $this->Html->tag('div', $this->Html->tag('label', __('Suku Bunga Float')), array(
                                'class' => 'col-sm-6',
                            ));
                            $value = $this->Html->tag('div', $this->Html->tag('span', $customFloatRate, array(
                                'id' => 'price',
                            )), array(
                                'class' => 'col-sm-6',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value));

                        }

                    ?>
                </ul>
            </div>
        </div>
<?php
    // }

?>
