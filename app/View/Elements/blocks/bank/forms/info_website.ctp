<?php 
        $data = $this->request->data;
        $options = !empty($options)?$options:array();
        
        $general_path = Configure::read('__Site.general_folder');
        $favicon = $this->Rumahku->filterEmptyField($data, 'Bank', 'favicon_hide');
?>
<div class="form-group plus">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <?php 
                    echo $this->Html->tag('h4', __('Informasi Website'));
            ?>
        </div>
    </div>
</div>
<?php
        echo $this->Rumahku->buildInputForm('sub_domain', array_merge($options, array(
            'label' => __('Sub Domain'),
            'placeholder' => __('Http://subdomain.domain.com')
        )));
        echo $this->Rumahku->buildInputForm('favicon', array_merge($options, array(
            'type' => 'file',
            'label' => __('Favicon ( 16x16 )'),
            'preview' => array(
                'photo' => $favicon,
                'save_path' => $general_path,
                'size' => 's',
            ),
        )));
?>