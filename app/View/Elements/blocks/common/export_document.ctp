<?php
        $urlPrint = $this->here.'/export:excel';
        echo $this->Html->link($this->Rumahku->icon('rv4-doc'), $urlPrint, array(
            'escape' => false,
            'title' => __('Unduh .XLS'),
        ));
        echo $this->Html->link($this->Rumahku->icon('rv4-print'), 'javascript:window.print();', array(
            'escape' => false,
            'title' => __('Cetak'),
        ));
?>