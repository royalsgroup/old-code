<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Report.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Report
 * @description     : Manage all reports of the system.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Report extends My_Controller {

    public $data = array();
    public $date_from = '';
    public $date_to = '';

    public function __construct() {
        parent::__construct();
        
        $this->load->model('Report_Model', 'report', true);
        $this->load->helper('report');
        $this->load->model("accounting/Invoice_Model",'invoice', true);

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $school_id = $this->session->userdata('school_id');
            $this->data['academic_years'] = $this->report->get_list('academic_years', array( 'school_id'=>$school_id));
        }
        
        $this->date_from = date('Y-m-01');
        $this->date_to = date('Y-m-t');
    }

        
    /*****************Function income**********************************
    * @type            : Function
    * @function name   : income
    * @description     : Load Income report user interface                 
    *                    with various filtering options
    *                    and process to load income report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function income() {


        redirect('dashboard');
        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');

            if ($group_by == 'daily') {
                $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
                $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
            } else {
                $date_from = '';
                $date_to = '';
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;
            
            

            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['income'] = $this->report->get_income_report($school_id, $academic_year_id, $group_by, $date_from, $date_to);


                     
        }  else {
            $sql = "SELECT I.*, SUM(T.amount) AS total_amount, T.payment_date, H.title AS head, AY.session_year 
                FROM invoices AS I                
                LEFT JOIN income_heads AS H ON H.id = I.income_head_id 
                LEFT JOIN transactions AS T ON T.invoice_id = I.id 
                LEFT JOIN academic_years AS AY ON AY.id = I.academic_year_id 
                WHERE I.status = 1";
                $data1 =  $this->db->query($sql)->result();
                if(count($data1)>0){
               $this->data['income'] =$data1;
            }
        }
        
        

        $this->data['report_url'] = site_url('report/income');
        
        $this->layout->title($this->lang->line('income') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('income/index', $this->data);
        
    }

    
    
        
    /*****************Function expenditure**********************************
    * @type            : Function
    * @function name   : expenditure
    * @description     : Load expenditure report user interface                 
    *                    with various filtering options
    *                    and process to load expenditure report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function expenditure() {

        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');

            if ($group_by == 'daily') {
                $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
                $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
            } else {
                $date_from = '';
                $date_to = '';
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['expenditure'] = $this->report->get_expenditure_report($school_id, $academic_year_id, $group_by, $date_from, $date_to);
        }
         else {

        	$sql = "SELECT E.*, SUM(E.amount) AS total_amount, H.title AS head, AY.session_year $group_by_field 
                FROM expenditures AS E 
                LEFT JOIN expenditure_heads AS H ON H.id = E.expenditure_head_id 
                LEFT JOIN academic_years AS AY ON AY.id = E.academic_year_id 
                WHERE E.status = 1 ";
                $data1 = $this->db->query($sql)->result();
                  
                if(count($data1)>0){
                	$this->data['expenditure'] =$data1;
                }


        }
        
        $this->data['report_url'] = site_url('report/expenditure');
        $this->layout->title($this->lang->line('expenditure') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('expenditure/index', $this->data);
    }

    
        
        
    /*****************Function invoice**********************************
    * @type            : Function
    * @function name   : invoice
    * @description     : Load invoice report user interface                 
    *                    with various filtering options
    *                    and process to load invoice report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function invoice() {

        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');

            if ($group_by == 'daily') {
                $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
                $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
            } else {

                $date_from = '';
                $date_to = '';
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['invoice'] = $this->report->get_invoice_report($school_id, $academic_year_id, $group_by, $date_from, $date_to);
        }  else {
                
            //     $sql = "SELECT I.*, SUM(I.net_amount) AS total_amount, SUM(I.discount) AS total_discount, H.title AS head, AY.session_year $group_by_field 
            //     FROM invoices AS I               
            //     LEFT JOIN income_heads AS H ON H.id = I.income_head_id 
            //     LEFT JOIN academic_years AS AY ON AY.id = I.academic_year_id 
            //     LEFT JOIN classes AS C ON C.id = I.class_id 
            //     WHERE I.status = 1 AND I.invoice_type != 'income'  and I.emi_type is null";

            //      $data1 = $this->db->query($sql)->result();
                  
            //  if(count($data1)>0){
            //         $this->data['invoice'] =$data1;
            //     }
            $this->data['invoice'] =  array();
            error($this->lang->line('select_school'));
        }

        $this->data['report_url'] = site_url('report/invoice');
        $this->layout->title($this->lang->line('invoice') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

     
        
    /*****************Function duefees**********************************
    * @type            : Function
    * @function name   : duefees
    * @description     : Load duefees report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function duefee() {
      
		$school_id=null;
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $student_id = $this->input->post('student_id');
                
            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['student_id'] = $student_id;
            $this->data['class_id'] = $class_id;  
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['sbalance'] = $this->report->get_student_due_fee_report($school_id, $academic_year_id, $class_id, $student_id);
          // echo $this->db->last_query();

            
        } else {
            //   $this->db->select('I.*, SUM(T.amount) AS paid_amount, IH.title AS head, C.name AS class_name, ST.name AS student,  AY.session_year');
            // $this->db->from('invoices AS I');   
            // $this->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
            // $this->db->join('students AS ST', 'ST.id = I.student_id', 'left');
            // $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
            // $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
            // $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
            // $this->db->where('I.paid_status !=', 'paid');
            // $this->db->where('I.emi_type', null);
            // $this->db->group_by('I.id', 'DESC'); 
              
            // ==$this->data['sbalance'] =  $this->db->get()->result();
             $this->data['sbalance'] =  0;
            error($this->lang->line('select_school'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $school_id = $this->session->userdata('school_id');
            $this->data['classes'] = $this->report->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');

            
        }
		$this->data['school_id'] = $school_id;
        
        $this->data['report_url'] = site_url('report/duefee');
        $this->layout->title($this->lang->line('due_fee') . ' ' .$this->lang->line('invoice') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('invoice/duefee', $this->data);
    }    
    
    
    /*****************Function feecollection**********************************
    * @type            : Function
    * @function name   : feecollection
    * @description     : Load fee collection report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function feecollection() {

        check_permission(VIEW);
	$school_id=null;
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $student_id = $this->input->post('student_id');
            $fee_type = $this->input->post('fee_type');
                     
            $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : '';
            $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : '';
                  
            
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;   
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['fee_type'] = $fee_type;
            $this->data['student_id'] = $student_id;
            $this->data['class_id'] = $class_id; 
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['feecollection'] = $this->report->get_student_fee_collection_report($school_id, $academic_year_id, $class_id, $student_id, $fee_type, $date_from, $date_to);
        } else {

            $this->db->select('T.*, T.note,ST.name AS student, C.name AS class_name, IH.title AS head, AY.session_year');
            $this->db->from('transactions AS T');   
            $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
            $this->db->join('students AS ST', 'ST.id = I.student_id', 'left');
            $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
            $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
            $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');

             $this->data['feecollection'] = $this->db->get()->result();

        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            
            $school_id = $this->session->userdata('school_id');

            $this->data['classes'] = $this->report->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $this->data['fee_types'] = $this->report->get_list('income_heads', array('status' => 1, 'head_type !='=> 'income', 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        }
        $this->data['school_id'] = $school_id;
        $this->data['report_url'] = site_url('report/feecollection');
        $this->layout->title($this->lang->line('fee') . ' ' .$this->lang->line('collection') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('invoice/fee_collection', $this->data);
        
    }
    
        
    /*****************Function balance**********************************
    * @type            : Function
    * @function name   : balance
    * @description     : Load balance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function balance() {

        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');

            if ($group_by == 'daily') {
                $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
                $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
            } else {

                $date_from = '';
                $date_to = '';
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;


            if ($group_by == 'daily') {
                $this->data['balance'] = $this->_get_daily_balance_data($school_id, $date_from, $date_to);
            } else {
                $this->data['expenditure'] = $this->report->get_expenditure_report($school_id,$academic_year_id, $group_by, $date_from, $date_to);
                $this->data['income'] = $this->report->get_income_report($school_id, $academic_year_id, $group_by, $date_from, $date_to);
                $this->data['balance'] = $this->_combine_balance_data($this->data['expenditure'], $this->data['income']);
            }
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
        }

        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title($this->lang->line('balance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('balance/index', $this->data);
    }
      
    /*****************Function balance**********************************
    * @type            : Function
    * @function name   : balance
    * @description     : Load balance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function student_statics() {
             check_permission(VIEW,1);
        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
        $this->data['student_data'] = array();

        if ($_POST) {
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            if($report_type != "class")
            {
                $school_id= 0;
            }
            if($report_type != "school")
            {
                $district_id =0;
            }
            $this->data['filter_type'] = $report_type;
            switch($report_type )
            {
                case "class":
                    $filter_column = "class_id";
                    $filter_column_name = "class_name";
                    break;
                case "school":
                    $filter_column = "school_id";
                    $filter_column_name = "school_name";
                    break;
                case "prant":
                    $filter_column = "zone_id";
                    $filter_column_name = "zone_name";
                    break;
                case "district":
                    $filter_column = "district_id";
                    $filter_column_name = "district_name";
                    break;
            }
           
            if($report_type != "class" && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
            {
                // error($this->lang->line('permission_denied'));
                // redirect('dashboard');
            }
           
            if($report_type != "school" && !($this->session->userdata('role_id') == SUPER_ADMIN))
            {
                // error($this->lang->line('permission_denied'));
                // redirect('dashboard');
            }
          
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                $school_id=$this->session->userdata('school_id');
            }
            if(!$school_id)
            {
                if($district_id)
                {
                    $schools = get_school_list($district_id);
                    $this->data['district_id'] = $district_id;
                }
                else if($this->session->userdata('role_id') == SUPER_ADMIN){
                    $schools = get_school_list();
                }
                else if($this->session->userdata('dadmin') == 1){
                    $schools = get_school_list($this->session->userdata('district_id'));
                }
            }
            else
            {
                $school = new stdClass();
                $school->id = $school_id;
                $schools = [$school];
                $this->data['school_id'] = $school_id;
                $school = $this->report->get_school_by_id($school_id);
                $filter_head = $school->school_name;
             

            }
          
            foreach($schools as $school_obj)
            {
                $school_id = $school_obj->id;
                $condition['school_id'] = $school_id;
                $academic_years = $this->report->get_academic_years($school_id);
                if(!empty( $academic_years))
                {
                    if($school_id)
                    {
                        $this->data['current_year'] = $academic_years->curr_start;
                        $this->data['prev_year']    = $academic_years->prev_start_year;
                    }
                    $current_academic_year = $academic_years->id ? $academic_years->id  : 0;
                    $prev_academic_year = $academic_years->prev ? $academic_years->prev  : 0;
                   
                    $students_data = $this->report->get_student_data($school_id,$current_academic_year,$prev_academic_year,$filter_column);
                    if($report_type == "class" ) {
                        $class_order =array();
                        $iClassCount = 0;
                    }
                   
                    foreach($students_data as $obj)
                    {
                        $filter_id = $obj->$filter_column;
                        if(!isset($previous_year_boys_students[$filter_id] )) $previous_year_boys_students[$filter_id] = array();
                        if(!isset($current_year_boys_students[$filter_id] )) $current_year_boys_students[$filter_id] = array();
                        if(!isset($previous_year_girls_students[$filter_id] )) $previous_year_girls_students[$filter_id] = array();
                        if(!isset($current_year_girls_students[$filter_id] )) $current_year_girls_students[$filter_id] = array();
                       
                        if($report_type == "class" && !in_array($filter_id, $class_order) ) {
                            $class_order[$iClassCount] = $filter_id;
                            $iClassCount++;
                        }
                        if(!isset($student_data[$filter_id]))
                        {
                            $student_data[$filter_id] = array("filter_name" =>'',"prev"=>array(),"curr"=>array());
                            $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                            $student_data[$filter_id]['prev'] = $prev_data = array('boys'=>[],'girls'=>[],'total'=>[]);
                            $student_data[$filter_id]['curr'] = $curr_data = array('boys'=>[],'girls'=>[],'total'=>[],"tc_boys"=>[],"tc_girls"=>[]);
                        }
                        else
                        {
                            $prev_data = $student_data[$filter_id]['prev'];
                            $curr_data = $student_data[$filter_id]['curr'];
                        }
                        if($obj->academic_year_id == $current_academic_year)
                        {
                           
                            if($obj->gender == "male" )
                            {
                                $current_year_boys_students[$filter_id][] = $obj->student_id;
                                if ($obj->status_type != "regular") {
                                    $curr_data['tc_boys'][] =  $obj->student_id;
                                }
                                $curr_data['boys'][] =  $obj->student_id;

                            }
                            else
                            {
                                $current_year_girls_students[$filter_id][] = $obj->student_id;
                                if ($obj->status_type != "regular") {
                                    $curr_data['tc_girls'][] =  $obj->student_id;
                                }
                                $curr_data['girls'][] =  $obj->student_id;
                            }                         
                        }
                        else  if($obj->academic_year_id ==  $prev_academic_year)
                        {
                            if ($obj->status_type != "regular") {
                                continue;
                            }
                            $previous_year_students[$filter_id][] = $obj->student_id;
                            if($obj->gender == "male" )
                            {
                                $previous_year_boys_students[$filter_id][] = $obj->student_id;
                                $prev_data['boys'][] =   $obj->student_id;
                            }
                            else
                            {
                                $previous_year_girls_students[$filter_id][] = $obj->student_id;
                                $prev_data['girls'][] =   $obj->student_id;
                            }
                        }
    
                        $student_data[$filter_id]['prev'] = $prev_data ;
                        $student_data[$filter_id]['curr'] = $curr_data ;
                    }
                   
                }
                $this->data['previous_year_boys_students'] = $previous_year_boys_students;
                $this->data['previous_year_girls_students'] = $previous_year_girls_students;
                $this->data['current_year_girls_students'] = $current_year_girls_students;
                $this->data['current_year_boys_students'] = $current_year_boys_students;
                $this->data['student_data'] = $student_data;
                if($report_type == "class" ) {
                    $this->data['class_order'] = $class_order;
                }
               
            }

            }
            if (isset($filter_head)) {
                $this->data['filter_head'] =  $filter_head;
            }
       
           
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Student statics | ' . SMS);
        $this->layout->view('student/statics_new', $this->data);
    }

    
    /*****************Function _get_daily_balance_data**********************************
    * @type            : Function
    * @function name   : _get_daily_balance_data
    * @description     : format the daily balanace report data for user friendly data presentation                
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _get_daily_balance_data($school_id, $date_from, $date_to) {

        $data = array();

        $days = date('d', strtotime($date_to));

        for ($i = 0; $i < $days; $i++) {

            $date = date('Y-m-d', strtotime($date_from . '+' . $i . ' day'));
            $data[$i]['expenditure'] = $this->report->get_expenditure_by_date($school_id, $date);
            $data[$i]['income'] = $this->report->get_income_by_date($school_id, $date);
            $data[$i]['group_by_field'] = date($this->global_setting->date_format, strtotime($date));
        }

        return $data;
    }

        
                                        
    /*****************Function _combine_balance_data**********************************
    * @type            : Function
    * @function name   : _combine_balance_data
    * @description     : combined expenditure and income report data for user friendly data presentation                
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _combine_balance_data($expenditure, $income) {
        $data = array();
        foreach ($expenditure as $obj) {
            $data[$obj->group_by_field]['expenditure'] = $obj->total_amount;
            $data[$obj->group_by_field]['group_by_field'] = $obj->group_by_field;
            if (empty($data[$obj->group_by_field]['income'])) {
                $data[$obj->group_by_field]['income'] = 0;
            }
        }
        foreach ($income as $obj) {
            $data[$obj->group_by_field]['income'] = $obj->total_amount;
            $data[$obj->group_by_field]['group_by_field'] = $obj->group_by_field;

            if (empty($data[$obj->group_by_field]['expenditure'])) {
                $data[$obj->group_by_field]['expenditure'] = 0;
            }
        }
        return $data;
    }

    
                
        
    /*****************Function library**********************************
    * @type            : Function
    * @function name   : library
    * @description     : Load library report user interface                 
    *                    with various filtering options
    *                    and process to load library report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function library() {

        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');

            if ($group_by == 'daily') {
                $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
                $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
            } else {

                $date_from = '';
                $date_to = '';
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['library'] = $this->report->get_library_report($school_id, $academic_year_id, $group_by, $date_from, $date_to);
  
        }

        $this->data['report_url'] = site_url('report/library');
        $this->layout->title($this->lang->line('library') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('library/index', $this->data);
    }

    
            
    /*****************Function sattendance**********************************
    * @type            : Function
    * @function name   : sattendance
    * @description     : Load student attendance report user interface                 
    *                    with various filtering options
    *                    and process to load student attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function sattendance() {

        check_permission(VIEW);

        $this->data['month_number'] = 1;       
        $this->data['days'] = 31;
        $school_id=null;
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $month = $this->input->post('month');


            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['month'] = $month;
            $this->data['month_number'] = date('m', strtotime($this->data['month']));
            
            $session = $this->report->get_single('academic_years', array('id' => $academic_year_id, 'school_id'=>$school_id));            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['students'] = $this->report->get_student_list($school_id, $academic_year_id, $class_id, $section_id);
            
            $this->data['year'] = substr($session->session_year, 7);
            $this->data['days'] =  @date('t', mktime(0, 0, 0, $this->data['month_number'], 1, $this->data['year']));
            //$this->data['days'] = cal_days_in_month(CAL_GREGORIAN, $this->data['month_number'], $this->data['year']);
        }



        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
            $school_id=$this->session->userdata('school_id');
            $condition['school_id'] = $school_id;  
            $this->data['classes'] = $this->report->get_list('classes', $condition);
        }
        $this->data['school_id'] = $school_id;

        $this->data['report_url'] = site_url('report/sattendance');
        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('sattendance/index', $this->data);
        
    }

                
    /*****************Function syattendance**********************************
    * @type            : Function
    * @function name   : syattendance
    * @description     : Load student yearly attendance report user interface                 
    *                    with various filtering options
    *                    and process to load student yearly attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function syattendance() {

        check_permission(VIEW);
		$school_id=null;
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');

            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['student_id'] = $student_id;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
        }


        $this->data['days'] = 31;

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){    
            $school_id=$this->session->userdata('school_id');
            $condition['school_id'] = $school_id;  
            $this->data['classes'] = $this->report->get_list('classes', $condition);
        }
$this->data['school_id'] = $school_id;
        $this->data['report_url'] = site_url('report/syattendance');
        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('sattendance/yearly', $this->data);
    }

                    
    /*****************Function tattendance**********************************
    * @type            : Function
    * @function name   : tattendance
    * @description     : Load teacher attendance report user interface                 
    *                    with various filtering options
    *                    and process to load teacher attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function tattendance() {

        check_permission(VIEW);

        $this->data['month_number'] = 1;
        $this->data['days'] = 31;
                
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $month = $this->input->post('month');

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['month'] = $month;
            $this->data['month_number'] = date('m', strtotime($this->data['month']));
            
            $this->data['teachers'] = $this->report->get_list('teachers', array('status' => 1, 'school_id'=>$school_id));
            $session = $this->report->get_single('academic_years', array('id' => $academic_year_id, 'school_id'=>$school_id));
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            $this->data['year'] = substr($session->session_year, 7);
            //$this->data['days'] = cal_days_in_month(CAL_GREGORIAN, $this->data['month_number'], $this->data['year']);
            $this->data['days'] =  @date('t', mktime(0, 0, 0, $this->data['month_number'], 1, $this->data['year']));
        }

        $this->data['report_url'] = site_url('report/tattendance');
        $this->layout->title($this->lang->line('teacher') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('tattendance/index', $this->data);
    }

                        
    /*****************Function tyattendance**********************************
    * @type            : Function
    * @function name   : tyattendance
    * @description     : Load teacher yearly attendance report user interface                 
    *                    with various filtering options
    *                    and process to load teacher yearly attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function tyattendance() {

        check_permission(VIEW);
		$school_id=null;
        if ($_POST) {
	
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $teacher_id = $this->input->post('teacher_id');

            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['teacher_id'] = $teacher_id;  
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $school_id=$this->session->userdata('school_id');
            $condition['school_id'] = $school_id;        
            $this->data['teachers'] = $this->report->get_list('teachers', $condition);
            
        } 
           
        $this->data['school_id'] = $school_id;
        $this->data['days'] = 31;
        $this->data['report_url'] = site_url('report/tyattendance');
        $this->layout->title($this->lang->line('teacher') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('tattendance/yearly', $this->data);
    }

                            
    /*****************Function eattendance**********************************
    * @type            : Function
    * @function name   : eattendance
    * @description     : Load Employee attendance report user interface                 
    *                    with various filtering options
    *                    and process to load Employee attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function eattendance() {

        check_permission(VIEW);

        $this->data['month_number'] = 1;
        $this->data['days'] = 31;
        
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $month = $this->input->post('month');

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['month'] = $month;
            $this->data['month_number'] = date('m', strtotime($this->data['month']));
            
            $this->data['employees'] = $this->report->get_list('employees', array('status' => 1, 'school_id'=>$school_id));            
            $session = $this->report->get_single('academic_years', array('id' => $academic_year_id, 'school_id'=>$school_id));
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            $this->data['year'] = substr($session->session_year, 7);
            //$this->data['days'] = cal_days_in_month(CAL_GREGORIAN, $this->data['month_number'], $this->data['year']);
            $this->data['days'] =  @date('t', mktime(0, 0, 0, $this->data['month_number'], 1, $this->data['year']));
        }


        $this->data['report_url'] = site_url('report/eattendance');
        $this->layout->title($this->lang->line('employee') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('eattendance/index', $this->data);
    }

                                
    /*****************Function eyattendance**********************************
    * @type            : Function
    * @function name   : eyattendance
    * @description     : Load Employee yearly attendance report user interface                 
    *                    with various filtering options
    *                    and process to load Employee yearly attendance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function eyattendance() {

        check_permission(VIEW);
$school_id=null;
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $employee_id = $this->input->post('employee_id');

           
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['employee_id'] = $employee_id;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
        }

        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            
			$school_id=$this->session->userdata('school_id');  
            $condition['school_id'] = $school_id;
            $this->data['employees'] = $this->report->get_list('employees', $condition);
        } 
        
		 $this->data['school_id'] = $school_id;
        $this->data['days'] = 31;

        $this->data['report_url'] = site_url('report/eyattendance');
        $this->layout->title($this->lang->line('employee') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('eattendance/yearly', $this->data);
    }
    
    
                                    
    /*****************Function student**********************************
    * @type            : Function
    * @function name   : student
    * @description     : Load student report user interface                 
    *                    with various filtering options
    *                    and process to load student report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function student(){
        
        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by');           

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            $this->data['students'] = $this->report->get_student_report($school_id, $academic_year_id, $group_by);
            $this->data['students'] = $this->_get_pormatted_student_report($school_id, $group_by, $this->data['students']);
   
        }

        $this->data['report_url'] = site_url('report/student');
        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }
    
    
                                        
    /*****************Function _get_pormatted_student_report**********************************
    * @type            : Function
    * @function name   : _get_pormatted_student_report
    * @description     : Format the student report for better representation                 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _get_pormatted_student_report($school_id, $group_by,$students = null){
        
        $data = array();
        if(!empty($students)){
            foreach($students as $obj){
                
                $male = $this->report->get_student_by_gender($school_id, $group_by, $obj->class_id, $obj->academic_year_id, 'male');
                $obj->male = $male ? $male : 0;
                $female = $this->report->get_student_by_gender($school_id, $group_by, $obj->class_id, $obj->academic_year_id, 'female');
                $obj->female = $female ? $female : 0;
                $data[] = $obj;
            }
            
        }
        
        return $data;
    }
    
    
        
    /*****************Function sbalance**********************************
    * @type            : Function
    * @function name   : sbalance
    * @description     : Load sbalance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function sinvoice() {

        check_permission(VIEW);
		$school_id=null;
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $student_id = $this->input->post('student_id');
                      
            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['student_id'] = $student_id;
            $this->data['class_id'] = $class_id;         

             if($academic_year_id){
                $this->data['academic_year'] = $this->db->get_where('academic_years', array('id'=>$academic_year_id))->row()->session_year;
            }
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            $this->data['sbalance'] = $this->report->get_student_invoice_report($school_id, $academic_year_id, $class_id, $student_id);
            
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $school_id=$this->session->userdata('school_id');  
            $condition['school_id'] = $school_id;  
            $this->data['classes'] = $this->report->get_list_new('classes', $condition, '', '', '', 'id', 'ASC');
        } 
        $this->data['school_id'] = $school_id;
        $this->data['report_url'] = site_url('report/sinvoice');
        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('invoice') . ' ' .$this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('student/sinvoice', $this->data);
        
    }
    
    
            
    /*****************Function sactivity**********************************
    * @type            : Function
    * @function name   : sactivity
    * @description     : Load balance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function sactivity() {

        check_permission(VIEW);
		$school_id=null;
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $student_id = $this->input->post('student_id');
                      
            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['student_id'] = $student_id;
            $this->data['class_id'] = $class_id;   
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            if($academic_year_id){
                $this->data['academic_year'] = $this->db->get_where('academic_years', array('id'=>$academic_year_id))->row()->session_year;
            }
            
            $this->data['activities'] = $this->report->get_student_activity_report($school_id, $academic_year_id, $class_id, $student_id);
            
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $school_id= $this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id;
            $this->data['classes'] = $this->report->get_list_new('classes', $condition, '', '', '', 'id', 'ASC');
        } 
        $this->data['school_id'] = $school_id;
        $this->data['report_url'] = site_url('report/sactivity');
        $this->layout->title($this->lang->line('activity') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('student/activity', $this->data);
    }    
    

    
        
        
    /*****************Function payroll**********************************
    * @type            : Function
    * @function name   : payroll
    * @description     : Load payroll report user interface                 
    *                    with various filtering options
    *                    and process to load payroll report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function payroll() {

        check_permission(VIEW);

        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $group_by = $this->input->post('group_by'); 
            $month = $this->input->post('month');
            $payment_to = $this->input->post('payment_to');
            $group_id = $this->input->post('group_id');

            
            $this->data['school_id'] = $school_id;
            $this->data['group_id'] = $group_id;

            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['group_by'] = $group_by;
            $this->data['payment_to'] = $payment_to;          
            $this->data['month'] = $month;
            $this->data['groups'] = $this->report->payroll_groups($school_id);
            
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            if($academic_year_id){
                $this->data['academic_year'] = $this->db->get_where('academic_years', array('id'=>$academic_year_id, 'school_id'=>$school_id))->row()->session_year;
            }

            $this->data['payrolls'] = $this->report->get_payroll_report($school_id, $academic_year_id, $group_by, $payment_to, $month);
        }
        $this->data['groups'] = $this->report->payroll_groups($school_id);


        $this->data['report_url'] = site_url('report/payroll');
        $this->layout->title($this->lang->line('payroll') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('payroll/index', $this->data);
    }
    
    
        
    
    
    
    
    /*****************Function statement**********************************
    * @type            : Function
    * @function name   : statement
    * @description     : Load balance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function statement() {

        check_permission(VIEW);

        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
            $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
                      
            $this->data['school_id'] = $school_id;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;
          
            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
        
            
            $this->data['statement'] = $this->_get_daily_actbalance_data($school_id, $date_from, $date_to);
          
        }
        
        $this->data['report_url'] = site_url('report/statement');
        $this->layout->title($this->lang->line('statement') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('balance/statement', $this->data);
    }
    
    /*****************Function _get_daily_actbalance_data**********************************
    * @type            : Function
    * @function name   : _get_daily_actbalance_data
    * @description     : format the daily balanace report data for user friendly data presentation                
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _get_daily_actbalance_data($school_id, $date_from, $date_to) {

        $data = array();

        $start = strtotime($date_from);
        $end   = strtotime($date_to);
        $days  = ceil(abs($end - $start) / 86400)+1;
        $j = 0;
        for ($i = 0; $i < $days; $i++) {           

            $date = date('Y-m-d', strtotime($date_from . '+' . $i . ' day'));
            
            $expenditure = $this->report->get_debit_by_date($school_id, $date);
            if(!empty($expenditure)){
                
                foreach($expenditure as $exp){
                    $data[$j+1]['head'] = $exp->head;                       
                    $data[$j+1]['debit'] = $exp->debit;                       
                    $data[$j+1]['credit'] = 0;                       
                    $data[$j+1]['gross'] = $exp->debit;                      
                    $data[$j+1]['tax'] = 0;                      
                    $data[$j+1]['note'] = $exp->note;                       
                    $data[$j+1]['date'] = $date; 
                    
                    $j++;
                }
            }
            
            $income = $this->report->get_credit_by_date($school_id, $date);
            if(!empty($income)){
                
                foreach($income as $inc){
                    $data[$j+1]['head'] = $inc->head;                       
                    $data[$j+1]['debit'] = 0;                       
                    $data[$j+1]['credit'] = $inc->credit;                        
                    $data[$j+1]['gross'] = $inc->credit;                      
                    $data[$j+1]['tax'] = 0;                      
                    $data[$j+1]['note'] = $inc->note;                       
                    $data[$j+1]['date'] = $date; 
                    
                    $j++;
                }
            }
            
        }

        return $data;
        
    }

    
        
    /*****************Function transaction**********************************
    * @type            : Function
    * @function name   : transaction
    * @description     : Load balance report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function transaction() {

        check_permission(VIEW);

        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $date_from = $this->input->post('date_from') ? date('Y-m-d', strtotime($this->input->post('date_from'))) : $this->date_from;
            $date_to = $this->input->post('date_to') ? date('Y-m-d', strtotime($this->input->post('date_to'))) : $this->date_to;
                      
            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['date_from'] = $date_from;
            $this->data['date_to'] = $date_to;     

            $this->data['school'] = $this->report->get_school_by_id($school_id);
            
            if($academic_year_id){
                $this->data['academic_year'] = $this->db->get_where('academic_years', array('id'=>$academic_year_id, 'school_id'=>$school_id))->row()->session_year;
            }
            
            $this->data['transaction'] = $this->report->get_transaction_report($school_id, $academic_year_id, $date_from, $date_to);
            
        }
        
        $this->data['report_url'] = site_url('report/transaction');
        $this->layout->title($this->lang->line('transaction') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('balance/transaction', $this->data);
    }    
    
    
     
    /*****************Function examresult**********************************
    * @type            : Function
    * @function name   : examresult
    * @description     : Load examresult report user interface                 
    *                    with various filtering options
    *                    and process to load balance report   
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function examresult() {

        check_permission(VIEW);
		$school_id=null;
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
           
            
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['class_id'] = $class_id;         
            $this->data['section_id'] = $section_id;  
            
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['class'] = $this->db->get_where('classes', array('id'=>$class_id, 'school_id'=>$school_id))->row()->name;
            
            if($section_id){
                $this->data['section'] = $this->db->get_where('sections', array('id'=>$section_id, 'school_id'=>$school_id))->row()->name;
            }
            
            $this->data['academic_year'] = $this->db->get_where('academic_years', array('id'=>$academic_year_id, 'school_id'=>$school_id))->row()->session_year;
            $this->data['examresult'] = $this->report->get_student_examresult_report($school_id, $academic_year_id, $class_id, $section_id);
        }        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] =  $school_id;
            $this->data['classes'] = $this->report->get_list_new('classes', $condition, '', '', '', 'id', 'ASC');
        }        
        $this->data['school_id'] = $school_id;
        $this->data['report_url'] = site_url('report/examresult');
        $this->layout->title($this->lang->line('exam') . ' ' .$this->lang->line('result') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('student/exam_result', $this->data);
        
    }
    public function teacher_report() {
    
        check_permission(VIEW,1);
        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');		
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";
        if ($_POST) {
           
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $zone_id = $this->input->post('zone_id');
            
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
           
            switch($report_type )
            {
                case "kshetra":
                    $filter_column = "zone_id";
                    $filter_column_name = "zone_name";
                    $filter_heading  = "Prant Name";
                    break;
                case "school":
                    $filter_column = "faculty_id";
                    $filter_column_name = "faculty_name";
                    $filter_heading  = "Teaching Class Level";
                    break;
                case "prant":
                    $filter_column = "district_id";
                    $filter_column_name = "district_name";
                    $filter_heading  = "District Name";
                    break;
                case "district":
                    $filter_column = "school_id";
                    $filter_column_name = "school_name";
                    $filter_heading  = "School Name";
                    break;
            }
            $this->data['filter_heading'] = $filter_heading ;
         
           

          
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                if($report_type != "school" )
                {
                    error($this->lang->line('permission_denied'));
                    redirect('dashboard');
                }
                $school_id=$this->session->userdata('school_id');
            }
            if(!$school_id)
            {   if($report_type == "district")
                {
                    $district = $this->report->get_single('districts', array('id' => $district_id));            
                    $this->data['table_heading'] = $district->name;
                }
                else if($report_type == "prant")
                {
                    $zone = $this->report->get_single('zone', array('id' => $zone_id));            
                    $this->data['table_heading'] = $zone->name;           
                }
                if($district_id)
                {
                    $schools = $this->report->get_school_list_teacher_count($school_id,$district_id,$zone_id);
                    $this->data['district_id'] = $district_id;
                }
                else if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,null,$zone_id);
                }
                else if($this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,$this->session->userdata('district_id'),$zone_id);
                }
            }
            else
            {

                $schools = $this->report->get_school_list_teacher_count($school_id,$this->session->userdata('district_id'));
                $school = $this->report->get_school_by_id($school_id);
                $this->data['table_heading'] = $school->name;

            }
       
            foreach($schools as $school_obj)
            {
                $school_id = $school_obj->id;
                $looping_school_id  = 0;
                $condition['school_id'] = $school_id;
                $academic_years = $this->report->get_academic_years($school_id);
                
                if(!empty( $academic_years))
                {
                    if( isset($this->data['school_id']) && $this->data['school_id'])
                    {
                        $this->data['current_year'] = $academic_years->curr_start."-".$academic_years->curr_end;
                    }
                    $current_academic_year = $academic_years->id ? $academic_years->id  : 0;
                   
                    $students_data = $this->report->get_student_data ($school_id,$current_academic_year,null,$filter_column, null, 1);
                  
                    $looping_class_id = 0;
                    foreach($students_data as $obj)
                    {                       
                        $filter_id = $obj->$filter_column;
                        $class_id  = $obj->class_id;
                       
                        if(!isset($current_year_boys_students[$filter_id] )) $current_year_boys_students[$filter_id] = array();
                        if(!isset($current_year_girls_students[$filter_id] )) $current_year_girls_students[$filter_id] = array();
                        if(!isset($student_data[$filter_id]))
                        {
                            $student_data[$filter_id] = array("filter_name" =>'',"curr"=>array(),'male_teachers'=>array(),'female_teachers'=>array());
                            $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                            $student_data[$filter_id]['curr'] = $curr_data = array('boys'=>[],'girls'=>[],'total'=>[]);
                            $student_data[$filter_id]['male_teacher_count'] = 0;
                            $student_data[$filter_id]['female_teacher_count'] = 0;
                            $student_data[$filter_id]['section_count'] = 0;
                            $student_data[$filter_id]['section_count_'] = array();
                           
                        }
                        else
                        {
                            $curr_data = $student_data[$filter_id]['curr'];
                        }
                        if($report_type == "school")
                        {
                            if($class_id != $looping_class_id)
                            {
                                $section_count             = isset($obj->section_count) && $obj->section_count ? $obj->section_count : 0  ;
                                $student_data[$filter_id]['section_count'] =  $student_data[$filter_id]['section_count'] +  $section_count  ;
                                $looping_class_id = $class_id;
                            }
                        }
                        if( $obj->section_id)
                        {
                            $student_data[$filter_id]['section_count_'][] =  $obj->section_id;
                        }
                        
                        if($school_id != $looping_school_id)
                        {
                            if($report_type != "school")
                            {
                                $student_data[$filter_id]['section_count'] = $student_data[$filter_id]['section_count'] + $school_obj->section_count;
                            }
                           
                            $looping_school_id = $school_id;
                            $student_data[$filter_id]['male_teacher_count'] = $student_data[$filter_id]['male_teacher_count'] + $school_obj->male_teacher_count;
                            $student_data[$filter_id]['female_teacher_count'] = $student_data[$filter_id]['female_teacher_count'] + $school_obj->female_teacher_count;
                        }
                        if($obj->academic_year_id == $current_academic_year)
                        {
                           
                            if($obj->gender == "male" )
                            {
                                $current_year_boys_students[$filter_id][] = $obj->student_id;
                                $curr_data['boys'][] =  $obj->student_id;
                            }
                            else
                            {
                                $current_year_girls_students[$filter_id][] = $obj->student_id;
                                $curr_data['girls'][] = $obj->student_id;
                            }
                         
                        }
                        $student_data[$filter_id]['curr'] = $curr_data ;

                    }
                    
                }
               
               
            }
            $this->data['current_year_girls_students'] = $current_year_girls_students;
            $this->data['current_year_boys_students'] = $current_year_boys_students;
            $this->data['student_data'] = $student_data;

        }

           
        $this->data['school'] = $school;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Teacher Report | ' . SMS);
        $this->layout->view('teacher_report/teacher_report', $this->data);
    }
    
    public function faculty_report() {
        check_permission(VIEW,1);

        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $disciplines = $this->report->get_list('academic_disciplines', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";
	
        $faculties = array();
        foreach($disciplines as $discipline)	
        {
            $faculties[$discipline->id] = $discipline->name;
        }
        $this->data['faculties'] = $faculties;		
        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $zone_id = $this->input->post('zone_id');
            
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
           
            switch($report_type )
            {
                case "kshetra":
                    $filter_column = "SC.zone_id";
                    $filter_column_id = "zone_id";
                    $filter_column_filter = null;
                    $filter_column_filter_value = null;
                    $filter_column_name = "zone_name";
                    $filter_heading  = "Prant Name";
                    break;
                case "school":
                    $filter_column = "faculty_id";
                    $filter_column_name = "faculty_name";
                    $filter_heading  = "Teaching Class Level";
                    break;
                case "prant":
                    $filter_column = "SC.district_id";
                    $filter_column_filter = "SC.zone_id";
                    $filter_column_filter_value = $zone_id;
                    $filter_column_id = "district_id";
                    $filter_column_name = "district_name";
                    $filter_heading  = "District Name";
                    break;
                case "district":
                    $filter_column = "E.school_id";
                    $filter_column_id = "school_id";
                    $filter_column_name = "school_name";
                    $filter_heading  = "School Name";
                    $filter_column_filter = "SC.district_id";
                    $filter_column_filter_value = $district_id;
                    break;
            }
            $this->data['filter_heading'] = $filter_heading ;
          
            if($report_type != "school" && !($this->session->userdata('role_id') == SUPER_ADMIN))
            {
                error($this->lang->line('permission_denied'));
                redirect('dashboard');
            }
            if($report_type == "kshetra" || $report_type == "prant" || $report_type == "district")
            {
               
                $zones =  
                $student_data = array();
                $counts_data =  $this->report->get_class_level_student_count(null,$filter_column, $filter_column_filter, $filter_column_filter_value);
                $heading_cols = array();
                foreach($counts_data as $class_counts_data)
                { 

                    if(!isset($student_data[$class_counts_data->$filter_column_id]))
                    {
                        
                        $student_data[$class_counts_data->$filter_column_id] = array();
                        $student_data[$class_counts_data->$filter_column_id]['filter_name'] =  $class_counts_data->$filter_column_name;
                    }
                    if(!isset($student_data[$class_counts_data->$filter_column_id][$class_counts_data->id]))
                    {

                        $student_data[$class_counts_data->$filter_column_id][$class_counts_data->id] = array();
                    }
                    $class_name = strtoupper($class_counts_data->class_name);
                    if(!in_array($class_name ,$heading_cols[$class_counts_data->id]))
                    {
                        $heading_cols[$class_counts_data->id][] = $class_name ;
                    }
                    if(!isset($student_data[$class_counts_data->$filter_column_id][$class_counts_data->id][$class_name]))
                    {
                        $student_data[$class_counts_data->$filter_column_id][$class_counts_data->id][$class_name]['students'] =  $class_counts_data->count;
                    }
                    else
                    {
                        $student_data[$class_counts_data->$filter_column_id][$class_counts_data->id][$class_name]['students'] = $student_data[$class_counts_data->$filter_column_id][$class_counts_data->id][$class_name]['students'] + $class_counts_data->count;
                    }
                    
                }
                $this->data['heading_cols'] =  $heading_cols ;

            }
            else
            {
                $heading_cols = array();

                if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                    $school_id=$this->session->userdata('school_id');
                }
                if(!$school_id)
                {
                    if($district_id)
                    {
                        $schools = $this->report->get_school_list_teacher_count($school_id,$district_id,$zone_id);
                        $this->data['district_id'] = $district_id;
                    }
                    else if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
                        $schools = $this->report->get_school_list_teacher_count( $school_id,null,$zone_id);
                    }
                    else if($this->session->userdata('dadmin') == 1){
                        $schools = $this->report->get_school_list_teacher_count( $school_id,$this->session->userdata('district_id'),$zone_id);
                    }
                }
                else
                {
    
                    $schools = $this->report->get_school_list_teacher_count($school_id,$this->session->userdata('district_id'));
                    $school = $this->report->get_school_by_id($school_id);
                }
                
                $iCount1 = 0 ;
                foreach($schools as $school_obj)
                {
                    $school_id = $school_obj->id;
                    $looping_school_id  = 0;
                    $condition['school_id'] = $school_id;
                    $academic_years = $this->report->get_academic_years($school_id);
                    
                    if(!empty( $academic_years))
                    {
                        if( isset($this->data['school_id']) && $this->data['school_id'])
                        {
                            $this->data['current_year'] = $academic_years->curr_start."-".$academic_years->curr_end;
                        }
                        $current_academic_year = $academic_years->id ? $academic_years->id  : 0;
                        $students_data = $this->report->get_student_data ($school_id,$current_academic_year,null,$filter_column,true, 1);
                        $looping_class_id = 0;
                        foreach($students_data as $obj)
                        {                       
                            $filter_id = $obj->$filter_column;
                            $class_id  = $obj->class_id;
                            $faculty_id  = $obj->faculty_id;
                            
                            $class_name = $obj->class_name ? strtoupper($obj->class_name) : "";
                            if(!$class_name) continue;
                            if(!isset($student_data[$filter_id]))
                            {
                                $student_data[$filter_id] = array("filter_name" =>'',"curr"=>array(), $faculty_id => array());
                                $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                            }
                            if(!isset($student_data[$filter_id][$faculty_id][$class_name]))
                            {
                                $student_data[$filter_id][$faculty_id][$class_name] =  array();
                                $student_data[$filter_id][$faculty_id][$class_name]['students'] = $students_array = array();
                            }
                            else
                            {
                                $students_array = $student_data[$filter_id][$faculty_id][$class_name]['students'];
                            }
                            if(!isset($heading_cols[$faculty_id]))
                            {
                                $heading_cols[$faculty_id] = array();
                            }
                            if(!in_array($class_name ,$heading_cols[$faculty_id]))
                            {
                                $heading_cols[$faculty_id][] = $class_name ;
                            }
                             if( $obj->section_id)
                            {
                                if($filter_id == 74 && $faculty_id == 1)
                                {
                                    $iCount1++;
    
                                }
                                $student_data[$filter_id]['section_count_'][] =  $obj->section_id;
                            }
                            $students_array[] =  $obj->student_id;
                            $student_data[$filter_id][$faculty_id][$class_name]['students'] = $students_array;
                        }
                    }
                }
                // echo "<pre>";
                // print_r($heading_cols);
                // die();
                $this->data['heading_cols'] =  $heading_cols ;

            }
            
            $this->data['student_data'] = $student_data;
        }
        $this->data['school'] = $school ;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Faculty Report | ' . SMS);
        $this->layout->view('student/class_level_report', $this->data);
    }
    public function fee_report() {
        // error_on();
        check_permission(VIEW,1);
        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
            $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
            $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
            $disciplines = $this->report->get_list('academic_disciplines', array(), '','', '', 'id', 'ASC');          
            $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
            $this->data['student_data'] = array();
            $this->data['fee_types'] = array();
            $school_id = $this->input->post('school_id');
        }
        else {
            $school_id = $this->session->userdata('school_id');
            $this->data['school_id'] =  $school_id ;
            $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
            $this->data['academic_year_id'] = $academic_year->id;
            $this->data['fee_types'] = $this->report->get_list('income_heads', array("school_id"=>$school_id,'academic_year_id'=>  $academic_year->id), '','', '', 'id', 'ASC');
        }
        $this->data['school_id'] =  $school_id ;
        // else
        // {
        //     $school_id = $this->session->userdata('school_id');
        //     $school = $this->report->get_school_by_id($school_id);
        //     $this->data['fee_types'] = $this->report->get_list('income_heads', array("school_id"=>$school_id,'financial_year_id'=>$school->financial_year_id), '','', '', 'id', 'ASC');
        // }
        $this->data['fee_data']  = array();


        $this->data['filter_heading'] = "School Name";
       
        $faculties = array();
      
        if ($_POST) {
          
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $income_head_id = $this->input->post('income_head_id');
            $academic_year_id_filter = $this->input->post('academic_year_id_filter');
            if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
                $school_id = $this->input->post('school_id');

            }
            else
            {
                $this->data['school_id'] =  $school_id ;
            }

            $zone_id = $this->input->post('zone_id');
            if($academic_year_id_filter) {
                $this->data['academic_year_id'] = $academic_year_id_filter;
            }
            else {
                $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	

                $academic_year_id_filter = $academic_year->id;
            }
            $this->data['income_head_id']  = $income_head_id;
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
            switch($report_type )
            {
                case "kshetra":
                    $filter_column = "SC.zone_id";
                    $filter_column_id = "zone_id";
                    $filter_column_filter = null;
                    $filter_column_filter_value = null;
                    $filter_column_name = "zone_name";
                    
                    $filter_heading  = "Prant Name";
                    break;
                case "school":
                    $filter_column = "E.class_id,E.section_id";
                    $filter_column_filter = "SC.id";
                    $students_data = array();
                    $school = $this->report->get_school_by_id($school_id);

                   // $school = $this->report->get_school_by_id($school_id);
                    // $income_head=$this->report->get_single('income_heads',array('school_id'=>$school_id,'head_type'=>'fee','academic_year_id'=>  $academic_year_id_filter ));	
                    $income_head=$this->report->get_single('income_heads',array('id'=>$income_head_id ));	

                    // $income_head_id =  $income_head->id;
                    $this->data['income_head_id'] = $income_head->id;

                    $this->data['fee_type_name'] = $income_head->title;
                    $this->data['fee_type'] = $income_head->head_type;

                    $filter_column_name = "class_name";
                    $filter_column_filter_value = $school_id;
                    $filter_heading  = "Teaching Class Level";
                    $section_datas =  $this->report->get_sections( $school_id,$income_head_id, $income_head->head_type, $academic_year_id_filter );
                    $section_ids = [];
                    $class_ids  = [];
                    $students_counts = [];
                    $rte_students = [];
                    $students_fees = [];
                    $paid_students_count = [];

                    $p_paid_students_count = [];
                    $discount_students_count = [];
                    // debug_a($section_datas);

                    if(!empty($section_datas))
                    {

                    $aSectionFeeAmounts = array();
                    foreach($section_datas as $section_data)
                    { 
                        $section_data->total_fee_amount = 0;
                        if ($income_head->head_type == "transport")
                        {
                            $iStudentCount =  $section_data->member_count; 
                            $yearly_stop_fare = $section_data->yearly_stop_fare ? json_decode($section_data->yearly_stop_fare,true) : array();
                            if($academic_year_id_filter && isset($yearly_stop_fare[$academic_year_id_filter]))
                                $iFeeAmount  = $yearly_stop_fare[$academic_year_id_filter];
                            else
                                $iFeeAmount = $section_data->fee_amount;
                            $section_data->total_fee_amount =  $iFeeAmount* $iStudentCount;
                            // $aSectionFeeAmounts[ $section_data->class_id][$section_data->section_id] = ($aSectionFeeAmounts[ $section_data->class_id][$section_data->section_id] ?? 0) + $iFeeAmount* $iStudentCount;
                        }
                        if ($income_head->head_type == "hostel")
                        {
                            $iStudentCount =  $section_data->member_count; 
                            $yearly_room_rent = $section_data->yearly_room_rent ? json_decode($section_data->yearly_room_rent,true) : array();
                            if($academic_year_id_filter && isset($yearly_room_rent[$academic_year_id_filter]))
                                $iFeeAmount  = $yearly_room_rent[$academic_year_id_filter];
                            else 
                                $iFeeAmount = $section_data->fee_amount;
    
                            $section_data->total_fee_amount =  $iFeeAmount* $iStudentCount;
                            // $aSectionFeeAmounts[ $section_data->class_id][$section_data->section_id] = ($aSectionFeeAmounts[ $section_data->class_id][$section_data->section_id] ?? 0) + $iFeeAmount* $iStudentCount;
                        }
                        if (!isset($students_data[$section_data->class_id."".$section_data->id] ))
                        {
                            $students_data[$section_data->class_id."".$section_data->id] =  array( "section_id"  =>  $section_data->id , "class_id" =>  $section_data->class_id,"class_name"  =>  $section_data->class_name , "section_name" =>  $section_data->section_name, "fee_amount" =>   $section_data->fee_amount,'total_fees_amount'=>$section_data->total_fee_amount) ;
                        }
                        else
                            $students_data[$section_data->class_id."".$section_data->id]['total_fees_amount'] =  $students_data[$section_data->class_id."".$section_data->id]['total_fees_amount']+ $section_data->total_fee_amount;
                        if(!in_array($section_data->id,$section_ids))
                        {
                            $section_ids[] = $section_data->id;
                        }
                        if(!in_array($section_data->class_id,$class_ids))
                        {
                            $class_ids[]  = $section_data->class_id;
                        }
                       
                    }
                    $students_count_datas =  $this->report->get_section_students_count( $section_ids,$class_ids,$school_id, $academic_year_id_filter, $income_head->head_type );
                    
                    foreach($students_count_datas as $students_count_data)
                    {
                        $rte_value =  strtolower($students_count_data->rte) == "yes" ? "yes" : "no";
                        if(!isset($students_counts[$students_count_data->section_id][$students_count_data->class_id]))
                        {
                            $students_counts[$students_count_data->section_id][$students_count_data->class_id]['count'] = $students_count = 0;
                            $students_counts[$students_count_data->section_id][$students_count_data->class_id]['rte_count'] = $rte_students_count = 0;
                        }
                        else
                        {
                            $students_count =  $students_counts[$students_count_data->section_id][$students_count_data->class_id]['count'];
                            $rte_students_count = $students_counts[$students_count_data->section_id][$students_count_data->class_id]['rte_count'];
                        }
                          
                        if($rte_value == "yes")
                        {
                            $rte_students_count = $rte_students_count +  $students_count_data->count;
                        }
                        $students_count =  $students_count + $students_count_data->count;
                        $students_counts[$students_count_data->section_id][$students_count_data->class_id]['count'] =  $students_count;
                        $students_counts[$students_count_data->section_id][$students_count_data->class_id]['rte_count'] =  $rte_students_count;
                    }
                    $students_paid_count_datas =  $this->report->get_section_paid_students_count( $section_ids,$class_ids,$school_id,$income_head_id, $academic_year_id_filter );
                  
                    foreach($students_paid_count_datas as $students_paid_count_data)
                    {
                        $paid_students_count[$students_paid_count_data->section_id][$students_paid_count_data->class_id] =  $students_paid_count_data->count;
                    }
                    $students_p_paid_count_datas =  $this->report->get_section_paid_students_count1( $section_ids,$class_ids,$school_id,$income_head_id, $academic_year_id_filter );
                    
                    foreach($students_p_paid_count_datas as $students_p_paid_count_data)
                    {
                        if(!isset($p_paid_students_count[$students_p_paid_count_data->section_id][$students_p_paid_count_data->class_id]))
                        {
                            $p_paid_students_count[$students_p_paid_count_data->section_id][$students_p_paid_count_data->class_id]  = 0;
                            $p_paid_students_count[$students_p_paid_count_data->section_id][$students_p_paid_count_data->class_id] = 0;
                        }
                        $p_paid_students_count[$students_p_paid_count_data->section_id][$students_p_paid_count_data->class_id]++;
                    }
                    $students_discount_count_datas =  $this->report->get_section_dicount_students_count( $section_ids,$class_ids,$school_id,$income_head_id, $academic_year_id_filter);
                    foreach($students_discount_count_datas as $students_discount_count_data)
                    {
                        $discount_students_count[$students_discount_count_data->section_id][$students_discount_count_data->class_id] =  $students_discount_count_data->count;
                    }
                    $students_fee_datas =  $this->report->get_section_fee_amount( $section_ids,$class_ids,$school_id,$income_head_id, $academic_year_id_filter );
                    foreach($students_fee_datas as $students_fee_data)
                    {
                        $students_fees[$students_fee_data->section_id][$students_fee_data->class_id]['total_paid'] =  $students_fee_data->total_paid;
                        $students_fees[$students_fee_data->section_id][$students_fee_data->class_id]['total_discount'] =  $students_fee_data->total_discount;
                    }
                    // echo "<pre>";
                    // var_dump($students_fees);
                    // die();
                    $fee_data = [];
                    foreach( $students_data as $data)
                    {

                        $total_students = isset($students_counts[$data['section_id']][$data['class_id']]['count']) ? $students_counts[$data['section_id']][$data['class_id']]['count'] : 0;
                        $rte_students = isset($students_counts[$data['section_id']][$data['class_id']]['rte_count']) ? $students_counts[$data['section_id']][$data['class_id']]['rte_count'] : 0;
                        $total_fee_amount = isset($students_fees[$data['section_id']][$data['class_id']]['total_paid']) ? $students_fees[$data['section_id']][$data['class_id']]['total_paid'] : 0;
                        $total_discount_amount = isset($students_fees[$data['section_id']][$data['class_id']]['total_discount']) ? $students_fees[$data['section_id']][$data['class_id']]['total_discount'] : 0;
                        $paid_students = isset($paid_students_count[$data['section_id']][$data['class_id']]) ? $paid_students_count[$data['section_id']][$data['class_id']] : 0;
                        $discount_students = isset($discount_students_count[$data['section_id']][$data['class_id']]) ? $discount_students_count[$data['section_id']][$data['class_id']] : 0;
                        $p_paid_students = isset($p_paid_students_count[$data['section_id']][$data['class_id']]) ? $p_paid_students_count[$data['section_id']][$data['class_id']] : 0;

                        $data['total_fee_amount'] =$total_fee_amount;
                        $data['total_discount_amount'] =$total_discount_amount;
                        $data['total_students'] =$total_students;
                        $data['paid_students'] =$paid_students;
                        $data['p_paid_students'] =$p_paid_students;
                        $data['discount_students'] =$discount_students;
                        $data['rte_students'] =$rte_students;
                        $fee_data[] = $data;
                    }
                    $this->data['fee_data']  = $fee_data;

                    $this->data['school']  = $school;
                }

                    
                    break;
                case "prant":
                    $filter_column = "SC.district_id";
                    $filter_column_filter = "SC.zone_id";
                    $filter_column_filter_value = $zone_id;
                    $filter_column_id = "district_id";
                    $filter_column_name = "district_name";
                    $filter_heading  = "District Name";
                    break;
                case "district":
                    $filter_column = "E.school_id";
                    $filter_column_id = "school_id";
                    $filter_column_name = "school_name";
                    $filter_heading  = "School Name";
                    $filter_column_filter = "SC.district_id";
                    $filter_column_filter_value = $district_id;
                    break;
            }

        }    
        $this->layout->title( 'Fees Report | ' . SMS);   

        $this->layout->view('student/tution_fee_report', $this->data);

    }
    public function installment_wise_old() {
        error_on();

        check_permission(VIEW,1);
        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $academic_year_id_filter = $this->input->post('academic_year_id_filter');

        if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
       
        }
        else
        {
            $school_id = $this->session->userdata('school_id');
            $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
            $this->data['academic_year_id'] = $academic_year->id;
            
        }
        if( $school_id)
        {
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['classes'] = $this->report->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $school = $this->report->get_school_by_id($school_id);
            if($academic_year_id_filter) {
                $fee_type_acdemic_year = $academic_year_id_filter;
            } else {
                $fee_type_acdemic_year =$school->academic_year_id;
            }
            $this->data['fee_types'] = $this->report->get_list('income_heads', array("school_id"=>$school_id,'academic_year_id'=> $fee_type_acdemic_year), '','', '', 'id', 'ASC');
        }
        $this->data['fee_data']  = array();


        $this->data['emi_data'] =  array();
       
        $faculties = array();
        $this->data['income_head_id']  = 0;
        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $income_head_id = $this->input->post('income_head_id');
            $zone_id = $this->input->post('zone_id');
            $class_id = $this->input->post('class_id');

            if($academic_year_id_filter) {
                $this->data['academic_year_id'] = $academic_year_id_filter;
            }
            else {
                $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
                $academic_year_id_filter = $academic_year->id;
            }
            $this->data['school_id']  = $school_id;
            $this->data['class_id'] = $class_id;
            $this->data['academic_year_id_sel']  = $academic_year_id_filter;
            $this->data['academic_year_id']  = $academic_year_id_filter;

            $this->data['income_head_id']  = $income_head_id;
            $fee_data = array();
            $income_head = array();
           if($school_id)
           {
                          
                    $filter_column = "E.class_id,E.section_id";
                    $filter_column_filter = "SC.id";
                    $students_data = array();
                   // $school = $this->report->get_school_by_id($school_id);
                    $income_head=$this->report->get_single('income_heads',array('id'=>$income_head_id));	
                   
                    $income_head_id =  $income_head->id;
                    $emi_fees_row = $this->report->get_list('emi_fee',array('school_id'=>$school_id,'income_heads_id'=>$income_head_id), '', '', '', 'id', 'ASC');	
                    $this->data['emi_data'] =  $emi_fees_row ;
                    $emi_fees_ids = [];
                    $student_ids = [];
                    $students_emi_data = [];
                    $students_paid = [];
                    $students_discount = [];
                    foreach($emi_fees_row as $emi_fee)
                    {
                        $emi_fees_ids[]  =  $emi_fee->id;
                    }
                   
                    if($income_head->head_type == "transport")
                    {
                        $students_raw =  $this->report->get_transport_students( $school_id ,$class_id  ,$academic_year_id_filter);
                    }
                    elseif($income_head->head_type == "hostel")
                    {
                        $students_raw =  $this->report->get_hostel_students( $school_id ,$class_id  ,$academic_year_id_filter);
                    }
                    else
                    {
                        $fees=$this->report->get_single('fees_amount',array('income_head_id'=>$income_head_id,"class_id"=>$class_id));	
                      
                        $students_raw =  $this->report->get_students( $school_id,$class_id ,$academic_year_id_filter,$fees->fee_amount);
                    }
                    
                    foreach($students_raw as $student)
                    {
                        $student_ids[]  =  $student->id;
                    }

                    if(!empty($student_ids))
                    {

                        $students_paid_datas =  $this->report->get_paid_students_installment_data( $student_ids,$school_id,$class_id,$income_head_id, $academic_year_id_filter);
                       
                        foreach($students_paid_datas as $students_paid_data)
                        {
                            if(!isset($students_emi_data[$students_paid_data->student_id][$students_paid_data->emi_type]))
                            {
                                $paid_amount = 0;
                            }
                            else
                            {
                                $paid_amount = $students_emi_data[$students_paid_data->student_id][$students_paid_data->emi_type];
                            }
                            if(!isset($students_discount[$students_paid_data->student_id]))
                            {
                                $discount_amount = 0;
                            }
                            else
                            {
                                $discount_amount  = $students_discount[$students_paid_data->student_id];
                            }
                            if(!isset($students_paid[$students_paid_data->student_id]))
                            {
                                $net_amount = 0;
                            }
                            else
                            {
                                $net_amount  = $students_paid[$students_paid_data->student_id];
                            }
                          
                            $discount_amount =  $discount_amount+ $students_paid_data->discount;

                            $paid_amount =  $paid_amount+ $students_paid_data->net_amount;
                            $net_amount =  $net_amount+ $students_paid_data->net_amount;
                            $students_emi_data[$students_paid_data->student_id][$students_paid_data->emi_type] =  $paid_amount;
                            $students_paid[$students_paid_data->student_id] = $net_amount;
                            $students_discount[$students_paid_data->student_id] =  $discount_amount ;
                        }
                        //debug_a($students_paid);

                   
                        foreach( $students_raw as $student)
                        {
                            if ($income_head->head_type == "transport")
                            {
                                $yearly_stop_fare = $student->yearly_stop_fare ? json_decode($student->yearly_stop_fare,true) : array();
                                if($academic_year_id_filter && isset($yearly_stop_fare[$academic_year_id_filter]))
                                {
                                    $student->fee_amount = $yearly_stop_fare[$academic_year_id_filter];
                                }
                            }
                            if ($income_head->head_type == "hostel")
                            {
                                $yearly_room_rent = $student->yearly_room_rent ? json_decode($student->yearly_room_rent,true) : array();
                                if($academic_year_id_filter && isset($yearly_room_rent[$academic_year_id_filter]))
                                {
                                    $student->fee_amount = $yearly_room_rent[$academic_year_id_filter];
                                }
                            }
                            $student->total_paid =  isset($students_paid[$student->id]) ? $students_paid[$student->id] : 0;
                            $student->total_discount =  isset($students_discount[$student->id]) ? $students_discount[$student->id] : 0;
                            $student->emi = array();
                            foreach($emi_fees_ids as $emi_fees_id)
                            {
                                $student->emi[$emi_fees_id] = isset( $students_emi_data[$student->id][$emi_fees_id])  ?  $students_emi_data[$student->id][$emi_fees_id] : 0;
                            }
                            $fee_data[] = $student;
                        }
                    }
                   
                    $this->data['fee_data']  = $fee_data;
            }
            $this->data['income_head'] =  $income_head ;
        }            
        $this->layout->title( 'installment wise Report | ' . SMS);   

        $this->layout->view('student/installmentwise_report', $this->data);

    }
    public function installment_wise() {
        error_on();
        // check_permission(VIEW,1);
        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $academic_year_id_filter = $this->input->post('academic_year_id_filter');
        $school_id = 0;
        if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
       
        }
        else
        {
            $school_id = $this->session->userdata('school_id');
            $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
            $this->data['academic_year_id'] = $academic_year->id;
            
        }
        if( $school_id)
        {
            $this->data['school'] = $this->report->get_school_by_id($school_id);

            $this->data['classes'] = $this->report->get_list('classes', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $school = $this->report->get_school_by_id($school_id);
            if($academic_year_id_filter) {
                $fee_type_acdemic_year = $academic_year_id_filter;
            } else {
                $fee_type_acdemic_year =$school->academic_year_id;
            }
            $this->data['fee_types'] = $this->report->get_list('income_heads', array("school_id"=>$school_id,'academic_year_id'=> $fee_type_acdemic_year), '','', '', 'id', 'ASC');
        }
        $this->data['fee_data']  = array();


        $this->data['emi_data'] =  array();
       
        $faculties = array();
        $this->data['income_head_id']  = 0;
        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $hostel_id = $this->input->post('hostel_id');
            $route_id = $this->input->post('route_id');

            if ($hostel_id)
                $extra_value = $hostel_id; 
            else 
                $extra_value = $route_id; 
            $this->data['extra_value'] =  $extra_value ;

            $income_head_id = $this->input->post('income_head_id');
            $zone_id = $this->input->post('zone_id');
            $class_id = $this->input->post('class_id');
            if($academic_year_id_filter) 
            {
                $this->data['academic_year_id'] = $academic_year_id_filter;
                $academic_year=$this->report->get_single('academic_years',array('id'=>$academic_year_id_filter));	
                $previous_academic_year_id = $academic_year->previous_academic_year_id;
                $this->data['previous_academic_year_id'] = $academic_year->previous_academic_year_id;
            }
            else 
            {
                $academic_year=$this->report->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
                $academic_year_id_filter = $academic_year->id;
                $previous_academic_year_id = $academic_year->previous_academic_year_id;
                $this->data['previous_academic_year_id'] = $academic_year->previous_academic_year_id;
            }
            
            $this->data['school_id']  = $school_id;
            $this->data['class_id'] = $class_id;
            $this->data['academic_year_id_sel']  = $academic_year_id_filter;
            $this->data['academic_year_id']  = $academic_year_id_filter;

            $this->data['income_head_id']  = $income_head_id;
            $fee_data = array();
            $income_head = array();
           if($school_id)
           {
                          
                    $filter_column = "E.class_id,E.section_id";
                    $filter_column_filter = "SC.id";
                    $students_data = array();
                   // $school = $this->report->get_school_by_id($school_id);
                    $income_head=$this->report->get_single('income_heads',array('id'=>$income_head_id));	
                    if($income_head->head_type == "transport")
                    {
                        $students_raw =  $this->report->get_transport_students( $school_id ,$class_id  ,$academic_year_id_filter, null, $route_id);
                    }
                    elseif($income_head->head_type == "hostel")
                    {

                        $students_raw =  $this->report->get_hostel_students( $school_id ,$class_id  ,$academic_year_id_filter,null, $hostel_id);

                    }
                  
                 // Assign the value of $hostel_id to $extra_value
       
                    $income_head_id =  $income_head->id;
                    $emi_fees_row = $this->report->get_list('emi_fee',array('school_id'=>$school_id,'income_heads_id'=>$income_head_id), '', '', '', 'id', 'ASC');	
                    $this->data['emi_data'] =  $emi_fees_row ;
                    $emi_fees_ids = [];
                    $student_ids = [];
                    $students_emi_data = [];
                    $students_paid = [];
                    $students_discount = [];
                    foreach($emi_fees_row as $emi_fee)
                    {
                        $emi_fees_ids[]  =  $emi_fee->id;
                    }
                   
                    if($income_head->head_type == "transport")
                    {
                        $students_raw =  $this->report->get_transport_students( $school_id ,$class_id  ,$academic_year_id_filter,null, $route_id);;
                    }
                    elseif($income_head->head_type == "hostel")
                    {
                        $students_raw =  $this->report->get_hostel_students( $school_id ,$class_id  ,$academic_year_id_filter,null, $hostel_id);;
                    }
                    else
                    {
                        $fees=$this->report->get_single('fees_amount',array('income_head_id'=>$income_head_id,"class_id"=>$class_id));	
                      
                        $students_raw =  $this->report->get_students( $school_id,$class_id ,$academic_year_id_filter,$fees->fee_amount);
                    }
                    
                    foreach($students_raw as $student)
                    {
                        $student_ids[]  =  $student->id;
                    }

                    if(!empty($student_ids))
                    {

                        $students_paid_datas =  $this->report->get_paid_students_installment_data( $student_ids,$school_id,$class_id,$income_head_id, $academic_year_id_filter);
                       
                        foreach($students_paid_datas as $students_paid_data)
                        {
                            if(!isset($students_emi_data[$students_paid_data->student_id][$students_paid_data->academic_year_id][$students_paid_data->emi_type]))
                            {
                                $paid_amount = 0;
                            }
                            else
                            {
                                $paid_amount = $students_emi_data[$students_paid_data->student_id][$students_paid_data->academic_year_id][$students_paid_data->emi_type];
                            }
                            if(!isset($students_discount[$students_paid_data->student_id][$students_paid_data->academic_year_id]))
                            {
                                $discount_amount = 0;
                            }
                            else
                            {
                                $discount_amount  = $students_discount[$students_paid_data->student_id][$students_paid_data->academic_year_id];
                            }
                            if(!isset($students_paid[$students_paid_data->student_id][$students_paid_data->academic_year_id]))
                            {
                                $net_amount = 0;
                            }
                            else
                            {
                                $net_amount  = $students_paid[$students_paid_data->student_id][$students_paid_data->academic_year_id];
                            }
                          
                            $discount_amount =  $discount_amount+ $students_paid_data->discount;

                            $paid_amount =  $paid_amount+ $students_paid_data->net_amount;
                            $net_amount =  $net_amount+ $students_paid_data->net_amount;
                            $students_emi_data[$students_paid_data->student_id][$students_paid_data->academic_year_id][$students_paid_data->emi_type] =  $paid_amount;
                            $students_paid[$students_paid_data->student_id][$students_paid_data->academic_year_id] = $net_amount;
                            $students_discount[$students_paid_data->student_id][$students_paid_data->academic_year_id] =  $discount_amount ;
                        }
                        //debug_a($students_paid);

                   
                        foreach( $students_raw as $student)
                        {
                            if ($income_head->head_type == "transport")
                            {
                                $yearly_stop_fare = $student->yearly_fee_amount ? json_decode($student->yearly_fee_amount,true) : array();
                                if($academic_year_id_filter && isset($yearly_fee_amount[$academic_year_id_filter]))
                                {
                                    $student->fee_amount = $yearly_fee_amount[$academic_year_id_filter];
                                }
                            }
                            if ($income_head->head_type == "hostel")
                            {
                                $yearly_room_rent = $student->yearly_room_rent ? json_decode($student->yearly_room_rent,true) : array();
                                if($academic_year_id_filter && isset($yearly_room_rent[$academic_year_id_filter]))
                                {
                                    $student->fee_amount = $yearly_room_rent[$academic_year_id_filter];
                                }
                            }
                            $student->total_paid =  isset($students_paid[$student->id][$student->academic_year_id]) ? $students_paid[$student->id][$student->academic_year_id] : 0;
                            $student->total_discount =  isset($students_discount[$student->id][$student->academic_year_id]) ? $students_discount[$student->id][$student->academic_year_id] : 0;
                            $student->emi = array();
                            foreach($emi_fees_ids as $emi_fees_id)
                            {
                                $student->emi[$emi_fees_id] = isset( $students_emi_data[$student->id][$emi_fees_id])  ?  $students_emi_data[$student->id][$emi_fees_id] : 0;
                            }
                            $student->due_amount = 0;
                            if ($academic_year->previous_academic_year_id)
                            {
                                $due_amount =  $this->__invoice_creation(array('student_id'=> $student->id,'school_id'=>$school_id,'previous_academic_year_id'=>$academic_year->previous_academic_year_id,'fee_type'=>$income_head->head_type)); 
                                if ($due_amount)
                                    $student->due_amount = $due_amount['due_amount'];
                                
                            }		
                            $fee_data[$student->id][$student->academic_year_id] = $student;
                        }
                    }
                    
                    $this->data['fee_data']  = $fee_data;
            }
            $this->data['income_head'] =  $income_head ;
        }            
        $this->layout->title( 'installment wise Report | ' . SMS);   

        $this->layout->view('student/installment_dev', $this->data);

    }
    public function payroll_report() {
        check_permission(VIEW,1);
        error_on();
        if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
          
        }
        else
        {
            $school_id = $this->session->userdata('school_id');
            $school= $this->report->get_school_by_id($school_id);
             $this->data['school'] = $school;
        }

        $this->data['payment_data']  = array();       
        $users = array();
        $user_ids = array();
        $payment_users =  array();
        $all_earns = array();
        $all_exps =  array();
        $payment_ids =  array();
        $user_payscale_categories = array();
        $school_id = $this->input->post('school_id');



        if ($_POST) {
          
            $employee_type = $this->input->post('employee_type');
            $salary_month = $this->input->post('salary_month');
            $group_id = $this->input->post('group_id');
            $this->data['group_id'] = $group_id;
            $this->data['employee_type'] = $employee_type;
            $this->data['school_id'] = $school_id;
            $this->data['salary_month']  = $salary_month;
            $employee_type  = !$employee_type  ? "all" :  $employee_type ;

            $users = $this->report->get_payment_users($employee_type,$school_id, $salary_month ,@$school->academic_year_id );
            if(!empty($users)){  
                foreach($users as $user){
                    $user_ids[]  = $user->user_id;
                    $payment_ids[]  = $user->payment_id;
                }
                $details=$this->report->get_salary_payment_details($payment_ids, $group_id);
                foreach($details as $detail){
                    if ($detail->type=="FALSE")
                    {
                        if(!isset( $all_earns[$detail->payscalecategory_id]))
                        {
                            $all_earns[$detail->payscalecategory_id] = $detail->cat_name;
                        }
                        $detail_earnings[$detail->salary_payment_id][] =array(
                            'id'=>$detail->payscalecategory_id,
                            'cat_name'=>$detail->cat_name,
                            'amount'=>$detail->amount
                        );
                    }
                    else
                    {
                        if(!isset( $all_exps[$detail->payscalecategory_id]))
                        {
                            $all_exps[$detail->payscalecategory_id] = $detail->cat_name;
                        }
                        $detail_exps[$detail->salary_payment_id][] =array(
                            'id'=>$detail->payscalecategory_id,
                            'cat_name'=>$detail->cat_name,
                            'amount'=>$detail->amount
                        );
                    }
                }

                foreach($users as $user){
                    $calculated_basic_salary = $user->cal_basic_salary ; 
                    $total_deduction = 0;
                    $total_earnings = 0;
                    $earnings = array();
                    $expenditure =  array();
                    $user_earnings = isset($detail_earnings[$user->payment_id]) ?  $detail_earnings[$user->payment_id] : array();  

                    foreach($user_earnings as $user_earning)
                    {
                            $total_earnings += $user_earning['amount'];
                            $earnings[$user_earning['id']]= $user_earning['amount'];
                    }
                    $user_exps = isset($detail_exps[$user->payment_id]) ?  $detail_exps[$user->payment_id] : array();  
                    foreach($user_exps as $user_exp)
                    {
                        $expenditure[$user_exp['id']]=$user_exp['amount'];
     
                        $total_deduction += $user_exp['amount'];
                    }
                    $net_salary=$calculated_basic_salary+$total_earnings-$total_deduction;
                    $user->calc_basic_salary =   $calculated_basic_salary;               
                    $user->tot_earn = $total_earnings+$calculated_basic_salary;
                    $user->tot_exp   = $total_deduction; 
                    $user->earnings   = $earnings;
                    $user->expenditure   = $expenditure;
                    $payment_users[] = $user;
                } 
            }
        } 

        if(empty($all_earns))
        {
            $all_earns = array("");
        }  
        if(empty($all_exps))
        {
            $all_exps = array("");
        }  
        $this->data['groups'] = $this->report->payroll_groups($school_id);

        $this->data['payment_users'] = $payment_users;       
        $this->data['all_earns'] = $all_earns;        
        $this->data['all_exps'] = $all_exps;     
        $this->layout->title( 'Payroll Report | ' . SMS);   
        $this->layout->view('payroll/payroll_report', $this->data);

    }
   
    public function payroll_group_report() {
        // check_permission(VIEW,1);
        error_on();
        if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {
          
        }
        else
        {
            $school_id = $this->session->userdata('school_id');
            $school= $this->report->get_school_by_id($school_id);
             $this->data['school'] = $school;
        }

        $this->data['payment_data']  = array();       
        $users = array();
        $user_ids = array();
        $payment_users =  array();
        $all_earns = array();
        $all_exps =  array();
        $payment_ids =  array();
        $user_payscale_categories = array();



        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $employee_type = $this->input->post('employee_type');
            $salary_month = $this->input->post('salary_month');
            $group_id = $this->input->post('group_id');
            $this->data['group_id'] = $group_id;
            $this->data['employee_type'] = $employee_type;
            $this->data['salary_month']  = $salary_month;
            $employee_type  = !$employee_type  ? "all" :  $employee_type ;

            $users = $this->report->get_payment_users($employee_type,$school_id, $salary_month ,@$school->academic_year_id );
            if(!empty($users)){  
                foreach($users as $user){
                    $user_ids[]  = $user->user_id;
                    $payment_ids[]  = $user->payment_id;
                }
                $details=$this->report->get_salary_payment_details($payment_ids, $group_id);
                foreach($details as $detail){
                    if($detail->type=="FALSE"){
                  
                    if(!isset( $all_earns[$detail->pay_group_id]))
                    {
                        $all_earns[$detail->pay_group_id] = $detail->group_name;
                    }
                    $detail_earnings[$detail->salary_payment_id][] =array(
                        'id'=>$detail->pay_group_id,
                        'cat_name'=>$detail->group_name,
                        'amount'=>$detail->amount
                        );
                    
                    } else {
                        if(!isset( $all_exps[$detail->pay_group_id]))
                        {
                            $all_exps[$detail->pay_group_id] = $detail->group_name;
                        }
                    $detail_exps[$detail->salary_payment_id][] =array(
                        'id'=>$detail->pay_group_id,
                    'cat_name'=>$detail->group_name,
                    'amount'=>$detail->amount
                    );
                    }
                }

                foreach($users as $user){
                    $calculated_basic_salary = $user->cal_basic_salary ; 
                    $total_deduction = 0;
                    $total_earnings = 0;
                    $earnings = array();
                    $expenditure =  array();
                    $user_earnings = isset($detail_earnings[$user->payment_id]) ?  $detail_earnings[$user->payment_id] : array();  

                    foreach($user_earnings as $user_earning)
                    {
                            $total_earnings += $user_earning['amount'];
                            $earnings[$user_earning['id']]= $user_earning['amount'];
                    }
                    $user_exps = isset($detail_exps[$user->payment_id]) ?  $detail_exps[$user->payment_id] : array();  
                    foreach($user_exps as $user_exp)
                    {
                        $expenditure[$user_exp['id']]=$user_exp['amount'];
     
                        $total_deduction += $user_exp['amount'];
                    }
                    $net_salary=$calculated_basic_salary+$total_earnings-$total_deduction;
                    $user->calc_basic_salary =   $calculated_basic_salary;               
                    $user->tot_earn = $total_earnings+$calculated_basic_salary;
                    $user->tot_exp   = $total_deduction; 
                    $user->earnings   = $earnings;
                    $user->expenditure   = $expenditure;
                    $payment_users[] = $user;
                } 
            }
        } 

        if(empty($all_earns))
        {
            $all_earns = array("");
        }  
        if(empty($all_exps))
        {
            $all_exps = array("");
        }  
        $this->data['groups'] = $this->report->payroll_groups($school_id);

        $this->data['payment_users'] = $payment_users;       
        $this->data['all_earns'] = $all_earns;        
        $this->data['all_exps'] = $all_exps;     
        $this->layout->title( 'Payroll Report | ' . SMS);   
        $this->layout->view('payroll/payroll_group_report', $this->data);

    }
    public function category_report() {
       
        check_permission(VIEW,1);

        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $disciplines = $this->report->get_list('academic_disciplines', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";
	
        $categories = array();
     
        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $zone_id = $this->input->post('zone_id');
            
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
         
            switch($report_type )
            {
                case "kshetra":
                    $filter_column = "zone_id";
                    $filter_column_name = "zone_name";
                    $filter_heading  = "Prant Name";
                    break;
                case"school":
                    $filter_column = "class_id";
                    $filter_column_name = "class_name";
                    $filter_heading  = "Class Name";
                    break;
                case "prant":
                    $filter_column = "district_id";
                    $filter_column_name = "district_name";
                    $filter_heading  = "District Name";
                    break;
                case "district":
                    $filter_column = "school_id";
                    $filter_column_name = "school_name";
                    $filter_heading  = "School Name";
                    break;
            }
            $this->data['filter_heading'] = $filter_heading ;

            // if($report_type != "class" && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
            // {
            //     error($this->lang->line('permission_denied'));
            //     redirect('dashboard');
            // }
           
            if($report_type != "school" && !($this->session->userdata('role_id') == SUPER_ADMIN))
            {
                error($this->lang->line('permission_denied'));
                redirect('dashboard');
            }
            
          if($report_type == "kshetra")
            {
              
                $categories = array("GENERAL","OBC","SC","ST","SBC","Minority");
                $zones =  $this->data['zones'];
                $student_data = array();
                foreach($zones as $zone)
                {
                    $zone_id=  $zone->id;
                    $student_data[$zone_id] = array();
                    foreach($categories as $category)
                    {
                        $student_data[$zone_id][$category] =  array("girls"=>0,"boys"=>0);
                        $counts_data =  $this->report->get_caste_student_count($category,"SC.zone_id",$zone_id);
                        
                       foreach($counts_data as $student_count)
                       {
                            if($student_count->gender == "male" || $student_count->gender == "boy")
                            {
                                $student_data[$zone_id][$category]['boys'] = $student_data[$zone_id][$category]['boys']+$student_count->count; 
                            }
                            else if($student_count->gender == "female" || $student_count->gender == "girl")
                            {
                                $student_data[$zone_id][$category]['girls'] = $student_data[$zone_id][$category]['girls']+$student_count->count; 
                            }
                       }
                    }
                }

                
            }
            else
            {
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                $school_id=$this->session->userdata('school_id');
            }
            if(!$school_id)
            {
                if($district_id)
                {
                    $schools = $this->report->get_school_list_teacher_count($school_id,$district_id,$zone_id);
                    $this->data['district_id'] = $district_id;
                }
                else if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,null,$zone_id);
                }
                else if($this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,$this->session->userdata('district_id'),$zone_id);
                }
            }
            else
            {
             
    
                $schools = $this->report->get_school_list_teacher_count($school_id,$this->session->userdata('district_id'));
                // var_dump( $schools);
                // die();
                $this->data['school'] = $this->report->get_school_by_id($school_id);

            }
          
            $iCount1 = 0 ;
           
            foreach($schools as $school_obj)
            {
                $school_id = $school_obj->id;
                $looping_school_id  = 0;
                $condition['school_id'] = $school_id;
                $academic_years = $this->report->get_academic_years($school_id);
                
                if(!empty( $academic_years))
                {
                    if( isset($this->data['school_id']) && $this->data['school_id'])
                    {
                        $this->data['current_year'] = $academic_years->curr_start."-".$academic_years->curr_end;
                    }
                    $current_academic_year = $academic_years->id ? $academic_years->id  : 0;
                    $students_data = $this->report->get_student_data ($school_id,$current_academic_year,null,$filter_column,true, 1);
                    // echo $this->db->last_query();
                    $looping_class_id = 0;
                    foreach($students_data as $obj)
                    {                       
                        $filter_id = $obj->$filter_column;
                        $class_id  = $obj->class_id;
                        $category  = $obj->caste ? strtoupper(trim($obj->caste)) : " Not Assigned";
                        // if(!$category)
                        // {
                        //     continue;
                        // }
                        if(!in_array($category,$categories)) $categories[] = $category;
                        if(!isset($student_data[$filter_id]))
                        {
                            $student_data[$filter_id] = array("filter_name" =>'',"curr"=>array(), $category => array());
                            $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                        }
                        if(!isset($student_data[$filter_id][$category]))
                        {
                            $student_data[$filter_id][$category] =  array();
                            $student_data[$filter_id][$category]['students'] = $students_array = array();
                            $student_data[$filter_id][$category]['boys'] = $boys_array = array();
                            $student_data[$filter_id][$category]['girls'] = $girls_array = array();
                        }
                        else
                        {
                            $boys_array = $student_data[$filter_id][$category]['boys'];
                            $girls_array = $student_data[$filter_id][$category]['girls'];
                            $students_array = $student_data[$filter_id][$category]['students'];
                        }
                        if($obj->gender == "male" )
                        {
                            $boys_array[] = $obj->student_id;
                        }
                        else
                        {
                            $girls_array[] = $obj->student_id;
                        }
                        $students_array[] =  $obj->student_id;
                        $student_data[$filter_id][$category]['students'] = $students_array;
                        $student_data[$filter_id][$category]['boys'] = $boys_array ;
                        $student_data[$filter_id][$category]['girls'] = $girls_array ;
                    }
                }
            }
        }
            
            $this->data['student_data'] = $student_data;
        }
        $this->data['student_data'] = $student_data;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Students category Report | ' . SMS);
        $this->layout->view('student/category_report', $this->data);
    }

    public function faculty_wise_report() {
     
     

        $this->data['currrent_year_data'] = array();
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $disciplines = $this->report->get_list('academic_disciplines', array(), '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone', array(), '','', '', 'id', 'ASC');
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";
	
        $categories = array();
     
        if ($_POST) {
          
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $this->input->post('district_id');
            $zone_id = $this->input->post('zone_id');
            
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
         
            switch($report_type )
            {
                case "kshetra":
                    $filter_column = "zone_id";
                    $filter_column_name = "zone_name";
                    $filter_heading  = "Prant Name";
                    break;
                case"school":
                    $filter_column = "class_id";
                    $filter_column_name = "class_name";
                    $filter_heading  = "Class Name";
                    break;
                case "prant":
                    $filter_column = "district_id";
                    $filter_column_name = "district_name";
                    $filter_heading  = "District Name";
                    break;
                case "district":
                    $filter_column = "school_id";
                    $filter_column_name = "school_name";
                    $filter_heading  = "School Name";
                    break;
            }
            $this->data['filter_heading'] = $filter_heading ;

            if($report_type != "class" && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
            {
                error($this->lang->line('permission_denied'));
                redirect('dashboard');
            }
           
            if($report_type != "school" && !($this->session->userdata('role_id') == SUPER_ADMIN))
            {
                error($this->lang->line('permission_denied'));
                redirect('dashboard');
            }
            if(!$school_id)
            {
                if($district_id)
                {
                    $faculties =  $this->report->get_faculties($school_id,$zone_id,$district_id,$filter_column_name);

                    $this->data['district_id'] = $district_id;
                }
                else if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,null,$zone_id);
                    $faculties =  $this->report->get_faculties($school_id,$zone_id,null,$filter_column_name);

                }
                else if($this->session->userdata('dadmin') == 1){
                    $faculties =  $this->report->get_faculties($school_id,$zone_id,$this->session->userdata('district_id'),$filter_column_name);
                }
            }
            else
            {
                $faculties =  $this->report->get_faculties($school_id,$zone_id,$district_id,$filter_column_name);
            }
           
            $this->data['faculties'] = $faculties;
            $student_data = array();
            foreach($faculties as $faculty)
            {
                $faculty_id=  $faculty->id;
                $counts_data =  $this->report->get_students_count($faculty_id,$filter_column_name);
              

                $student_data[$filter_column][$faculty_id] = array();
              
                $student_data[$filter_column][$faculty_id]['filter_name'] = $faculty->$filter_column_name;
                foreach($counts_data as $student_count)
                {
                   if(!isset($student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id])) $student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id] =  array("girls"=>0,"boys"=>0);
                    if($student_count->gender == "male" || $student_count->gender == "boy")
                    {
                        $student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id]['boys'] = $student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id]['boys']+$student_count->count; 
                    }
                    else if($student_count->gender == "female" || $student_count->gender == "girl")
                    {
                        $student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id]['girls'] = $student_data[$faculty->$filter_column_name][$faculty_id][$student_count->class_id]['girls']+$student_count->count; 
                    }
                   
                }
                echo "<pre>";
                print_r($student_data);
                die();
                
            }
            $this->data['student_data'] = $student_data;
        }
        $this->data['student_data'] = $student_data;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Students category Report | ' . SMS);
        $this->layout->view('student/faculty_wise', $this->data);
    }
    
    public function all_teacher_report1() {
    
        check_permission(VIEW,1);
        $district_id_selected = 0;
        $districtfilter = array();
        $zone_id_selected = 0;
        $zonefilter = array();

        if($this->session->userdata('dadmin') && $this->session->userdata('district_id'))
        {
            $district_id_selected = $this->session->userdata('district_id');
            $districtfilter = array('id' =>$this->session->userdata('district_id'));
        }
        
        if($this->session->userdata('dadmin') && $this->session->userdata('zone_id'))
        {
            $zone_id_selected = $this->session->userdata('zone_id');
            $zonefilter = array('id' =>$this->session->userdata('zone_id'));
        }
        $this->data['currrent_year_data'] = array();
        $this->data['all_teacher_report'] = true;
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', $districtfilter, '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone',  $zonefilter, '','', '', 'id', 'ASC');	
       
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";	
        if ($_POST) {
            $districts_processed = array();
            foreach( $this->data['districts']  as $district)
            {
                $districts_processed[$district->id] =  $district->name;
            }
            $zones_processed = array();
            foreach( $this->data['zones']  as $zone)
            {
                $zones_processed[$zone->id] =  $zone->name;
            }
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $district_id_selected ? $district_id_selected : $this->input->post('district_id') ;
            $zone_id = $zone_id_selected ? $zone_id_selected : $this->input->post('district_id') ;
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
           
            
                    $filter_column = "faculty_id";
                    $filter_column_name = "faculty_name";
                    $filter_heading  = "Teaching Class Level";
               
            $this->data['filter_heading'] = $filter_heading ;
           
           
           
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                if($report_type != "school" )
                {
                    error($this->lang->line('permission_denied'));
                    redirect('dashboard');
                }
              
                $school_id=$this->session->userdata('school_id');
            }
            if(!$school_id)
            {                
                if($report_type == "district")
                {
                    $district = $this->report->get_single('districts', array('id' => $district_id));            
                    $this->data['table_heading'] = $district->name;
                }
                else if($report_type == "prant")
                {
                    $zone = $this->report->get_single('zone', array('id' => $zone_id));            
                    $this->data['table_heading'] = $zone->name;           
                }
    
                if($district_id)
                {
                    $schools = $this->report->get_school_list_teacher_count($school_id,$district_id,$zone_id);
                    $this->data['district_id'] = $district_id;
                }
                else if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,null,$zone_id);
                }
                else if($this->session->userdata('dadmin') == 1){
                    $schools = $this->report->get_school_list_teacher_count( $school_id,$this->session->userdata('district_id'),$zone_id);
                }
            }
            else
            {

                $schools = $this->report->get_school_list_teacher_count($school_id,$this->session->userdata('district_id'));
                $school = $this->report->get_school_by_id($school_id);
                $this->data['table_heading'] = $school->school_name;   
            }
       
            foreach($schools as $school_obj)
            {
                $school_id = $school_obj->id;
                $looping_school_id  = 0;
                $condition['school_id'] = $school_id;
                $academic_years = $this->report->get_academic_years($school_id);
                
                if(!empty( $academic_years))
                {
                    if( isset($this->data['school_id']) && $this->data['school_id'])
                    {
                        $this->data['current_year'] = $academic_years->curr_start."-".$academic_years->curr_end;
                    }
                    $current_academic_year = $academic_years->id ? $academic_years->id  : 0;
                   
                    $students_data = $this->report->get_student_data1 ($school_id,$current_academic_year,null,$filter_column);
                    $looping_class_id = 0;
                    foreach($students_data as $obj)
                    {                       
                        $obj->zone_name = isset($zones_processed[$obj->zone_id]) ? : "";
                        $obj->district_name = isset($districts_processed[$obj->district_id]) ? : "";
                        $filter_id = $obj->$filter_column;
                        $class_id  = $obj->class_id;
                       
                        if(!isset($current_year_boys_students[$filter_id] )) $current_year_boys_students[$filter_id] = array();
                        if(!isset($current_year_girls_students[$filter_id] )) $current_year_girls_students[$filter_id] = array();
                        if(!isset($student_data[$filter_id]))
                        {
                            $student_data[$filter_id] = array("filter_name" =>'',"curr"=>array(),'male_teachers'=>array(),'female_teachers'=>array());
                            $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                            $student_data[$filter_id]['curr'] = $curr_data = array('boys'=>[],'girls'=>[],'total'=>[]);
                            $student_data[$filter_id]['male_teacher_count'] = 0;
                            $student_data[$filter_id]['female_teacher_count'] = 0;
                            $student_data[$filter_id]['section_count'] = 0;
                            $student_data[$filter_id]['section_count_'] = array();
                           
                        }
                        else
                        {
                            $curr_data = $student_data[$filter_id]['curr'];
                        }
                        if($report_type == "school")
                        {
                            if($class_id != $looping_class_id)
                            {
                                $section_count             = isset($obj->section_count) && $obj->section_count ? $obj->section_count : 0  ;
                                $student_data[$filter_id]['section_count'] =  $student_data[$filter_id]['section_count'] +  $section_count  ;
                                $looping_class_id = $class_id;
                            }
                        }
                        if( $obj->section_id)
                        {
                            $student_data[$filter_id]['section_count_'][] =  $obj->section_id;
                        }
                        
                        if($school_id != $looping_school_id)
                        {
                            if($report_type != "school")
                            {
                                $student_data[$filter_id]['section_count'] = $student_data[$filter_id]['section_count'] + $school_obj->section_count;
                            }
                           
                            $looping_school_id = $school_id;
                            $student_data[$filter_id]['male_teacher_count'] = $student_data[$filter_id]['male_teacher_count'] + $school_obj->male_teacher_count;
                            $student_data[$filter_id]['female_teacher_count'] = $student_data[$filter_id]['female_teacher_count'] + $school_obj->female_teacher_count;
                        }
                        if($obj->academic_year_id == $current_academic_year)
                        {
                           
                            if($obj->gender == "male" )
                            {
                                $current_year_boys_students[$filter_id][] = $obj->student_id;
                                $curr_data['boys'][] =  $obj->student_id;
                            }
                            else
                            {
                                $current_year_girls_students[$filter_id][] = $obj->student_id;
                                $curr_data['girls'][] = $obj->student_id;
                            }
                         
                        }
                        $student_data[$filter_id]['curr'] = $curr_data ;

                    }
                    
                }
               
               
            }
            $this->data['current_year_girls_students'] = $current_year_girls_students;
            $this->data['current_year_boys_students'] = $current_year_boys_students;
            $this->data['student_data'] = $student_data;

        }
        
           
        $this->data['school'] = $school;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Classification Report | ' . SMS);
        $this->layout->view('teacher_report/teacher_report', $this->data);
    }
    
        
    public function all_teacher_report() {
        check_permission(VIEW,1);
        $district_id_selected = 0;
        $districtfilter = array();
        $zone_id_selected = 0;
        $zonefilter = array();

        if($this->session->userdata('dadmin') && $this->session->userdata('district_id'))
        {
            $district_id_selected = $this->session->userdata('district_id');
            $districtfilter = array('id' =>$this->session->userdata('district_id'));
        }
        
        if($this->session->userdata('dadmin') && $this->session->userdata('zone_id'))
        {
            $zone_id_selected = $this->session->userdata('zone_id');
            $zonefilter = array('id' =>$this->session->userdata('zone_id'));
        }
        $this->data['currrent_year_data'] = array();
        $this->data['all_teacher_report'] = true;
        $this->data['previous_year_data'] = array();
        $this->data['districts'] = $this->report->get_list('districts', $districtfilter, '','', '', 'id', 'ASC');          
        $this->data['zones'] = $this->report->get_list('zone',  $zonefilter, '','', '', 'id', 'ASC');	
       
        $this->data['student_data'] = array();
        $this->data['filter_heading'] = "School Name";	
        if ($_POST) {
            $academic_disciplines_raw = $this->report->get_list('academic_disciplines', array(), '','', '', 'id', 'ASC');          
            $academic_disciplines = array();
            foreach( $academic_disciplines_raw  as $academic_discipline)
            {
                $academic_disciplines[$academic_discipline->id] =  $academic_discipline->name;
            }
            $districts_processed = array();
            foreach( $this->data['districts']  as $district)
            {
                $districts_processed[$district->id] =  $district->name;
            }
            $zones_processed = array();
            foreach( $this->data['zones']  as $zone)
            {
                $zones_processed[$zone->id] =  $zone->name;
            }
            $school_id = $this->input->post('school_id');
            $report_type = $this->input->post('filter_type');
            $district_id = $district_id_selected ? $district_id_selected : $this->input->post('district_id') ;
            $zone_id = $zone_id_selected ? $zone_id_selected : $this->input->post('zone_id') ;
            
            if($report_type != "school")
            {
                $school_id =0;
            }
            if($report_type != "district")
            {
                $district_id =0;
            }
            if($report_type != "prant")
            {
                $zone_id =0;
            }
            $this->data['filter_type'] = $report_type;
            $this->data['school_id'] = $school_id;
            $this->data['district_id'] = $district_id;
           
            
                    $filter_column = "faculty_id";
                    $filter_column_name = "faculty_name";
                    $filter_heading  = "Teaching Class Level";
               
            $this->data['filter_heading'] = $filter_heading ;
           
           
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
                if($report_type != "school" )
                {
                    error($this->lang->line('permission_denied'));
                    redirect('dashboard');
                }
              
                $school_id=$this->session->userdata('school_id');
            }
            if(!$school_id)
            {                
                if($report_type == "district")
                {
                    $district = $this->report->get_single('districts', array('id' => $district_id));            
                    $this->data['table_heading'] = $district->name;
                }
                else if($report_type == "prant")
                {
                    $zone = $this->report->get_single('zone', array('id' => $zone_id));            
                    $this->data['table_heading'] = $zone->name;           
                }
    
                if($district_id)
                {
                    $schools = $this->report->get_school_list($school_id,$district_id,$zone_id);
                    $this->data['district_id'] = $district_id;
                }
                else {
                    $schools = $this->report->get_school_list( $school_id,null,$zone_id);
                    // echo $this->db->last_query();
                    // debug_a($schools);
                }
                
            }
            else
            {

                $schools = $this->report->get_school_list($school_id,$this->session->userdata('district_id'));
                $school = $this->report->get_school_by_id($school_id);
                $this->data['table_heading'] = $school->school_name;   
            }
            $this->data['zone_id'] = $zone_id;

            $school_ids = [];
            $schools_processed = [];
            foreach($schools as $school_obj)
            {
                $schools_processed[$school_obj->id] = $school_obj;
                $school_ids[] =  $school_obj->id;
            }
            $teachers_data  = [];
            $classes = [];
            $section_count_data = [];
            $students_data = [];
            if(!empty($school_ids))
            {
                $teachers_raw = $this->report->get_faculty_teacher_count ($school_ids);
                //debug_a($teachers_raw);
    
                foreach($teachers_raw as $teacher_raw)
                {
                    if(!isset( $teachers_data[$teacher_raw->faculty_id])){
                        $teachers_data[$teacher_raw->faculty_id]['male'] = 0;
                        $teachers_data[$teacher_raw->faculty_id]['female'] = 0;
                    } 
                    if($teacher_raw->gender == "male")
                    {
                        $teachers_data[$teacher_raw->faculty_id]['male'] = $teacher_raw->count;
                    }
                    else
                    {
                        $teachers_data[$teacher_raw->faculty_id]['female'] = $teacher_raw->count;
                    }
                }
                $section_counts = $this->report->get_section_count ($school_ids);
                foreach($section_counts as $section_count)
                {
                    $section_count_data[$section_count->faculty_id] =  $section_count->count;
                }
                $classes_raw = $this->report->get_classes ($school_ids);
                foreach($classes_raw as $class_raw)
                {
                    $classes[$class_raw->id] =  $class_raw->name;
                }
                $students_data = $this->report->get_student_data1 ($school_ids,null,$filter_column);
            }

           
            $student_data = [];
            $looping_class_id = 0;
            $looping_school_id = 0;
                    foreach($students_data as $obj)
                    {                       
                        $obj->zone_name = isset($zones_processed[$obj->zone_id]) ? $zones_processed[$obj->zone_id] : "";
                        $obj->district_name = isset($districts_processed[$obj->district_id]) ? $districts_processed[$obj->district_id] : "";
                        $obj->faculty_name = isset($academic_disciplines[$obj->faculty_id]) ? $academic_disciplines[$obj->faculty_id] : "";
                        $male_teacher_count = isset($teachers_data[$obj->faculty_id]['male']) ? $teachers_data[$obj->faculty_id]['male'] : 0;
                        $female_teacher_count = isset($teachers_data[$obj->faculty_id]['female']) ? $teachers_data[$obj->faculty_id]['female'] : 0;
                       // if(!isset($obj->section_count)) $obj->section_count =0;

                        $obj->section_count = isset($section_count_data[$obj->faculty_id]) ?  $section_count_data[$obj->faculty_id]: 0;

                        $obj->class_name = isset($classes[$obj->class_id]) ?  $classes[$obj->class_id]: '';
                        
                        $filter_id = $obj->$filter_column;
                        $class_id  = $obj->class_id;
                       
                        if(!isset($current_year_boys_students[$filter_id] )) $current_year_boys_students[$filter_id] = 0;
                        if(!isset($current_year_girls_students[$filter_id] )) $current_year_girls_students[$filter_id] = 0;
                        if(!isset($student_data[$filter_id]))
                        {
                            $student_data[$filter_id] = array("filter_name" =>'',"curr"=>array(),'male_teachers'=>0,'female_teachers'=>0);
                            $student_data[$filter_id]['filter_name'] = $obj->$filter_column_name;
                            $student_data[$filter_id]['curr'] = $curr_data = array('boys'=>0,'girls'=>0,'total'=>0);
                            $student_data[$filter_id]['male_teacher_count'] = 0;
                            $student_data[$filter_id]['female_teacher_count'] = 0;
                            $student_data[$filter_id]['section_count'] = 0;
                            $student_data[$filter_id]['section_count_'] = array();
                           
                        }
                        else
                        {
                            $curr_data = $student_data[$filter_id]['curr'];
                        }
                        $student_data[$filter_id]['male_teacher_count'] = $male_teacher_count ;
                        $student_data[$filter_id]['female_teacher_count'] = $female_teacher_count;
                        $student_data[$filter_id]['section_count'] =  $obj->section_count ;
                        
                        
                        if($obj->gender == "male" )
                        {
                            $current_year_boys_students[$filter_id] = $current_year_boys_students[$filter_id] +  $obj->count;
                            $curr_data['boys'] = $curr_data['boys'] + $curr_data['boys']+ $obj->count;
                        }
                        else
                        {
                            //debug_a($obj,1,1);
                            $current_year_girls_students[$filter_id] = $current_year_girls_students[$filter_id] +   $obj->count;
                            $curr_data['girls'] =  $curr_data['girls']  + $obj->count;
                        }
                        
                        $student_data[$filter_id]['curr'] = $curr_data ;

                    }

            $this->data['current_year_girls_students'] = $current_year_girls_students;
            $this->data['current_year_boys_students'] = $current_year_boys_students;
            $this->data['student_data'] = $student_data;

        }
    //     debug_a(2
    // );


        $this->data['school'] = $school;
        $this->data['report_url'] = site_url('report/balance');
        $this->layout->title( 'Classification Report | ' . SMS);
        $this->layout->view('teacher_report/all_teacher_report', $this->data);
    }
    
    public function working_area() {
        check_permission(VIEW,1);
        $district_id_selected = 0;
        $districtfilter = array();
        $zone_id_selected = 0;
        $zonefilter = array();

     if($this->session->userdata('dadmin') && $this->session->userdata('district_id'))
     {
         $district_id_selected = $this->session->userdata('district_id');
         $districtfilter = array('id' =>$this->session->userdata('district_id'));
     }
    
     if($this->session->userdata('dadmin') && $this->session->userdata('zone_id'))
     {
         $zone_id_selected = $this->session->userdata('zone_id');
         $zonefilter = array('id' =>$this->session->userdata('zone_id'));
     }
    
        $this->data['processed_data'] =  array();
        $this->data['districts'] = $this->report->get_list('districts', $districtfilter, '','', '', 'id', 'ASC');    
        $this->data['zones'] = $this->report->get_list('zone', $zonefilter, '','', '', 'id', 'ASC');		
       // $this->data['kshetras'] = $this->block->get_list('themes', array(), '','', '', 'id', 'ASC');      
        $this->data['report_type'] = "";
        if ($_POST) {
            $this->load->model('Block_Model', 'block', true);			

            $report_type = $this->input->post('filter_type');
            $district_id = $district_id_selected ? $district_id_selected : $this->input->post('district_id') ;
            $zone_id = $zone_id_selected ? $zone_id_selected : $this->input->post('zone_id') ;
            if($report_type != "district")
            {
                $district_id= 0;
            }
                if($report_type == "district")
                {
                    $district = $this->report->get_single('districts', array('id' => $district_id));            
                    $this->data['report_heading'] = $district->name;
                }
                else if($report_type == "prant")
                {
                    $zone = $this->report->get_single('zone', array('id' => $zone_id));            
                    $this->data['report_heading'] = $zone->name;           
                }
            switch($report_type )
            {
                case "district":
                    $filter_column = "";
                    $filter_column_name = "";
                    break;
                case "prant":
                    $filter_column = "district_id";
                    $filter_column_name = "district_name";
                    break;
                case "kshetra":
                    $filter_column = "zone_id";
                    $filter_column_name = "zone_name";
                    break;
            }
            $this->data['report_type'] = $report_type;
            $this->data['district_id'] = $district_id;
            $this->data['zone_id'] = $zone_id;
          
            if( $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
            {
                error($this->lang->line('permission_denied'));
                redirect('dashboard');
            }
         
                    if($report_type  == "district")
            {
                $block_list = $this->report->get_block_list($district_id);
                $sankul_list = $this->report->get_sankul_list($district_id);
                $data         = array();
                foreach($block_list as $block)
                {
                    $array['block'] =  $block;
                    $array['sankul'] =  array_pop($sankul_list);

                    $data[] = $array;
                }
                foreach($sankul_list as $sankul)
                {
                    $array['block'] =  null;
                    $array['sankul'] =  $sankul;

                    $data[] = $array;
                }
                $this->data['processed_data'] = $data;
            }
            else
            {
                if($report_type  == "prant")
                {
                    $block_list = $this->report->get_block_list("",$zone_id,$filter_column_name);
                    $sankul_list = $this->report->get_sankul_list("",$zone_id,$filter_column_name);
                    $data         = array();
                    foreach($block_list as $block)
                    {
                        $filter_id = $block->$filter_column;
                        if(!isset( $data[$filter_id]))
                        {
                            $data[$filter_id]['block'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;
                            $data[$filter_id]['working_block'] =0;
                            $data[$filter_id]['sankul'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;

                            $data[$filter_id]['working_sankul'] =0;
                        }
                        $data[$filter_id]['block']++;
                        if($block->school_count > 0)  $data[$filter_id]['working_block']++;
                    }
                    foreach($sankul_list as $sankul)
                    {
                        $filter_id = $sankul->$filter_column;
                        if(!isset( $data[$filter_id]))
                        {
                            $data[$filter_id]['block'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;
                            $data[$filter_id]['working_block'] =0;
                            $data[$filter_id]['sankul'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;

                            $data[$filter_id]['working_sankul'] =0;
                        }
                        $data[$filter_id]['sankul']++;
                        if($sankul->school_count > 0)  $data[$filter_id]['working_sankul']++;
                    }
                    $this->data['processed_data'] = $data;
                }
                else if($report_type  == "kshetra")
                {
                    $block_list = $this->report->get_block_list("",$zone_id,$filter_column_name);
                    $sankul_list = $this->report->get_sankul_list("",$zone_id,$filter_column_name);
                    $data         = array();
                    foreach($block_list as $block)
                    {
                        $filter_id = $block->$filter_column;
                        if(!isset( $data[$filter_id]))
                        {
                            $data[$filter_id]['block'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;
                            $data[$filter_id]['working_block'] =0;
                            $data[$filter_id]['sankul'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;

                            $data[$filter_id]['working_sankul'] =0;
                        }
                        $data[$filter_id]['block']++;
                        if($block->school_count > 0)  $data[$filter_id]['working_block']++;
                    }
                    foreach($sankul_list as $sankul)
                    {
                        $filter_id = $sankul->$filter_column;
                        if(!isset( $data[$filter_id]))
                        {
                            $data[$filter_id]['block'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;
                            $data[$filter_id]['working_block'] =0;
                            $data[$filter_id]['sankul'] =0;
                            $data[$filter_id]['filter_name'] = $block->$filter_column_name;

                            $data[$filter_id]['working_sankul'] =0;
                        }
                        $data[$filter_id]['sankul']++;
                        if($sankul->school_count > 0)  $data[$filter_id]['working_sankul']++;
                    }
                    $this->data['processed_data'] = $data;
                }
                
            }
           
          
         

        }
           
           
        $this->layout->title( 'Working Area Report | ' . SMS);
        $this->layout->view('working_area/index', $this->data);
    }
    function consolidated_trail_balance()
    {
        $account_types_raw    =     $this->report->get_account_types();
        $account_types          = array(999=>'Reciepts');
        $district_id = 0;
        $zone_id = 0;
        foreach($account_types_raw as $account_type)
        {
                $account_types[$account_type->id] = $account_type->name;
        }

        if( $this->session->userdata('role_id') != SUPER_ADMIN && !$this->session->userdata('dadmin') )
        {
            error($this->lang->line('permission_denied'));
            redirect('dashboard');
        }
   
        $schools = get_school_list($zone_id);
       

    }
      /*****************Function __invoice_creation**********************************
    * @type            : Function
    * @function name   : __invoice_creation
    * @description     : Invoice creation for student                  
    * @param           : $data array value
    * @return          : null 
    * ********************************************************** */
	  function __invoice_creation($data) {
	 
        
        $school = $this->invoice->get_school_by_id($data['school_id']);
        if(!empty($school ))
        {
            $school_id      = $school->id;
            $student_id     = $data['student_id'];
            $academic_year_id = $school->academic_year_id;

            $class_id         = 0;
            $income_head   = $this->invoice->get_income_heads($school_id,$data['previous_academic_year_id'], $data['fee_type']); 
            $aIncomeheadIds   = [];
            $iFeeIncomeheadIds   = [];
            
            $fee_amount = 0;
            $previous_enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=>$data['previous_academic_year_id']));

            if(empty($previous_enrollment ) || empty($income_head))
            {
                return array();
            }

            $class_id            = $previous_enrollment->class_id ?? 0;
            if( $data['fee_type']== 'fee' )
            {
                $fee                 = $this->invoice->fee_list($school_id,$income_head->id,$class_id);
                $fee_amount          = $fee->fee_amount;
                $student = $this->invoice->get_single('students', array('id' => $student_id));
                $enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=> @$data['previous_academic_year_id']));
                if(!empty($enrollment) && $enrollment->rte != "" && $enrollment->rte != null)
                {
                    if(strtolower($enrollment->rte) == "yes")
                    {
                       return array();
                    }
                }
                else if(!empty($student) && strtolower($student->rte) == "yes")
                {
                   return array();
                }
            }
            else if( $data['fee_type']== 'transport')
            {
                $membership = $this->invoice->get_transport_membership($student_id, @$data['previous_academic_year_id'], $school_id);
                if(empty($membership)) {
                    $fee_amount = 0;
                }
                else {
                    $fee = $this->invoice->get_transport_fee($student_id, $membership->id);
                    if (!empty($fee)) {
                        $yearly_stop_fares = $fee->yearly_stop_fare ? json_decode($fee->yearly_stop_fare,true) : array();
                        if(isset( $yearly_stop_fares[$data['previous_academic_year_id']]))
                        {
                            $fee_amount = $yearly_stop_fares[$data['previous_academic_year_id']];
                        }
                        else
                        {
                            $fee_amount = $fee->stop_fare;
                        }
                    }
                }
            }
            else if( $data['fee_type']== 'hostel')
            { 
                $membership = $this->invoice->get_hostel_membership($student_id, @$data['previous_academic_year_id'], $school_id);
                if(empty($membership)) {
                    $fee_amount = 0;
                }
                else {
                    $fee = $this->invoice->get_hostel_fee($student_id, $membership->id);
                    if (!empty($fee)) {
                        $yearly_stop_fares = $fee->yearly_room_rent ? json_decode($fee->yearly_room_rent,true) : array();
                        if(isset( $yearly_room_rent[$data['previous_academic_year_id']]))
                        {
                            $fee_amount = $yearly_room_rent[$data['previous_academic_year_id']];
                        }
                        else
                        {
                            $fee_amount = $fee->cost;
                        }
                    }
                }
            }
            else if( $data['fee_type']== 'other')
            { 
               
                $fee                 = $this->invoice->fee_list($school_id,$income_head->id,$class_id);
                $fee_amount          =  $fee_amount +$fee->fee_amount;

                $student = $this->invoice->get_single('students', array('id' => $student_id));
                $enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=> @$data['previous_academic_year_id']));
                if(!empty($enrollment) && $enrollment->rte != "" && $enrollment->rte != null)
                {
                    if(strtolower($enrollment->rte) == "yes")
                    {
                        return array();
                    }
                }
                else if(!empty($student) && strtolower($student->rte) == "yes")
                {
                    return array();
                }
                
            }


            if(!empty($income_head) )
            {
                $previous_invoices = $this->invoice->get_invoice_list_prev($school_id, $student_id,$income_head->id);
                $previous_paid   = 0;
                if(!empty($previous_invoices))
                {
                    foreach($previous_invoices as $obj)
                    {
                            $previous_paid =  $previous_paid+$obj->net_amount;   
                            if($obj->is_applicable_discount ==1 && $obj->discount)
                            {
                                $previous_paid =  $previous_paid+$obj->discount;   
                            }                     
                    }
                }
                $fee_amount = $fee_amount;
                $invoice_data = array();
                $invoice_data['income_head_id'] = $income_head->id;
                $invoice_data['income_title'] = $income_head->title;
                $invoice_data['academic_year_id'] = $income_head->academic_year_id;
                $invoice_data['invoice_type']   = $income_head->head_type;                           
                $invoice_data['fee_amount']   =  $fee_amount;

                
                $invoice_data['due_amount']     = $fee_amount - $previous_paid;



                $invoice_data['prev_class_id']  = $class_id;
                return $invoice_data['due_amount'] >0 ? $invoice_data : array();    
            }
            return     array();
        }
        return     array();

    }
      
}
