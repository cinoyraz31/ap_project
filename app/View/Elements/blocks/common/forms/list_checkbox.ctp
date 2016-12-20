<?php
        $id = !empty($id)?$id:false;
        $label = !empty($label)?$label:false;
        $description = !empty($description)?$description:false;
        $values = !empty($values)?$values:false;
        $modelName = !empty($modelName)?$modelName:false;
        $fieldName = !empty($fieldName)?$fieldName:false;
        $classLabel = !empty($classLabel)?$classLabel:'col-sm-10 col-sm-offset-1';
        $classForm = !empty($classForm)?$classForm:'col-sm-8 col-sm-offset-1';
        $classList = !empty($classList)?$classList:'col-sm-4';
        $customContent = !empty($customContent)?$customContent:false;

        if( !empty($values) ) {
?>
<div class="form-group plus">
    <div class="row">
        <div class="<?php echo $classLabel; ?> block">
            <?php 
                    echo $this->Html->tag('h4', $label);

                    if( !empty($description) ) {
                        echo $this->Html->tag('p', $description);
                    }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="<?php echo $classForm; ?>">
            <div class="cb-custom" autocomplete="off">
                <ul class="row">
                    <?php 
                            $options = array(
                                'modelName' => $modelName,
                                'fieldName' => $fieldName,
                                'classList' => $classList,
                                'id' => $id,
                            );

                            if( is_array($values) ) {
                                foreach ($values as $id => $value) {
                                    $options['id'] = $id;
                                    $options['value'] = $value;

                                    echo $this->element('blocks/common/forms/list_checkbox_items', $options);
                                }
                            } else {
                                $options['value'] = $values;
                                echo $this->element('blocks/common/forms/list_checkbox_items', $options);
                            }

                            if( !empty($customContent) ) {
                                echo $customContent;
                            }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
        }
?>