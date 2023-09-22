<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************absentsms.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Resultsms
 * @description     : Manage text sms which are send to the users.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Resultsms extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Resultemailsms_Model', 'sms', true);
                       
        $this->load->library('twilio');
        $this->load->library('clickatell');
        $this->load->library('bulk');
        $this->load->library('msg91');
        $this->load->library('plivo');
        $this->load->library('smscountry');
        $this->load->library('textlocalsms');
        $this->load->library('betasms');
        $this->load->library('message/message');
    }

            
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Sent SMS List" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        check_permission(VIEW);

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->sms->get_list('classes', $condition, '','', '', 'id', 'ASC');          
        }
        
        $this->data['texts'] = $this->sms->get_sms_list($school_id);
        $this->data['roles'] = $this->sms->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_sms') . ' | ' . SMS);
        $this->layout->view('result_sms/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Send new SMS" user interface                 
    *                    and process to send "SMS"
    *                    and store SMS into database
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_sms_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_sms_data();
                $insert_id = $this->sms->insert('text_messages', $data);
                if ($insert_id) {
                    $data['class_id'] = $this->input->post('class_id');
                    $data['receiver_id'] = $this->input->post('receiver_id');
                    $this->_send_message_notification( $data );
                    $data['text_msg_id'] = $insert_id;
                    $this->_send_sms($data);
                    
                    create_log('Has been sent a Result SMS using : '.$data['sms_gateway']);                    
                    success($this->lang->line('sms_send_success'));
                    redirect('exam/resultsms/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('sms_send_failed'));
                    redirect('exam/resultsms/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                redirect('exam/resultsms/add');
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->sms->get_list('classes', $condition, '','', '', 'id', 'ASC');          
        }
        
        $this->data['texts'] = $this->sms->get_sms_list();
        $this->data['roles'] = $this->sms->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['schools'] = $this->schools;

        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('send') . ' ' . $this->lang->line('sms') . ' | ' . SMS);
        $this->layout->view('result_sms/index', $this->data);
    }

    
        
    /*****************Function get_single_sms**********************************
     * @type            : Function
     * @function name   : get_single_sms
     * @description     : "Load single email information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_single_sms(){
        
       $sms_id = $this->input->post('sms_id');
       
       $this->data['sms'] = $this->sms->get_single_sms($sms_id);
       echo $this->load->view('result_sms/get-single-sms', $this->data);
    }
     
            
    /*****************Function _prepare_sms_validation**********************************
    * @type            : Function
    * @function name   : _prepare_sms_validation
    * @description     : Process "SMS" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
   public function _prepare_sms_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('role_id', $this->lang->line('receiver_type'), 'trim|required');
        if ($this->input->post('role_id') == STUDENT) {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        }
        
        $this->form_validation->set_rules('receiver_id', $this->lang->line('receiver'), 'trim|required');
        $this->form_validation->set_rules('sms_gateway', $this->lang->line('gateway'), 'trim|required|callback_sms_gateway');
        $this->form_validation->set_rules('body', $this->lang->line('sms'), 'trim|required');
    }

               
    /*****************Function sms_gateway**********************************
    * @type            : Function
    * @function name   : sms_gateway
    * @description     : Process to SMS gateway validation                
    *                       
    * @param           : null
    * @return          : boolean true/flase 
    * ********************************************************** */ 
   public function sms_gateway() {

        $getway = $this->input->post('sms_gateway');

        if ($getway == "clicktell") {
            if ($this->clickatell->ping() == TRUE) {
                return TRUE;
            } else {
                $this->form_validation->set_message("sms_gateway", $this->lang->line('setup_your_sms_gateway'));
                return FALSE;
            }
        } elseif ($getway == 'twilio') {            
            $get = $this->twilio->get_twilio();
            $ApiVersion = $get['version'];
            $AccountSid = $get['accountSID'];
            $check = $this->twilio->request("/$ApiVersion/Accounts/$AccountSid/Calls");

            if ($check->IsError) {
                $this->form_validation->set_message("sms_gateway", $this->lang->line('setup_your_sms_gateway'));
                return FALSE;
            }
            return TRUE;
        } elseif ($getway == 'bulk') {
            if ($this->bulk->ping() == TRUE) {
                return TRUE;
            } else {
                $this->form_validation->set_message("sms_gateway", $this->lang->line('setup_your_sms_gateway'));
                return FALSE;
            }
        } elseif ($getway == 'msg91') {
            return true;
        } elseif ($getway == 'plivo') {
            return true;
        } elseif ($getway == 'text_local') {
            return true;       
        } elseif ($getway == 'sms_country') {
            return true;
        }elseif ($getway == 'beta_sms') {
            return true;
        }
    }

    
       
    /*****************Function _get_posted_sms_data**********************************
    * @type            : Function
    * @function name   : _get_posted_sms_data
    * @description     : Prepare "SMS" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_sms_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'role_id';
        $items[] = 'sms_gateway';
        $items[] = 'body';
        $data = elements($items, $_POST);

        $school = $this->sms->get_school_by_id($data['school_id']);
        
        if(!$school->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('exam/resultsms');
        }
        
        $data['academic_year_id'] = $school->academic_year_id;
        
        $data['sender_role_id'] = $this->session->userdata('role_id');
        $data['status'] = 1;
        $data['sms_type'] = 'result';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();

        return $data;
    }

            
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "SMS" data from database                  
    *                    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        $sms = $this->sms->get_single('text_messages', array('id' => $id));
        
        if ($this->sms->delete('text_messages', array('id' => $id))) {
            
            create_log('Has been deleted a Result SMS sent by : '.$sms->sms_gateway);            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('exam/resultsms/index');
    }

        
        
    /*****************Function _send_sms**********************************
    * @type            : Function
    * @function name   : _send_sms
    * @description     : Process to send SMS to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    private function _send_sms($data) {

        $class_id = $this->input->post('class_id');
        $school = $this->sms->get_school_by_id($data['school_id']);
        $students = $this->sms->get_student_list($data['school_id'], $this->input->post('receiver_id'), $class_id, $school->academic_year_id);
        $receivers = '';

        foreach ($students as $obj) {

            $message = '';
                     
           if($data['role_id'] == GUARDIAN){ 
               
                $guardian = $this->sms->get_single_guardian($data['school_id'], $obj->guardian_id);
                $obj->phone = $guardian->phone;
                $obj->name  = $guardian->name;
                $obj->id    = $guardian->id;
            }            
            
            $result = $this->sms->get_single('final_results',array('school_id'=>$data['school_id'], 'student_id'=> $obj->student_id, 'class_id'=>$class_id, 'academic_year_id'=> $school->academic_year_id));
            
            if(!empty($result) && $obj->phone != ' '){
                
                $grade = $this->sms->get_single('grades',array('school_id'=>$data['school_id'], 'id'=> $result->grade_id)); 

                $class_position = get_student_position($data['school_id'], $school->academic_year_id, $class_id, $obj->student_id);
                $section_position = get_student_position($data['school_id'], $school->academic_year_id, $class_id, $obj->student_id, $obj->section_id);
                
                $message .= '[Sub:'.$result->total_subject.','; 
                $message .= 'Mark:'.$result->total_mark.','; 
                $message .= 'Obtain:'.$result->total_obtain_mark.','; 
                $message .= 'GPA:'.$grade->name.'('.$result->avg_grade_point.')'; 
                $message .= 'Position in Class:('.$class_position.') in Section:('.$section_position.')]'; 
              
                $body = get_formatted_body($data['body'], $data['role_id'], $obj->id);

                if (strpos($body, '[exam_result]') !== false) {
                    $body = str_replace('[exam_result]', $message, $body);
                }else{
                    $body = $body . ' ' . $message;
                } 

                $body = trim($body);
                $receivers .= $obj->name.','; 
                $phone = '+' . $obj->phone;
                $this->_send($data['sms_gateway'], $phone, $body);              
              
            }
        }

        // update text_messages table 
        $this->sms->update('text_messages', array('receivers' => $receivers), array('id' => $data['text_msg_id']));
    }

            
    /*****************Function _send**********************************
    * @type            : Function
    * @function name   : _send
    * @description     : Send SMS to the users using configured SMS Gateway                 
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send($sms_gateway, $phone, $message) {

        if ($sms_gateway == "clicktell") {
            
            $this->clickatell->send_message($phone, $message);
            
        } elseif ($sms_gateway == 'twilio') {
            
            $get = $this->twilio->get_twilio();
            $from = $get['number'];            
            $response = $this->twilio->sms($from, $phone, $message);   
            
        } elseif ($sms_gateway == 'bulk') {

            //https://github.com/anlutro/php-bulk-sms     
            
            $this->bulk->send($phone, $message);
            
        } elseif ($sms_gateway == 'msg91') {
            
            $response = $this->msg91->send($phone, $message);
            
        } elseif ($sms_gateway == 'plivo') {
            
            $response = $this->twilio->send($phone, $message);
            
        }elseif ($sms_gateway == 'sms_country') { 
            
            $response = $this->smscountry->sendSMS($phone, $message);   
            
        } elseif ($sms_gateway == 'text_local') {  
            
            $response = $this->textlocalsms->sendSms(array($phone), $message);
        } elseif ($sms_gateway == 'beta_sms') {     
            
            $response = $this->betasms->sendSms(array($phone), $message);
        }
    }
    /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_message_notification
    * @description     : Process to send in app messages to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($message_data = null) {
 
        $school = $this->sms->get_school_by_id($message_data['school_id']);
        $students = $this->sms->get_student_list($message_data['school_id'], $message_data['receiver_id'], $message_data['class_id'], $school->academic_year_id);
        $receivers = '';

        $data                       = array("");
        $data['academic_year_id']   = $school->academic_year_id;
        $data['school_id']          = $message_data['school_id'];
        $data['subject']            = $this->lang->line('exam_result');   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
       
        foreach ($students as $obj) {
            $heading = $this->lang->line('following_is_your_exam_result');
            if($message_data['role_id'] == GUARDIAN){ 
                $heading = $this->lang->line('following_is_your_child_exam_result');
                $guardian = $this->sms->get_single_guardian($message_data['school_id'], $obj->guardian_id); 
                $obj->id = $guardian->id;               
            } 
           
            $result = $this->sms->get_single('final_results',array('school_id'=>$data['school_id'], 'student_id'=> $obj->student_id, 'class_id'=> $message_data['class_id'], 'academic_year_id'=> $school->academic_year_id));
            
            if(!empty($result) && $obj->id != ''){
            
                $grade = $this->sms->get_single('grades',array('school_id'=>$data['school_id'], 'id'=> $result->grade_id));
               
                $class_position = get_student_position($data['school_id'], $school->academic_year_id, $message_data['class_id'], $obj->student_id);
                $section_position = get_student_position($data['school_id'], $school->academic_year_id, $message_data['class_id'], $obj->student_id, $obj->section_id);
                
                $message .= "<br/><br/><b> $heading</b><br/>"; 
                $message .= 'Total Subject: '.$result->total_subject.'<br/>'; 
                $message .= 'Total Exam Mark: '.$result->total_mark.'<br/>'; 
                $message .= 'Total Obtain Mark: '.$result->total_obtain_mark.'<br/>'; 
                $message .= 'Avg Grade Point: '.$result->avg_grade_point.'<br/>'; 
                $message .= 'Grade : '.$grade->name.'<br/>'; 
                $message .= 'Result : '. $this->lang->line($result->result_status).'<br/>'; 
                $message .= 'Position in Section: '. $section_position.'<br/>'; 
                $message .= 'Position in Class: '. $class_position.'<br/>'; 
                $message .= 'Comment: '. $result->remark.'<br/><br/>'; 
               
                $data['body'] = $message;
                $data['receiver_id'] = $obj->id;
                $data['receiver_role_id'] = $message_data['role_id'];
               
                $this->message->send_message($data);           
            }  
            // student message
           
        }
    }  


}
