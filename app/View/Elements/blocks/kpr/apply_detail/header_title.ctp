<?php 
        $code = $this->Rumahku->filterEmptyField($value, 'KprBank', 'code');
?>
<div class="boxhead with-bg btn-blue">
    <div class="row">
        <div class="col-sm-6 print-split-col">
        <?php
            if( !empty($code) ) {
                echo $this->Html->tag('label', __('Kode Pengajuan : '));
                echo $this->Html->tag('span', $code, array(
                    'id' => 'appcode',
                ));
            }
        ?>
        </div>
        <?php
            echo $this->Html->tag('div', $this->element('blocks/common/export_document'), array(
                'class' => 'col-sm-6 taright print-split-col hidden-print',
            ));
        ?>
    </div>
</div>