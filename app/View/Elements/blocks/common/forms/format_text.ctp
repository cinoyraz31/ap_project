<?php 
        $label = !empty($label)?$label:false;
        $labelClass = !empty($labelClass)?$labelClass:false;

        $class = !empty($class)?$class:false;

        $inputClass = !empty($inputClass)?$inputClass:false;
        $inputText = !empty($inputText)?$inputText:false;
        $frameClass = !empty($frameClass)?$frameClass:false;

        if( empty($class) && empty($labelClass) ) {
            $rowFormClass = '';
        } else {
            $rowFormClass = 'row';
        }
?>
<div class="form-group form-text">
    <div class="row">
        <div class="<?php echo $frameClass; ?>">
            <div class="<?php echo $rowFormClass; ?>">
                <?php 
                        if( !empty($label) ) {
                            echo $this->Html->tag('div', $this->Html->tag('label', $label, array(
                                'class' => 'control-label',
                            )), array(
                                'class' => $labelClass,
                            ));
                        }
                ?>
                <div class="<?php echo $class; ?>">
                    <?php 
                            $optionsInput = array(
                                'class' => $inputClass,
                                'label' => false,
                                'disabled' => $disabled,
                            );

                            if( !empty($id) ) {
                                $optionsInput['id'] = $id;
                            }
                            if( !empty($attributes) ) {
                                $optionsInput = array_merge($optionsInput, $attributes);
                            }

                            $optionsInput['class'] .= ' text-control';

                            echo $this->Html->tag('div', $inputText, $optionsInput);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>