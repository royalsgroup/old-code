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

class Vouchers extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		//$this->load->model('Payscalecategory_Model', 'grade', true); 
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
        //check_permission(ADD);

        if ($_POST) {            
            $status = $this->_get_posted_type_data();
            if ($status) {                   
                success($this->lang->line('insert_success'));
                redirect('vouchers/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('import/vouchers');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }else{ 
            //$this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('voucher') . ' | ' . SMS);
        $this->layout->view('vouchers/import', $this->data);
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

        $destination = 'assets/csv/bulk_uploaded_vouchers.csv';
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
					
					// group
					$type_name= trim($arr[2]);					
					$type=$this->voucher->get_single('voucher_types',array('name'=>$type_name));
										
														
					$data['created'] = date('Y-m-d H:i:s');
					//$data['created_by'] = logged_in_user_id();
                    if(!empty($type)){
						$data['type_id']=$type->id;											
						$voucher_id = $this->voucher->insert('vouchers', $data);
																								
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

        $file = $_FILES['bulk_vouchers']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_vouchers.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_vouchers']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('import/vouchers');
        }       
    }
   
    
  
}