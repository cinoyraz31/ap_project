<?php 
        $data = $this->request->data;
        $modelName = !empty($modelName)?$modelName:false;
        $idx = !empty($idx)?$idx:0;
        $year = $this->Rumahku->_callPeriodeYear(20);

        $interest_rates = $this->Rumahku->filterEmptyField($data, $modelName, 'interest_rate');
        $periodes = $this->Rumahku->filterEmptyField($data, $modelName, 'periode');

        $interest_rate = !empty($interest_rates[$idx])?$interest_rates[$idx]:false;
        $periode = !empty($periodes[$idx])?$periodes[$idx]:false;

        $options = array(
            'rows' => false,
            'frameClass' => 'col-sm-12',
            'labelClass' => false,
            'class' => 'relative col-sm-12',
        );

        if( empty($idx) ) {
            $addClass = 'field-copy';
        } else {
            $addClass = '';
        }
?>
<li class="<?php echo $addClass; ?>">
    <?php 
            $inputContent = $this->Html->tag('div', $this->Rumahku->buildInputForm($modelName.'.interest_rate.', array_merge($options, array(
                'type' => 'text',
                'label' => false,
                'class' => 'relative col-sm-11',
                'textGroup' => __('%'),
                'placeholder' => __('Masukan bunga fix Anda'),
                'fieldError' => sprintf('%s.%s.interest_rate', $modelName, $idx),
                'attributes' => array(
                    'value' => $interest_rate,
                ),
            ))), array(
                'class' => 'col-sm-6',
            ));
            $inputContent .= $this->Html->tag('div', $this->Rumahku->buildInputForm($modelName.'.periode.', array_merge($options, array(
                'type' => 'select',
                'label' => false,
                'empty' => __('Periode suku bunga fix'),
                'fieldError' => sprintf('%s.%s.periode', $modelName, $idx),
                'options' => $year,
                'attributes' => array(
                    'value' => $periode,
                ),
            ))), array(
                'class' => 'col-sm-6',
            ));

            echo $this->Html->tag('div', $inputContent, array(
                'class' => 'row',
            ));

            echo $this->Html->tag('span', $this->Html->link($this->Rumahku->icon('rv4-cross'), '#', array(
                'escape' => false,
            )), array(
                'class' => 'removed',
            ));
    ?>
</li>