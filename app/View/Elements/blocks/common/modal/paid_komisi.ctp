<?php
    
    $type_komisi = !empty($type_komisi)?$type_komisi:false;
    $type_komisi_v = !empty($type_komisi)?ucfirst($type_komisi):false;
    $kpr_app_id = !empty($kpr_app_id)?$kpr_app_id:false;
    $payment_id = !empty($payment_id)?$payment_id:false;


    if(!empty($type_komisi)){
        $id_modal = sprintf('openModal%s',$type_komisi_v);
?>
        <div id="<?php echo $id_modal;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="openModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php
                            echo $this->element('blocks/common/modal/header', array(
                                'modalClass' => 'green',
                                'modalTitle' => 'Paid Commission',
                                'submodalTitle' => sprintf('Anda yakin ingin melanjutkan pembayaran komisi %s ini?', $type_komisi_v),
                            ));
                    ?>
                    <div class="modal-body">
                        <?php

                                echo $this->Rumahku->buildInputForm('KprCommissionPaymentConfirm.paid_date_'.$type_komisi, array(
                                    'label' => __('Tanggal Pembayaran'),
                                    'labelClass' => 'col-sm-3 col-xl-1 taright',
                                    'type' => 'text',
                                    'inputClass' => 'datepicker',
                                    'frameClass' => 'col-sm-12',
                                    'class' => 'relative col-sm-4 col-xl-4',
                                ));
                                echo $this->Form->error('KprCommissionPaymentConfirm.paid_date_'.$type_komisi); 

                                echo $this->Rumahku->buildInputForm('KprCommissionPaymentConfirm.note_reason_'.$type_komisi, array(
                                    'label' => __('Keterangan'),
                                    'labelClass' => 'col-sm-3 col-xl-1 taright',
                                    'type' => 'textarea',
                                    'rows' => 3,
                                    'frameClass' => 'col-sm-12',
                                    'class' => 'relative col-sm-8 col-xl-8',
                                ));
                                echo $this->Form->error('KprCommissionPaymentConfirm.note_reason_'.$type_komisi); 


                        ?>
                    </div>
                    <div class="modal-footer">
                        <?php
                                echo $this->Form->button(__('Batal'), array(
                                    'class' => 'btn default',
                                    'data-dismiss' => 'modal',
                                ));

                                echo $this->Form->button(__('Setuju'), array(
                                    'id' => 'akad-kpr-apply',
                                    'type' => 'button',
                                    'class' => 'btn green',
                                    'url' => '/admin/kpr/paid/'.$kpr_app_id.'/'.$payment_id.'/',
                                ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php     
    }
    
?>