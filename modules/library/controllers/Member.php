<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Member.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Member
 * @description     : Manage library member, from the student whose are library member.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Member extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Book_Model', 'book', true);        
    }

        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Library Member List" user interface                 
    *                        
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {
		check_permission(VIEW);
		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
           $school_id = $this->session->userdata('school_id');  
        }
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $school = $this->book->get_school_by_id($school_id);
       // $this->data['members'] = $this->book->get_library_member_list($is_library_member = 1, $school_id, $school->academic_year_id); 
		        

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('library') . ' ' . $this->lang->line('member') . ' | ' . SMS);		
        $this->layout->view('member/member', $this->data);
    }

        /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_members_list(){		
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
        $school = $this->book->get_school_by_id($school_id);
         $totalRecords = $this->book->get_library_member_list_total($is_library_member = 1, $school_id,@$school->academic_year_id,$search_text);
         
        $members = $this->book->get_library_member_list($is_library_member = 1, $school_id, @$school->academic_year_id,$start,$limit ,$search_text);
       }
       else
       {
            $totalRecords =0;
            $members =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($members) && !empty($members)){
               foreach($members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $action = "";
                    if(has_permission(DELETE, 'library', 'member')){
                        $action  = ' <a href="'.site_url('library/member/delete/'.$obj->lm_id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').'</a>';
                    }
                    $row_data[] = '<input type="checkbox" class="library_member" name="members[]" value="'.$obj->lm_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                    $row_data[] = $obj->admission_no;
                    $row_data[] = $member_photo;
                   $row_data[] = $obj->custom_member_id;
                   $row_data[] = $obj->name;
                   $row_data[] = $obj->father_name;
                   
                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->section;
                   $row_data[] = $obj->roll_no;
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
      /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_non_members_list(){		
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
        $school = $this->book->get_school_by_id($school_id);

         $totalRecords = $this->book->get_library_member_list_total($is_library_member = 0, $school_id,@$school->academic_year_id,$search_text);
         
        $members = $this->book->get_library_member_list($is_library_member = 0, $school_id, @$school->academic_year_id,$start,$limit,$search_text);
       }
       else
       {
            $totalRecords =0;
            $members =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($members) && !empty($members)){
               foreach($members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $action = "";
                    if(has_permission(ADD, 'library', 'member')){
                       
                        $action  = ' <a href="javascript:void(0);" id="'.$obj->user_id.'" class="btn btn-success btn-xs fn_add_to_library"><i class="fa fa-reply"></i>'.$this->lang->line('add').' '.$this->lang->line('library').' '.$this->lang->line('member').'</a>';
                     
                    }
                    $row_data[] ='<input type="checkbox" class="library_non_member" name="members[]" value="'.$obj->user_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                    
                 $row_data[] = $member_photo;
                 $row_data[] = $obj->admission_no;

                   $row_data[] = $obj->name;
                   $row_data[] = $obj->father_name;

                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->section;
                   $row_data[] = $obj->roll_no;
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
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Library Member" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add($school_id = null) {

        check_permission(ADD);

        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
           $school_id = $this->session->userdata('school_id');  
        }
        $school = $this->book->get_school_by_id($school_id);
        
        //$this->data['non_members'] = $this->book->get_library_member_list($is_library_member = 0, $school_id, @$school->academic_year_id);       
        
        $this->data['non_list'] = TRUE;
        $this->layout->title($this->lang->line('library') . ' ' . $this->lang->line('non_member') . ' | ' . SMS);
        $this->layout->view('member/non_member', $this->data);
        
    }

        
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Library member" data from library member list                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('library/member/index');
        }
        
        $member = $this->book->get_single('library_members', array('id' => $id));
        if ($this->book->delete('library_members', array('id' => $id))) {
            $this->book->update('students', array('is_library_member' => 0), array('user_id' => $member->user_id));
            
            $student = $this->book->get_single('students', array('user_id' => $member->user_id));
            create_log('Has been deleted a Library Member : '.$student->name);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('library/member/index');
    }


    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : Process to add/store "Library Member" into database                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function add_to_library() {

        $user_id = $this->input->post('user_id');

        if ($user_id) {
            
            $user   = $this->book->get_single('users', array('id' => $user_id));
            $member = $this->book->get_single('library_members', array('user_id' => $user_id));
            
            if (empty($member)) {

                $data['school_id'] = $user->school_id;
                $data['user_id'] = $user_id;
                $data['custom_member_id'] = $this->book->get_custom_id('library_members', 'LM');
                $data['status'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = logged_in_user_id();

                $insert_id = $this->book->insert('library_members', $data);
                $this->book->update('students', array('is_library_member' => 1), array('user_id' => $user_id));
                echo TRUE;
            } else {
                echo FALSE;
            }
        } else {
            echo FALSE;
        }
    }
    public function add_to_library_bulk() {
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
        $response =  array();
        $updated_members = array();
        $error_members = array();

        if( !(has_permission(ADD, 'library', 'member'))){
            $response['success'] =  false;
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
        }
        $user_ids = $this->input->post('user_ids');
        $response['success'] =  true;
        if (!empty($user_ids)) {
            foreach($user_ids  as $user_id)
            {
                $user   = $this->book->get_single('users', array('id' => $user_id));
                $member = $this->book->get_single('library_members', array('user_id' => $user_id));
                
                if (empty($member)) {
    
                    $data['school_id'] = $user->school_id;
                    $data['user_id'] = $user_id;
                    $data['custom_member_id'] = $this->book->get_custom_id('library_members', 'LM');
                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
    
                    $insert_id = $this->book->insert('library_members', $data);
                    $this->book->update('students', array('is_library_member' => 1), array('user_id' => $user_id));
                    $updated_members[] = $user_id;
                    
                } else {
                    $error_members[] = $user_id;
                }
            }
           
        } else {
            $response['success'] =  false;
        }
        $response['updated_members'] =  $updated_members;
        $response['error_members'] =  $error_members;
        echo json_encode($response);
        die();
    }
    public function remove_bulk() {
        $response =  array();
        $updated_members = array();
        $error_members = array();
        $member_ids = $this->input->post('member_ids');        
        if( !(has_permission(DELETE, 'library', 'member'))){
            $response['success'] =  false;
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
        }
        $response['success'] =  true;
        if (!empty($member_ids)) {
            foreach($member_ids  as $member_id)
            {
                $response['true'] =  true;
                $member = $this->book->get_single('library_members', array('id' => $member_id));
                if ($this->book->delete('library_members', array('id' => $member_id))) {
                    $this->book->update('students', array('is_library_member' => 0), array('user_id' => $member->user_id));
                    
                    $student = $this->book->get_single('students', array('user_id' => $member->user_id));
                    create_log('Has been deleted a Library Member : '.$student->name);
                    
                    $updated_members[] = $member_id;
                } else {
                    $error_members[] = $member_id;
                }
            }
           
        } else {
            $response['success'] =  false;
        }
        $response['updated_members'] =  $updated_members;
        $response['error_members'] =  $error_members;
        echo json_encode($response);
        die();
    }

}
