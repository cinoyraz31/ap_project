<?php
        $_soldStatus = !empty($_soldStatus)?$_soldStatus:false;
        $kpr_bank_id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
        $id = $this->Rumahku->filterEmptyField($value, 'Property', 'id');
        $mls_id = $this->Rumahku->filterEmptyField($value, 'Property', 'mls_id');
        $photo = $this->Rumahku->filterEmptyField($value, 'Property', 'photo');
        $title = $this->Rumahku->filterEmptyField($value, 'Property', 'title');
        $change_date = $this->Rumahku->filterEmptyField($value, 'Property', 'change_date');
        $created = $this->Rumahku->filterEmptyField($value, 'Property', 'created');
        $sold = $this->Rumahku->filterEmptyField($value, 'Property', 'sold');
        $in_update = $this->Rumahku->filterEmptyField($value, 'Property', 'in_update');

        $url = array(
            'controller' => 'properties',
            'action' => 'detail',
            $mls_id,
            'application' => $id,
            'id' => $kpr_bank_id,
            'admin' => true,
        );

        $photoProperty = $this->Rumahku->photo_thumbnail(array(
            'save_path' => Configure::read('__Site.property_photo_folder'), 
            'src'=> $photo, 
            'size' => 'm',
        ), array(
            'alt' => $title,
            'title' => $title,
            'class' => 'default-thumbnail',
        ));

    	$btnSlide = $this->Html->link( $this->Rumahku->icon('rv4-angle-down'), '#', array(
            'escape' => false,
            'class' => 'toggle-display floright',
            'data-display' => "#detail-project-properti",
            'data-type' => 'slide',
            'data-arrow' => 'true',
        ));

        $label = $this->Html->tag('label', __('Informasi Properti'));
        echo $this->Html->tag('div', $label.$btnSlide, array(
    	    'class' => 'info-title hidden-print',
    	    'id' => 'buyer-information',
    	));
?>
<div id="detail-project-properti" class="hidden-print" style="display:none">
    <div id="list-property" class="tab-content">
        <div class="item row">
            <div class="col-sm-4">
                <?php

                    echo $this->Html->link($photoProperty, $url, array(
                        'escape' => false,
                    ));
                ?>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                            echo $this->element('blocks/kpr/apply_detail/sub_detail/detail_properti_col1', array(
                                'url' => $url,
                                'title' => $title,
                                'mls_id' => $mls_id,
                            ));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                            echo $this->element('blocks/kpr/apply_detail/sub_detail/detail_properti_col2', array(
                                '_soldStatus' => $_soldStatus,
                            ));
                        ?>
                    </div>
                </div>
                <?php
                    
                ?>
            </div>
        </div>
    </div>
</div>