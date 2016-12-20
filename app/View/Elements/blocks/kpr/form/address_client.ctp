<?php
        $data = $this->request->data;
        $idx = isset($idx)?$idx:0;
        $subareas = !empty($subareas)?$subareas:false;
        $aditionals = !empty($aditionals)?$aditionals:false;
        $labelHeader = !empty($labelHeader)?$labelHeader:false;
        $modelName = !empty($modelName)?$modelName:'KprApplication';
        $labelClass = $this->Rumahku->filterEmptyField( $options, 'labelClass');
        $attributes = $this->Rumahku->filterEmptyField( $optionAttributes, 'attributes');
        $hideAddress = $this->Rumahku->filterEmptyField( $attributes, 'hideAddress');
        $same_as_address_ktp = $this->Rumahku->filterEmptyField($value, 'same_as_address_ktp');
        $display = !empty($same_as_address_ktp)?'hide':'show';
        $class = $this->Rumahku->filterEmptyField( $options, 'class');
        echo $this->Html->tag('h2', sprintf(__('Alamat Pembeli %s'), $labelHeader), array(
            'class' => 'sub-heading'
        ));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.address', array_merge($options, array(
            'type' => 'textarea',
            'label' => __('Alamat sesuai KTP *'),
            'rows' => 2,
        )));

        $same_address = $this->Form->input($modelName.'.'.$idx.'.same_as_address_ktp', array_merge( array(
            'label' => __(' Alamat domisili saat ini sama dengan alamat KTP ?'),
            'type' => 'checkbox',
            'class' => 'same_address',
        ), $attributes));
        ## CHECK BOX SAME ADDRESS
        $div_same_address = $this->Html->tag( 'div', $same_address, array(
            'class' => $class,
        ));
        $label = $this->Html->div($labelClass, __(' '));
        echo $this->Html->tag('row', $label.$div_same_address);
        ##
        $address_2 =  $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.address_2', array_merge( $options, array(
            'type' => 'textarea',
            'label' => __('Alamat Domisili *'),
            'rows' => 2,
        )));
        echo $this->Html->tag('div', $address_2, array(
            'class' => sprintf('%s %s', $hideAddress, $display),
        ));
?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-10">
            <div class="row">
                <div class="col-md-8 col-sm-10 col-xl-2 col-sm-offset-3 col-xl-offset-1 no-pleft">
                    <?php 
                            echo $this->Form->label($modelName.'.'.$idx.'.rw', __('RW'), array(
                                'class' => 'col-xl-1 taright col-sm-1',
                            ));
                    ?>
                    <div class="relative col-sm-2 col-xl-1 col-md-2">
                        <?php 
                                echo $this->Form->input($modelName.'.'.$idx.'.rw', array(
                                    'label' => false,
                                    'div' => false,
                                    'class' => 'form-control',
                                ));
                        ?>
                    </div>
                    <?php 
                            echo $this->Form->label($modelName.'.'.$idx.'.rt', __('RT'), array(
                                'class' => 'col-xl-1 taright col-sm-1',
                            ));
                    ?>
                    <div class="relative col-sm-2 col-xl-1 col-md-2">
                        <?php 
                                echo $this->Form->input($modelName.'.'.$idx.'.rt', array(
                                    'label' => false,
                                    'div' => false,
                                    'class' => 'form-control',
                                ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.region_id', array_merge($options, array(
            'label' => __('Provinsi *'),
            'empty' => __('Pilih Provinsi'),
            'id' => sprintf('regionId%s', $aditionals),
            'attributes' => array(
                'aditionals' => $aditionals
            ),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.city_id', array_merge($options, array(
            'label' => __('Kota *'),
            'empty' => __('Pilih Kota'),
            'id' => sprintf('cityId%s', $aditionals),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.subarea_id', array_merge($options, array(
            'label' => __('Area *'),
            'empty' => __('Pilih Area'),
            'options' => $subareas,
            'id' => sprintf('subareaId%s', $aditionals),
        )));
        echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.zip', array_merge($options, array(
            'label' => __('Kode Pos *'),
            'id' => sprintf('rku-zip%s', $aditionals),
        )));
?>
