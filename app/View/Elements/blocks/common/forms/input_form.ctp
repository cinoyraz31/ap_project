<?php 
        $label = !empty($label)?$label:false;
        $labelClass = !empty($labelClass)?$labelClass:false;
        $default = !empty($default)?$default:null;
        $class = !empty($class)?$class:false;
        $fieldName = !empty($fieldName)?$fieldName:false;
        $textGroup = !empty($textGroup)?$textGroup:false;
        $positionGroup = !empty($positionGroup)?$positionGroup:'right';
        $classGroup = !empty($classGroup)?$classGroup:false;
        $placeholder = !empty($placeholder)?$placeholder:false;
        $inputClass = !empty($inputClass)?$inputClass:false;
        $inputText = !empty($inputText)?$inputText:false;
        $frameClass = !empty($frameClass)?$frameClass:false;
        $infoText = !empty($infoText)?$infoText:false;
        $rows = !empty($rows)?$rows:false;
        $readonly = !empty($readonly)?$readonly:false;
        $custom = !empty($custom)?$custom:false;
        $preview = !empty($preview)?$preview:false;
        $photoName = !empty($preview['photo'])?$preview['photo']:false;

        $otherInput = !empty($otherInput)?$otherInput:false;
        $otherClass = $this->Rumahku->filterEmptyField($otherInput, 'class');
        $otherFieldName = $this->Rumahku->filterEmptyField($otherInput, 'fieldName');
        $otherInputOptions = $this->Rumahku->filterEmptyField($otherInput, 'inputOptions');

        $paramsPrice = !empty($paramsPrice)?$paramsPrice:false;
        $paramsPriceClass = $this->Rumahku->filterEmptyField($paramsPrice, 'class');
        $paramsPriceFieldName = $this->Rumahku->filterEmptyField($paramsPrice, 'fieldName');
        $paramsPriceInputOptions = $this->Rumahku->filterEmptyField($paramsPrice, 'inputOptions');

        $fieldError = isset($fieldError)?$fieldError:$fieldName;
        $errorMsg = false;

        $infopopover = isset($infopopover) ? $infopopover : '';
        if(!empty($infopopover)){

            $desc_modal = $this->Rumahku->filterEmptyField($infopopover, 'content');
            $title_modal = $this->Rumahku->filterEmptyField($infopopover, 'title');
            $options_modal = $this->Rumahku->filterEmptyField($infopopover, 'options', false, array());
            $icon_modal = $this->Rumahku->filterEmptyField($infopopover, 'icon', false, 'rv4-shortip');

            $infopopover = $this->Rumahku->noticeInfo($desc_modal, $title_modal, $options_modal, $icon_modal);
        }

        if( !empty($textGroup) ) {
            $inputClass .= sprintf(' has-side-control at-%s', $positionGroup);
            $class .= ' input-group';
        }

        if( empty($class) && empty($labelClass) ) {
            $rowFormClass = '';
        } else {
            $rowFormClass = 'row';
        }

        if( !empty($fieldError) && $this->Form->isFieldError($fieldError) ) {
            $errorMsg = $this->Form->error($fieldError, null, array(
                'class' => 'error-message'
            ));

            if( !empty($errorMsg) ) {
                $errorMsg = $this->Html->tag('div', '', array(
                    'class' => 'clear',
                )).$errorMsg;
            }
        }

        if( !empty($preview) && !empty($photoName) ) {
            $photoName = !empty($preview['photo'])?$preview['photo']:false;
            $save_path = !empty($preview['save_path'])?$preview['save_path']:false;
            $size = !empty($preview['size'])?$preview['size']:false;
            $photo = $this->Rumahku->photo_thumbnail(array(
                'save_path' => $save_path, 
                'src'=> $photoName, 
                'size' => $size,
            ));
?>
<div class="form-group preview-img">
    <div class="row">
        <div class="<?php echo $frameClass; ?>">
            <?php 
                    if( !empty($label) ) {
                        echo $this->Html->tag('div', $this->Form->label($fieldName, '&nbsp;', array(
                            'class' => 'control-label',
                        )), array(
                            'class' => $labelClass.' lbl-preview',
                        ));
                    }

                    echo $this->Html->tag('div', $photo, array(
                        'class' => $class.' wrapper-img',
                    ));
                    echo $this->Form->hidden(sprintf('%s_hide', $fieldName));
                    echo $this->Form->hidden(sprintf('%s_save_path', $fieldName));
                    echo $this->Form->hidden(sprintf('%s_name', $fieldName));
            ?>
        </div>
    </div>
</div>
<?php 
        }
?>
<div class="form-group">
    <div class="row">
        <div class="<?php echo $frameClass; ?>">
            <div class="<?php echo $rowFormClass; ?>">
                <?php 
                        if( !empty($label) ) {
                            echo $this->Html->tag('div', $this->Form->label($fieldName, $label, array(
                                'class' => 'control-label',
                            )), array(
                                'class' => $labelClass,
                            ));
                        }

                        if(!empty($otherInput)){
                ?>
                <div class = "<?php echo $otherClass; ?>">
                    <?php
                            echo $this->Form->input($otherFieldName, $otherInputOptions);
                    ?>
                </div>
                <?php
                        }
                ?>
                <div class="<?php echo $class; ?>">
                    <?php 
                            $optionsInput = array(
                                'class' => $inputClass,
                                'label' => false,
                                'required' => false,
                                'error' => false,
                                'div' => false,
                                'placeholder' => $placeholder,
                                'disabled' => $disabled,
                                'readonly' => $readonly,
                                'default' => $default
                             
                            );

                            if( !empty($empty) ) {
                                $optionsInput['empty'] = $empty;
                            }
                            if( !empty($id) ) {
                                $optionsInput['id'] = $id;
                            }
                            if( !empty($options) ) {
                                $optionsInput['options'] = $options;
                            }
                            if( !empty($rows) ) {
                                $optionsInput['rows'] = $rows;
                            }
                            if( !empty($attributes) ) {
                                $optionsInput = array_merge($optionsInput, $attributes);
                            }


                            if( !empty($inputText) ) {
                                $optionsInput['class'] .= ' text-control';

                                echo $this->Html->tag('div', $inputText, $optionsInput);
                            } else {
                                $optionsInput['class'] .= ' form-control';

                                if( !empty($type) ) {
                                    $optionsInput['type'] = $type;
                                }
                                echo $this->Form->input($fieldName, $optionsInput);
                            }

                            if( !empty($textGroup) ) {
                                echo $this->Html->tag('div', $textGroup, array(
                                    'class' => sprintf('input-group-addon at-%s %s', $positionGroup, $classGroup),
                                ));
                            }

                            if( !empty($infoText) ) {
                                echo $this->Html->tag('small', $this->Html->tag('span', $infoText), array(
                                    'class' => 'overflow-extra-text tajustify',
                                ));
                            }

                            if( !empty($custom) ) {
                                $customType = !empty($custom['type'])?$custom['type']:false;
                                $customFieldName = !empty($custom['fieldName'])?$custom['fieldName']:false;
                                $customLabel = !empty($custom['label'])?$custom['label']:false;

                                switch ($customType) {
                                    case 'whatsapp':
                                        echo $this->Html->tag('div', $this->Form->input($customFieldName, array(
                                            'type' => 'checkbox',
                                            'label' => $customLabel,
                                            'div' => false,
                                        )), array(
                                            'class' => 'extra-checkbox relative',
                                        ));
                                        break;
                                }
                            }

                            if( !empty($errorMsg) && empty($textGroup) ) {
                                echo $errorMsg;
                            }
                    ?>
                </div>
                    <?php
                            if(!empty($infopopover)){
                                echo $this->Html->tag('div', $infopopover, array(
                                    'class' => 'col-sm-1 infopopover notice'
                                ));
                            }

                            if(!empty($paramsPrice)){
                    ?>
                        <div class = "<?php echo $paramsPriceClass; ?>">
                            <?php
                                    echo $this->Form->input($paramsPriceFieldName, $paramsPriceInputOptions);
                            ?>
                        </div>
                    <?php
                            }
                        ?>
                </div>
            <?php 
                    if( !empty($errorMsg) && !empty($textGroup) ) {
                        echo $errorMsg;
                    }
            ?>
        </div>
    </div>
</div>