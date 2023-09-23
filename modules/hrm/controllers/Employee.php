<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Employee.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Employee
 * @description     : Manage employee information of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Employee extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Employee_Model', 'employee', true);  
		$this->load->model('EmploymentTypes_Model', 'employment_types', true);  
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            	
		$this->load->model('Teacher/Teacher_Model', 'teacher', true);            	
       
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Employeet List" user interface                 
    *                      
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        check_permission(VIEW);
        
        $teachers = $this->teacher->get_teacher_list($school_id);              
        $employees = $this->employee->get_employee_list($school_id);
		$res=array_merge($employees,$teachers);
		$this->data['employees']=$res;
        $this->data['roles'] = $this->employee->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
			$school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id;
            //$this->data['designations'] = $this->employee->get_list('designations', $condition, '', '', '', 'id', 'ASC');
			//$this->data['employment_types'] = $this->employment_types->get_list_by_school($school_id);
           // $this->data['grades'] = $this->employee->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
            
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;        
        if($school_id)
        {
            $this->data['employee_code'] = $this->employee->generate_employee_code($school_id);        
        }
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_employee') . ' | ' . SMS);
        $this->layout->view('employee/index', $this->data);
    }
	public function get_list(){		
       
		 // for super admin 
        $school_id = '';
		$start=null;
		$limit=null;
		$search_text='';
        if($_POST){            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');  
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
			$draw = $this->input->post('draw');	
			if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
				$search_text=$_POST['search']['value'];
			}
        }		        
        if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $school_id = $this->session->userdata('school_id');
        }
                
        
        $school = $this->employee->get_school_by_id($school_id);
                    
        //echo "<pre>";print_r($_POST);die;
       if($school_id){
        $employees = $this->employee->get_employee_teachers_list($school_id,$school->academic_year_id,$start,$limit,$search_text);
        $totalRecords = $this->employee->get_employee_teachers_list_total($school_id,$school->academic_year_id,$search_text);
        // var_dump(        $employees,$totalRecords);
        // die();
       }
       else
       {
        $employees = array();
        $totalRecords = 0; 
       }
		$count = 1; 
		$data = array();

		if(isset($employees) && !empty($employees)){
			if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
				foreach($employees as $obj){
					$action='';


                    if($obj->role_id ==5){ 
                        if(has_permission(EDIT, 'teacher', 'teacher')){ 
                            $action .= '<a href="'.site_url('teacher/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>'.$this->lang->line("edit").'</a><br/>';
                        }  
                        if(has_permission(VIEW, 'teacher', 'teacher')){ 
                            $action .= '<a   onclick="get_teacher_modal('.$obj->id.')"  data-toggle="modal" data-target=".bs-teacher-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line("view").' </a><br/>';
                        } 
                         if(has_permission(EDIT, 'teacher', 'teacher')){ 
                            $action .= '<a onclick="alumni_teacher('.$obj->id.')"  class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-teacher-modal-lg"><i class="fa fa-trash-o"></i> Alumi </a>';
                        }  
                        if(has_permission(DELETE, 'teacher', 'teacher')){ 
                            $action .= '<a href="'.site_url('teacher/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line("confirm_alert").'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line("delete").' </a>';
                        }  
                        $role_name = $this->lang->line('teacher');
                        } 
                    else { 
                        $role_name = $this->lang->line('employee');
                        if(has_permission(EDIT, 'hrm', 'employee')){  
                            $action .=  '<a href="'.site_url('hrm/employee/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>'.$this->lang->line('edit').'</a><br/>';
                        }  
                        if(has_permission(VIEW, 'hrm', 'employee')){ 
                            $action .= '<a  onclick="get_employee_modal('.$obj->id.');"  data-toggle="modal" data-target=".bs-employee-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line('view').'</a><br/>';
                        } 
                        if(has_permission(EDIT, 'hrm', 'employee')){  
                            if($obj->id != 1){  
                                $action .= '<button type="button"  onclick="alumni_employee('.$obj->id.')" "  class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-employee-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('alumni').' </button>';
                            } 
                        } 
                                
                        if(has_permission(DELETE, 'hrm', 'employee')){  
                            if($obj->id != 1){  
                                $action .= '<a href="'.site_url('hrm/employee/delete/'.$obj->id).' " onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').'</a>';
                            } 
                        } 
                    } 
                    if($obj->photo != ''){ 
                        if($obj->role_id == 5){ 
                       $employee_photo =' <img src="'.UPLOAD_PATH.'/teacher-photo/'.$obj->photo.'" alt="" width="70" /> ';
                        } else { 
                        $employee_photo ='<img src="'.UPLOAD_PATH.'/employee-photo/'.$obj->photo.'" alt="" width="70" /> ';
                         }
                    
                     }else{ 
                        $employee_photo ='<img src="'.IMG_URL.'/default-user.png" alt="" width="70" /> ';
                     } 
					$row_data = array();
                    $row_data[] = $count;
                    if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ 
                        $row_data[] = "$obj->school_name"; 
                     }
                     //$row_data[] =  $employee_photo;
                     $row_data[] = $obj->name;
                     $row_data[] =  $obj->phone;
                     $row_data[] =  $obj->email;
                    $row_data[] =  $role_name ;
                    $row_data[] =  $obj->salary_type;
                    $row_data[] =  $obj->basic_salary;
                    
                    $row_data[] =  $obj->father_name;
                    $row_data[] =  $obj->present_address;
                    $row_data[] =  $obj->qualification;
                    $row_data[] =  $obj->gender;
                    $row_data[] =  $obj->joining_date!= '0000-00-00' ? $obj->joining_date : "";
                    $row_data[] =  $obj->is_view_on_web ? $this->lang->line('yes') : $this->lang->line('no');
                    $row_data[] =  $obj->dob!= '0000-00-00' ? $obj->dob : "";
                    
                    $row_data[] =  $action;		
                    $data[] = $row_data;	
                  
					   $count++;
				}
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
echo json_encode($response,JSON_UNESCAPED_UNICODE);
exit;
	}
	 public function alumni($school_id = null) {

        check_permission(VIEW);
        
        $this->data['employees'] = $this->employee->get_alumniemployee_list($school_id);
        $this->data['roles'] = $this->employee->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');        
           // $this->data['designations'] = $this->employee->get_list('designations', $condition, '', '', '', 'id', 'ASC');
		   $this->data['employment_types'] = $this->employee->get_list('employment_types', array('school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->employee->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
            
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_alumni_employee') . ' | ' . SMS);
        $this->layout->view('employee/alumni', $this->data);
    }
    

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Employee" user interface                 
    *                    and process to store "Empoyee" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) { 	
            $this->_prepare_employee_validation();			
            if ($this->form_validation->run() === TRUE) {
				if($_POST['role_id']== TEACHER){
					$data = $this->_get_posted_teacher_data();	
					$insert_id = $this->teacher->insert('teachers', $data);					
				}
				else{
					$data = $this->_get_posted_employee_data();		
					$insert_id = $this->employee->insert('employees', $data);
				}

                
                if ($insert_id) {
                    // insert  into employee payscale category
					if(!empty($_POST['payscalecategory_id'])){
						foreach($_POST['payscalecategory_id'] as $grade_id){
							$in_arr=array();
							$in_arr['user_id']=$data['user_id'];
							$in_arr['payscalecategory_id']=$grade_id;
							$e_id = $this->employee->insert('user_payscalecategories', $in_arr);
						}
					}
					// insert employment types
					if($_POST['role_id']!= TEACHER){
						if(!empty($_POST['employment_type_id'])){
							foreach($_POST['employment_type_id'] as $e_id){
								$in_arr=array();
								$in_arr['employee_id']=$insert_id;
								$in_arr['employment_type_id']=$e_id;
								$et_id = $this->employee->insert('employee_employment_types', $in_arr);
							}
						}
					}
                    create_log('Has been added a Employee : '.$data['name']);
                    
                    success($this->lang->line('insert_success'));
                    redirect('hrm/employee/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('hrm/employee/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }
        
        $this->data['employees'] = $this->employee->get_employee_list();
        $this->data['roles'] = $this->employee->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
         if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
            $school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id;        
            $this->data['designations'] = $this->employee->get_list('designations', $condition, '', '', '', 'id', 'ASC');
		  $this->data['employment_types'] = $this->employee->get_list('employment_types', array('school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->employee->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
        if($school_id)
        {
            $this->data['employee_code'] = $this->employee->generate_employee_code($school_id);        
        }
        $this->data['schools'] = $this->schools;
        
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('employee') . ' | ' . SMS);
        $this->layout->view('employee/index', $this->data);
    }

    
    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Employee" user interface                 
    *                    with populate "Employee" value 
    *                    and process to update "Employee" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function alumni_employee() {

        check_permission(EDIT);
        $employee_id = $this->input->post('employee_id') ;
        $reason      = $this->input->post('drop-reason') ;
        $leaving_date   = $this->input->post('drop-date') ;
        $transfer_school_id   = $this->input->post('transfer_school_id') ;
        if(!$employee_id  )
        {
            redirect('hrm/employee/');

        }
        $employee = $this->employee->get_single('employees', array('id' => $employee_id));
        $user = (array)$this->employee->get_single('users', array('id' => $employee->user_id));

        if($reason == "Transfer")
        {
            if(!empty($employee))
            {
                $employee_code = $this->employee->generate_employee_code($transfer_school_id);
               unset($user['id']);
               $user['school_id']  = $transfer_school_id;
               $user['username']  = $employee_code;
               $user['created_at']  = date("Y-m-d H:i:s");
               $user['created_by']  = logged_in_user_id();
               $user_id = $this->employee->insert('users', $user);
               $employee_data = (array)$employee;
               unset($employee_data['id']);
               $employee_data['school_id']  = $transfer_school_id;
               $employee_data['user_id']  = $user_id;
               $employee_data['employee_code']  =  $employee_code;
               $employee_data['created_at']  = date("Y-m-d H:i:s");
               $employee_data['created_by']  = logged_in_user_id();
                $this->employee->insert('employees', $employee_data);
            }
        }
        
            $this->data['alumni']=1;
            $this->data['leaving_reason']= $reason;
            $this->data['leaving_date']= $leaving_date;
        
        $this->data['modified_at']= date("Y-m-d H:i:s");
        $this->data['modified_by']=  logged_in_user_id();
        
        $updated = $this->employee->update('employees',$this->data, array('id' => $employee_id));
        if($user['id'] ?? 0)
        {
            $updated = $this->employee->update('users', array("status"=>0), array('id' => $user['id']));

        }
        redirect('hrm/employee/');
    }
   
    public function unalumni_emp($id = null) {

        check_permission(EDIT);
        $this->input->post('id') ;
        $this->data['alumni']=0;
        $this->data['leaving_reason']=null;
        $this->data['leaving_date']=null;
        $this->data['modified_at']= date("Y-m-d H:i:s");
        $this->data['modified_by']=  logged_in_user_id();
        $updated = $this->employee->update('employees',$this->data, array('id' => $id));

	    redirect('hrm/employee/');
    }
    
    public function edit($id = null) {

        check_permission(EDIT);
if(isset($_GET['alumni']) || isset($_POST['alumni'])){
			$this->data['alumni']=1;
		}
        if ($_POST) {
            $this->_prepare_employee_validation();
            if ($this->form_validation->run() === TRUE) {
				if($_POST['role_id']== TEACHER)
                {
					$data = $this->_get_posted_teacher_data();
					// remove from employee
					 $this->employee->delete('employees', array('id' => $id));
					$updated = $this->teacher->insert('teachers', $data);					
				}
				else
                {
					$data = $this->_get_posted_employee_data();
					$updated = $this->employee->update('employees', $data, array('id' => $this->input->post('id')));
				}
                $this->employee->delete('user_payscalecategories', array('user_id' =>  $data['user_id']));
                if(!empty($_POST['payscalecategory_id'])){
                    foreach($_POST['payscalecategory_id'] as $grade_id){
                        $in_arr=array();
                        $in_arr['user_id']=$data['user_id'];
                        $in_arr['payscalecategory_id']=$grade_id;
                        $e_id = $this->employee->insert('user_payscalecategories', $in_arr);
                    }
                }
                if ($updated) {
                     // insert  into employee payscale category					 

					// employment type
					$this->employee->delete('employee_employment_types', array('employee_id' =>  $id));
					if($_POST['role_id']!= TEACHER){
						if(!empty($_POST['employment_type_id'])){
							foreach($_POST['employment_type_id'] as $e_id){
								$in_arr=array();
								$in_arr['employee_id']=$id;
								$in_arr['employment_type_id']=$e_id;
								$et_id = $this->employee->insert('employee_employment_types', $in_arr);
							}
						}
					}
                     create_log('Has been updated a Employee : '.$data['name']);
                    
                    success($this->lang->line('update_success'));
					if(isset($_POST['alumni'])){
						redirect('hrm/employee/alumni/'.$data['school_id']);
					}
					else{
                    redirect('hrm/employee/index/'.$data['school_id']);
					}
                } else {
                    $error = $this->db->error();
                   if($error['code'])
                   {
                        error($this->lang->line('update_failed'));
                        redirect('hrm/employee/edit/' . $this->input->post('id'));
                   }
                   else
                   {
                        success($this->lang->line('update_success'));
                        if(isset($_POST['alumni'])){
                            redirect('hrm/employee/alumni/'.$data['school_id']);
                        }
                        else{
                        redirect('hrm/employee/index/'.$data['school_id']);
                        }
                   }
                   //die();  
                }
            } else {                
                $this->data['employee'] = $this->employee->get_single_employee($this->input->post('id'));
            }
        } else {
            if ($id) {
                $this->data['employee'] = $this->employee->get_single_employee($id);

                if (!$this->data['employee']) {
                    redirect('hrm/employee/index');
                }
            }
        }

        $this->data['employees'] = $this->employee->get_employee_list($this->data['employee']->school_id);
        $this->data['roles'] = $this->employee->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $condition = array();
            $condition['status'] = 1;
                $condition['school_id'] = $this->session->userdata('school_id');        
            //$this->data['designations'] = $this->employee->get_list('designations', $condition, '', '', '', 'id', 'ASC');
			$this->data['employment_types'] = $this->employee->get_list('employment_types', array('school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->employee->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }
		
        
        $this->data['schools'] = $this->schools; 
        $this->data['filter_school_id'] = $this->data['employee']->school_id;
        $this->data['school_id'] = $this->data['employee']->school_id;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('employee') . ' | ' . SMS);
        $this->layout->view('employee/index', $this->data);
    }


        
    
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific Employee data                 
    *                       
    * @param           : $employee_id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($employee_id = null) {

        check_permission(VIEW);

        if(!is_numeric($employee_id)){
             error($this->lang->line('unexpected_error'));
             redirect('dashboard');  
        }
        
        $this->data['employees'] = $this->employee->get_employee_list();
        $this->data['roles'] = $this->employee->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['employee'] = $this->employee->get_single_employee($employee_id);
        
        
        $condition = array();
        $condition['status'] = 1;
        if($this->session->userdata('school_id') > 0){
            $condition['school_id'] = $this->session->userdata('school_id');
        }
        
        $this->data['designations'] = $this->employee->get_list('designations', $condition, '', '', '', 'id', 'ASC');
        $this->data['grades'] = $this->employee->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('employee') . ' | ' . SMS);
        $this->layout->view('employee/index', $this->data);
    }

    
    
     /*****************Function get_single_employee**********************************
     * @type            : Function
     * @function name   : get_single_employee
     * @description     : "Load single employee information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_employee(){
        
       $employee_id = $this->input->post('employee_id');
       
       $this->data['employee'] = $this->employee->get_single_employee($employee_id);
	   $cats = $this->grade->get_payscale_data_by_user($this->data['employee']->user_id);
	   $cat_name=array();
	   foreach($cats as $c){
		   $cat_name[]=$c->name;
	   }
		$this->data['payscale_categories'] = implode(", ",$cat_name);
		 $types = $this->employment_types->get_employment_types_by_employee($this->data['employee']->id);
	   $type_name=array();
	   foreach($types as $t){
		   $type_name[]=$t->name;
	   }
		$this->data['employment_types'] = implode(", ",$type_name);
       echo $this->load->view('employee/get-single-employee', $this->data);
    }
    
    /*****************Function _prepare_employee_validation**********************************
    * @type            : Function
    * @function name   : _prepare_employee_validation
    * @description     : Process "Employee" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_employee_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        if (!$this->input->post('id')) {       
            
            $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
        }
         $this->form_validation->set_rules('employee_code', $this->lang->line('employee_code'), 'trim');
        
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email');
        $this->form_validation->set_rules('school_id', $this->lang->line('role'), 'trim|required');
        if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
                $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        }
        //$this->form_validation->set_rules('designation_id', $this->lang->line('designation'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim');
        $this->form_validation->set_rules('present_address', $this->lang->line('present') . ' ' . $this->lang->line('address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent') . ' ' . $this->lang->line('address'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood_group'), 'trim');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('joining_date', $this->lang->line('join_date'), 'trim|required');
        //$this->form_validation->set_rules('salary_grade_id', $this->lang->line('salary_grade'), 'trim|required');
        $this->form_validation->set_rules('salary_type', $this->lang->line('salary_type'), 'trim|required');
        $this->form_validation->set_rules('other_info', $this->lang->line('other_info'), 'trim');
    }
   
    
    public function alumni_modal()
    {
       
        $data['employee_id'] = $this->input->post('employee_id');
        if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
            $data['schools'] = $this->employee->get_schools();
        }

        echo $this->load->view('employee/employee_modal', $data);
    }
   
            
    /*****************Function email**********************************
    * @type            : Function
    * @function name   : email
    * @description     : Unique check for "Employee Email" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function username() {
        if ($this->input->post('id') == '') {
            $username = $this->employee->duplicate_check($this->input->post('username'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $username = $this->employee->duplicate_check($this->input->post('username'), $this->input->post('id'));
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
	 public function employeecode() {
        if ($this->input->post('id') == '') {
            $emp = $this->employee->duplicate_check_emp_code($this->input->post('employee_code'));
            if ($emp) {
                $this->form_validation->set_message('employee_code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $emp = $this->employee->duplicate_check_emp_code($this->input->post('employee_code'), $this->input->post('id'));
            if ($emp) {
                $this->form_validation->set_message('employee_code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    private function _get_posted_teacher_data (){
		$items = array();
        $items[] = 'school_id';
        //$items[] = 'designation_id';
        //$items[] = 'national_id';
        $items[] = 'name';
		//$items[] = 'teacher_code';
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
        $items[] = 'is_view_on_web';      
		$items[] = 'father_name';      
		$items[] = 'alternate_name';      
		$items[] = 'reservation_category';      
		//$items[] = 'qualification';      
		$items[] = 'adhar_no';      
		//$items[] = 'pf_no';      
		//$items[] = 'uan_no';   

   		$items[] = 'pacific_ability'; 
		$items[] = 'rtet_qualified'; 
		$items[] = 'secondary_roll_no'; 
		$items[] = 'secondary_year'; 
		$items[] = 'current_subject'; 
       // $items[] = 'employment_type_id';
		
        $data = elements($items, $_POST);  

        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));

        if ($this->input->post('id')) 
        {
            if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id(); 
            $this->employee->update('users', array('role_id'=> $this->input->post('role_id'),'username'=> $this->input->post('username'),'modified_at'=>date('Y-m-d H:i:s')), array('id'=> $this->input->post('user_id')));
            }
            $data['user_id']=$this->input->post('user_id');
            $data['smc']=$this->input->post('smc_edit');
        } 
        else 
        {
            $data['teacher_code'] = $this->teacher->generate_teacher_code($data['school_id']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            $data['smc']=$this->input->post('smc_add');
            // create user 
            $data['user_id'] = $this->teacher->create_user($data['teacher_code']);
        }

        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_teacher_photo();
        }
        if ($_FILES['resume']['name']) {
            $data['resume'] = $this->_upload_teacher_resume();
        }
        return $data;
	}
   
    /*****************Function _get_posted_employee_data**********************************
    * @type            : Function
    * @function name   : _get_posted_employee_data
    * @description     : Prepare "Employee" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */ 
    private function _get_posted_employee_data() {

        $items = array();
        $items[] = 'school_id';
        //$items[] = 'designation_id';
        $items[] = 'national_id';
        $items[] = 'name';
        if($this->input->post('id') && $this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ){   
		    $items[] = 'employee_code';
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
                $this->employee->update('users', array('username' => $this->input->post('username'),'role_id'=> $this->input->post('role_id'),'modified_at'=>date('Y-m-d H:i:s')), array('id'=> $this->input->post('user_id')));
            }
            $data['user_id']=$this->input->post('user_id');
            $data['smc']=$this->input->post('smc_edit');
            
        } else {
            $data['employee_code']=$this->employee->generate_employee_code($data['school_id']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            // create user 
            $data['user_id'] = $this->employee->create_user($data['employee_code']);
            $data['smc']=$this->input->post('smc_add');
        }

        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        if ($_FILES['resume']['name']) {
            $data['resume'] = $this->_upload_resume();
        }
        return $data;
    }

    
       
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : Process to upload employee photo into server                  
    *                     and return photo name  
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

                $destination = 'assets/uploads/employee-photo/';

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
    * @description     : Process to upload employee resume into server                  
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

                $destination = 'assets/uploads/employee-resume/';

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

     private function _upload_teacher_photo() {

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
    private function _upload_teacher_resume() {
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
    * @description     : delete "Employee" data from database                  
    *                     and unlink employee photo and Resume from server  
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('hrm/employee');       
        }
        
        $employee = $this->employee->get_single('employees', array('id' => $id));
        if (!empty($employee)) {

            // delete employee data
            $this->employee->delete('employees', array('id' => $id));
            // delete employee login data
            $this->employee->delete('users', array('id' => $employee->user_id));

            // delete employee resume and photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/employee-resume/' . $employee->resume)) {
                @unlink($destination . '/employee-resume/' . $employee->resume);
            }
            if (file_exists($destination . '/employee-photo/' . $employee->photo)) {
                @unlink($destination . '/employee-photo/' . $employee->photo);
            }
            
            create_log('Has been deleted a Employee : '.$employee->name);
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        if(isset($_GET['alumni'])){
			redirect('hrm/employee/alumni/'.$employee->school_id);
		}
		else{
			redirect('hrm/employee/index/'.$employee->school_id);
		}
    }

}
