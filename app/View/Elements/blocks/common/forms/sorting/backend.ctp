<?php 
        $sorting = !empty($sorting)?$sorting:false;
        $with_checkall = isset($with_checkall) ? $with_checkall : false;

        if( !empty($sorting) ) {
            $labelFilter = $this->Rumahku->filterEmptyField($sorting, 'labelFilter', false, __('Filter'));
            $optionsStatus = $this->Rumahku->filterEmptyField($sorting, 'optionsStatus');
            $optionsFromWeb = $this->Rumahku->filterEmptyField($sorting, 'optionsFromWeb');
            $fieldStatus = $this->Rumahku->filterEmptyField($sorting, 'fieldStatus', false, 'status');
            $optionsFilter = $this->Rumahku->filterEmptyField($sorting, 'optionsFilter');
            $optionsWrapperWide = $this->Rumahku->filterEmptyField($sorting, 'optionsWrapperWide');
            $options = $this->Rumahku->filterEmptyField($sorting, 'options');
            $url = $this->Rumahku->filterEmptyField($sorting, 'url');
            
            if( !empty($optionsStatus) || !empty($optionsFromWeb)) {
                if( !empty($optionsWrapperWide) ) {
                    $addClass = 'col-sm-7';
                    $addClassFilter = 'col-sm-3';
                    $addClassSort = 'col-sm-3';
                    $addClassInput = 'col-sm-3';
                } else {
                    $addClass = 'col-sm-5';
                    $addClassFilter = 'col-sm-2';
                    $addClassSort = 'col-sm-2';
                    $addClassInput = 'col-sm-5';
                }
            } else {
                $addClassInput = 'col-sm-4';

                if(!$with_checkall){
                    $addClass = 'col-sm-4';
                    $addClassFilter = 'col-sm-5';
                    $addClassSort = 'col-sm-7';
                }else{
                    $addClass = 'col-sm-3';
                    $addClassFilter = 'col-xs-2 col-sm-2';
                    $addClassSort = 'col-xs-10 col-sm-10';
                }
            }
?>
<div class="<?php echo $addClass; ?>">
    <div class="sorting-type sorting-style-1">
        <div class="form-group">
            <div class="row">
                <?php 
                        if(!$with_checkall){
                            echo $this->Html->tag('div', $this->Form->label('sorting', $labelFilter), array(
                                'class' => $addClassFilter,
                            ));
                        }else{
                            echo $this->Html->tag('div', $this->Html->tag('div', $this->Form->input('checkbox_all', array(
                                'type' => 'checkbox',
                                'class' => 'checkAll',
                                'label' => ' ',
                                'div' => array(
                                    'class' => 'cb-checkmark',
                                ),
                            )), array(
                                'class' => 'cb-custom',
                            )), array(
                                'class' => $addClassFilter,
                            ));
                        }

                        if( !empty($optionsStatus) ) {
                            echo $this->Form->input($fieldStatus, array(
                                'label' => false,
                                'class' => 'form-control',
                                'options' => $optionsStatus,
                                'id' => 'status-changed',
                                'empty' => __('- Semua -'),
                                'div' => array(
                                    'class' => 'no-pright '.$addClassInput,
                                ),
                                'onChange' => 'submit();',
                            ));
                        }

                        if(!empty($optionsFromWeb)){
                            echo $this->Form->input('from_web', array(
                                'label' => false,
                                'class' => 'form-control',
                                'options' => $optionsFromWeb,
                                'id' => 'status-changed',
                                'empty' => __('- Semua -'),
                                'div' => array(
                                    'class' => 'no-pright '.$addClassInput,
                                ),
                                'onChange' => 'submit();',
                            ));
                        }

                        if( !empty($optionsFilter) ) {
                            echo $this->Form->input('filter', array(
                                'label' => false,
                                'class' => 'form-control',
                                'options' => $optionsFilter,
                                'id' => 'status-changed',
                                'empty' => __('- Filter -'),
                                'div' => array(
                                    'class' => 'no-pright '.$addClassInput,
                                ),
                                'onChange' => 'submit();',
                            ));
                        }

                        if( !empty($options) ) {
                            echo $this->Form->input('sort', array(
                                'label' => false,
                                'class' => 'form-control',
                                'options' => $options,
                                'onChange' => 'submit();',
                                'empty' => __('- Urutan -'),
                                'div' => array(
                                    'class' => $addClassSort,
                                ),
                            ));
                        }
                ?>
            </div>
        </div>
    </div>
</div>
<?php 
        }
?>