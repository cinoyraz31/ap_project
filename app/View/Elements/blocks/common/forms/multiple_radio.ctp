<?php 
        if( !empty($options) ) {
            if( !empty($label) ) {
                echo $this->Form->label($fieldName, $label);
            }
?>
<div class="btn-group tracker-radio-id block" data-toggle="buttons">
    <label class="btn btn-default action">
        <?php 
                echo $this->Form->radio('radio_default', $options, array(
                    'label' => $label,
                    'legend' => false,
                    'separator' => '</label><label class="btn btn-default action">',
                    'hiddenField' => false,
                ));
        ?>
    </label>
</div>
<?php         
            echo $this->Form->hidden($fieldName, array(
                'class' => 'info-radio-id',
                'error' => false,
            ));

            if( !empty($error) ) {
                echo $this->Form->error($fieldName);
            }
        }
?>