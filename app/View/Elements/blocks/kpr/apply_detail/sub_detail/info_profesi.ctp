<?php
    $label_header = !empty($label_header)?$label_header:false;
    $label = sprintf(__('Informasi Profesi %s'), $label_header);
?>

<div class="mt30">
    <div class="row">
        <div class="col-sm-12">
            <?php
                    echo $this->Html->tag('label', $label);

                    if(!empty($company)){
                        echo $this->Kpr->generateViewDetail(__('Nama Perusahaan'), $company);
                    }

                    if(!empty($job_type)){
                        echo $this->Kpr->generateViewDetail(__('Jenis Pekerjaan'), $job_type);
                    }
            ?>
        </div>
    </div>
</div>