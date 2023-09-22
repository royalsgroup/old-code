<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ***************Dashboard.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Dashboard
 * @description     : This class used to showing basic statistics of whole application 
 *                    for logged in user.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers    
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Dashboard extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Dashboard_Model', 'dashboard', true);  
        $this->load->model('Administrator/Year_Model', 'year', true);
		$this->load->model('Voucher_Model', 'voucher', true);    
		$this->load->model('Accountledgers_Model', 'ledgers', true);
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            		
		$this->load->model('Academic/Classes_Model', 'classes', true);            		
		$this->load->model('Academic/Subject_Model', 'subject', true);  
        
    }

    public $data = array();

    /*     * ***************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Default function, Load logged in user dashboard stattistics  
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function index() {
      
        $this->data['school'] = array();
        $school_id = $this->session->userdata('school_id');   
        $theme = $this->session->userdata('theme');
        
        $this->data['theme'] = $this->dashboard->get_single('themes', array('status' => 1, 'slug' => $theme));    
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $this->data['school']   = $this->dashboard->get_single('schools', array('status'=>1, 'id'=>$school_id));
            $school =  $this->data['school'] ;
            if(isset($school->import_data) && $school->import_data ==1 )
            {
                    $session_start      = Date("01-04-Y",time());
                    $session_end        = Date("31-03-Y",strtotime('+1 year'));    
                    $financial_year_id = $this->create_financial_year($school_id);	
                    $this->ledgers->insert_default($school_id,$financial_year_id);
                    //now insert default payscale category 
                    $this->grade->insert_default($school_id);
                    //now insert default to vouchers
                    $this->voucher->insert_default($school_id,$financial_year_id);
                    $this->classes->insert_default($school_id);
                    $this->subject->insert_default($school_id);
                    $data['financial_year_id']= $financial_year_id;
                    $data['academic_year_id'] = $this->create_academic_year($school_id);	
					//$update_arr['academic_year']=preg_replace('/\D/', '', $$session_start)." - ".preg_replace('/\D/', '', $session_end );
                    $data['import_data']= 0;
                    $this->dashboard->update('schools', $data, array('id' => $school_id));
            }
        }            
       
        
        $this->data['news'] = $this->dashboard->get_list('news', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $this->data['notices'] = $this->dashboard->get_list('notices', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $this->data['events'] = $this->dashboard->get_list('events', array('status' => 1, 'school_id'=>$school_id), '', '', '10', 'id', 'DESC');
        $this->data['holidays'] = $this->dashboard->get_list('holidays', array('status' => 1, 'school_id'=>$school_id), '', '10', '', 'id', 'DESC');
       
        
        $this->data['users'] = $this->dashboard->get_user_by_role($school_id);
        $this->data['students'] = $this->dashboard->get_student_by_class($school_id);

        $this->data['total_student'] = $this->dashboard->get_total_student($school_id);
        $this->data['total_guardian'] = $this->dashboard->get_total_guardian($school_id);
        $this->data['total_teacher'] = $this->dashboard->get_total_teacher($school_id);
        $this->data['total_employee'] = $this->dashboard->get_total_employee($school_id);
        $this->data['total_expenditure'] = $this->dashboard->get_total_expenditure($school_id);
        $this->data['total_income'] = $this->dashboard->get_total_income($school_id);
        
                 
        $this->data['sents'] = $this->dashboard->get_message_list($type = 'sent');
        $this->data['drafts'] = $this->dashboard->get_message_list($type = 'draft');
        $this->data['trashs'] = $this->dashboard->get_message_list($type = 'trash');
        $this->data['inboxs'] = $this->dashboard->get_message_list($type = 'inbox');
        $this->data['new'] = $this->dashboard->get_message_list($type = 'new');
        
        $this->data['school_setting'] = $this->school_setting;
        $this->data['schools'] = $this->schools;
        
        $stats = array();
        
        foreach($this->data['schools'] as $obj){
            
            $arr = array();
            
            $total_class = $this->dashboard->get_total_class($obj->id);
            $total_student = $this->dashboard->get_total_student($obj->id);
            $total_teacher = $this->dashboard->get_total_teacher($obj->id);
            $total_employee = $this->dashboard->get_total_employee($obj->id);
            $total_income = $this->dashboard->get_total_income($obj->id);
            $total_expenditure = $this->dashboard->get_total_expenditure($obj->id);
            
            $arr[] = $total_class > 0 ? $total_class : 0;
            $arr[] = $total_student > 0 ? $total_student : 0;
            $arr[] = $total_teacher > 0 ? $total_teacher : 0;
            $arr[] = $total_employee > 0 ? $total_employee : 0;
            $arr[] = $total_income > 0 ? $total_income : 0;
            $arr[] = $total_expenditure > 0 ? $total_expenditure : 0;

            $stats[$obj->id] = $arr;
              
        } 
        
        $this->data['stats'] = $stats;
        
        $this->layout->title($this->lang->line('dashboard') . ' | ' . SMS);
        $this->layout->view('dashboard', $this->data);
        
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
