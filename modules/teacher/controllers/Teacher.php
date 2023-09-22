<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Teacher.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Teacher
 * @description     : Manage teacers information of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Teacher extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Teacher_Model', 'teacher', true);
			$this->load->model('Payroll/Payscalecategory_Model', 'grade', true); 
        
    }

        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Teacher List" user interface                 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        check_permission(VIEW);
        
        
      //  $this->data['teachers'] = $this->teacher->get_teacher_list($school_id);
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
			$school_id= $this->session->userdata('school_id'); 
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->data['teacher_code'] = $this->teacher->generate_teacher_code($school_id);
        $this->layout->title($this->lang->line('manage_teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }
	public function get_list(){		
		 // for super admin 
        $school_id = '';
		$start=null;
		$limit=null;
		$search_text='';
        if($_POST)
        {            
            $school_id = $this->input->post('school_id');            
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
			$draw = $this->input->post('draw');	
			if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
				$search_text=$_POST['search']['value'];
			}
        }		        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
			$school_id= $this->session->userdata('school_id'); 
                       
        }
                             
                    
        $data = array();
		$totalRecords=0;
        if($school_id){		   
		  $totalRecords = $this->teacher->get_teacher_list_total( $school_id,$search_text);
           $teachers = $this->teacher->get_teacher_list($school_id, $start,$limit,$search_text);
		$count = 1; 
		

		if(isset($teachers) && !empty($teachers)){			
				foreach($teachers as $obj){
					$action='';
                                                if(has_permission(EDIT, 'teacher', 'teacher')){ 
                                                    $action .= '<a href="'.site_url('teacher/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>'.$this->lang->line('edit').'</a><br/>';
                                                } 



                                                if(has_permission(VIEW, 'teacher', 'teacher')){ 
                                                    $action .= '<a  onclick="get_teacher_modal('.$obj->id.');"  data-toggle="modal" data-target=".bs-teacher-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a><br/>';
                                                } 
                                               

                                                 if(has_permission(EDIT, 'teacher', 'teacher')){ 
                                                   $action .= '<a onclick="alumni_teacher('.$obj->id.')"  class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-teacher-modal-lg"><i class="fa fa-trash-o"></i> Alumi </a>';
                                                } 


                                              
                                                if(has_permission(DELETE, 'teacher', 'teacher')){
                                                    $action .= '<a href="'.site_url('teacher/delete/'.$obj->id).'" onclick="javascript: return confirm('.$this->lang->line('confirm_alert').');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').' </a>';
                                                } 
					 
if($obj->photo != ''){ 
$photo='<img src="'.UPLOAD_PATH.'/teacher-photo/'.$obj->photo.'" alt="" width="70" />'; 
                                                }else{ 
                             $photo= '<img src="'.IMG_URL.'/default-user.png" alt="" width="70" />'; 
                                          } 
					 $data[] = array( 
					 
						  0=>$count,
						  1=>$obj->school_name,
						 // 2=>$photo,
						  2=>$obj->name,						  
						  3=>$obj->responsibility,
						  4=>$obj->phone,
						  5=>$obj->email,
						  6=>$obj->salary_type,
						  7=>$obj->basic_salary,
						  8=>$obj->teacher_code,
						  9=>$obj->father_name,
						  10=>$obj->present_address,
						  11=>$obj->qualification,
						  12=>$obj->gender,
						  13=>$obj->joining_date,
						  14=>$action
					   );
					   $count++;
				}
			
		}			
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
	public function alumni($school_id = null) {

        check_permission(VIEW);

        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');      
            $school_id  = $this->session->userdata('school_id');      
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        if(!$school_id)
        {
            error($this->lang->line('select_school'));
        }
        else
        {
            $this->data['teachers'] = $this->teacher->get_alumniteacher_list($school_id);
        }
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_teacher') . ' | ' . SMS);
        $this->layout->view('teacher/alumni', $this->data);
    }
    
    public function teacher_modal()
    {
        $data['teacher_id'] = $this->input->post('teacher_id');
        if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
            $data['schools'] = $this->teacher->get_schools();
        }
        echo $this->load->view('teacher/teacher_modal', $data);
    }
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Teacer" user interface                 
    *                    and process to store "Teacer" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_teacher_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_teacher_data();

                $insert_id = $this->teacher->insert('teachers', $data);
                if ($insert_id) {
					if(!empty($_POST['payscalecategory_id'])){
						foreach($_POST['payscalecategory_id'] as $grade_id){
							$in_arr=array();
							$in_arr['user_id']=$data['user_id'];
							$in_arr['payscalecategory_id']=$grade_id;
							$e_id = $this->teacher->insert('user_payscalecategories', $in_arr);
						}
					}
                    success($this->lang->line('insert_success'));
                    redirect('teacher/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('teacher/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['teachers'] = $this->teacher->get_teacher_list();
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['teacher_code'] = $this->teacher->generate_teacher_code($school_id);
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Teacer" user interface                 
    *                    with populate "Teacher" data/value 
    *                    and process to update "Teacher" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_teacher_validation();
            if ($this->form_validation->run() === TRUE) 
            {
                if (isset($_POST['role_id']) && $_POST['role_id'] && $_POST['role_id'] != TEACHER)
                {
					$data       = $this->_get_posted_employee_data();
					$this->teacher->delete('teachers', array('id' => $id));
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
                    $data['status'] = 1;
					$updated    = $this->teacher->insert('employees', $data);
                  		
				}
				else 
                {
					$data       = $this->_get_posted_teacher_data();				
                    $updated    = $this->teacher->update('teachers', $data, array('id' => $this->input->post('id')));
				}
                if ($updated)
                {
					$this->teacher->delete('user_payscalecategories', array('user_id' =>  $_POST['user_id']));
					if(!empty($_POST['payscalecategory_id'])){
						foreach($_POST['payscalecategory_id'] as $grade_id){
							$in_arr=array();
							$in_arr['user_id']=$_POST['user_id'];
							$in_arr['payscalecategory_id']=$grade_id;
							$e_id = $this->teacher->insert('user_payscalecategories', $in_arr);
						}
					}
                    success($this->lang->line('update_success'));
                    redirect('teacher/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('teacher/edit/' . $this->input->post('id'));
                }
            } 
            else 
            {
                
                $this->data['teacher'] = $this->teacher->get_single_teacher($this->input->post('id'));
            }
        }

        if ($id) {
            $this->data['teacher'] = $this->teacher->get_single_teacher($id);

            if (!$this->data['teacher']) {
                redirect('teacher/index');
            }
        }		

        $this->data['teachers'] = $this->teacher->get_teacher_list($this->data['teacher']->school_id);
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
         
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['teacher']->school_id;
        $this->data['filter_school_id'] = $this->data['teacher']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }

        
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific Teacher data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {

        check_permission(VIEW);

        $this->data['teachers'] = $this->teacher->get_teacher_list();
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['teacher'] = $this->teacher->get_single_teacher($id);

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }

    
    public function unalumni($id = null) {

        check_permission(EDIT);
        $this->input->post('id') ;
        $this->data['alumni']=0;
        $this->data['leaving_reason']=null;
        $this->data['leaving_date']=null;
        $this->data['modified_at']= date("Y-m-d H:i:s");
        $this->data['modified_by']=  logged_in_user_id();
        $updated = $this->teacher->update('teachers',$this->data, array('id' => $id));

	    redirect('teacher');
        
    }
        
     /*****************Function get_single_teacher**********************************
     * @type            : Function
     * @function name   : get_single_teacher
     * @description     : "Load single teacher information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_teacher(){
        
       $teacher_id = $this->input->post('teacher_id');
       
       $this->data['teacher'] = $this->teacher->get_single_teacher($teacher_id);
	   $cats = $this->grade->get_payscale_data_by_user($this->data['teacher']->user_id);
	   $cat_name=array();
	   foreach($cats as $c){
		   $cat_name[]=$c->name;
	   }
		$this->data['payscale_categories'] = implode(", ",$cat_name);
       echo $this->load->view('teacher/get-single-teacher', $this->data);
    }
    
        
    /*****************Function _prepare_teacher_validation**********************************
    * @type            : Function
    * @function name   : _prepare_teacher_validation
    * @description     : Process "Teacher" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_teacher_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        if (!$this->input->post('id')) {   
            $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
        }
         $this->form_validation->set_rules('teacher_code', $this->lang->line('teacher_code'), 'trim|callback_teachercode');
        
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email');
        if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        }
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');

        $this->form_validation->set_rules('responsibility', $this->lang->line('responsibility'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required');
        $this->form_validation->set_rules('present_address', $this->lang->line('present') . ' ' . $this->lang->line('address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent') . ' ' . $this->lang->line('address'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood_group'), 'trim');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('joining_date', $this->lang->line('join_date'), 'trim|required');
        //$this->form_validation->set_rules('salary_grade_id', $this->lang->line('salary_grade'), 'trim|required');
        $this->form_validation->set_rules('salary_type', $this->lang->line('salary_type'), 'trim|required');
        $this->form_validation->set_rules('facebook_url', $this->lang->line('facebook_url'), 'trim');
        $this->form_validation->set_rules('linkedin_url', $this->lang->line('linkedin_url'), 'trim');
        $this->form_validation->set_rules('google_plus_url', $this->lang->line('google_plus_url'), 'trim');
        $this->form_validation->set_rules('instagram_url', $this->lang->line('instagram_url'), 'trim');
        $this->form_validation->set_rules('pinterest_url', $this->lang->line('pinterest_url'), 'trim');
        $this->form_validation->set_rules('twitter_url', $this->lang->line('twitter_url'), 'trim');
        $this->form_validation->set_rules('youtube_url', $this->lang->line('youtube_url'), 'trim');
        $this->form_validation->set_rules('other_info', $this->lang->line('other_info'), 'trim');
    }

        
                    
    /*****************Function username**********************************
    * @type            : Function
    * @function name   : username
    * @description     : Unique check for "Teacher username" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function username() {
        if ($this->input->post('id') == '') {
            $username = $this->teacher->duplicate_check($this->input->post('username'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $username = $this->teacher->duplicate_check($this->input->post('username'), $this->input->post('id'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    } 
 public function teachercode() {
        if ($this->input->post('id') == '') {
            $emp = $this->teacher->duplicate_check_teacher_code($this->input->post('teacher_code'));
            if ($emp) {
                $this->form_validation->set_message('teacher_code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $emp = $this->teacher->duplicate_check_teacher_code($this->input->post('teacher_code'), $this->input->post('id'));
            if ($emp) {
                $this->form_validation->set_message('teacher_code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }	
    /*****************Function _get_posted_employee_data**********************************
    * @type            : Function
    * @function name   : _get_posted_employee_data
    * @description     : Prepare "Employee" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */ 
    private function _get_posted_employee_data()
    {

        $items = array();
        $items[] = 'school_id';
        //$items[] = 'designation_id';
        $items[] = 'national_id';
        $items[] = 'name';
        if($this->input->post('id') && $this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
		    $items[] = 'teacher_code';
        }
        
        $items[] = 'email';
        $items[] = 'phone';
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        $items[] = 'gender';
        $items[] = 'blood_group';
        $items[] = 'religion';
        $items[] = 'other_info';
        //$items[] = 'salary_grade_id';
		$items[] = 'basic_salary';
        $items[] = 'salary_type';
        $items[] = 'facebook_url';
        $items[] = 'linkedin_url';
        $items[] = 'google_plus_url';
        $items[] = 'instagram_url';
        $items[] = 'pinterest_url';
        $items[] = 'twitter_url';
        $items[] = 'youtube_url';   
        $items[] = 'esi';      
        $items[] = 'is_view_on_web';      
		$items[] = 'father_name';      
		$items[] = 'alternate_name';      
		$items[] = 'reservation_category';      
		$items[] = 'qualification';      
		$items[] = 'adhar_no';      
		$items[] = 'pf_no';      
		$items[] = 'uan_no';   
        
   		$items[] = 'pacific_ability'; 
		$items[] = 'rtet_qualified'; 
		$items[] = 'secondary_roll_no'; 
		$items[] = 'secondary_year'; 
		$items[] = 'current_subject'; 
       // $items[] = 'employment_type_id';
      
        $data = elements($items, $_POST);  
        $data['employee_code'] =  $data['teacher_code'];
        unset($data['teacher_code']);
        $qualification='';
		if(isset($_POST['qualification']) && !empty($_POST['qualification'])){
			$qualification=implode(",",$_POST['qualification']);
		}
		$data['qualification']=$qualification;
    
        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));

        if ($this->input->post('id')) {
            if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
                $data['modified_at'] = date('Y-m-d H:i:s');
                $data['modified_by'] = logged_in_user_id(); 
                $this->teacher->update('users', array('username' => $this->input->post('username'),'role_id'=> $this->input->post('role_id'),'modified_at'=>date('Y-m-d H:i:s')), array('id'=> $this->input->post('user_id')));
            }
            $data['user_id']=$this->input->post('user_id');
            
        } else {
            $data['employee_code']=$this->teacher->generate_employee_code($data['school_id']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            // create user 
            $data['user_id'] = $this->teacher->create_user($data['employee_code']);
        }

        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        if ($_FILES['resume']['name']) {
            $data['resume'] = $this->_upload_resume();
        }
        return $data;
    }
    /*****************Function _get_posted_teacher_data**********************************
    * @type            : Function
    * @function name   : _get_posted_teacher_data
    * @description     : Prepare "Teacher" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_teacher_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'name';
     
       if($this->input->post('id') && $this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 )
       {
            $items[] = 'teacher_code';
       }
        $items[] = 'email';
       // $items[] = 'national_id';
        $items[] = 'responsibility';
        $items[] = 'phone';
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        $items[] = 'gender';
        $items[] = 'blood_group';
        $items[] = 'religion';
        $items[] = 'other_info';
        $items[] = 'basic_salary';
        $items[] = 'salary_type';
        $items[] = 'facebook_url';
        $items[] = 'linkedin_url';
        $items[] = 'google_plus_url';
        $items[] = 'instagram_url';
        $items[] = 'pinterest_url';
        $items[] = 'twitter_url';
        $items[] = 'youtube_url';
        $items[] = 'esi';
        $items[] = 'pf_no';
        $items[] = 'is_view_on_web';
		$items[] = 'father_name';
		$items[] = 'alternate_name';
		$items[] = 'reservation_category';
		$items[] = 'adhar_no';  
		//$items[] = 'qualification';      		
		$items[] = 'pacific_ability'; 
		$items[] = 'rtet_qualified'; 
		$items[] = 'secondary_roll_no'; 
		$items[] = 'secondary_year'; 
		$items[] = 'current_subject'; 
        
        $data = elements($items, $_POST);
       
		$qualification='';
		if(isset($_POST['qualification']) && !empty($_POST['qualification'])){
			$qualification=implode(",",$_POST['qualification']);
		}
		$data['qualification'] = $qualification;
        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
			//$data['user_id']=$this->input->post('user_id');
        } else {
            $data['teacher_code']=$this->teacher->generate_teacher_code($data['school_id']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            // create user 
            $data['user_id'] = $this->teacher->create_user($data['teacher_code']);
            
        }

        if($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        if($_FILES['resume']['name']) {
            $data['resume'] = $this->_upload_resume();
        }  
      
        return $data;
    }

       
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : process to upload teacher profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */
    private function _upload_photo() {

        $prev_photo = $this->input->post('prev_photo');
        $photo = $_FILES['photo']['name'];
        $photo_type = $_FILES['photo']['type'];
        $return_photo = '';
        if ($photo != "") {
            if ($photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                    $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                    $photo_type == 'image/x-png' || $photo_type == 'image/gif') {

                $destination = 'assets/uploads/teacher-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);
                if($converted_file = webpConverter($destination . $photo_path,null, 415,515))
                {
                    $photo_path = get_filename($converted_file);
                }
                // need to unlink previous photo
                if ($prev_photo != "") {
                    if (file_exists($destination . $prev_photo)) {
                        @unlink($destination . $prev_photo);
                    }
                }

                $return_photo = $photo_path;
            }
        } else {
            $return_photo = $prev_photo;
        }

        return $return_photo;
    }
    
           
    /*****************Function _upload_resume**********************************
    * @type            : Function
    * @function name   : _upload_resume
    * @description     : process to upload teacher profile resume in the server                  
    *                     and return resume file name  
    * @param           : null
    * @return          : $return_resume string value 
    * ********************************************************** */
    private function _upload_resume() {
        $prev_resume = $this->input->post('prev_resume');
        $resume = $_FILES['resume']['name'];
        $resume_type = $_FILES['resume']['type'];
        $return_resume = '';

        if ($resume != "") {
            if ($resume_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                    $resume_type == 'application/msword' || $resume_type == 'text/plain' ||
                    $resume_type == 'application/vnd.ms-office' || $resume_type == 'application/pdf') {

                $destination = 'assets/uploads/teacher-resume/';

                $file_type = explode(".", $resume);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $resume_path = 'resume-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['resume']['tmp_name'], $destination . $resume_path);

                // need to unlink previous photo
                if ($prev_resume != "") {
                    if (file_exists($destination . $prev_resume)) {
                        @unlink($destination . $prev_resume);
                    }
                }

                $return_resume = $resume_path;
            }
        } else {
            $return_resume = $prev_resume;
        }

        return $return_resume;
    }

    
        
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Teacher" data from database                  
    *                    also unlink teacher profile photo & resume from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('teacher');       
        }
        
        $teacher = $this->teacher->get_single('teachers', array('id' => $id));
        if (!empty($teacher)) {

            // delete teacher data
            $this->teacher->delete('teachers', array('id' => $id));
            // delete teacher login data
            $this->teacher->delete('users', array('id' => $teacher->user_id));

            // delete teacher resume and photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/teacher-resume/' . $teacher->resume)) {
                @unlink($destination . '/teacher-resume/' . $teacher->resume);
            }
            if (file_exists($destination . '/teacher-photo/' . $teacher->photo)) {
                @unlink($destination . '/teacher-photo/' . $teacher->photo);
            }

            success($this->lang->line('delete_success'));
            redirect('teacher/index/'.$teacher->school_id);
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('teacher/index');
    }


    // Ak
    public function alumni_teacher() {

        check_permission(EDIT);
        $teacher_id = $this->input->post('teacher_id') ;
        $reason      = $this->input->post('drop-reason') ;
        $leaving_date   = $this->input->post('drop-date') ;
        $transfer_school_id   = $this->input->post('transfer_school_id') ;
        if(!$teacher_id  || !$reason || !$leaving_date)
        {
            redirect('teacher/');

        }
        if($reason == "Transfer" )
        {
            $teacher = $this->teacher->get_single('teachers', array('id' => $teacher_id));
            if(!empty($teacher))
            {
                $user = (array)$this->teacher->get_single('users', array('id' => $teacher->user_id));
                $teacher_code = $this->teacher->generate_teacher_code($transfer_school_id);
               unset($user['id']);
               $user['school_id']  = $transfer_school_id;
               $user['username']  = $teacher_code;
               $user['created_at']  = date("Y-m-d H:i:s");
               $user['created_by']  = logged_in_user_id();
               $user_id = $this->teacher->insert('users', $user);
               $teacher_data = (array)$teacher;
               unset($teacher_data['id']);
               $teacher_data['school_id']  = $transfer_school_id;
               $teacher_data['user_id']  = $user_id;
               $teacher_data['teacher_code']  =  $teacher_code;
               $teacher_data['created_at']  = date("Y-m-d H:i:s");
               $teacher_data['created_by']  = logged_in_user_id();
               $this->teacher->insert('teachers', $teacher_data);
            }
        }
       
            $this->data['alumni']=1;
            $this->data['leaving_reason']= $reason;
            $this->data['leaving_date']= $leaving_date;
        
        $this->data['modified_at']= date("Y-m-d H:i:s");
        $this->data['modified_by']=  logged_in_user_id();
        $updated = $this->teacher->update('teachers',$this->data, array('id' => $teacher_id));
      
        redirect('teacher/alumni', 'refresh');

        
    }
    public function alumni_employee($id){


 
        $logme['alumni'] = '1';


      if ($this->teacher->update_data($logme, 'id', $id , 'teachers')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Updated Successfully"
              )
            );
            redirect('teacher/alumni', 'refresh');
    }


    }



    // Ak

}
