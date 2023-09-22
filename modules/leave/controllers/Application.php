<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Application.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Application
 * @description     : Manage application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Application extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Application_Model', 'application', true);        
        $this->load->library('message/message');   
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Application List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        check_permission(VIEW);
                         
       // $this->data['applications'] = $this->application->get_application_list($school_id);
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $school_id = $this->session->userdata('school_id');
        }        
     
        $this->data['classes'] = $this->application->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
         //$this->data['classes'] = $this->application->get_class_by_session('classes', $condition['school_id']);
        
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        // $this->data['roles'] = $this->application->get_by_a('classes',1, $condition['school_id']);
        //$this->data['school_id'] = $school_id;        
        $this->data['filter_school_id'] = $school_id; 
        if(!$school_id)
        {
            error($this->lang->line('select_school'));
        }       
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').  ' ' .  $this->lang->line('leave') .' '.  $this->lang->line('application') .' | ' . SMS);
        $this->layout->view('application/index', $this->data);
        
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Application" user interface                 
    *                    and process to store "Application" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_application_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_application_data();

                $insert_id = $this->application->insert('leave_applications', $data);
                if ($insert_id) {
                    $application = $this->application->get_single_application($insert_id);
                    if($data['role_id'] == STUDENT)//if student
                    {
                        $source_users = $this->application->get_reporting_users($data['school_id'],$application->class_teacher_id);
                    }
                    else $source_users = $this->application->get_reporting_users($data['school_id']);
                    $admin_users = $this->application->get_admins($data['school_id']);
                    if(!empty( $admin_users))
                    {
                        $source_users = array_merge($source_users,$admin_users);
                    }

                    $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id);  
                    $message_data               = array(); 
                    $message_data['school_id']          = $data['school_id'];
                    $message_data['academic_year_id']   = $application->academic_year_id;   
                    $message_data['subject']            = $this->lang->line('leave_application');  
                    $message_data['sender_id']          = $application->user_id;
                    $message_data['sender_role_id']     = $application->role_id;
                    foreach ($source_users as $obj) {
                        // student message
                        if($obj->id != ''){        
                            $sourse_user = get_user_by_role($obj->role_id, $obj->id, $application->academic_year_id);
                            $message = $this->lang->line('hi'). ' '. $sourse_user->name.',';
                            $message .= '<br/>';
                            $message .= $this->lang->line('following_is_your_leave_application');
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
                    
                    create_log('Has been added leave application');                     
                    success($this->lang->line('insert_success'));
                    redirect('leave/application/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('leave/application/add/'.$data['school_id']);
                }
            } else {
                $this->data['post'] = $_POST;
                $this->data['school_id'] = $this->input->post('school_id');
                $this->data['filter_school_id'] = $this->input->post('school_id');
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['classes'] = $this->application->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
             
        $this->data['applications'] = $this->application->get_application_list(); 
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('leave') . ' ' . $this->lang->line('application'). ' | ' . SMS);
        $this->layout->view('application/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Application" user interface                 
    *                    with populated "Application" value 
    *                    and process to update "Application" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('leave/application/index');
        }
       
        if ($_POST) {
            $this->_prepare_application_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_application_data();
                $updated = $this->application->update('leave_applications', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a leave application');                    
                    success($this->lang->line('update_success'));
                    redirect('leave/application/index/'.$this->input->post('school_id'));
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('leave/application/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['application'] = $this->application->get_single_application($this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['application'] = $this->application->get_single_application($id);
            if (!$this->data['application']) {
                redirect('application/index');
            }
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['classes'] = $this->application->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['applications'] = $this->application->get_application_list( $this->data['application']->school_id);      
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        $this->data['school_id'] = $this->data['application']->school_id;  
        $this->data['filter_school_id'] = $this->data['application']->school_id;
        $this->data['schools'] = $this->schools; 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('leave') . ' '. $this->lang->line('application') . ' | ' . SMS);
        $this->layout->view('application/index', $this->data);
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
       
       $this->data['application'] = $this->application->get_single_application($application_id);
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
    private function _prepare_application_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        
        if($this->input->post('role_id') == STUDENT){
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        }
        
        $this->form_validation->set_rules('user_id', $this->lang->line('user'), 'trim|required');
        $this->form_validation->set_rules('type_id', $this->lang->line('type').' '.$this->lang->line('type'), 'trim|required');
        $this->form_validation->set_rules('leave_from', $this->lang->line('leave').' '.$this->lang->line('from'), 'trim|required');
        $this->form_validation->set_rules('leave_to', $this->lang->line('leave').' '.$this->lang->line('to'), 'trim|required|callback_leave_to');
        $this->form_validation->set_rules('leave_reason', $this->lang->line('leave').' '.$this->lang->line('reason'), 'trim');
        $this->form_validation->set_rules('attachment', $this->lang->line('leave').' '.$this->lang->line('attachment'), 'trim|callback_attachment');
        
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
           $status = $this->input->post('status');
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
         $applications = $this->application->get_application_list($school_id,0,$limit,$start,$search_text);
        // $applications = $this->application->get_application_list($school_id);
         $totalRecords = $this->application->get_application_list_total($school_id,0,$search_text);
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
                    $action .=' <a href="'.site_url('leave/approve/update/'.$obj->id).'" title="'.$this->lang->line('approve').'" class="btn btn-success btn-xs"><i class="fa fa-check-square-o"></i> '.$this->lang->line('approve').' </a>';
                     }                            
                    if(has_permission(EDIT, 'leave', 'application')){
                        $action .=' <a href="'.site_url('leave/application/waiting/'.$obj->id).'" title="'.$this->lang->line('waiting').'" class="btn btn-info btn-xs"><i class="fa fa-spinner"></i> '.$this->lang->line('waiting').'</a>';
                     }
                    if(has_permission(EDIT, 'leave', 'decline')){ 
                        $action .=' <a href="'.site_url('leave/decline/update/'.$obj->id).'" title="'.$this->lang->line('decline').'" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> '.$this->lang->line('decline').' </a>';
                     }     
                        
                     if(has_permission(EDIT, 'leave', 'application')){
                        $action .='<a href="'.site_url('leave/application/edit/'.$obj->id).'" title="'.$this->lang->line('edit').'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('edit').' </a>';
                     } 
                     if(has_permission(VIEW, 'leave', 'application')){
                        $action .='<a  onclick="get_application_modal('.$obj->id.');"  data-toggle="modal" data-target=".bs-application-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
                     } 
                     if(has_permission(DELETE, 'leave', 'application')){
                        $action .='<a href="'.site_url('leave/application/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').'</a>';
                     } 
                   $row_data[] = $obj->session_year;;
                   $row_data[] = $obj->role_name;
                   $row_data[] = $obj->type;
                   $row_data[] = $user_name;
                   $row_data[] = $status;
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
        }
        else{
            $existing_leave = $this->application->get_leaves_between($this->input->post('school_id'), $this->input->post('user_id'), $this->input->post('role_id') ,$leave_from, $leave_to);
          
            if (!empty($existing_leave) ) {
                $this->form_validation->set_message('leave_to', $this->lang->line('already_applied_leave'));
                return FALSE;
            }
            else {
                return TRUE;
            }      
        }        
    }

    /*****************Function attachment**********************************
    * @type            : Function
    * @function name   : attachment
    * @description     : Process/check attachment document validation                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function attachment() {

        if ($this->input->post('id')) {

            if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
                $name = $_FILES['attachment']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('attachment', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        } else {

            if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {                
           
                $name = $_FILES['attachment']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('attachment', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        }
    }

    
    
    /*****************Function _get_posted_application_data**********************************
    * @type            : Function
    * @function name   : _get_posted_application_data
    * @description     : Prepare "Application" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_application_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'role_id';
        $items[] = 'user_id';
        $items[] = 'class_id';
        $items[] = 'type_id';
        $items[] = 'leave_reason';

        $data = elements($items, $_POST);
        
        $data['leave_date'] = date('Y-m-d', strtotime($this->input->post('leave_date')));
        $data['leave_from'] = date('Y-m-d', strtotime($this->input->post('leave_from')));
        $data['leave_to']   = date('Y-m-d', strtotime($this->input->post('leave_to')));
        
        $start = strtotime($data['leave_from']);
        $end   = strtotime($data['leave_to']);
        $days = ceil(abs($end - $start) / 86400);
        $data['leave_day'] = $days+1;
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();            
            
        } else {
            
            $data['leave_status'] = 0;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            
            $school = $this->application->get_school_by_id($data['school_id']);            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('leave/application/index');
            }            
            $data['academic_year_id'] = $school->academic_year_id;
            
        }
        

        if (isset($_FILES['attachment']['name'])) {
            $data['attachment'] = $this->_upload_attachment();
        }

        return $data;
    }

        
    /*****************Function _upload_attachment**********************************
    * @type            : Function
    * @function name   : _upload_attachment
    * @description     : Process to to upload attachment in the server
    *                    and return image name                   
    *                       
    * @param           : null
    * @return          : $return_image string value 
    * ********************************************************** */
    private function _upload_attachment() {

        $prev_attachment = $this->input->post('prev_attachment');
        $attachment = $_FILES['attachment']['name'];
        $return_attachment = '';
        if ($attachment != "") {

                $destination = 'assets/uploads/leave/';

                $file_type = explode(".", $attachment);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $attachment_path = 'leave-attachment-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['attachment']['tmp_name'], $destination . $attachment_path);
                if(is_image( $destination . $attachment_path))
                {
                    if($converted_file = webpConverter($destination . $attachment_path))
                    {
                        $attachment_path = get_filename($converted_file);
                    }
                }
                // need to unlink previous image
                if ($prev_attachment != "") {
                    if (file_exists($destination . $prev_attachment)) {
                        @unlink($destination . $prev_attachment);
                    }
                }

                $return_attachment = $attachment_path;
          
        } else {
            $return_attachment = $prev_attachment;
        }

        return $return_attachment;
    }

     
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Application" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('leave/application/index');
        }
        
        $application = $this->application->get_single_application($id);
        
        if ($this->application->delete('leave_applications', array('id' => $id))) {
            
             // delete teacher resume and image
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/leave/' . $application->attachment)) {
                @unlink($destination . '/leave/' . $application->attachment);
            }            
            
            create_log('Has been deleted a leave application : '.$application->type);
            success($this->lang->line('delete_success'));
            redirect('leave/application/index/'.$application->school_id);
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('leave/application/index/');
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
            redirect('leave/application/index');     
        }
        
        $leave = $this->application->get_single('leave_applications', array('id'=>$application_id));               
        $status = $this->application->update('leave_applications', array('leave_status'=>1, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$application_id));               
        
        if($status){
            $application = $this->application->get_single_application($application_id);
            $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id); 
            $message_data               = array(); 
            $message_data['school_id']          = $application->school_id;
            $message_data['academic_year_id']   = $application->academic_year_id;   
            $message_data['subject']            = $this->lang->line('leave_application_added_to_waiting');  
            $message_data['sender_id']          = logged_in_user_id();
            $message_data['sender_role_id']     = $this->session->userdata('role_id');

            $message = $this->lang->line('hi'). ' '. $user->name.',';
            $message .= '<br/>';
            $message .= $this->lang->line('your_leave_application_added_to_waiting');
            $message .= '<br/><br/>';
            $message .= $this->lang->line('leave_type'). ': ' . $application->type;
            $message .= '<br/>';
            $message .= $this->lang->line('leave_from'). ': ' . $application->leave_from;
            $message .= '<br/>';
            $message .= $this->lang->line('leave_to'). ': ' . $application->leave_to;
            $message .= '<br/>';
            $message .= $this->lang->line('leave_reason'). ': ' . $application->leave_reason;
            $message .= '<br/>';
            $message .= $this->lang->line('note'). ': ' .  $application->leave_note;
            $message .= '<br/>';
            $message .= '<br/><br/>';

            $message .= $this->lang->line('thank_you').'<br/>';
            $message_data['body'] = $message;
            $message_data['receiver_id'] = $application->user_id;
            $message_data['receiver_role_id'] = $application->role_id;
            $this->message->send_message($message_data);   
                    if($application->role_id == STUDENT)//if student
                    {
                        $source_users = $this->application->get_reporting_users($application->school_id,$application->class_teacher_id);
                    }
                    else $source_users = $this->application->get_reporting_users($application->school_id);
                    $admin_users = $this->application->get_admins($application->school_id);
                    if(!empty( $admin_users))
                    {
                        $source_users = array_merge($source_users,$admin_users);
                    }
                    

                    $user = get_user_by_role($application->role_id, $application->user_id, $application->academic_year_id);  
                    $message_data               = array(); 
                    $message_data['school_id']          = $application->school_id;
                    $message_data['academic_year_id']   = $application->academic_year_id;   
                    $message_data['subject']            = $this->lang->line('leave_application_added_to_waiting');  
                    $message_data['sender_id']          = $application->user_id;
                    $message_data['sender_role_id']     = $application->role_id;
                    foreach ($source_users as $obj) {
                        // student message
                        if($obj->id != ''){        
                            $sourse_user = get_user_by_role($obj->role_id, $obj->id, $application->academic_year_id);
                            $message = $this->lang->line('hi'). ' '. $sourse_user->name.',';
                            $message .= '<br/>';
                            $message .= $this->lang->line('your_leave_application_added_to_waiting');
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
            success($this->lang->line('update_success'));
            redirect('leave/application/index/'.$leave->school_id);  
        }else{
            error($this->lang->line('update_failed'));
            redirect('leave/application/index/'.$leave->school_id);      
        }
    }

}
