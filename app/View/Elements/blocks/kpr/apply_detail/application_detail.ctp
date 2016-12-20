<?php

        $modelName = 'KprBankInstallment';
        $result = $this->Kpr->_getStatus( $value); 
        $allow_edit = $this->Rumahku->filterEmptyField( $result, 'allow_edit');
        $kpr_bank_installments = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment');
    	$appraisal_date = $this->Rumahku->filterEmptyField($value, 'KprBank', 'appraisal_date');
        $aprove_proposal = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'aprove_proposal');
        $status_application = $this->Kpr->_callStatusDocument($value);
        $count = count($kpr_bank_installments);

        $btnSlide = $this->Html->link($this->Rumahku->icon('rv4-angle-up'), '#', array(
            'escape' => false,
            'class' => 'toggle-display floright hidden-print',
            'data-display' => "#detail-project-loan",
            'data-type' => 'slide',
            'data-arrow' => 'true',
        ));

        $label = $this->Html->tag('label', __('Informasi Pinjaman'));
        echo $this->Html->tag('div', $label.$btnSlide, array(
            'class' => 'info-title'
        ));
?>  
<div id ="detail-project-loan" class="tab-content">
        <?php
                if(!empty($value['BankProduct'])){
                    $product_name = $this->Rumahku->filterEmptyField($value, 'BankProduct', 'name');
                    $text_promo = $this->Rumahku->filterEmptyField($value, 'BankProduct', 'text_promo');
        ?>
    <div class="mb30">
        <div class="row">
                <?php
                        echo $this->Html->div('col-sm-4', $this->Html->tag('label', __('Produk KPR')));
                        echo $this->Html->div('col-sm-4', $product_name);
                ?>
        </div>
        <div class="row">
            <?php
                    echo $this->Html->div('col-sm-4', $this->Html->tag('label', __('Promo')));
                    echo $this->Html->div('col-sm-8', $this->Html->tag('span', $text_promo));
            ?>
        </div>
    </div>
        <?php
                }

                // if($count > 1){
                    $Class = 'col-sm-4 taright';
                    $labelClass = 'col-sm-4';
                // }else{
                //     $Class = 'col-sm-4 taright';
                //     $labelClass = 'col-sm-4';
                // }
                $options = array(
                    'labelClass' => $labelClass,
                    'valueClass' => $Class,
                );
        ?>

    <div class="row">
        <div class="col-sm-9">
            <div class="row mb10">
                    <?php
                            echo $this->Html->div($labelClass, $this->Html->tag('label', __('Pinjaman')));
                            echo $this->Html->div( $Class, $this->Html->tag('label', __('Pengajuan  ')));
                            if($count > 1){
                                echo $this->Html->div($Class, $this->Html->tag('label', __('Disetujui'), array(
                                    'class' => 'color-blue'
                                )));
                            }
            		?>
            </div>
            <?php
        	    	echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Harga Terjual'), $modelName, 'property_price', $options);
                    echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Total Pinjaman'), $modelName, 'loan_price', $options);
                    echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Lama Pinjaman'), $modelName, 'credit_total', $options, __(' Tahun'));

            ?>
            <div class="row mt50 mb10">
                <?php
                    echo $this->Html->div('col-sm-12', $this->Html->tag('label', __('Pembayaran')));
                ?>
            </div>
                <?php
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Uang Muka'), $modelName, 'down_payment', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Appraisal'), $modelName, 'appraisal', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Administrasi'), $modelName, 'administration', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Asuransi'), $modelName, 'insurance', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Biaya Notaris'), $modelName, 'notary', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Provisi'), $modelName, 'commission', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Angsuran per Bulan'), $modelName, 'total_first_credit', $options);
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Pembayaran Pertama ( Dp + Biaya Bank + Biaya Notaris + Angsuran Pertama )'), $modelName, 'grandTotal', $options);
                ?>
            <div class="row mt50 mb10">
                <?php
                    echo $this->Html->div('col-sm-12', $this->Html->tag('label', __('Suku Bunga')));
                ?>
            </div>
                <?php
                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Fix'), $modelName, 'interest_rate_fix', $options, '%', array(
                            'fieldName' => 'periode_fix',
                            'concat' => ' Tahun',
                        )); 

                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Cap'), $modelName, 'interest_rate_cabs', $options, '%', array(
                            'fieldName' => 'periode_cab',
                            'concat' => ' Tahun',
                        )); 

                        echo $this->Kpr->generateDetailPinjaman( $kpr_bank_installments, __('Floating (Saat ini)'), $modelName, 'interest_rate_float', $options, '%');

                        if( $count == 1 ){
                            echo $this->Html->tag('span', __('* Rincian pinjaman hanya berupa ilustrasi'), array(
                                'class' => 'ilustration',
                            ));
                        }
                ?>
        </div>
    </div>
</div>