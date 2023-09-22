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

class Import_school extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   		
		$this->load->model('Administrator/Year_Model', 'year', true);
		$this->load->model('Voucher_Model', 'voucher', true);    
		$this->load->model('Accountledgers_Model', 'ledgers', true);
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            		
		$this->load->model('Academic/Classes_Model', 'classes', true);            		
		$this->load->model('Academic/Subject_Model', 'subject', true);          		
    }  
    public function index() {	
        $this->data = array();
        if ($_POST) {    
            $errors = $this->_get_posted_data();
           
            if (empty($errors)) {       

               success($this->lang->line('insert_success'));
                redirect('import/import_school');
            } else {
                $this->data['duplicate_schools'] = $errors;
                error($this->lang->line('insert_failed'));
                
            }            
        }                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            redirect('dashboard/index');
        }       
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('csv') . ' | ' . SMS);        
        $this->layout->view('import/school_import', $this->data);
    }
    
    private function _get_posted_data() {
        $this->_upload_file();

        return $this->import_school();
    } 

	private function import_school(){		
       
        
		$destination = 'assets/csv/school_upload.csv';
		if (($handle = fopen($destination, "r")) !== FALSE) {

        $count = 1;            
        $duplicate_schools=[];

        $session_start      = Date("01-04-Y",time());
        $session_end        = Date("31-03-Y",strtotime('+1 year'));                            
      
        
          
            $last_insert_id = !empty($last_insert) ? $last_insert->id : 0;
            $school_sequence_id = $last_insert_id+ 1;
           
            while (($arr = fgetcsv($handle)) !== false) {
                if ($count == 1) {
                    $count++;
                    continue;
                }	
                if(isset($arr[0]))
                $name=$arr[0];		
                $data['school_name'] = $name;			
                $check=$this->year->get_single('schools',array('school_name'=>$name));
               
				if (empty($check)) {	
                   		                				
                    $data = array();                  
                    $data['state_id'] = 1;
			
					$data['school_name'] =$name;
					$data['school_code'] = "TEMP";
					$data['address'] =$arr[1];
					$data['phone'] =$arr[2];
					$data['email'] =$arr[3];
					$data['currency'] = "INR";
					$data['currency_symbol'] = "₹";

								
					$data['enable_frontend'] = 1;
                    $data['category'] = 'School Samiti';
					$data['enable_online_admission'] =0;
					$data['final_result_type'] =0;
					$data['language'] ="english";
					$data['theme_name'] ="trinidad";
					
							
					$data['created_at'] =date('Y-m-d H:i:s');	
					$data['modified_at'] =date('Y-m-d H:i:s');	
					$data['created_by'] =logged_in_user_id();
					$data['modified_by'] =logged_in_user_id();
                    $data['import_data'] =1;
                    
					$insert_id = $this->year->insert('schools', $data);
                    $data = array();
                    $financial_year_id = $this->create_financial_year($insert_id);	
                    $this->ledgers->insert_default($insert_id,$financial_year_id);
                    // now insert default payscale category 
                    $this->grade->insert_default($insert_id);
                    // now insert default to vouchers
                    $this->voucher->insert_default($insert_id,$financial_year_id);
                    $this->classes->insert_default($insert_id);
                    $this->subject->insert_default($insert_id);
                    $data['financial_year_id']= $financial_year_id;
					$data['school_code']= $this->generate_school_code($insert_id);
                    $data['academic_year_id'] = $this->create_academic_year($insert_id);	
					$update_arr['academic_year']=preg_replace('/\D/', '', $$session_start)." - ".preg_replace('/\D/', '', $session_end );

                    $this->year->update('schools', $data, array('id' => $insert_id));
					// create default user as principal
					$user_arr=array();
					$user_arr['role_id']    = 2;
					$user_arr['school_id']    = $insert_id;
					$user_arr['password']   = md5('welcome');
					$user_arr['temp_password'] = base64_encode('welcome');		
					$user_arr['username']      = $data['school_code'];
					$user_arr['created_at'] = date('Y-m-d H:i:s');
					$user_arr['created_by'] = logged_in_user_id();
					$user_arr['status']     = 1; // by default would not be able to login
				    $this->year->insert('users', $user_arr);
					$user_id=$this->db->insert_id();
				}
                else 
                {
                    $duplicate_schools[] = $name;
                }
                
			}

		}
       
        return $duplicate_schools;
    }

	
	
	
	
    private function _upload_file() {

        $file = $_FILES['bulk_data']['name'];

        if ($file != "") {

            $destination = 'assets/csv/school_upload.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                if(!move_uploaded_file($_FILES['bulk_data']['tmp_name'], $destination)){
					error('Problem in uploading file.');
					redirect('import/csv');
				}
            }
			else{
				error('Upload CSV file only');
				redirect('import/csv');
			}
        } else {
            error($this->lang->line('insert_failed'));
            redirect('import/csv');
        }       
    }
	 private function create_financial_year($school_id) {
        $data =array();
        $session_start      = Date("01-04-Y",time());
        $session_end        = Date("31-03-Y",strtotime('+1 year'));
        $data['start_year'] = preg_replace('/\D/', '', $session_start);
        $data['end_year']   = preg_replace('/\D/', '', $session_end);
        $data['session_year'] = $session_start .' -> '. $session_end;
        $data['school_id']   = $school_id;
        $data['is_running'] = 1;
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
       return  $this->year->insert('financial_years', $data);
    }
    private function generate_school_code($school_id)
    {
        $school_code = sprintf('%04d', $school_id);
        $school_code = "RJ01".$school_code;
        return $school_code;
    }
    private function create_academic_year($school_id) {
        
        $data =array();
        $session_start      = Date("01-04-Y",time());
        $session_end        = Date("31-03-Y",strtotime('+1 year'));
        $session_start      = Date("F Y",strtotime($session_start));
        $session_end        = Date("F Y",strtotime( $session_end));
        $data['start_year'] = preg_replace('/\D/', '', $session_start);
        $data['end_year']   = preg_replace('/\D/', '', $session_end);
        $data['session_year']   = $session_start .' - '. $session_end;
        $data['is_running'] = 1;
        $data['school_id']   = $school_id;
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        return $this->year->insert('academic_years', $data);
    }
   
    
  
}