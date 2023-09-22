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

class Year extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   
		$this->load->model('Year_Model', 'year', true);		
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
                redirect('administrator/year/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('administrator/import/year/');
            }            
        } 
        
                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }else{ 
            //$this->data['classes'] = array();   
        }
        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('academic_year') . ' | ' . SMS);
        $this->layout->view('year/import', $this->data);
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

        $destination = 'assets/csv/bulk_uploaded_year.csv';
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
					$session_year=date('F Y',strtotime($arr[2]))."-".date('F Y',strtotime($arr[1]));
					$data['session_year']=$session_year;
					$start_year=date('Y',strtotime($arr[2]));
					$data['start_year']=$start_year;
					$end_year=date('Y',strtotime($arr[1]));
					$data['end_year']=$end_year;
					$data['status']=1;						
					$data['created_at'] = date('Y-m-d H:i:s');					
                    
					// check for duplicate record
					$year=$this->year->get_single('academic_years',array("school_id"=>$school_id,'start_year'=>$start_year,"end_year"=>$end_year));
					if(empty($year)){						
						$y_id = $this->year->insert('academic_years', $data);                  
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

        $file = $_FILES['bulk_year']['name'];

        if ($file != "") {

            $destination = 'assets/csv/bulk_uploaded_year.csv';          
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                move_uploaded_file($_FILES['bulk_year']['tmp_name'], $destination);  
            }
        } else {
            error($this->lang->line('insert_failed'));
            redirect('administrator/import/year/');
        }       
    }        
}