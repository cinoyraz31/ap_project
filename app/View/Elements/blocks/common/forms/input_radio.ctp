<?php 
        $frameClass = !empty($frameClass)?$frameClass:false;
        
        $label = !empty($label)?$label:false;
        $labelClass = !empty($labelClass)?$labelClass:false;

        $class = !empty($class)?$class:false;
        $fieldName = !empty($fieldName)?$fieldName:false;

        $options = !empty($options)?$options:false;
        $styling = !empty($styling)?$styling:false;
?>
<div class="form-group radio-custom">
    <div class="row">
        <div class="<?php echo $frameClass; ?>">
            <div class="row">
                <div class="<?php echo $labelClass; ?> extra-pd-top">
                <?php 
                        if( !empty($label) ) {
                            echo $this->Form->label($fieldName, $label, array(
                                'class' => 'form-control',
                            ));
                        }
                ?>
                </div>
                <div class="<?php echo $class; ?> cb-custom">
                    <?php 
                            switch ($styling) {
                                case 'line':
                                    echo $this->Html->tag('ul', $this->Html->tag('li', $this->Form->radio($fieldName, $options, array(
                                        'legend' => false,
                                        'separator' => '</li><li class="cb-checkmark radio">',
                                        'required' => false,
                                    )), array(
                                        'class' => 'cb-checkmark radio',
                                    )), array(
                                        'class' => 'rd-line',
                                    ));
                                    break;
                                
                                default:
                                    echo $this->Form->radio($fieldName, $options, array(
                                        'legend' => false,
                                    ));
                                    break;
                            }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>