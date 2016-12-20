<?php
    $class = isset($modalClass) ? $modalClass : '';
?>

<div class="modal-header <?php echo $class; ?>">
    <a href="" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">
            <i class="rv4-bold-cross"></i>
        </span>
    </a>
    <?php
            if( isset($modalTitle) ) {
                echo $this->Html->tag('h4', $modalTitle, array(
                    'id' => 'openModalLabel',
                    'class' => 'modal-title',
                ));
            }
    ?>
</div>
    <?php
            if( isset($submodalTitle) ) {
                echo $this->Html->tag('div', $this->Html->tag('p', $submodalTitle), array(
                    'class' => 'modal-subheader '.$class
                ));
            }
    ?>