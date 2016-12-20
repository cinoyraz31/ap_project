<?php   
        $document_status = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'document_status');
        $from_web = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'from_web');
        $gender_options = $this->Rumahku->filterEmptyField( $_global_variable, 'gender_options');
        $application = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprApplication');
        $application_particular = $this->Rumahku->filterEmptyField($value, 'KprBank', 'KprApplicationParticular');
        $application_status = $this->Rumahku->filterEmptyField($value, 'KprBank', 'application_status');
        $currency = $this->Rumahku->filterEmptyField($value, 'Currency', 'symbol');

        if( $application_status == 'completed' && (in_array($document_status, array('approved_proposal', 'proposal_without_comiission')))|| $from_web == 'rumahku' ){
            $display = 'block';
            $icon = $this->Rumahku->icon('rv4-angle-up');
        }else{
            $display = 'none';
            $icon = $this->Rumahku->icon('rv4-angle-down');
        }

        $btnSlide = $this->Html->link( $icon, '#', array(
            'escape' => false,
            'class' => 'toggle-display floright hidden-print',
            'data-display' => "#detail-project-info",
            'data-type' => 'slide',
            'data-arrow' => 'true',
        ));

        $label = $this->Html->tag('label', __('Informasi Pembeli'));

        echo $this->Html->tag('div', $label.$btnSlide, array(
            'class' => 'info-title',
            'id' => 'buyer-information',
        ));
?>
<div id="detail-project-info" class="tab-content visible-print" style="display:<?php echo $display; ?>;">
    <div class="row">
        <div class="col-sm-12">
            <?php
                    echo $this->element('blocks/kpr/apply_detail/sub_detail/category_kpr');

            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php
                    $full_name = $this->Rumahku->filterEmptyField($application, 'full_name');
                    $gender_id = $this->Rumahku->filterEmptyField($application, 'gender_id');
                    $gender = !empty($gender_options[$gender_id])?$gender_options[$gender_id]:'-';
                    $no_ktp = $this->Rumahku->filterEmptyField($application, 'ktp');
                    $birthday = $this->Rumahku->filterEmptyField($application, 'birthday');
                    $birthplace = $this->Rumahku->filterEmptyField($application, 'birthplace');
                    $phone = $this->Rumahku->filterEmptyField($application, 'phone');
                    $no_hp = $this->Rumahku->filterEmptyField($application, 'no_hp');
                    $email = $this->Rumahku->filterEmptyField($application, 'email');
                    $status_marital = $this->Rumahku->filterEmptyField($application, 'status_marital');
                    echo $this->element('blocks/kpr/apply_detail/sub_detail/biodata_pemohon', array(
                        'full_name' => $full_name,
                        'gender' => $gender,
                        'no_ktp' => $no_ktp,
                        'birthday' => $birthday,
                        'birthplace' => $birthplace,
                        'phone' => $phone,
                        'no_hp' => $no_hp,
                        'email' => $email,
                        'status_marital' => $status_marital,
                    ));
                    $company = $this->Rumahku->filterEmptyField($application, 'company');
                    $job_type = $this->Rumahku->filterEmptyField($application, 'JobType', 'name');

                    if(!empty($company) && !empty($job_type)){
                        echo $this->element('blocks/kpr/apply_detail/sub_detail/info_profesi', array(
                            'company' => $company,
                            'job_type' => $job_type,
                        ));
                    }
                    
            ?>
        </div>
        <div class="col-sm-6">
            <?php
                    $address = $this->Rumahku->filterEmptyField($application, 'address');
                    $address_2 = $this->Rumahku->filterEmptyField($application, 'address_2');
                    $same_as_address_ktp = $this->Rumahku->filterEmptyField($application, 'same_as_address_ktp');
                    $rt = $this->Rumahku->filterEmptyField($application, 'rt');
                    $rw = $this->Rumahku->filterEmptyField($application, 'rw');
                    $region = $this->Rumahku->filterEmptyField($application, 'Region', 'name');
                    $city = $this->Rumahku->filterEmptyField($application, 'City', 'name');
                    $subarea = $this->Rumahku->filterEmptyField($application, 'Subarea', 'name');
                    $zip = $this->Rumahku->filterEmptyField($application, 'zip');

                    $location = $this->Rumahku->getGenerateAddress($address, array(
                        'rt' => $rt,
                        'rw' => $rw,
                        'region' => $region,
                        'city' => $city,
                        'subarea' => $subarea,
                        'zip' => $zip,
                    ), ', ', '-');

                    echo $this->element('blocks/kpr/apply_detail/sub_detail/biodata_address', array(
                        'address' => $address,
                        'address_2' => $address_2,
                        'location' => $location,
                        'same_as_address_ktp' => $same_as_address_ktp
                    )); 

                    $id = $this->Rumahku->filterEmptyField($application, 'id');
                    $income = $this->Rumahku->filterEmptyField($application, 'income');
                    $household_fee = $this->Rumahku->filterEmptyField($application, 'household_fee');
                    $other_installment = $this->Rumahku->filterEmptyField($application, 'other_installment');
                    $customIncome = $this->Rumahku->getCurrencyPrice($income, false, $currency);
                    $customHouseholdFee = $this->Rumahku->getCurrencyPrice($household_fee, false, $currency);
                    $customOtherInstallment = $this->Rumahku->getCurrencyPrice($other_installment, false, $currency);

                    if(in_array(true, array( $income, $household_fee, $other_installment))){
                        echo $this->element('blocks/kpr/apply_detail/sub_detail/informasi_financial', array(
                            'id' =>$id,
                            'customIncome' => $customIncome,
                            'customHouseholdFee' => $customHouseholdFee,
                            'customOtherInstallment' => $customOtherInstallment,
                        ));  
                    }
                     
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
                    $document = $this->Rumahku->filterEmptyField($application, 'Document');
                    if(!empty($document)){
                        echo $this->element('blocks/kpr/apply_detail/sub_detail/document_detail', array(
                            'document' => $document,
                        )); 
                    }
            ?>
        </div>
    </div>
    <?php
            if(!empty($application_particular)){
                echo $this->element('blocks/kpr/apply_detail/detail_spouse_particular', array(
                    'application_particular' => $application_particular,
                ));
            }
    ?>
</div>