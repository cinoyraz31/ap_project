<?php 
        $id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id', 0);
        $label_name = array(0 =>'agen',1 => Configure::read('__Site.site_name'));
        $flag_approved = $this->Rumahku->filterEmptyField($value,'KprApplication','flag_approved',array());
        $flag_rejected = $this->Rumahku->filterEmptyField($value,'KprApplication','flag_rejected',array());
        $div_height = null;

        if( !in_array(1,$flag_approved) || !in_array(1,$flag_rejected)){
            $div_height = 'auto-height';
        }

?>
<div id="openModalAgentRejected" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="openModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content <?php echo $div_height; ?>">
            <?php
                    echo $this->element('blocks/common/modal/header', array(
                        'modalClass' => 'red',
                        'modalTitle' => 'Provisi KPR Ditolak',
                        'submodalTitle' => 'Berikan alasan mengapa Provisi KPR ditolak.',
                    ));
            ?>
            <div class="modal-body">

            <?php

                    foreach($label_name AS $key => $name){
                        $flag_approved = !empty($flag_approved[$key])?$flag_approved[$key]:false;
                        $flag_rejected = !empty($flag_rejected[$key])?$flag_rejected[$key]:false;


                        if(!$flag_approved && !$flag_rejected){
            ?>      
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo $this->Html->tag('strong',$name)?></legend>
                                <div class="control-group">
                                <?php  

                                    echo $this->Form->hidden('KprCommissionPayment.'.$key.'.label_name',array(
                                        'default' => $name
                                    )); 

                                    echo $this->Rumahku->buildInputForm('KprCommissionPayment.'.$key.'.note_reason_rejected', array(
                                        'label' => __('Keterangan'),
                                        'type' => 'textarea',
                                        'labelClass' => 'col-sm-3 col-xl-3',
                                        'rows' => 3,
                                        'frameClass' => 'col-sm-12',
                                        'class' => 'relative col-sm-9 col-xl-9',
                                    ));
                                    echo $this->Form->error('KprCommissionPayment.'.$key.'.note_reason'); 
                                ?>
                                </div>
                            </fieldset>
            <?php
                        }
                    }
            ?> 
            </div>
            <div class="modal-footer">
                <?php
                        echo $this->Form->button(__('Batal'), array(
                            'class' => 'btn default',
                            'data-dismiss' => 'modal',
                        ));

                        echo $this->Form->button(__('Tolak'), array(
                            'id' => 'reject-akad-apply',
                            'type' => 'button',
                            'class' => 'btn red',
                            'url' => '/admin/kpr/doConfirm/'.$id.'/2'
                        ));
                ?>
            </div>
        </div>
    </div>
</div>