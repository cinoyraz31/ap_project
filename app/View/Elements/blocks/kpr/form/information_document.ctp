<?php
    $data = !empty($data)?$data:false;
    $idx = isset($idx)?$idx:false;
    $modelName = !empty($modelName)?$modelName:'KprApplication';
    echo $this->Html->tag('h2', __('Unggah Dokumen'), array(
        'class' => 'sub-heading'
    ));


    if(!empty($documents)){
        foreach ($documents as $key => $document_category) {
            $category_id = $this->Rumahku->filterEmptyField($document_category, 'DocumentCategory', 'id');

            $data_document = $this->Rumahku->filterEmptyField( $data, 'Document');
            $document_temp = !empty($data_document[$category_id])?$data_document[$category_id]:false;

            $name = $this->Rumahku->filterEmptyField($document_category, 'DocumentCategory', 'name');
            $slug = $this->Rumahku->filterEmptyField($document_category, 'DocumentCategory', 'slug');
            
            $document = $this->Rumahku->filterEmptyField($document_category, 'Document');
            
            $file = $this->Rumahku->filterEmptyField($document, 'file');
            $file_hide = $this->Rumahku->filterEmptyField($document_temp, 'file_hide', false, $file);

            $save_path = $this->Rumahku->filterEmptyField($document, 'save_path');
            $save_path = $this->Rumahku->filterEmptyField($document_temp, 'file_save_path', false, $save_path);
            echo $this->Form->hidden($modelName.'.'.$idx.'.Document.'.$category_id.'.document_category_id', array(
                'value' => $category_id,
            ));
            echo $this->Rumahku->buildInputForm($modelName.'.'.$idx.'.Document.'.$category_id.'.file', array_merge($options, array(
                'type' => 'file',
                'label' => $name,
                'preview' => array(
                    'photo' => $file_hide,
                    'save_path' => $save_path,
                    'size' => 's',
                ),
            )));
            
        }
    }
?>
