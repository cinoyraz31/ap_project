<?php         
        $data = $this->request->data;
        $frameClass = !empty($frameClass)?$frameClass:false;
        
        $label = !empty($label)?$label:false;
        $labelClass = !empty($labelClass)?$labelClass:false;

        $class = !empty($class)?$class:false;
        $inputClass = !empty($inputClass)?$inputClass:false;
        $fieldName = !empty($fieldName)?$fieldName:false;
        $infoText = !empty($infoText)?$infoText:false;
        $infoClass = isset($infoClass)?$infoClass:'overflow-extra-text tajustify';

        $data_toggle = !empty($data_toggle)?$data_toggle:false;
        $data_width = !empty($data_width)?$data_width:false;
        $data_height = !empty($data_height)?$data_height:false;
        $default = isset($default)?$default:false;

        $infopopover = isset($infopopover) ? $infopopover : '';
        if(!empty($infopopover)){

            $desc_modal = $this->Rumahku->filterEmptyField($infopopover, 'content');
            $title_modal = $this->Rumahku->filterEmptyField($infopopover, 'title');
            $options_modal = $this->Rumahku->filterEmptyField($infopopover, 'options', false, array());
            $icon_modal = $this->Rumahku->filterEmptyField($infopopover, 'icon', false, 'rv4-shortip');

            $infopopover = $this->Rumahku->noticeInfo($desc_modal, $title_modal, $options_modal, $icon_modal);
        }
?>
<div class="form-group">
    <div class="row">
        <div class="<?php echo $frameClass; ?>">
            <div class="row">
                <?php 
                        if( !empty($label) ) {
                            echo $this->Html->tag('div', $this->Form->label($fieldName, $label, array(
                                'class' => 'control-label',
                            )), array(
                                'class' => $labelClass,
                            ));
                        }
                ?>
                <div class="<?php echo $class; ?>">
                    <div class="relative">
                        <div class="toggle-container">
                            <?php 
                                    $option_input = array(
                                        'type' => 'checkbox',
                                        'label' => false,
                                        'required' => false,
                                        'div' => false,
                                        'data-toggle' => $data_toggle,
                                        'data-width' => $data_width,
                                        'data-height' => $data_height,
                                        'class' => 'toggle-input '.$inputClass,
                                    );

                                    if(!empty($attributes)){
                                        $option_input = array_merge($option_input, $attributes);
                                    }

                                    if( empty($data) ) {
                                        $option_input['value'] = $default;
                                    }

                                    echo $this->Form->input($fieldName, $option_input);
                            ?>
                        </div>
                        <?php

                                if( !empty($infoText) ) {
                                    echo $this->Html->tag('small', $this->Html->tag('span', $infoText), array(
                                        'class' => $infoClass,
                                    ));
                                }
                        ?>
                    </div>
                </div>
                <?php
                        if(!empty($infopopover)){
                            echo $this->Html->tag('div', $infopopover, array(
                                'class' => 'col-sm-1 infopopover notice'
                            ));
                        }
                ?>
            </div>
        </div>
    </div>
</div>