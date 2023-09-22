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

class Accountledgers extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		//$this->load->model('Payscalecategory_Model', 'grade', true);            	
		$this->load->model('Accountgroups_Model', 'accountgroups', true);            	
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
        //check_permission(ADD);

        if ($_POST) {            
            $status = $this->_get_posted_type_data();
            if ($status) {                   
                success($this->lang->line('insert_success'));
                redirect('accountledgers/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('import/accountledgers');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }else{ 
            //$this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('ledger') . ' | ' . SMS);
        $this->layout->view('accountledgers/import', $this->data);
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

        $destination = 'assets/csv/bulk_uploaded_ledgers.csv';
        if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	
			//print_r($arr); exit;
                if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[0];	
					$data['dr_cr']=$arr[6];
					
					// group
					$group_name= trim(preg_replace('/\[(.*?)\]/', '', $arr[1]));					
					$group=$this->accountgroups->get_accountgroup_by_name($school_id,$group_name);
										
														
					$data['created'] = date('Y-m-d H:i:s');
					//$data['created_by'] = logged_in_user_id();
                    if(!empty($group)){
						$data['account_group_id']=$group->id;
						// academic year
						$year=$arr[14];
						$y_arr=explode("-",$year);
						if(isset($y_arr[0]) && isset($y_arr[1])){
							$year_data=$this->accountledgers->get_single('academic_years', array('start_year' => $y_arr[0],'end_year'=>$y_arr[1],'school_id'=>$school_id)); 							
							if(!empty($year_data)){								
								$ledger_id = $this->accountledgers->insert('account_ledgers', $data);
								
								// detail
								$detail=array();
								$detail['ledger_id']=$ledger_id;
								$detail['academic_year_id']=$year_data->id;
								$detail['opening_cr_dr']=$arr[8];
								$detail['opening_balance']=$arr[9];
								$detail['budget']=$arr[11];
								$detail['budget_cr_dr']=$arr[10];
								$ledgerdetail_id = $this->accountledgers->insert('account_ledger_details', $detail);
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

        $file = $_FILES['bulk_ledgers']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_ledgers.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_ledgers']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('import/accountledgers');
        }       
    }
   
    
  
}