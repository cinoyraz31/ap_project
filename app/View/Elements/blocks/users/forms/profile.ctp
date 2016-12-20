<?php 
        $data = $this->request->data;
        $save_path = Configure::read('__Site.profile_photo_folder');

        $genderOptions = !empty($_global_variable['gender_options'])?$_global_variable['gender_options']:false;
        $photoSize = $this->Rumahku->_rulesDimensionImage($save_path, 'large', 'size');

        $_add = isset($_add)?$_add:false;

        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative col-sm-6 col-xl-6',
        );

        if( !empty($manualUploadPhoto) ) {
            $photo = $this->Rumahku->filterEmptyField($data, 'User', 'photo_hide');
            echo $this->Rumahku->buildInputForm('photo', array_merge($options, array(
                'type' => 'file',
                'label' => sprintf(__('Foto Profil (%s) *'), $photoSize),
                'preview' => array(
                    'photo' => $photo,
                    'save_path' => $save_path,
                    'size' => 'pm',
                ),
            )));
        }

        if( !empty($division)) {
            echo $this->element('blocks/users/forms/bank', array(
                'options' => $options,
            ));
        }

        if( !empty($_add) ) {
            echo $this->element('blocks/users/forms/add', array(
                'options' => $options,
            ));
        }

        echo $this->Rumahku->buildInputForm('full_name', array_merge($options, array(
            'label' => __('Nama Lengkap *'),
        )));
?>

<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="relative col-xl-2 col-sm-3 taright">
                <?php 
                        echo $this->Form->label('birthday', __('Tanggal Lahir'), array(
                            'class' => 'control-label',
                        ));
                ?>
                </div>
                <div class="relative col-sm-6 col-xl-6">
                    <div class="row">
                    <?php
                            echo $this->Rumahku->setFormBirthdate();
                    ?>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>
    
<?php
        echo $this->Rumahku->buildInputRadio('gender_id', $genderOptions, array(
            'label' => __('Jenis Kelamin'),
            'labelClass' => 'relative col-xl-2 col-sm-3 taright',
            'frameClass' => 'col-sm-12',
        ));
?>
