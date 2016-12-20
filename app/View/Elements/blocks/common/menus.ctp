<?php 
        
        $type = !empty($type)?$type:false;
        $active_menu = !empty($active_menu)?$active_menu:false;
        $contentDashboard = $this->Rumahku->link(__('Dashboard'), array(
            'controller' => 'users',
            'action' => 'account',
            'admin' => true,
        ), array(
            'data-active' => $active_menu,
            'data-icon' => 'rv4-dashboard',
        ));

        //Master
        $labelMenuMaster = __('Master');
        if(in_array($logged_group, array('20'))){
            $contentLiMaster = $this->Html->tag('li', $this->Rumahku->link(__('Daftar User'), array(
                'controller' => 'users',
                'action' => 'lists',
                'admin' => true,
            )));

            $contentLiMaster .= $this->Html->tag('li', $this->Rumahku->link(__('Daftar COA'), array(
                'controller' => 'coas',
                'action' => 'lists',
                'admin' => true,
            )));

            $contentLiMaster .= $this->Html->tag('li', $this->Rumahku->link(__('Daftar Unit'), array(
                'controller' => 'units',
                'action' => 'lists',
                'admin' => true,
            )));

            $contentLiMaster .= $this->Html->tag('li', $this->Rumahku->link(__('Daftar Sub Unit'), array(
                'controller' => 'units',
                'action' => 'sub_lists',
                'admin' => true,
            )));
        }

        // Profile
        $labelMenuProfile = __('Profil');
        $contentLiProfile = $this->Html->tag('li', $this->Rumahku->link(__('Informasi Dasar'), array(
            'controller' => 'users',
            'action' => 'edit',
            'admin' => true,
        )));
        $contentLiProfile .= $this->Html->tag('li', $this->Rumahku->link(__('Ganti Password'), array(
            'controller' => 'users',
            'action' => 'admin_change_password',
            'admin' => true,
        )));
        $contentLiProfile .= $this->Html->tag('li', $this->Rumahku->link(__('Ganti Email'), array(
            'controller' => 'users',
            'action' => 'admin_change_email',
            'admin' => true,
        )));

        // Logout User
        $contentLogout = $this->Rumahku->link(__('Logout'), array(
            'controller' => 'users',
            'action' => 'logout',
            'admin' => true,
        ), array(
            'data-active' => $active_menu,
            'data-icon' => 'rv4-lock',
        ));

        switch ($type) {
            case 'header':
                echo $this->Html->tag('li', $this->Html->tag('div', $contentDashboard, array(
                    'class' => 'btn-group',
                )));

                // Master
                if($contentLiMaster){
                    echo $this->Rumahku->_generateMenuTop($labelMenuMaster, 'rv4-burger', true, $contentLiMaster); 
                }
                
                // Profile
                echo $this->Rumahku->_generateMenuTop($labelMenuProfile, 'rv4-user', true, $contentLiProfile);


                // Logout
                echo $this->Html->tag('li', $contentLogout);

                break;
            
            default:
                echo $this->Html->tag('li', $contentDashboard);

                // Master
                if($contentLiMaster){
                    echo $this->Rumahku->_generateMenuSide($labelMenuMaster, 'rv4-burger', false, $active_menu, 'master', $contentLiMaster);
                }

                // Profile
                echo $this->Rumahku->_generateMenuSide($labelMenuProfile, 'rv4-user', false, $active_menu, 'profile', $contentLiProfile);


                // Logout
                echo $this->Html->tag('li', $contentLogout);

                break;
        }
?>