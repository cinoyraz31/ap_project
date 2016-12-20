<?php 
        $property_path = Configure::read('__Site.property_photo_folder');
?>
<div id="carousel-gallery" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <?php 
            if( !empty($medias) ) {
    ?>
    <ol class="carousel-indicators">
        <?php
                foreach ($medias as $key => $media) {
                    if( empty($key) ) {
                        $addClass = 'active';
                    } else {
                        $addClass = '';
                    }

                    echo $this->Html->tag('li', '', array(
                        'class' => $addClass,
                        'data-target' => '#carousel-gallery',
                        'data-slide-to' => $key,
                    ));
                }
        ?>
    </ol>
    <?php
            }
    ?>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php 
                if( !empty($medias) ) {
                    foreach ($medias as $key => $media) {
                        $mediaPhoto = $this->Rumahku->filterEmptyField($media, 'PropertyMedias', 'name');
                        $mediaTitle = $this->Rumahku->filterEmptyField($media, 'PropertyMedias', 'title');

                        $mediaPhoto = $this->Rumahku->photo_thumbnail(array(
                            'save_path' => $property_path, 
                            'src' => $mediaPhoto, 
                            'size' => 'l',
                        ), array(
                            'title'=> $mediaTitle, 
                            'alt'=> $mediaTitle, 
                            'class' => 'default-thumbnail',
                        ));

                        $content = $mediaPhoto;

                        if( empty($key) ) {
                            $addClass = 'active';
                        } else {
                            $addClass = '';
                        }

                        if( !empty($mediaTitle) ) {
                            $content = $this->Html->tag('div', $mediaTitle, array(
                                'class' => 'carousel-caption',
                            ));
                        }

                        echo $this->Html->tag('div', $content, array(
                            'class' => 'item '.$addClass,
                        ));
                    }
                }
        ?>
    </div>
    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-gallery" role="button" data-slide="prev">
        <?php 
                echo $this->Rumahku->icon('rv4-angle-left', false, 'i', ' arrow-left');
                echo $this->Html->tag('span', __('Previous'), array(
                    'class' => 'sr-only',
                ));
        ?>
    </a>
    <a class="right carousel-control" href="#carousel-gallery" role="button" data-slide="next">
        <?php 
                echo $this->Rumahku->icon('rv4-angle-right', false, 'i', ' arrow-right');
                echo $this->Html->tag('span', __('Next'), array(
                    'class' => 'sr-only',
                ));
        ?>
    </a>
</div>