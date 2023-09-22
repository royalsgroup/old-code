<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Approve.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Approve
 * @description     : Manage Approve.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Approve extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Application_Model', 'approve', true);        
        $this->load->library('message/message');   
    }

    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Approve Leave List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ***********************************************************/
    public function index($school_id = null) {

        check_permission(VIEW);
        if(!$school_id)
        {
            error($this->lang->line('select_school'));
        }      
        //$this->data['applications'] = $this->approve->get_application_list($school_id, $approve = 2);          
        $this->data['school_id'] = $school_id;        
        $this->data['filter_school_id'] = $school_id;        
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').  ' ' .  $this->lang->line('approve') .' '.  $this->lang->line('leave') .' | ' . SMS);
        $this->layout->view('approve/index', $this->data);        
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
            redirect('leave/approve/index');
        }
       
        if ($_POST) {
            $this->_prepare_approve_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_approve_data();
                
                $updated = $this->approve->update('leave_applications', $data, array('id' => $this->input->post('id')));

                if ($updated) {                
                    $application = $this->approve->get_single_application($this->input->post('id'));
               
                $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id); 
                
                
                $attendance_param = array(              
                    'school_id'=>$application->school_id,
                     'academic_year_id'=>$application->academic_year_id
                    );
                 
                    
                     if($application->role_id == STUDENT)
                     {
                        $attendance_param['student_id'] = $user->id;
                        $attendance_param['class_id'] = $user->class_id;
                        $table = "student_attendances";
                     }
                     elseif ($application->role_id == TEACHER)
                     {
                        $attendance_param['teacher_id'] = $user->id;
                        $table = "teacher_attendances";
                     }
                     else
                     {
                            $attendance_param['employee_id'] = $user->id;
                            $table = "employee_attendances";
                     }
                    $startTime = strtotime( $application->leave_from );
                    $endTime = strtotime( $application->leave_to );

                    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {

                        
                        $month = date('m',$i);
                        $year = date('Y',$i);
                        $day = date('j',$i);
                    
                        $day_coloumn = "day_$day";
                        $attendance_param['month'] = $month;
                        $attendance_param['year'] = $year;
                         
                        $attendance_month = $this->approve->get_single($table, $attendance_param);
                        if(empty($attendance_month)){
                            $attendance_param['status'] = 1;
                            $attendance_param[$day_coloumn] = "L";
                            if($application->role_id == STUDENT)
                            {
                               // $enrollment = $this->approve->get_single('enrollments', array('student_id'=>$user->id,'class_id' =>$application->class_id));
                                $attendance_param['section_id'] = $user->section_id;
                            }
                          

                            $attendance_param['created_at'] = date('Y-m-d H:i:s');
                            $attendance_param['created_by'] = logged_in_user_id();
                            $this->approve->insert($table, $attendance_param);
                            
                        }   
                        else
                        {
                            $column_arr = array($day_coloumn => "L");
                            $this->approve->update($table, $column_arr, $attendance_param);
                        }
                    }
                    $message_data               = array(); 
                    $message_data['school_id']          = $application->school_id;
                    $message_data['academic_year_id']   = $application->academic_year_id;   
                    $message_data['subject']            = $this->lang->line('leave_application_approved');  
                    $message_data['sender_id']          = logged_in_user_id();
                    $message_data['sender_role_id']     = $this->session->userdata('role_id');
    
                    $message = $this->lang->line('hi'). ' '. $user->name.',';
                    $message .= '<br/>';
                    $message .= $this->lang->line('your_leave_application_approved');
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
                    create_log('Has been updated a approve leave');                    
                    success($this->lang->line('update_success'));
                    redirect('leave/approve/index/'.$this->input->post('school_id'));
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('leave/approve/update/' . $this->input->post('id'));
                }
            } else {
                $this->data['application'] = $this->approve->get_single_application( $this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['application'] = $this->approve->get_single_application($id);
            if (!$this->data['application']) {
                redirect('leave/approve/index');
            }
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['classes'] = $this->approve->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['applications'] = $this->approve->get_application_list( $this->data['application']->school_id);      
        $this->data['roles'] = $this->approve->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
       
        $this->data['school_id'] = $this->data['application']->school_id;  
        $this->data['filter_school_id'] = $this->data['application']->school_id;
        $this->data['schools'] = $this->schools; 
        
        //print_r($this->data['application']);
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('update') . ' ' . $this->lang->line('approve') . ' '. $this->lang->line('application') . ' | ' . SMS);
        $this->layout->view('approve/index', $this->data);
    }

       
            /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_leave_list(){		
        // for super admin 
       $school_id = '';
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       
        
       
      if($school_id){
         $applications = $this->approve->get_application_list($school_id,2,$limit,$start,$search_text);
        // $applications = $this->application->get_application_list($school_id);
         $totalRecords = $this->approve->get_application_list_total($school_id,2,$search_text);
      }
      else
      {
        $totalRecords = 0;
        $non_members = array();
      }
       $count = 1; 
       $data = array();

       if(isset($applications) && !empty($applications)){
               foreach($applications as $obj){
                $row_data = array();
                    $user_name="";
                   if($user = get_user_by_role($obj->role_id, $obj->user_id))
                   {
                    $user_name= $user->name;
                    if($obj->role_id == STUDENT){
                        $user_name .= '<br/>'.$user->class_name.', '. $user->section.', '.$user->roll_no;
                    }
                   }
                   
                   if($search_text && strpos(strtolower($user_name),strtolower($search_text))===false)
                   {
                    continue;
                   }
                   $status  = '<a href="javascript:void(0);" class="btn btn-default red btn-xs">'.$this->lang->line('new').' </a> ';
                    $action = "";
                   
                    $row_data[] = $count." ".strpos($user_name,$search_text);
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                  if(has_permission(EDIT, 'leave', 'approve')){ 
                    $action .='<a  onclick="get_application_modal('.$obj->id.')"  data-toggle="modal" data-target=".bs-application-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line('view').'</a>';
                     }                            
                 
                   $row_data[] = $obj->session_year;;
                   $row_data[] = $obj->role_name;
                   $row_data[] = $obj->type;
                   $row_data[] = $user_name;
                   $row_data[] = $action;
                    $data[] = $row_data;
                    $count++;
               }
       }
       else{
           $data=array();
       }
       //print_r($data); exit;
       $response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecords,
 "aaData" => $data
);
echo json_encode($response);
exit;
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
       
       $this->data['application'] = $this->approve->get_single_application($application_id);
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
    private function _prepare_approve_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('leave_from', $this->lang->line('leave').' '.$this->lang->line('from'), 'trim|required');
        $this->form_validation->set_rules('leave_to', $this->lang->line('leave').' '.$this->lang->line('to'), 'trim|required|callback_leave_to');
        $this->form_validation->set_rules('leave_note', $this->lang->line('approve').' '.$this->lang->line('approve'), 'trim|required');
    }
    
    
    /*****************Function leave_to**********************************
    * @Type            : Function
    * @function name   : leave_to
    * @description     : date schedule check data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function leave_to() {
        
        $leave_from = date('Y-m-d', strtotime($this->input->post('leave_from')));
        $leave_to   = date('Y-m-d', strtotime($this->input->post('leave_to')));
            
        if ($leave_from > $leave_to ) {
            $this->form_validation->set_message('leave_to', $this->lang->line('to_date_must_be_big'));
            return FALSE;
        } else {
            return TRUE;
        }        
    }
    
    /*****************Function _get_posted_leave_data**********************************
    * @type            : Function
    * @function name   : _get_posted_leave_data
    * @description     : Prepare "Leave" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_approve_data() {

        $items = array();     
        $items[] = 'leave_note';   
        
        $data = elements($items, $_POST);
        
        $data['leave_date'] = date('Y-m-d', strtotime($this->input->post('leave_date')));
        $data['leave_from'] = date('Y-m-d', strtotime($this->input->post('leave_from')));
        $data['leave_to']   = date('Y-m-d', strtotime($this->input->post('leave_to')));
        
        $start = strtotime($data['leave_from']);
        $end   = strtotime($data['leave_to']);
        $days = ceil(abs($end - $start) / 86400);
        $data['leave_day'] = $days+1;
        
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id(); 
        $data['leave_status'] = 2;           
   

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
             redirect('leave/approve/index');
        }
        
        $application = $this->approve->get_single_application($id);
        
        if ($this->approve->delete('leave_applications', array('id' => $id))) {
            
             // delete teacher resume and image
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/leave/' . $application->attachment)) {
                @unlink($destination . '/leave/' . $application->attachment);
            }            
            
            create_log('Has been deleted a approve application');
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
         redirect('leave/approve/index/'.$application->school_id);
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
            redirect('leave/approve/index');     
        }
        
        $leave = $this->approve->get_single('leave_applications', array('id'=>$application_id));               
        $status = $this->approve->update('leave_applications', array('leave_status'=>1, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$application_id));               
        
        if($status){
            success($this->lang->line('update_success'));
            redirect('leave/approve/index/'.$leave->school_id);  
        }else{
            error($this->lang->line('update_failed'));
            redirect('leave/approve/index/'.$leave->school_id);      
        }
    }
    
}
