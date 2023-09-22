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

class Bulk extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();      
        
        $this->load->model('Student_Model', 'student', true);          
		$this->load->model('academic/Classes_Model', 'classes', true);     
		$this->load->model('academic/Section_Model', 'section', true); 
		$this->load->model('administrator/Year_Model', 'year', true); 		
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add Bulk Student" user interface                 
    *                    and process to store "Bulk Student" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {            
            $status = $this->_get_posted_student_data();
            if ($status) {                   

                create_log('Has been added Bulk Student');
                success($this->lang->line('insert_success'));
                redirect('student/index/'.$this->input->post('class_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('student/bulk/add/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');
            $this->data['classes'] = $this->student->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        }else{ 
            $this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('student') . ' | ' . SMS);
        $this->layout->view('bulk', $this->data);
    }

   

    /*****************Function _get_posted_student_data**********************************
    * @type            : Function
    * @function name   : _get_posted_student_data
    * @description     : Prepare "Student" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_student_data() {

        $this->_upload_file();

        $destination = 'assets/csv/bulk_uploaded_student.csv';
        if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');           
            $school = $this->student->get_school_by_id($school_id);             
          /*  if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('student/bulk/add');
            }*/

            while (($arr = fgetcsv($handle)) !== false) {		
		print_r($arr); exit;
                if ($count == 1) {
                    $count++;
                    continue;
                }		
			
                // need atleast some mandatory data
                // name, admission_no, roll_no, username, password
				if ($arr[1] != '') {					
                //if ($arr[0] != '' && $arr[1] != '' && $arr[6] != '' && $arr[11] != '' && $arr[12] != '') {

                    // need to check email unique
                 /*   if ($this->student->duplicate_check($arr[11])) {
                        continue;
                    }*/					


                    $data = array();
                    $enroll = array();
                    $user = array();
					$guardian_user=array();

                    $data['school_id'] =$school_id;
                    $data['admission_date'] = date('Y-m-d',strtotime($arr[19]));
					$name=$arr[1];
					if(isset($arr[2])){
						$name.=" ".$arr[2];
					}
                    $data['name'] = $name;					
                    $data['admission_no'] = isset($arr[13]) ? $arr[13] : '';
					$data['registration_no'] = isset($arr[13]) ? $arr[13] : '';
					// student codetroopers
					$data['student_code']=$this->generate_student_code($school_id);					
					// add to guardian
					$guardian_user['school_id']=$school_id;
					$firstname_arr=explode(" ",$arr[3]);
					$firstname=strtolower($firstname_arr[0]);
					$username=$firstname.substr($arr[13],strlen($arr[13])-3);
					$guardian_user['username']=$username;
					$guardian_user['role_id']=GUARDIAN;
					
					$guardian_data['user_id'] = $this->_create_user($guardian_user);                
					$guardian_data['school_id']=$school_id;
					$guardian_data['name']=$arr[3];
					$guardian_data['phone']=$arr[18];
					$guardian_data['profession']=$arr[24];
					 $guardian_data['created_at'] = date('Y-m-d H:i:s');
                    $guardian_data['created_by'] = logged_in_user_id();
                    $guardian_data['status'] = 1;
					$this->student->insert('guardians', $guardian_data);
					$data['guardian_id']=$this->db->insert_id();
					if(isset($data['guardian_id'])){
                    $data['relation_with'] = 'father';}
                    $data['national_id'] = isset($arr[14]) ? $arr[14] : '';
                    $data['registration_no'] = isset($arr[13]) ? $arr[13] : '';
                    $enroll['roll_no'] = isset($arr[6]) ? $arr[6] : '';
                    $data['dob'] = isset($arr[8]) ? date('Y-m-d', strtotime($arr[8])) : '';
                    $data['gender'] = isset($arr[9]) ? strtolower($arr[9]) : '';
                    $data['phone'] = isset($arr[18]) ? $arr[18] : '';
                    $data['email'] = '';                
                //    $data['group'] = isset($arr[13]) ? $arr[13] : '';
                    $data['blood_group'] = '';
                    $data['religion'] = isset($arr[21]) ? $arr[21] : '';
                    $data['caste'] = isset($arr[23]) ? $arr[23] : '';
              //      $data['type_id'] = isset($arr[17]) ? $arr[17] : '';  
              //      $data['discount_id'] = isset($arr[18]) ? $arr[18] : '';
					$present_address= $arr[27];
					if(isset($arr[28]) && $arr[28]!= ''){
							$present_address.= ", ".$arr[28];
					}
					if(isset($arr[29]) && $arr[29]!= ''){
							$present_address.= ", ".$arr[29];
					}
					if(isset($arr[30]) && $arr[30]!= ''){
							$present_address.= ", ".$arr[30];
					}
					if(isset($arr[31]) && $arr[31]!= ''){
							$present_address.= ", ".$arr[31];
					}
					if(isset($arr[32]) && $arr[32]!= ''){
							$present_address.= ", ".$arr[32];
					}
					if(isset($arr[33]) && $arr[33]!= ''){
							$present_address.= "- ".$arr[33];
					}
					if(isset($arr[34]) && $arr[34]!= ''){
							$present_address.= ",Phone: ".$arr[34];
					}
					// PERMANENT ADDRESS
					$permanent_address= $arr[35];
					if(isset($arr[36]) && $arr[36]!= ''){
							$permanent_address.= ", ".$arr[36];
					}
					if(isset($arr[37]) && $arr[37]!= ''){
							$permanent_address.= ", ".$arr[37];
					}
					if(isset($arr[38]) && $arr[38]!= ''){
							$permanent_address.= ", ".$arr[38];
					}
					if(isset($arr[39]) && $arr[39]!= ''){
							$permanent_address.= ", ".$arr[39];
					}
					if(isset($arr[40]) && $arr[40]!= ''){
							$permanent_address.= ", ".$arr[40];
					}
					if(isset($arr[41]) && $arr[41]!= ''){
							$permanent_address.= "- ".$arr[41];
					}
					if(isset($arr[42]) && $arr[42]!= ''){
							$permanent_address.= ",Phone: ".$arr[42];
					}
					$data['present_address']=$present_address;
					$data['permanent_address']=$permanent_address;            
                    $data['second_language'] = '';
                    $data['health_condition'] = isset($arr[10]) ? $arr[10] : '';
                    $data['previous_school'] = isset($arr[20]) ? $arr[20] : '';
                    $data['previous_class'] = isset($arr[22]) ? $arr[22] : '';
                    $data['father_name'] = isset($arr[3]) ? $arr[3] : '';
					$data['father_phone'] = '';
                    $data['father_education'] = '';
                    $data['father_profession'] = isset($arr[24]) ? $arr[24] : '';
                    $data['father_designation'] = '';
                    $data['mother_name'] = isset($arr[4]) ? $arr[4] : '';
                    $data['mother_phone'] = '';
                    $data['mother_education'] = '';
                    $data['mother_profession'] = '';
                    $data['mother_designation'] = ''; 
                    $data['other_info'] = isset($arr[44]) ? $arr[44] : '';

                    $data['age'] = $data['dob'] ? floor((time() - strtotime($data['dob'])) / 31556926) : 0;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
                    $data['status'] = 1;
                    
                    $user['school_id'] = $school_id;

					// generate username
					$firstname_arr=explode(" ",$arr[1]);
					$firstname=strtolower($firstname_arr[0]);
					$username=$firstname.substr($arr[13],strlen($arr[13])-3);					
					$user['username']=$username;
					$user['role_id']=STUDENT;
                    // first need to create user
                    $data['user_id'] = $this->_create_user($user);

                    // now need to create student
                    $enroll['student_id'] = $this->student->insert('students', $data);

                    // now need to create enroll								
					$s_arr=explode(' - ',$arr[5]);
					$first=trim($s_arr[0]);
					$second=trim($s_arr[1]);
					$first_part=trim(preg_replace('/\[(.*?)\]/', '', $first));					
					if(isset($first_part)){
						$s1_arr=explode("_",$first_part);
						if(isset($s1_arr[0])){
							$class=trim($s1_arr[0]);							
							// check if class present - otherwise add
							$class_data=$this->classes->get_class_id($school_id,$class);
							if(!empty($class_data)){
								$enroll['class_id']=$class_data->id;
							}
							else{
								$class_insert_data=array();
								$class_insert_data['school_id']=$school_id;
								$class_insert_data['name']=$class;
								$class_insert_data['created_at'] = date('Y-m-d H:i:s');
								$class_insert_data['created_by'] = logged_in_user_id();
								$class_insert_data['status'] = 1;
								$this->student->insert('classes', $class_insert_data);
								$enroll['class_id']=$this->db->insert_id();
							}
							
						}
						if(isset($s1_arr[1])){
							$section=trim($s1_arr[1]);							
							// check if class present - otherwise add
							$section_data=$this->section->get_section_id($school_id,$enroll['class_id'],$section);
							if(!empty($section_data)){
								$enroll['section_id']=$section_data->id;
							}
							else{
								$section_insert_data=array();
								$section_insert_data['school_id']=$school_id;
								$section_insert_data['class_id']=$enroll['class_id'];
								$section_insert_data['name']=$section;
								$section_insert_data['created_at'] = date('Y-m-d H:i:s');
								$section_insert_data['created_by'] = logged_in_user_id();
								$section_insert_data['status'] = 1;
								$this->student->insert('sections', $section_insert_data);
								$enroll['section_id']=$this->db->insert_id();
							}
						}
					}
					/*$arr1=$s_arr;					
					unset($arr1[0]);					
					$mystring=implode(" ",$arr1);*/					
					preg_match_all("/\\[(.*?)\\]/", $second, $matches); 					
					//preg_match_all("/\\[(.*?)\\]/", $second, $matches); 					
					$index=count($matches)-1;
					$ay_year=$matches[$index][0];										
					$a_year="";
					$start_year="";
					$end_year="";
					if(isset($ay_year) && $ay_year!= ''){
						//$a_year=str_replace("[","",$s_arr[3]);
						//$a_year=str_replace("]","",$a_year);
						$year=explode("-",$ay_year);
						if(isset($year[0])){
							$start_year=$year[0];
						}
						if(isset($year[1])){
							$end_year=$year[1];
						}
					}	
		
					if($start_year!='' && $end_year!=''){
						$session_year=$start_year."-".$end_year;
						$year_data=$this->year->get_year_id($school_id,$start_year,$end_year);
							if(!empty($year_data)){
								$enroll['academic_year_id']=$year_data->id;
							}
							else{
								$year_insert_data=array();
								$year_insert_data['school_id']=$school_id;
								$year_insert_data['session_year']=$session_year;
								$year_insert_data['start_year']=$start_year;
								$year_insert_data['end_year']=$end_year;
								$year_insert_data['is_running']=0;
								$year_insert_data['created_at'] = date('Y-m-d H:i:s');
								$year_insert_data['created_by'] = logged_in_user_id();
								$year_insert_data['status'] = 1;
								$this->student->insert('academic_years', $year_insert_data);
								$enroll['academic_year_id']=$this->db->insert_id();
							}
                   // $enroll['academic_year_id'] = $school->academic_year_id;
						$this->_insert_enrollment($enroll);
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

        $file = $_FILES['bulk_student']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_student.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_student']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('student/bulk/add/');
        }       
    }
	public function generate_student_code($school_id){
		$school=$this->student->get_single('schools',array('id'=>$school_id));			
			$district=$this->student->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->student->get_single('students',array('student_code'=>$unique_code));
			if(!empty($employee)){
				return $this->generate_student_code($school_id);
			}
			else{
				return $unique_code;
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
        $this->student->insert('users', $data);
        return $this->db->insert_id();
    }
		
    
    
    /*****************Function _insert_enrollment**********************************
    * @type            : Function
    * @function name   : _insert_enrollment
    * @description     : save student info to enrollment while create a new student                  
    * @param           : $insert_id integer value
    * @return          : null 
    * ********************************************************** */
    private function _insert_enrollment($enroll) {
        
        $data = array();
        $data['student_id'] = $enroll['student_id'];
        $data['school_id']   = $this->input->post('school_id');
        $data['class_id']   = $enroll['class_id'];
        $data['section_id'] = $enroll['section_id'];        
        
        $data['academic_year_id'] = $enroll['academic_year_id'];
       
        
        $data['roll_no'] = $enroll['roll_no'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status'] = 1;
        $this->student->insert('enrollments', $data);
    }
}