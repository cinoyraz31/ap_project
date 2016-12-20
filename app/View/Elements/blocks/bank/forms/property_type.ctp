<?php
        $data = $this->request->data;
        $productTypes = $this->Rumahku->filterEmptyField($data, 'BankProductType');

        $modelName = !empty($modelName)?$modelName:false;
        $fieldName = !empty($fieldName)?$fieldName:'name';
        $element = !empty($element)?$element:'blocks/common/forms/multiple_items';
?>
<div class="row">
    <div class="col-sm-10">
        <div class="form-group plus" id="point-plus">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2 control-label">
                            <?php
                                    echo $this->Html->tag('label', __('Tipe Properti'), array(
                                        'class' => 'control-label',
                                    ));
                            ?>
                        </div>
                        <div class="relative col-sm-9">
                            <!-- <div class="row"> -->
                                <div class="extra-plus-list form-added">
                                    <ul>
                                        <?php 
                                                if( !empty($productTypes) ) {
                                                    foreach ($productTypes as $key => $productType) {
                                                        echo $this->element($element, array(
                                                            'type' => 'select',
                                                            'idx' => $key,
                                                            'value' => $productType,
                                                            'modelName' => $modelName,
                                                            'fieldName' => $fieldName,
                                                        ));
                                                    }
                                                } else {
                                                    for ($i=0; $i < 1; $i++) { 
                                                        echo $this->element($element, array(
                                                            'type' => 'select',
                                                            'idx' => $i,
                                                            'modelName' => $modelName,
                                                            'fieldName' => $fieldName,
                                                        ));
                                                    }
                                                }
                                        ?>
                                    </ul>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>