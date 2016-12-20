<?php
        $_global_variable = !empty($_global_variable) ? $_global_variable : false;
        $label_header = !empty($label_header)?$label_header:__('Pembeli');
        $label = sprintf(__('Biodata %s'), $label_header);

        $customMarital = $this->Rumahku->filterEmptyField($_global_variable, 'status_marital', $status_marital);
?>
<div class="row">
    <div class="col-sm-12">
        <?php
            echo $this->Html->tag('label', $label);
        ?>
    </div>
</div>
<?php
        if(!empty($full_name)){
            echo $this->Kpr->generateViewDetail(__('Nama'), $full_name);
        }

        if(!empty($email)){
            echo $this->Kpr->generateViewDetail(__('Email'), $email);
        }

        if(!empty($no_hp)){
            if(!empty($phone)){
                $no_hp .= ' / '.$phone;
            }
            echo $this->Kpr->generateViewDetail(__('No. Handphone'), $no_hp);
        }

        if(!empty($gender)){
            echo $this->Kpr->generateViewDetail(__('Jenis Kelamin'), $gender);
        }

        if(!empty($status_marital)){
            echo $this->Kpr->generateViewDetail(__('Status Menikah'), $customMarital);
        }

        if(!empty($no_ktp)){
            echo $this->Kpr->generateViewDetail(__('No. KTP'), sprintf( '%s', $no_ktp ));
        }

        if(!empty($birthday) && !empty($birthplace)){
            $customBirthDay = sprintf('%s, %s', $birthplace, $this->Rumahku->formatDate($birthday, 'd M Y'));
            echo $this->Kpr->generateViewDetail(__('Tempat/Tanggal Lahir'), $customBirthDay);
        }
?>