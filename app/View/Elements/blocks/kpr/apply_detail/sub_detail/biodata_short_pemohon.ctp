<?php
        $_isAdmin = Configure::read('User.admin');
        $_global_variable = !empty($_global_variable) ? $_global_variable : false;
        
        $application_status = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'application_status');
        $application_snyc = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'application_snyc');
        $application = $this->Rumahku->filterEmptyField( $value, 'KprBank');

        $full_name = $this->Rumahku->filterEmptyField( $application, 'client_name');
        $no_hp = $this->Rumahku->filterEmptyField( $application, 'client_hp');
        $email = $this->Rumahku->filterEmptyField( $application, 'client_email');
        $job_type = $this->Rumahku->filterEmptyField( $value, 'JobType', 'name');
        $address = $this->Rumahku->filterEmptyField( $application, 'address');
        $ktp = $this->Rumahku->filterEmptyField( $application, 'ktp');
        $birthplace = $this->Rumahku->filterEmptyField( $application, 'birthplace');
        $birthday = $this->Rumahku->filterEmptyField( $application, 'birthday');
        $gender_id = $this->Rumahku->filterEmptyField( $application, 'gender_id');
        $status_marital = $this->Rumahku->filterEmptyField( $application, 'status_marital');

        $region = $this->Rumahku->filterEmptyField( $value, 'Region', 'name');
        $city = $this->Rumahku->filterEmptyField( $value, 'City', 'name');
        $subarea = $this->Rumahku->filterEmptyField( $value, 'Subarea', 'name');
        $zip = $this->Rumahku->filterEmptyField( $application, 'zip');

        $customBirthday = $this->Rumahku->formatDate($birthday, 'd M Y');
        $customGender = $this->Rumahku->filterEmptyField($_global_variable, 'gender_options', $gender_id);
        $customMarital = $this->Rumahku->filterEmptyField($_global_variable, 'status_marital', $status_marital);

        $address = $this->Rumahku->getGenerateAddress($address, array(
            'region' => $region,
            'city' => $city,
            'subarea' => $subarea,
            'zip' => $zip,
        ), ', ', '-');

        if(!empty($full_name)){
            echo $this->Kpr->generateViewDetail(__('Nama'), $full_name, 'ml0');
        }
        if(!empty($email)){
            echo $this->Kpr->generateViewDetail(__('Email'), $email, 'ml0');
        }

        if(!empty($no_hp)){
            echo $this->Kpr->generateViewDetail(__('No Handphone'), $no_hp, 'ml0');
        }

        if(!empty($gender_id)){
            echo $this->Kpr->generateViewDetail(__('Jenis Kelamin'), $customGender, 'ml0');
        }

        if(!empty($status_marital)){
            echo $this->Kpr->generateViewDetail(__('Status Menikah'), $customMarital, 'ml0');
        }

        if(!empty($ktp)){
            echo $this->Kpr->generateViewDetail(__('No. KTP'), $ktp, 'ml0');
        }

        if(!empty($birthplace) && !empty($birthday)){
            echo $this->Kpr->generateViewDetail(__('Tempat / Tanggal lahir'), sprintf('%s / %s', $birthplace, $customBirthday), 'ml0');
        }

        if(!empty($job_type)){
            echo $this->Kpr->generateViewDetail(__('Jenis Pekerjaan'), $job_type, 'ml0');
        }

        if(!empty($address)){
            echo $this->Kpr->generateViewDetail(__('Alamat'), $address, 'ml0');
        }
?>