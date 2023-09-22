<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Sms.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Sms
 * @description     : Manage application sms gateway settings.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Sms extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Setting_Model', 'setting', true);   
        
        if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ 
            error($this->lang->line('permission_denied'));
            redirect('dashboard');
        }
         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['setting'] = $this->setting->get_single('sms_settings', $condition);
        }
    }

            
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "SMS Setting" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['clickatell'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

                
    /*****************Function clickatell**********************************
    * @type            : Function
    * @function name   : clickatell
    * @description     : Load "Clickatell Setting Tab" user interface                 
    *                     and process to save Clickatell setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function clickatell() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_clickatell_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/clickatell');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['clickatell'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

        
    /*****************Function _prepare_clickatell_validation**********************************
    * @type            : Function
    * @function name   : _prepare_clickatell_validation
    * @description     : Process "Clickatell Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_clickatell_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('clickatell_username', $this->lang->line('username'), 'trim|required');
        $this->form_validation->set_rules('clickatell_password', $this->lang->line('password'), 'trim|required');
        $this->form_validation->set_rules('clickatell_api_key', $this->lang->line('api_key'), 'trim|required');
        $this->form_validation->set_rules('clickatell_from_number', $this->lang->line('from_number'), 'trim|required');
        $this->form_validation->set_rules('clickatell_mo_no', $this->lang->line('mo_no'), 'trim|required');
        $this->form_validation->set_rules('clickatell_status', $this->lang->line('is_active'), 'trim|required');
    }

                    
    /*****************Function twilio**********************************
    * @type            : Function
    * @function name   : twilio
    * @description     : Load "Twilio Setting Tab" user interface                 
    *                     and process to save Twilio setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function twilio() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_twilio_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/twilio');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['twilio'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

            
    /*****************Function _prepare_twilio_validation**********************************
    * @type            : Function
    * @function name   : _prepare_twilio_validation
    * @description     : Process "Twilio Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_twilio_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('twilio_account_sid', $this->lang->line('account_sid'), 'trim|required');
        $this->form_validation->set_rules('twilio_auth_token', $this->lang->line('auth_token'), 'trim|required');
        $this->form_validation->set_rules('twilio_from_number', $this->lang->line('from_number'), 'trim|required');
        $this->form_validation->set_rules('twilio_status', $this->lang->line('is_active'), 'trim|required');
    }

                        
    /*****************Function bulk**********************************
    * @type            : Function
    * @function name   : bulk
    * @description     : Load "Bulk Setting Tab" user interface                 
    *                     and process to save Bulk setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function bulk() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_bulk_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/bulk');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['bulk'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

            
    /*****************Function _prepare_bulk_validation**********************************
    * @type            : Function
    * @function name   : _prepare_bulk_validation
    * @description     : Process "Bulk Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_bulk_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('bulk_username', $this->lang->line('username'), 'trim|required');
        $this->form_validation->set_rules('bulk_password', $this->lang->line('password'), 'trim|required');
        $this->form_validation->set_rules('bulk_status', $this->lang->line('is_active'), 'trim|required');
    }

                           
    /*****************Function msg91**********************************
    * @type            : Function
    * @function name   : msg91
    * @description     : Load "Msg91 Setting Tab" user interface                 
    *                     and process to save Msg91 setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function msg91() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_msg91_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/msg91');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['msg91'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

                
    /*****************Function _prepare_msg91_validation**********************************
    * @type            : Function
    * @function name   : _prepare_msg91_validation
    * @description     : Process "Msg91 Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_msg91_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('msg91_auth_key', $this->lang->line('auth_key'), 'trim|required');
        $this->form_validation->set_rules('msg91_sender_id', $this->lang->line('sender_id'), 'trim|required');
        $this->form_validation->set_rules('msg91_status', $this->lang->line('is_active'), 'trim|required');
    }

                               
    /*****************Function plivo**********************************
    * @type            : Function
    * @function name   : plivo
    * @description     : Load "Plivo Setting Tab" user interface                 
    *                     and process to save Plivo setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function plivo() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_plivo_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/plivo');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['plivo'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

    
                
    /*****************Function _prepare_plivo_validation**********************************
    * @type            : Function
    * @function name   : _prepare_plivo_validation
    * @description     : Process "Plivo Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_plivo_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('plivo_auth_id', $this->lang->line('auth_id'), 'trim|required');
        $this->form_validation->set_rules('plivo_auth_token', $this->lang->line('auth_token'), 'trim|required');
        $this->form_validation->set_rules('plivo_from_number', $this->lang->line('from_number'), 'trim|required');
        $this->form_validation->set_rules('plivo_status', $this->lang->line('is_active'), 'trim|required');
    }

    
    /*****************Function textlocal**********************************
    * @type            : Function
    * @function name   : textlocal
    * @description     : Load "textlocal Setting Tab" user interface                 
    *                     and process to save textlocal setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function textlocal() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_textlocal_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/textlocal');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['textlocal'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

    
                
    /*****************Function _prepare_textlocal_validation**********************************
    * @type            : Function
    * @function name   : _prepare_textlocal_validation
    * @description     : Process "textlocal Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_textlocal_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('textlocal_username', $this->lang->line('text_local').' '.$this->lang->line('username'), 'trim');
        $this->form_validation->set_rules('textlocal_hash_key', $this->lang->line('text_local').' '.$this->lang->line('hash_key'), 'trim');
        $this->form_validation->set_rules('textlocal_sender_id', $this->lang->line('text_local').' '.$this->lang->line('sender_id'), 'trim');
        $this->form_validation->set_rules('textlocal_status', $this->lang->line('text_local').' '.$this->lang->line('is_active'), 'trim');
        
    }

  
        
    /*****************Function smscountry**********************************
    * @type            : Function
    * @function name   : smscountry
    * @description     : Load "smscountry Setting Tab" user interface                 
    *                     and process to save smscountry setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function smscountry() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_smscountry_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/smscountry');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['smscountry'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

    
                
    /*****************Function _prepare_smscountry_validation**********************************
    * @type            : Function
    * @function name   : _prepare_smscountry_validation
    * @description     : Process "smscountry Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_smscountry_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('smscountry_username', $this->lang->line('sms_country').' '.$this->lang->line('username'), 'trim');
        $this->form_validation->set_rules('smscountry_password', $this->lang->line('sms_country').' '.$this->lang->line('password'), 'trim');
        $this->form_validation->set_rules('smscountry_sender_id', $this->lang->line('sms_country').' '.$this->lang->line('sender_id'), 'trim');
        $this->form_validation->set_rules('smscountry_status', $this->lang->line('sms_country').' '.$this->lang->line('is_active'), 'trim');
        
    }

    
      
        
    /*****************Function betasms**********************************
    * @type            : Function
    * @function name   : betasms
    * @description     : Load "betasms Setting Tab" user interface                 
    *                     and process to save betasms setting inormation into database  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function betasms() {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_betasms_validation();

            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_sms_data();
                if ($this->input->post('id')) {
                    $update = $this->setting->update('sms_settings', $data, array('id' => $this->input->post('id')));
                    if ($update) {
                        success($this->lang->line('update_success'));
                    } else {
                        error($this->lang->line('update_failed'));
                    }
                } else {
                    $insert_id = $this->setting->insert('sms_settings', $data);
                    if ($insert_id) {
                        success($this->lang->line('insert_success'));
                    } else {
                        error($this->lang->line('insert_failed'));
                    }
                }
                redirect('setting/sms/betasms');
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['betasms'] = TRUE;
        $this->layout->title($this->lang->line('sms') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('sms/index', $this->data);
    }

    
                
    /*****************Function _prepare_betasms_validation**********************************
    * @type            : Function
    * @function name   : _prepare_betasms_validation
    * @description     : Process "betasms Gateway" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_betasms_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="text-align: center;color: red;">', '</div>');

        $this->form_validation->set_rules('betasms_username', $this->lang->line('beta_sms').' '.$this->lang->line('username'), 'trim');
        $this->form_validation->set_rules('betasms_password', $this->lang->line('beta_sms').' '.$this->lang->line('password'), 'trim');
        $this->form_validation->set_rules('betasms_sender_id', $this->lang->line('beta_sms').' '.$this->lang->line('sender_id'), 'trim');
        $this->form_validation->set_rules('betasms_status', $this->lang->line('beta_sms').' '.$this->lang->line('is_active'), 'trim');
        
    }

    
    
    
        
           
    /*****************Function _get_posted_sms_data**********************************
    * @type            : Function
    * @function name   : _get_posted_sms_data
    * @description     : Prepare "SMS Gateway Settings" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_sms_data() {

        $items = array();

        if ($this->input->post('clickatell')) {
            $items[] = 'clickatell_username';
            $items[] = 'clickatell_password';
            $items[] = 'clickatell_api_key';
            $items[] = 'clickatell_from_number';
            $items[] = 'clickatell_mo_no';
            $items[] = 'clickatell_status';
        }

        if ($this->input->post('twilio')) {
            $items[] = 'twilio_account_sid';
            $items[] = 'twilio_auth_token';
            $items[] = 'twilio_from_number';
            $items[] = 'twilio_status';
        }

        if ($this->input->post('bulk')) {
            $items[] = 'bulk_username';
            $items[] = 'bulk_password';
            $items[] = 'bulk_status';
        }

        if ($this->input->post('msg91')) {
            $items[] = 'msg91_auth_key';
            $items[] = 'msg91_sender_id';
            $items[] = 'msg91_status';
        }
        
        if ($this->input->post('plivo')) {
            
            $items[] = 'plivo_auth_id';
            $items[] = 'plivo_auth_token';
            $items[] = 'plivo_from_number';
            $items[] = 'plivo_status';
        }
        
        if ($this->input->post('textlocal')) {
            
            $items[] = 'textlocal_username';
            $items[] = 'textlocal_hash_key';
            $items[] = 'textlocal_sender_id';
            $items[] = 'textlocal_status';
        }
        
        if ($this->input->post('smscountry')) {
            
            $items[] = 'smscountry_username';
            $items[] = 'smscountry_password';
            $items[] = 'smscountry_sender_id';
            $items[] = 'smscountry_status';
        }
        
        if ($this->input->post('betasms')) {
            
            $items[] = 'betasms_username';
            $items[] = 'betasms_password';
            $items[] = 'betasms_sender_id';
            $items[] = 'betasms_status';
        }

        $items[] = 'school_id';
        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
        }

        return $data;
    }
}