<?php
        $save_path = Configure::read('__Site.document_folder');
        $data = $this->request->data;
		echo $this->Form->create('Coa', array(
            'type' => 'file',
    	));

        // Set Build Input Form
        $options = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-3 taright',
            'class' => 'relative  col-sm-6 col-xl-6',
        );

		echo $this->Html->tag('h2', __('Informasi Coa'), array(
            'class' => 'sub-heading'
        ));
?>
<div class="row">
    <div class="col-sm-12">
    	<?php

                if( !empty($manualUploadPhoto) ) {
                    $photo = $this->Rumahku->filterEmptyField($data, 'Coa', 'photo_hide');
                    echo $this->Rumahku->buildInputForm('photo', array_merge($options, array(
                        'type' => 'file',
                        'label' => __('Foto *'),
                        'preview' => array(
                            'photo' => $photo,
                            'save_path' => $save_path,
                            'size' => 'm',
                        ),
                    )));
                }

    			echo $this->Rumahku->buildInputForm('Coa.type', array_merge($options, array(
                    'label' => __('Tipe *'),
                )));

                echo $this->Rumahku->buildInputForm('Coa.name', array_merge($options, array(
                    'label' => __('Nama *'),
                )));

                echo $this->element('blocks/common/forms/action_custom', array(
                    '_with_submit' => true,
                    '_button_text' => __('Simpan'),
                    '_textBack' => __('Kembali'),
                    '_button_class' => 'floright',
                    '_urlBack' => array(
                        'controller' => 'coas',
                        'action' => 'lists',
                        'admin' => true,
                    ),
                ));
    	?>
    </div>
</div>
<?php
    	echo $this->Form->end(); 
?>