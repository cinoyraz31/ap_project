<?php
App::uses('Controller', 'Controller');
App::import('Vendor', 'Facebook',array('file'=>'Facebook'.DS.'facebook.php'));
class AppController extends Controller {

    var $uses = array(
        'User'
    );

	public $components = array(
		'Acl', 'Auth','Session','Cookie', 
		'RequestHandler', 'RmCommon', 'RmUser',
		'Rest.Rest',
	);
	public $helpers = array(
		'Rumahku', 'Paginator'
	);

    function beforeFilter(){
        $debug = Configure::read('debug');
        // $site_url_default = 'http://ww.v3.Common.com';
        // $site_url_company_web = 'http://ww.v2.companyweb.com';

        $_base_url = FULL_BASE_URL;
        if($debug == 0){
            $site_url_default = 'http://www.angkasa-pura.com'; // live
            $_home_url = 'http://www.angkasa-pura.com'; // live
            $site_url_company_web = 'http://www.angkasa-pura.com'; // live
        }else{
            $site_url_default = 'http://www.angkasa-pura.com'; // live
            $_home_url = 'http://www.angkasa-pura.com'; // live
            $site_url_company_web = 'http://www.angkasa-pura.com'; // live
        }
        Configure::write('__Site.site_default', $site_url_default);
        Configure::write('__Site.site_company_web', $site_url_company_web);
        $_site_name = 'Angkasa Pura';
        $_superadmin = 'AngkasaAdmin';
        $_site_email = 'angkasasupport@yopmail.com';
        // $_site_email = 'supportrumahku@yopmail.com';

        Configure::write('__Site._superadmin', $_superadmin);
        Configure::write('__Site.site_name', $_site_name);
        Configure::write('__Site.send_email_from', $_site_email);
        
        // Set Layout - Base on Prefix
        $this->layout = $this->RmCommon->_layout( $this->params );
        $this->is_ajax = $isAjax = $this->RequestHandler->isAjax();

        // Set Variable Global

        // Set Configure Global
        $this->RmCommon->_setConfigVariable();
        // Set Variable Auth
        $this->Auth->authError = __('Mohon maaf, Anda tidak mempunyai hak untuk mengakses konten tersebut. Silahkan login terlebih dahulu.');
        $this->Auth->changeEmail = __('Silahkan cek email Anda untuk melakukan konfirmasi');

        // Token API
        $this->RmCommon->tokenCheck();

        // Set User Log-in
        $User = $this->Auth->user();
        $_global_variable = $this->RmCommon->_set_global_variable($_site_name);
        Configure::write('_global_variable', $_global_variable);

        if( !empty($User) ) {
            Configure::write('User.data', $User);
            
            $logged_in = $this->Auth->loggedIn();   
            $this->user_id = $this->RmCommon->filterEmptyField($User, 'User', 'id');
            $this->group_id = $logged_group = $this->RmCommon->filterEmptyField($User, 'User', 'group_id');

            // Check Is Admin
            if( $this->RmCommon->_isAdmin($logged_group) ) {
                Configure::write('User.admin', true);
            }
            Configure::write('User.id', $this->user_id);
            Configure::write('User.group_id', $logged_group);
            
        }

        // unset($this->request->data['change_bank']);
        $kpr_notifications = $this->User->Notification->getNotif();
        $this->set(compact(
            'User', 'logged_group', 'logged_in', 'value_bank',
            '_global_variable', '_site_name',
            '_site_email', 'isAjax', 'dataBank', 'kpr_notifications'
        ));
    }

    function isAuthorized($user) {
        if( FULL_BASE_URL == 'http://ww.angkasa-pura.com' ) {
            return false;
        } else {
            return true;
        }
    }
}