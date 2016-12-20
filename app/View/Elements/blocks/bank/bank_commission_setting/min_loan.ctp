<?php
        echo $this->Html->tag('div', $this->Html->tag('div', $this->Rumahku->buildButton($buttonAdd, 'col-sm-offset-3 col-xl-offset-2 col-sm-1 pull-center btn-add-full', 'btn green'), array(
            'class' => 'row',
        )), array(
            'class' => 'form-group',
        ));
?>
<div class="row">
    <div class="col-sm-offset-3 col-xl-offset-2 col-sm-9 col-xl-10">
        <div id="addMinLoan">
            <?php
                    if(!empty($data['BankCommissionSettingLoan'])){
                        $bankCommissionSettingLoan = $this->Rumahku->filterEmptyField($data,'BankCommissionSettingLoan');

                        foreach($bankCommissionSettingLoan AS $key => $value){
                            echo $this->Rumahku->buildInputForm('BankCommissionSettingLoan.'.$key.'.id', array_merge($options, array(
                                'type' => 'hidden',
                            ))); 

                            $label =  $this->Html->tag('h2', sprintf('%s %s',__('Min. Pinjaman'),($key+1)), array(
                                'class' => 'sub-heading label-slide-content'
                            )); 
                            $btn_delete = $this->Html->link(sprintf('%s Hapus', $this->Rumahku->icon('fa fa-times')),'javascript:',array(
                                'class' => 'offset6 delete-custom-loan btn red',
                                'action_type' => 'extend-content-loan',
                                'rel' => $key,
                                'escape' => false
                                ));

                            $fieldContent = $this->Html->tag('div', $this->Html->tag('div', $label, array(
                                'class' => 'col-xl-3 col-sm-4',
                            )).$this->Html->tag('div', $btn_delete, array(
                                'class' => 'col-sm-1 text-left no-pd',
                                'style' => 'margin-top:10px;',
                            )), array(
                                'class' => 'form-group',
                            ));

                            $fieldContent .= $this->Rumahku->buildInputForm('BankCommissionSettingLoan.'.$key.'.min_loan', array_merge($options, array(
                                'type' => 'text',
                                'label' => __('Min. Pinjaman *'),
                                'inputClass' => 'input_price',
                            )));    

                            $fieldContent .= $this->Rumahku->buildInputForm('BankCommissionSettingLoan.'.$key.'.rate', array_merge($options, array(
                                'type' => 'text',
                                'label' => __('Provisi Agen *'),
                                'textGroup' => __('%'),
                                'class' => 'relative col-xl-2 col-sm-4',
                                'id' => 'PersenLoan',
                            ))); 

                            $fieldContent .= $this->Rumahku->buildInputForm('BankCommissionSettingLoan.'.$key.'.rate_company', array_merge($options, array(
                                'type' => 'text',
                                'label' => __('Provisi Rumahku *'),
                                'textGroup' => __('%'),
                                'class' => 'relative col-xl-2 col-sm-4',
                                'id' => 'PersenLoan',
                            ))); 

                            echo $this->Html->tag('div', $fieldContent, array(
                                'class' => 'field-content',
                                'id' => sprintf('idx-extend-%s', $key),
                            ));

                        }
                    }
            ?>
        </div>
    </div>
</div>