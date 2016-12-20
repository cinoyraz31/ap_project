<?php 
        $options = !empty($options)?$options:array();
?>
<div class="form-group plus">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <?php 
                    echo $this->Html->tag('h4', __('Informasi Kontak'));
            ?>
        </div>
    </div>
</div>
<?php
        echo $this->Rumahku->buildInputForm('email', array_merge($options, array(
            'label' => __('Email Bank *'),
        )));
        echo $this->Rumahku->buildInputForm('phone_center', array_merge($options, array(
            'label' => __('Call Center *'),
        )));

        echo $this->Rumahku->buildInputForm('fax', array_merge($options, array(
            'label' => __('Fax'),
        )));

        echo $this->element('blocks/common/multiple_forms', array(
            'fieldName' => 'phone',
            'modelName' => 'BankContact',
            'labelName' => __('No. Telepon'),
            'placeholder' => __('Masukkan No. Telp untuk informasi kontak'),
            'limit' => 1,
        ));
?>