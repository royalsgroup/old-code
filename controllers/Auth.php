<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ***************Auth.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Auth
 * @description     : This class used to handle user authentication functionality 
 *                    of the application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Auth extends CI_Controller {

    public $data = array();
    public $global_setting = array();
    public function __construct() {

        parent::__construct();
        $this->load->model('Auth_Model', 'auth', true);
        $this->global_setting = $this->db->get_where('global_setting', array('status'=>1))->row();
       
	    if(!empty($this->global_setting) && $this->global_setting->language){             
            $this->lang->load($this->global_setting->language);             
        }else{
           $this->lang->load('english');
        }
    }

    /****************Function login**********************************
     * @type            : Function
     * @function name   : login
     * @description     : Authenticatte when uset try lo login. 
     *                    if autheticated redirected to logged in user dashboard.
     *                    Also set some session date for logged in user.   
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function login() {

        if ($_POST) {
         
            $data['username'] = $this->input->post('username');           
            $data['password'] = md5($this->input->post('password'));

            $login = $this->auth->get_single('users', $data);
            if (!empty($login)) {
              
                // check user active status
                if (!$login->status) {
                    $this->session->set_flashdata('error', $this->lang->line('user_active_status'));
                    redirect('login');
                }
                if($login->role_id == TEACHER || $login->role_id == STAFF){ 
                    $teacher = get_user_by_role(TEACHER, $login->id);  
                    if ($teacher->alumni ==1)
                    {
                        $this->session->set_flashdata('error', $this->lang->line('invalid_login'));
                        redirect('login');
                    }
                }
                if($login->role_id != SUPER_ADMIN && $login->role_id != TEACHER && $login->role_id != STUDENT && $login->role_id != GUARDIAN ){ 
                    $staff = get_user_by_role($login->role_id, $login->id);  
                    if ($staff->alumni ==1)
                    {
                        $this->session->set_flashdata('error', $this->lang->line('invalid_login'));
                        redirect('login');
                    }
                }
                if($login->role_id == STUDENT ){ 
                    $student = get_user_by_role(STUDENT, $login->id);  
                    if ($student->status_type != 'regular')
                    {
                        $this->session->set_flashdata('error', $this->lang->line('invalid_login'));
                        redirect('login');
                    }
                }
                // check is setting role permission by admin
                $privileges = $this->auth->get_list('privileges', array('role_id' => $login->role_id));
                if (empty($privileges)) {
                    $this->session->set_flashdata('error', $this->lang->line('privilege_not_setting'));
                    redirect('login');
                }

                // User table data
                $this->session->set_userdata('id', $login->id);
                $this->session->set_userdata('role_id', $login->role_id);
                $this->session->set_userdata('username', $login->username);
                $this->session->set_userdata('school_id', $login->school_id);
                
                // check if user is in district admin table
				$dadmin = $this->auth->get_single('district_admin', array('user_id'=>$login->id));
				if(!empty($dadmin)){
					$this->session->set_userdata('dadmin','1');
					/*$this->session->set_userdata('dadmin_state_id',$dadmin->state_id);
					$this->session->set_userdata('dadmin_zone_id',$dadmin->zone_id);
					$this->session->set_userdata('dadmin_subzone_id',$dadmin->subzone_id);
					$this->session->set_userdata('dadmin_district_id',$dadmin->district_id);
					$this->session->set_userdata('dadmin_block_id',$dadmin->block_id);
					$this->session->set_userdata('dadmin_sankul_id',$dadmin->sankul_id);*/					
					
					$this->db->select('S.*');
					$this->db->from('schools AS S');			
					$this->db->where('S.state_id', $dadmin->state_id);
					if($dadmin->zone_id >0){
                        $this->session->set_userdata('zone_id',$dadmin->zone_id);
					$this->db->where('S.zone_id', $dadmin->zone_id);
					}
					if($dadmin->subzone_id >0){
                    $this->session->set_userdata('subzone_id',$dadmin->subzone_id);
					$this->db->where('S.subzone_id', $dadmin->subzone_id);
					}
					if($dadmin->district_id >0){
                    $this->session->set_userdata('district_id',$dadmin->district_id);
					$this->db->where('S.district_id', $dadmin->district_id);
					}
					if($dadmin->block_id >0){
                        $this->session->set_userdata('block_id',$dadmin->block_id);
					$this->db->where('S.block_id', $dadmin->block_id);
					}if($dadmin->sankul_id >0){
                        $this->session->set_userdata('sankul_id',$dadmin->sankul_id);
					$this->db->where('S.sankul_id', $dadmin->sankul_id);
					}
				 
					$schools = $this->db->get()->result();
                    
					$school_ids=array();
					foreach($schools as $s){
						$school_ids[]=$s->id;
					}
					
					$this->session->set_userdata('dadmin_school_ids',$school_ids);
					//print_r($this->session->userdata('dadmin_school_ids')); exit;
				}
                if ($login->role_id == SUPER_ADMIN) {
                   $profile = $this->auth->get_single('system_admin', array('user_id' => $login->id));
                }
				else if ($login->role_id == DISTRICT_ADMIN) {
					$districtAdmin=$this->auth->get_single('district_admin', array('user_id' => $login->id));
					$district_id=$districtAdmin->district_id;
					$this->session->set_userdata('district_id', $district_id);					
				}
				elseif ($login->role_id == STUDENT) {
                    
                    $profile = $this->auth->get_single_student($login->id);                 
                    $this->session->set_userdata('class_id', $profile->class_id);
                    $this->session->set_userdata('section_id', $profile->section_id);
                    
                } elseif ($login->role_id == GUARDIAN) {
                    $profile = $this->auth->get_single('guardians', array('user_id' => $login->id));
                } elseif ($login->role_id == TEACHER) {
                    $profile = $this->auth->get_single('teachers', array('user_id' => $login->id));               
                } else {
                    $profile = $this->auth->get_single('employees', array('user_id' => $login->id));
                } 
            
                if (isset($profile->name)) {
                   $this->session->set_userdata('name', $profile->name);
                }
                if (isset($profile->phone)) {
                    $this->session->set_userdata('phone', $profile->phone);
                }
                if (isset($profile->email)) {
                    $this->session->set_userdata('email', $profile->email);
                }
                if (isset($profile->photo)) {
                    $this->session->set_userdata('photo', $profile->photo);
                }
                if (isset($profile->user_id)) {                
                    $this->session->set_userdata('user_id', $profile->user_id);
                }
                if (isset($profile->id)) {
                    $this->session->set_userdata('profile_id', $profile->id);
                }              

                // set appliction setting
                if($login->role_id != SUPER_ADMIN){ 
                    
                    $school = $this->auth->get_single('schools', array('status' => 1, 'id'=>$login->school_id));
                    if (isset($school->school_name)) {
                        $this->session->set_userdata('school_name', $school->school_name);
                    } 
                    $this->session->set_userdata('theme', $school->theme_name);
                    $this->session->set_userdata('front_school_id', $login->school_id);
                    
                }else{
                    
                    $global_setting = $this->auth->get_single('global_setting', array());
                    $this->session->set_userdata('theme', $global_setting->theme_name);
                }

                $this->auth->update('users', array('last_logged_in' => date('Y-m-d H:i:s')), array('id' => logged_in_user_id()));
                success($this->lang->line('login_success'));
                create_log('Has been logged in');
                redirect('dashboard/index');
                
            } else {
                
                $this->session->set_flashdata('error', $this->lang->line('invalid_login'));
                redirect('login');
            }
        }
        redirect();
    }

    /*     * ***************Function logout**********************************
     * @type            : Function
     * @function name   : logout
     * @description     : Log Out the logged in user and redirected to Login page  
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function logout($key = null) {

        @create_log('Has been logged out');
         
        $this->session->unset_userdata($key);
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('username');

        $this->session->unset_userdata('name');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('photo');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('profile_id');
        $this->session->unset_userdata('school_id');

        $this->session->unset_userdata('theme');
               

        $this->session->sess_destroy();
        redirect('login', 'refresh');
        exit;
    }

    /*     * ***************Function forgot**********************************
     * @type            : Function
     * @function name   : forgot
     * @description     : Load recover forgot password view file  
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function forgot() {

        $this->load->helper('form');
        $data = array();
        $this->load->view('forgot', $data);
    }

    /*     * ***************Function forgotpass**********************************
     * @type            : Function
     * @function name   : forgotpass
     * @description     : this function is used to send recover forgot password  email 
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function forgotpass() {

        if ($_POST) {
            $data['username'] = $this->input->post('username');
            $data['status'] = 1;
            $login = $this->auth->get_single('users', $data);
            if (!empty($login)) {
                if($this->_send_email($login)){
                    $this->session->set_flashdata('success', $this->lang->line('email_send_success'));
                }else{
                    $this->session->set_flashdata('success', $this->lang->line('unexpected_error'));
                }                
            } else {
                $this->session->set_flashdata('error', $this->lang->line('wrong_username'));
            }
        }

        redirect('auth/forgot');
        exit;
    }

    /*     * ***************Function _send_email**********************************
     * @type            : Function
     * @function name   : _send_email
     * @description     : this function used to send recover forgot password email 
     * @param           : $data array(); 
     * @return          : null 
     * ********************************************************** */

    private function _send_email($data) {	
        $school_id     = $data->school_id ? $data->school_id : 0;   
        $profile = get_user_by_role($data->role_id, $data->id);  
        $user_email = $profile->email ?? NULL;          
        if(!$user_email && $data->role_id == ADMIN && $school_id)
        {
            $school = $this->auth->get_single('schools', array('status' => 1, 'id'=>$school_id));
            $user_email = $school->email;
        }     
        if($user_email){
            
            $from_email = FROM_EMAIL;
            $from_name = FROM_NAME;                  
            
            $email_setting = $this->auth->get_single('email_settings', array('status' => 1, 'school_id'=>$school_id));   
            if(empty($email_setting)){
                $email_setting = $this->auth->get_single('email_settings', array('status' => 1, 'school_id'=>0));   
            }          
            if(!empty($email_setting)){
                $from_email = $email_setting->from_address;
                $from_name  = $email_setting->from_name;  
            }elseif(!empty($school)){
                $from_email = $school->email;
                $from_name  = $school->school_name;  
            }
                
            if (!empty($email_setting) && $email_setting->mail_protocol == 'smtp')
            {
                $config['protocol']     = 'smtp';
                $config['smtp_host']    = $email_setting->smtp_host;
                $config['smtp_port']    = 465;
                $config['smtp_timeout'] = $email_setting->smtp_timeout ? $email_setting->smtp_timeout  : 5;
                $config['smtp_user']    = $email_setting->smtp_user;
                $config['smtp_pass']    = $email_setting->smtp_pass;
                $config['smtp_crypto']  = $email_setting->smtp_crypto ? $email_setting->smtp_crypto  : 'tls';
                $config['mailtype'] = isset($email_setting) && $email_setting->mail_type ? $email_setting->mail_type  : 'html';
                $config['charset']  = isset($email_setting) && $email_setting->char_set ? $email_setting->char_set  : 'iso-8859-1';
                $config['priority']  = isset($email_setting) && $email_setting->priority ? $email_setting->priority  : '3';
                
            }elseif(!empty($email_setting) && $email_setting->mail_protocol != 'smtp'){
                $config['protocol'] = $email_setting->mail_protocol;
                $config['mailpath'] = '/usr/sbin/'.$email_setting->mail_protocol; 
                $config['mailtype'] = isset($email_setting) && $email_setting->mail_type ? $email_setting->mail_type  : 'html';
                $config['charset']  = isset($email_setting) && $email_setting->char_set ? $email_setting->char_set  : 'iso-8859-1';
                $config['priority']  = isset($email_setting) && $email_setting->priority ? $email_setting->priority  : '3';
                
            }else{// default    
                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail'; 
            }                             
            
            
            $config['wordwrap'] = TRUE;            
            $config['newline']  = "\r\n";            
            // debug_a($config);
            
            $this->load->library('email');
            $this->email->initialize($config);
            

            $this->email->from($from_email, $from_name);
            $this->email->to($user_email);
            $subject = 'Password reset Email from : '. $from_name;
            $this->email->subject($subject);
            $key = uniqid();
            $this->auth->update('users', array('reset_key' => $key), array('id' => $data->id));

            $message = 'You have requested to reset your ' . $from_name . ' web Application login password.<br/>';
            $message .= 'To reset you password plese click following url<br/><br/>';
            $message .= site_url('auth/reset/' . $key);
            $message .= '<br/><br/>';
            $message .= 'If you did not request to reset your password, Plesae ignore this email.<br/><br/>';
            $message .= 'Thank you<br/>';
            $message .= $from_name;

            $this->email->message($message);
                       
            if(!empty($email_setting) && $email_setting->mail_protocol == 'smtp'){
				// print '<pre>';
                // print_r($config);				
                $this->email->send(); 
             
            }else{
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From:  $from_name < $from_email >\r\n";
                $headers .= "Reply-To:  $from_name < $from_email >\r\n"; 
                                //  debug_a([$user_email, $subject, $message, $headers]);

                $result = mail($user_email, $subject, $message, $headers);

                debug_a( $result );


            } 
            // debug_a( "ddd" );

            return TRUE;
        }else{
            return FALSE;
        }
    }

    /*     * ***************Function reset**********************************
     * @type            : Function
     * @function name   : reset
     * @description     : this function used to load password reset view file 
     * @param           : $key string parameter; 
     * @return          : null 
     * ********************************************************** */

    public function reset($key) {

        $data = array();
        $this->load->helper('form');
        $user = $this->auth->get_single('users', array('reset_key' => $key));
        
        if (!empty($user)) {
            $data['user'] = $user;
            $data['key'] = $key;
            $this->load->view('reset', $data);
        } else {
            $this->session->set_flashdata('error', $this->lang->line('unexpected_error'));
            redirect('login');
        }
    }

    /*     * ***************Function resetpass**********************************
     * @type            : Function
     * @function name   : resetpass
     * @description     : this function used to reset user passwrd 
     *                    after sucessfull reset password it's redirected
     *                    user to log in page            
     * @param           : null; 
     * @return          : null 
     * ********************************************************** */
    
    public function resetpass() {

        if ($_POST) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[5]|max_length[12]');
            $this->form_validation->set_rules('conf_password', $this->lang->line('conf_password'), 'trim|required|matches[password]');

            if ($this->form_validation->run() === TRUE) {
                
                
                $data['password'] = md5($this->input->post('password'));
                $data['temp_password'] = base64_encode($this->input->post('password'));
                $data['reset_key'] = NULL;
                $data['modified_at'] = date('Y-m-d H:i:s');               
                $this->auth->update('users', $data, array('id' => $this->input->post('id')));
                $this->session->set_flashdata('success', $this->lang->line('update_success'));               
                redirect('login', 'refresh');
            } else {
                $this->session->set_flashdata('error', $this->lang->line('password_reset_error'));
                redirect('auth/reset/' . $this->input->post('key'));
            }
        }

        redirect();
        exit;
    }   

}