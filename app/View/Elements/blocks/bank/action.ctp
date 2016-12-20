<?php 
        $step = !empty($step)?$step:false;
        $urlBack = !empty($urlBack)?$urlBack:'#';
        $id = !empty($id)?$id:false;

        $dataBank = !empty($dataBank)?$dataBank:false;
        $dataCommission = !empty($dataCommission)?$dataCommission:false;
        $dataProduct = !empty($dataProduct)?$dataProduct:false;
        $dataKpr = !empty($dataKpr)?$dataKpr:false;

        $stepBank = 'Bank';
        $stepProduct = 'Product';
        $stepCommission = 'Commission';
        $stepKpr = 'KPR';

        $full_name = $this->Rumahku->filterEmptyField($User, 'full_name');
?>
<div class="greeting">
    <?php 
            echo $this->Html->tag('h4', sprintf(__('Hai, %s'), $full_name));
            echo $this->Html->tag('p', sprintf(__('Anda berada di halaman Pengaturan Bank. Lengkapi dan ikuti tahapannya pada kolom di bawah ini.')));
    ?>
</div>
<div class="action-group top">
    <?php 
            if( !empty($id) ) {
                $urlBank = array(
                    'controller' => 'bank',
                    'action' => 'edit',
                    $id,
                    'admin' => true,
                );
                $urlProduct = array(
                    'controller' => 'kpr',
                    'action' => 'product',
                    $id,
                    'admin' => true,
                );
                $urlCommission = array(
                    'controller' => 'bank',
                    'action' => 'komisi_agent',
                    $id,
                    'admin' => true,
                );
                $urlKpr = array(
                    'controller' => 'kpr',
                    'action' => 'setting',
                    $id,
                    'admin' => true,
                );
            } else {
                $urlBank = $urlProduct = $urlCommission = $urlKpr = '#';
            }

            $urlStepBank = $this->Rumahku->getUrlStep($urlBank, $stepBank, true, $id);
            $urlStepProduct = $this->Rumahku->getUrlStep($urlProduct, $stepProduct, $dataProduct, $id);
            $urlStepCommission = $this->Rumahku->getUrlStep($urlCommission, $stepCommission, $dataBank, $id);
            $urlStepKpr = $this->Rumahku->getUrlStep($urlKpr, $stepKpr, $dataCommission, $id);
    ?>
    <div class="step floright">
        <div class="step floright">
            <ul>
                <?php 
                        echo $this->Html->tag('li', $this->Html->link(sprintf('%s %s', $this->Html->tag('span', 1, array(
                            'class' => 'step-number',
                            'id' => 'step-1',
                        )), $this->Html->tag('label', __('Info Bank'), array(
                            'for' => '#step-1',
                        ))), $urlBank, array(
                            'escape' => false,
                        )), array(
                            'class' => $this->Rumahku->getActiveStep($step, $stepBank, $dataBank, $id),
                        ));
                        echo $this->Html->tag('li', $this->Html->link(sprintf('%s %s', $this->Html->tag('span', 2, array(
                            'class' => 'step-number',
                            'id' => 'step-2',
                        )), $this->Html->tag('label', __('Setting KPR'), array(
                            'for' => '#step-2',
                        ))), $urlStepKpr, array(
                            'escape' => false,
                        )), array(
                            'class' => $this->Rumahku->getActiveStep($step, $stepKpr, $dataKpr, $id),
                        ));
                        echo $this->Html->tag('li', $this->Html->link(sprintf('%s %s', $this->Html->tag('span', 3, array(
                            'class' => 'step-number',
                            'id' => 'step-3',
                        )), $this->Html->tag('label', __('Produk KPR'), array(
                            'for' => '#step-3',
                        ))), $urlStepProduct, array(
                            'escape' => false,
                        )), array(
                            'class' => $this->Rumahku->getActiveStep($step, $stepProduct, $dataProduct, $id),
                        ));
                        echo $this->Html->tag('li', $this->Html->link(sprintf('%s %s', $this->Html->tag('span', 4, array(
                            'class' => 'step-number',
                            'id' => 'step-4',
                        )), $this->Html->tag('label', __('Setting Provisi'), array(
                            'for' => '#step-4',
                        ))), $urlStepCommission, array(
                            'escape' => false,
                        )), array(
                            'class' => $this->Rumahku->getActiveStep($step, $stepCommission, $dataCommission, $id),
                        ));
                ?>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>