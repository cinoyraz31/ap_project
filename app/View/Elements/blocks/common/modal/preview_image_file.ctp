<?php
    $id = isset( $modal_id ) ? $modal_id : 'previewImageModal';
?>

<div id="<?php echo $id; ?>" class="modal fade previewImageModal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id; ?>Label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
                    echo $this->element('blocks/common/modal/header', array(
                        'modalTitle' => 'Preview',
                        // 'submodalTitle' => 'Berikan alasan mengapa pengajuan KPR ditolak.',
                    ));
            ?>
            <div class="modal-body">
                <?php
                        echo $this->Rumahku->photo_thumbnail(array(
                            'save_path' => $save_path, 
                            'src' => $source, 
                            'size' => $photoSize,
                        ));
                ?>
            </div>
            <div class="modal-footer">
                <?php
                        echo $this->Form->button(__('Tutup'), array(
                            'class' => 'btn default',
                            'data-dismiss' => 'modal',
                        ));
                ?>
            </div>
        </div>
    </div>
</div>