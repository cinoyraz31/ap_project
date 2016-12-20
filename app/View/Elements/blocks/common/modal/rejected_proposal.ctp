<?php 
        $id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id', 0);
?>
<div id="openModalProposal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="openModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
                    echo $this->element('blocks/common/modal/header', array(
                        'modalClass' => 'red',
                        'modalTitle' => 'Tolak Referral',
                        'submodalTitle' => 'Berikan alasan mengapa Referral KPR ditolak.',
                    ));
            ?>
            <div class="modal-body">
                <?php
                        echo $this->Rumahku->buildInputForm('note', array(
                            'type' => 'textarea',
                            'rows' => 3,
                            'frameClass' => 'col-sm-12',
                            'class' => 'relative col-sm-12 col-xl-12',
                        ));
                        echo $this->Form->error('KprApplication.note'); 
                ?>
            </div>
            <div class="modal-footer">
                <?php
                        echo $this->Form->button(__('Batal'), array(
                            'class' => 'btn default',
                            'data-dismiss' => 'modal',
                        ));

                        echo $this->Form->button(__('Tolak'), array(
                            'id' => 'reject-kpr-apply',
                            'type' => 'button',
                            'class' => 'btn red',
                            'url' => '/admin/kpr/doConfirm/'.$id.'/7'
                        ));
                ?>
            </div>
        </div>
    </div>
</div>