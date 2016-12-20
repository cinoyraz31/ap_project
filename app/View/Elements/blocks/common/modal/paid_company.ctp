<?php 
        $id = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'id', 0);

?>
<div id="openModalCompany" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="openModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
                    echo $this->element('blocks/common/modal/header', array(
                        'modalClass' => 'green',
                        'modalTitle' => 'Paid Company KPR',
                        'submodalTitle' => 'Anda yakin ingin membayar provisi perusahaan',
                    ));
            ?>
            <div class="modal-body">
                <?php
                        echo $this->Rumahku->buildInputForm('note_company', array(
                            'type' => 'textarea',
                            'rows' => 3,
                            'frameClass' => 'col-sm-12',
                            'class' => 'relative col-sm-12 col-xl-12',
                        ));
                        echo $this->Form->error('KprApplication.note_company'); 
                ?>
            </div>
            <div class="modal-footer">
                <?php
                        echo $this->Form->button(__('Batal'), array(
                            'class' => 'btn default',
                            'data-dismiss' => 'modal',
                        ));

                        echo $this->Form->button(__('Setuju'), array(
                            'id' => 'company-kpr-apply',
                            'type' => 'button',
                            'class' => 'btn green',
                            'url' => '/admin/kpr/doConfirm/'.$id.'/3'
                        ));
                ?>
            </div>
        </div>
    </div>
</div>