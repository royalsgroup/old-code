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

class Payscalecategory extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		$this->load->model('Payscalecategory_Model', 'grade', true);            	
		$this->load->model('Accountledgers_Model', 'accountledgers', true);            	
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
                success($this->lang->line('insert_success'));
                redirect('payroll/payscalecategory/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('payroll/import/payscalecategory/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }else{ 
            //$this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('grade') . ' | ' . SMS);
        $this->layout->view('payscalecategory/import', $this->data);
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

        $destination = 'assets/csv/bulk_uploaded_payscalecategory.csv';
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
					$data['name'] =$arr[0];					
					$data['is_deduction_type'] =$arr[2];
					$data['category_type']=$arr[3];
					$data['percentage']=$arr[4];
					$data['amount']=$arr[5];
					// get credit ledger id from ledger name
					$credit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[6]);
					if(isset($credit_ledger->id)){
						$data['credit_ledger_id']=$credit_ledger->id;
					}	

					$debit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[7]);
					if(isset($debit_ledger->id)){
						$data['debit_ledger_id']=$debit_ledger->id;
					}					
					$data['created'] = date('Y-m-d H:i:s');
					//$data['created_by'] = logged_in_user_id();
                    

                    // now need to create student
                    $cat['id'] = $this->grade->insert('payscale_category', $data);                  
				
				
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

        $file = $_FILES['bulk_payscalecategory']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_payscalecategory.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_payscalecategory']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('payroll/import/payscalecategory/');
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
		
    
  
}