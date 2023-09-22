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

class Feetype extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		$this->load->model('Feetype_Model', 'feetype', true);  		
		$this->load->model('Accountledgers_Model', 'accountledgers', true);  		
		$this->load->model('Voucher_Model', 'voucher', true);  		
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
            $status = $this->_get_posted_type_data();
            if ($status) {                   

                create_log('Has been added Bulk Student');
                success($this->lang->line('insert_success'));
                redirect('accounting/feetype/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('accounting/import/feetype/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');
          //  $this->data['classes'] = $this->student->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        }else{ 
            //$this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('fee_type') . ' | ' . SMS);
        $this->layout->view('fee_type/import', $this->data);
    }

   

    /*****************Function _get_posted_student_data**********************************
    * @type            : Function
    * @function name   : _get_posted_student_data
    * @description     : Prepare "Student" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_type_data() {

        $this->_upload_file();

        $destination = 'assets/csv/bulk_uploaded_feetype.csv';
        if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	

                if ($count == 1) {
                    $count++;
                    continue;
                }				            
				if ($arr[0] != '') {					                
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['head_type'] ='fee';					
					$data['title'] =$arr[0];
					$data['status']=1;
					// get credit ledger id from ledger name
					$credit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[1]);
					if(isset($credit_ledger->id)){
						$data['credit_ledger_id']=$credit_ledger->id;
					}	

					$refund_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[3]);
					if(isset($refund_ledger->id)){
						$data['refund_ledger_id']=$refund_ledger->id;
					}

					$voucher=$this->voucher->get_voucher_by_name($school_id,$arr[2]);
					if(isset($voucher->id)){
						$data['voucher_id']=$voucher->id;
					}					
					$data['created_at'] = date('Y-m-d H:i:s');
					$data['created_by'] = logged_in_user_id();
                    

                    // now need to create student
                    $feetype['id'] = $this->feetype->insert('income_heads', $data);                  
				
				
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

        $file = $_FILES['bulk_feetype']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_feetype.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_feetype']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('accounting/import/feetype/');
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