<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Decline.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Decline
 * @description     : Manage Decline.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Decline extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Application_Model', 'decline', true);        
        $this->load->library('message/message');   
    }

    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Decline Leave List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ***********************************************************/
    public function index($school_id = null) {

        check_permission(VIEW);
        $applications = array();        
        if($this->session->userdata('dadmin')==1){
            if($school_id)
            {
                $applications = $this->decline->get_application_list($school_id, 3);   
            }
        }       
        else
        {
            $applications = $this->decline->get_application_list($school_id, 3);   

        }
        $this->data['applications'] = $applications;                
        $this->data['school_id'] = $school_id;        
        $this->data['filter_school_id'] = $school_id;        
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').  ' ' .  $this->lang->line('decline') .' '.  $this->lang->line('leave') .' | ' . SMS);
        $this->layout->view('decline/index', $this->data);        
    }

    
    /*****************Function update**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Leave" user interface                 
    *                    with populated "Leave" value 
    *                    and process to update "Leave" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function update($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('leave/decline/index');
        }
       
        if ($_POST) {
            $this->_prepare_decline_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_decline_data();
                $updated = $this->decline->update('leave_applications', $data, array('id' => $this->input->post('id')));

                if ($updated) {   
                    $application = $this->decline->get_single_application($this->input->post('id'));
               
                    $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id); 
                    $message_data               = array(); 
                    $message_data['school_id']          = $application->school_id;
                    $message_data['academic_year_id']   = $application->academic_year_id;   
                    $message_data['subject']            = $this->lang->line('leave_application_rejected');  
                    $message_data['sender_id']          = logged_in_user_id();
                    $message_data['sender_role_id']     = $this->session->userdata('role_id');
    
                    $message = $this->lang->line('hi'). ' '. $user->name.',';
                    $message .= '<br/>';
                    $message .= $this->lang->line('your_leave_application_rejected');
                    $message .= '<br/><br/>';
                    $message .= $this->lang->line('leave_type'). ': ' . $application->type;
                    $message .= '<br/>';
                    $message .= $this->lang->line('leave_from'). ': ' . $application->leave_from;
                    $message .= '<br/>';
                    $message .= $this->lang->line('leave_to'). ': ' . $application->leave_to;
                    $message .= '<br/>';
                    $message .= $this->lang->line('leave_reason'). ': ' . $application->leave_reason;
                    $message .= '<br/>';
                    $message .= $this->lang->line('note'). ': ' . $data['leave_note'];
                    $message .= '<br/>';
                    $message .= '<br/><br/>';
    
                    $message .= $this->lang->line('thank_you').'<br/>';
                    $message_data['body'] = $message;
                    $message_data['receiver_id'] = $application->user_id;
                    $message_data['receiver_role_id'] = $application->role_id;
                    $this->message->send_message($message_data);       
                    if($application->role_id == STUDENT)//if student
                    {
                        $source_users = $this->decline->get_reporting_users($application->school_id,$application->class_teacher_id);
                    }
                    else $source_users = $this->decline->get_reporting_users($application->school_id);
                    $admin_users = $this->decline->get_admins($application->school_id);
                    if(!empty( $admin_users))
                    {
                        $source_users = array_merge($source_users,$admin_users);
                    }
                    

                    $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id);  
                    $message_data               = array(); 
                    $message_data['school_id']          = $application->school_id;
                    $message_data['academic_year_id']   = $application->academic_year_id;   
                    $message_data['subject']            = $this->lang->line('leave_application_rejected');  
                    $message_data['sender_id']          = $application->user_id;
                    $message_data['sender_role_id']     = $application->role_id;
                    foreach ($source_users as $obj) {
                        // student message
                        if($obj->id != ''){        
                            $sourse_user = get_user_by_role($obj->role_id, $obj->id, $application->academic_year_id);
                            $message = $this->lang->line('hi'). ' '. $sourse_user->name.',';
                            $message .= '<br/>';
                            $message .= $this->lang->line('your_leave_application_rejected');
                            $message .= '<br/><br/>';
                            $message .= $this->lang->line('applicant').': ' . $user->name;
                            $message .= '<br/>';
                            $message .= $this->lang->line('applicant_type'). ': ' . $application->role_name;
                            $message .= '<br/>';
                            $message .= $this->lang->line('leave_type'). ': ' . $application->type;
                            $message .= '<br/>';
                            $message .= $this->lang->line('leave_from'). ': ' . $application->leave_from;
                            $message .= '<br/>';
                            $message .= $this->lang->line('leave_to'). ': ' . $application->leave_to;
                            $message .= '<br/>';
                            $message .= $this->lang->line('leave_reason'). ': ' . $application->leave_reason;
                            $message .= '<br/>';
                            $message .= '<br/><br/>';
            
                            $message .= $this->lang->line('thank_you').'<br/>';
                            $message_data['body'] = $message;
                            $message_data['receiver_id'] = $obj->id;
                            $message_data['receiver_role_id'] = $obj->role_id;
                            $this->message->send_message($message_data);
                        }
                    }           
                    create_log('Has been updated a decline leave');                    
                    success($this->lang->line('update_success'));
                    redirect('leave/decline/index/'.$this->input->post('school_id'));
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('leave/decline/update/' . $this->input->post('id'));
                }
            } else {
                $this->data['application'] = $this->decline->get_single_application( $this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['application'] = $this->decline->get_single_application($id);
            if (!$this->data['application']) {
                redirect('leave/decline/index');
            }
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['classes'] = $this->decline->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['applications'] = $this->decline->get_application_list( $this->data['application']->school_id);      
        $this->data['roles'] = $this->decline->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        $this->data['school_id'] = $this->data['application']->school_id;  
        $this->data['filter_school_id'] = $this->data['application']->school_id;
        $this->data['schools'] = $this->schools; 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('update') . ' ' . $this->lang->line('decline') . ' '. $this->lang->line('application') . ' | ' . SMS);
        $this->layout->view('decline/index', $this->data);
    }

       
           
    /*****************Function get_single_application**********************************
     * @type            : Function
     * @function name   : get_single_application
     * @description     : "Load single application information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_application(){
        
       $application_id = $this->input->post('application_id');
       
       $this->data['application'] = $this->decline->get_single_application($application_id);
       echo $this->load->view('get-single-application', $this->data);
    }


        /*****************Function _prepare_application_validation**********************************
    * @type            : Function
    * @function name   : _prepare_application_validation
    * @description     : Process "application" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_decline_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('leave_note', $this->lang->line('decline').' '.$this->lang->line('decline'), 'trim|required');
    }
    
    
    /*****************Function _get_posted_leave_data**********************************
    * @type            : Function
    * @function name   : _get_posted_leave_data
    * @description     : Prepare "Leave" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_decline_data() {

        $items = array();     
        $items[] = 'leave_note';   
        
        $data = elements($items, $_POST);
        
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id(); 
        $data['leave_status'] = 3;           
   

        return $data;
    }


    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Leave" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('leave/decline/index');
        }
        
        $application = $this->decline->get_single_application($id);
        
        if ($this->decline->delete('leave_applications', array('id' => $id))) {
            
             // delete teacher resume and image
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/leave/' . $application->attachment)) {
                @unlink($destination . '/leave/' . $application->attachment);
            }            
            
            create_log('Has been deleted a decline application');
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
         redirect('leave/decline/index/'.$application->school_id);
    }
    

        /*****************Function waiting**********************************
     * @type            : Function
     * @function name   : waiting
     * @description     : "update leave status" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function waiting($application_id){
        if(!is_numeric($application_id)){
            error($this->lang->line('unexpected_error'));
            redirect('leave/decline/index');     
        }
        
        $leave = $this->decline->get_single('leave_applications', array('id'=>$application_id));               
        $status = $this->decline->update('leave_applications', array('leave_status'=>1, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$application_id));               
        
        if($status){
            success($this->lang->line('update_success'));
            redirect('leave/decline/index/'.$leave->school_id);  
        }else{
            error($this->lang->line('update_failed'));
            redirect('leave/decline/index/'.$leave->school_id);      
        }
    }
    
}
