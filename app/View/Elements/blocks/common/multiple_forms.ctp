<?php 
        $data = $this->request->data;
        $typeOptions = !empty($typeOptions)?$typeOptions:false;
        $modelName = !empty($modelName)?$modelName:false;
        $fieldName = !empty($fieldName)?$fieldName:'name';
        $labelName = !empty($labelName)?$labelName:false;
        $placeholder = !empty($placeholder)?$placeholder:false;
        $infoTop = !empty($infoTop)?$infoTop:false;
        $infoBottom = !empty($infoBottom)?$infoBottom:false;
        $divClassTop = isset($divClassTop)?$divClassTop:'col-sm-10 col-sm-offset-1';
        $divValueTop = isset($divValueTop)?$divValueTop:false;
        $divValueTop = !empty($divValueTop) ? $divValueTop : $divClassTop;
        $limit = !empty($limit)?$limit:2;
        $extra_plus = isset($extra_plus)?$extra_plus:'extra-plus-list form-added';
        $element = !empty($element)?$element:'blocks/common/forms/multiple_items';

        $values = $this->Rumahku->filterEmptyField($data, $modelName, $fieldName);
?>
<div class="form-group plus" id="point-plus">
    <?php 
            if( !empty($labelName) || !empty($infoTop) ) {
    ?>
    <div class="row desc">
        <div class="<?php echo $divClassTop; ?>">
            <?php 
                    echo $this->Html->tag('h4', $labelName);

                    if( !empty($infoTop) ) {
                        echo $this->Html->tag('p', $infoTop);
                    }
            ?>
        </div>
    </div>
    <?php 
            }
    ?>
    <div class="row">
        <div class="<?php echo $divValueTop; ?>">
            <div class="<?php echo $extra_plus; ?>">
                <ul>
                    <?php 
                            if( !empty($values) ) {
                                foreach ($values as $key => $value) {
                                    echo $this->element($element, array(
                                        'idx' => $key,
                                        'value' => $value,
                                        'typeOptions' => $typeOptions,
                                        'modelName' => $modelName,
                                        'fieldName' => $fieldName,
                                        'placeholder' => $placeholder,
                                    ));
                                }
                            } else {
                                for ($i=0; $i < $limit; $i++) { 
                                    echo $this->element($element, array(
                                        'idx' => $i,
                                        'modelName' => $modelName,
                                        'typeOptions' => $typeOptions,
                                        'placeholder' => $placeholder,
                                        'fieldName' => $fieldName,
                                    ));
                                }
                            }
                    ?>
                </ul>
                <?php 
                        $contentLink = $this->Html->tag('span', $this->Rumahku->icon('rv4-bold-plus'), array(
                            'class' => 'btn dark small-fixed',
                        ));
                        $contentLink .= $this->Html->tag('span', sprintf(__('Tambah %s'), $labelName));
                        echo $this->Html->link($contentLink, '#', array(
                            'escape' => false,
                            'role' => 'button',
                            'class' => 'field-added',
                        ));
                ?>
            </div>
        </div>
    </div>
    <?php 
            if( !empty($infoBottom) ) {
    ?>
    <div class="row">
        <div class="<?php echo $divClassTop; ?>">
            <?php 
                    echo $this->Html->tag('p', $infoBottom);
            ?>
        </div>
    </div>
    <?php 
            }
    ?>
</div>