<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Bulk.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Bulk
 * @description     : Manage bulk students imformation of the school.  
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
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            	
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add Bulk Student" user interface                 
    *                    and process to store "Bulk Student" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        check_permission(ADD);

        if ($_POST) {            			
            $status = $this->_get_posted_data();
            if ($status) {                   

                //create_log('Has been added Bulk Student');
                success($this->lang->line('insert_success'));
                redirect('hrm/employee/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('hrm/import/employee/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('employee') . ' | ' . SMS);
        $this->layout->view('employee/import', $this->data);
    }

   

    /*****************Function _get_posted_student_data**********************************
    * @type            : Function
    * @function name   : _get_posted_student_data
    * @description     : Prepare "Student" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_data() {

        $this->_upload_file();

        $destination = 'assets/csv/bulk_uploaded_employee.csv';
        if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	
			print_r($arr); exit;
                if ($count == 1) {
                    $count++;
                    continue;
                }				            
				if ($arr[0] != '') {					                
                    $data = array();
					$data['school_id'] =$school_id;
						$data['name'] =$arr[1]." ".$arr[2];					
						$data['dob']=date('Y-m-d',strtotime($arr[6]));
						$data['gender']=strtolower($arr[7]);
						$data['email']=$arr[10];
						$data['phone']=$arr[11];
						$data['salary_type']='monthly';
						$data['basic_salary']=$arr[15];											
						$data['reg_no']=$arr[11];
						// create user
						
						$user['school_id']=$school_id;
						$firstname_arr=explode(" ",$arr[1]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[9],strlen($arr[9])-3);
						$user['username']=$username;
					// if teaching subject is empty - employee otherwise teacher
					if($arr[13]!=''){
						// add to teacher
						$user['role_id']=TEACHER;						
					}
					else{
						// add to employee						
						$user['role_id']=STAFF;
						// employment type - designation
						$emp_type=explode(",",$arr[4]);
						$index=count($emp_type)-1;
						if($index >=0){
							$designation_name=$emp_type[$index];
							if(preg_match("/[a-z]/i", $designation_name)){
								$res=$this->employee->get_single("designations",array("school_id",$school_id,"name"=>$designation_name));
								if(!empty($res)){
									$data['designation_id']=$res->id;
								}
								else{
									$d_insert=array();
									$d_insert['school_id']=$school_id;
									$d_insert['name']=$designation_name;
									$d_insert['status']=1;
									$d_insert['created_at']=date('Y-m-d H:i:s');
									$d_insert['created_by'] = logged_in_user_id();
									$data['designation_id']=$this->employee->insert("designations",$d_insert);
								}
							}
						}
					}
						$data['user_id'] = $this->_create_user($user);  
												
						$data['status']=1;						
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
						if(isset($_POST['alumni']) && $_POST['alumni']==1){
							$data['alumni']=1;
						}
						else{
							$data['alumni']=0;
						}
					if($arr[13]!=''){
						// now need to create student
						$data['teacher_code']=$this->generate_teacher_code($school_id);
						$teacher_id = $this->employee->insert('teachers', $data);   
						// teaching subjects
						$teaching_subjects=$arr[13];
						$sub_arr=explode(",",$teaching_subjects);						
						foreach($sub_arr as $a){
							
							$sub=trim(preg_replace('/\[(.*?)\]/', '', $a));	
							$s_arr=explode(" Class ",$sub);
							if(isset($s_arr[0]) && isset($s_arr[1])){
								$subject_name=trim($s_arr[0]);
								$class_name=trim($s_arr[1]);								
								$class=$this->employee->get_single("classes",array("school_id"=>$school_id,"name"=>$class_name));
								if(!empty($class)){
									$class_id=$class->id;
								}
								else{
									// create class
									$class_insert_data=array();
									$class_insert_data['school_id']=$school_id;
									$class_insert_data['name']=$class_name;
									$class_insert_data['created_at'] = date('Y-m-d H:i:s');
									$class_insert_data['created_by'] = logged_in_user_id();
									$class_insert_data['status'] = 1;
									$class_id=$this->employee->insert('classes', $class_insert_data);
								}
								// check for subject
								$detail=$this->employee->get_single("subjects",array("school_id"=>$school_id,"name"=>$subject_name,"class_id"=>$class_id));
								if(!empty($detail)){
									// update teacher id
									$subject=array();
									$subject['teacher_id']=$teacher_id;
									$subject_id=$this->employee->update('subjects', $subject,array("id"=>$detail->id));
								}
								else{
									// insert subject
									$subject=array();
									$subject['school_id']=$school_id;
									$subject['class_id']=$class_id;
									$subject['teacher_id']=$teacher_id;
									$subject['name']=$subject_name;
									$subject['created_at'] = date('Y-m-d H:i:s');
									$subject['created_by'] = logged_in_user_id();
									$subject['status'] = 1;
									$subject_id=$this->employee->insert('subjects', $subject);
								}
							}
							
						}						
					}
					else{
						$data['employee_code']=$this->generate_employee_code($school_id);
						$employee_id = $this->employee->insert('employees', $data);                  
					}					
						// salary grade
						if($arr[16]!=''){
							$cats=explode(",",$arr[16]);							
							foreach($cats as $c){
								$name=trim($c);
								$name = trim(preg_replace('/\[(.*?)\]/', '', $name));								
								$grade=$this->grade->get_single_grade_by_name($school_id,$name);
								if(!empty($grade)){
									$in_arr=array();
									$in_arr['user_id']=$data['user_id'];
									$in_arr['payscalecategory_id']=$grade->id;
									$this->employee->insert('user_payscalecategories', $in_arr);
								}
							}					
						}
					
					
                }
            }
			
        }

        return TRUE;
    }
  
    
     /*****************Function _upload_file**********************************
    * @type            : Function
    * @function name   : _upload_file
    * @description     : upload bulk studebt csv file                  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _upload_file() {

        $file = $_FILES['bulk_employee']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_employee.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_employee']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('hrm/import/employee/');
        }       
    }
	public function generate_teacher_code($school_id = null){		
		if(isset($school_id)){		
			$school=$this->employee->get_single('schools',array('id'=>$school_id));			
			$district=$this->employee->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->employee->get_single('teachers',array('teacher_code'=>$unique_code));
			if(!empty($employee)){
				return $this->generate_teacher_code($school_id);
			}
			else{
				return $unique_code;
			}
		}
		else {
			return "";
		}
	}
	public function generate_employee_code($school_id = null){
		
		if(isset($school_id)){		
			$school=$this->employee->get_single('schools',array('id'=>$school_id));			
			$district=$this->employee->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->employee->get_single('employees',array('employee_code'=>$unique_code));
			if(!empty($employee)){
				return $this->generate_employee_code($school_id);
			}
			else{
				return $unique_code;
			}
		}
		else {
			return "";
		}
	}
   
    
    /*****************Function _create_user**********************************
    * @type            : Function
    * @function name   : _create_user
    * @description     : save user info to users while create a new student                  
    * @param           : $insert_id integer value
    * @return          : null 
    * ********************************************************** */
    private function _create_user($user){
        
		$password=substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 6 );
        $data = array();
        $data['role_id']    = $user['role_id'];
        $data['school_id']    = $user['school_id'];
        $data['password']   = md5($password);
        $data['temp_password'] = base64_encode($password);		
        $data['username']      = $user['username'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status']     = 1; // by default would not be able to login
        $this->employee->insert('users', $data);
        return $this->db->insert_id();
    }	
}