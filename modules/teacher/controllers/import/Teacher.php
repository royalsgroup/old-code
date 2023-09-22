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

class Teacher extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		
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
                redirect('teacher/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('teacher/import/teacher/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');         
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('teacher') . ' | ' . SMS);
        $this->layout->view('teacher/import', $this->data);
    }

   
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

        $destination = 'assets/csv/bulk_uploaded_teacher.csv';
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
						$data['dob']=date('Y-m-d',strtotime($arr[7]));
						$data['gender']=strtolower($arr[8]);
						$data['email']=$arr[4];
						$data['salary_type']='monthly';
						// salary grade
						
						
						// create user
						
						$user['school_id']=$school_id;
						$firstname_arr=explode(" ",$arr[1]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[10],strlen($arr[10])-3);
						$user['username']=$username;
						$user['role_id']=TEACHER;
					
						$data['user_id'] = $this->_create_user($user);  
												
						$data['status']=1;						
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
						

						// now need to create student
						$employee['id'] = $this->employee->insert('employees', $data);                  
				             
				
				
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

        $file = $_FILES['bulk_teacher']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_teacher.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_teacher']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('teacher/import/teacher/');
        }       
    }
   
    
    /*****************Function _create_user**********************************
    * @type            : Function
    * @function name   : _create_user
    * @description     : save user info to users while create a new student                  
    * @param           : $insert_id integer value
    * @return          : null 
    * ********************************************************** */  
}