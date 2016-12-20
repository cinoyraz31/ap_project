<?php 
        $options = array();
        $data = $this->request->data;  
        $value = !empty($value)?$value:false;
        $typeOptions = !empty($typeOptions)?$typeOptions:false;
        $modelName = !empty($modelName)?$modelName:false;
        $fieldName = !empty($fieldName)?$fieldName:'name';
        $placeholder = !empty($placeholder)?$placeholder:false;
        $idx = !empty($idx)?$idx:0;
        $error = !empty($this->validationErrors[$modelName][$idx])?$this->validationErrors[$modelName][$idx]:false;
        $errorMsg = false;

        $type = $this->Rumahku->filterEmptyField($typeOptions, 'type', false, 'text');

        switch ($type) {
            case 'select':
                $empty = $this->Rumahku->filterEmptyField($typeOptions, 'empty', false, $placeholder);
                $value = $this->Rumahku->filterEmptyField($typeOptions, 'options');
                $options = array(
                    'empty' => $empty,
                    'options' => $value,
                );
                break;
        }

        if( empty($idx) ) {
            $addClass = 'field-copy';
        } else {
            $addClass = '';
        }
?>
<li class="<?php echo $addClass; ?>">
    <?php 
            $inputContent = $this->Form->input($modelName.'.'.$fieldName.'.', array_merge(array(
                'label' => false,
                'div' => false,
                'type' => $type,
                'required' => false,
                'placeholder' => $placeholder,
                'value' => $value,
            ), $options));



            if(!empty($error[$fieldName])){
                $msg = !empty($error[$fieldName][0])?$error[$fieldName][0]:false;
                if(!empty($msg)){
                    $errorMsg = $this->Html->tag('div', $msg, array(
                        'class' => 'error-message'
                    )); 
                }
            }

            echo $this->Html->tag('div', sprintf('%s %s', $inputContent, $errorMsg), array(
                'class' => 'form-group',
            ));

            echo $this->Html->tag('span', $this->Html->link($this->Rumahku->icon('rv4-cross'), '#', array(
                'escape' => false,
            )), array(
                'class' => 'removed',
            ));
    ?>
</li>