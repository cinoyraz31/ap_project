<div class="row">
    <div class="col-sm-12 pr0 print-split-col borderbottom">
        <div class="col-sm-6 print-split-col">
            <?php

                if(!empty($commission_agent) || !empty($commission_company)){

                    echo $this->Html->tag('h4', __('Informasi Provisi'));
            ?>
            <ul>
            <?php    

                    if($commission_agent){

                        $custom_fee_agent = $this->Rumahku->default_status($paid_fee_agent);

                        $label = $this->Html->tag('div', $this->Html->tag('label', __('Provisi Agen')), array(
                            'class' => 'col-sm-5',
                        ));

                        $value =  $this->Html->tag('div', $this->Html->tag('span', $this->Html->tag('b',$custom_commission_agent)), array(
                            'class' => 'col-sm-3',
                        ));

                        $status = $this->Html->tag('div', $this->Html->tag('span',$custom_fee_agent), array(
                            'class' => 'col-sm-4',
                        ));

                        echo $this->Html->tag('li',$this->Html->div('row',$label.$value.$status));


                        }

                    if($commission_company){

                            $custom_fee_company = $this->Rumahku->default_status($paid_fee_company);

                            $label = $this->Html->tag('div', $this->Html->tag('label', sprintf('%s %s',__('Provisi'),Configure::read('__Site.site_name'))), array(
                                'class' => 'col-sm-5',
                            ));

                            $value =  $this->Html->tag('div', $this->Html->tag('span', $this->Html->tag('b',$custom_commission_company)), array(
                                'class' => 'col-sm-3',
                            ));

                            $status = $this->Html->tag('div', $this->Html->tag('span',$custom_fee_company), array(
                                'class' => 'col-sm-4',
                            ));

                            echo $this->Html->tag('li',$this->Html->div('row',$label.$value.$status));

                    }

                }

            ?>
            </ul>
        </div>
        <div class="col-sm-3 col-sm-offset-3 print-split-col col-right">
            <?php

                    echo $this->Kpr->_callDateStatusAgent( __('Provisi Agen'), $paid_agent_date); 
                    echo $this->Kpr->_callDateStatusAgent( sprintf('%s %s',__('Provisi'),Configure::read('__Site.site_name')), $paid_company_date); 

            ?>
        </div>
    </div>
</div>