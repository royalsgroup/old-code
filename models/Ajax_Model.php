<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_student_list($class_id, $school_id, $academic_year_id, $alumni =null){
        $this->db->select('E.roll_no,  S.id, S.user_id, S.name,S.admission_no,S.father_name');
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        if($class_id)
        {
            $this->db->where('E.class_id', $class_id); 
        }
        if($alumni)
        {
            $this->db->where("S.status_type != 'regular'"); 
        }
        else
        {
            $this->db->where('S.status_type', 'regular'); 
        }
        $this->db->where('E.school_id', $school_id);       
        return $this->db->get()->result();       
    }
    public function get_transport_students($school_id = null,$class_id =null, $academic_year_id =null,$alumni=null,$all = 0)
    {
        $this->db->select("S.*");
        $this->db->from('students AS S');        
        $this->db->join('enrollments AS E', 'E.student_id = S.id');
  
        $this->db->where("S.school_id=$school_id ");   
    
        $this->db->where('E.class_id', $class_id);   
        if($academic_year_id){
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where('E.academic_year_id', $academic_year_id);   
        $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
        $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
        $this->db->where('TM.id>0');
        if(!$all)
        {
            if($alumni)
            {
                $this->db->where("S.status_type != 'regular'"); 
            }
            else
            {
                $this->db->where('S.status_type', 'regular'); 
            }
    
        }
      
        $result =  $this->db->get();  
        if (($_GET['debug_mode'] ?? "") || ($_SESSION['debug_mode'] ?? ""))
        {
            // echo $this->db->last_query();
            // die();
        }
        
        return $result->result();  

    }
    public function get_fee_type($school_id,$academic_year_id=null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type = 'fee' OR IH.head_type = 'hostel'  OR IH.head_type = 'transport'  OR IH.head_type = 'other' ) "); 
        $this->db->where('IH.school_id', $school_id); 
      
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        return $this->db->get()->result(); 
      
    }
    public function get_hostel_students($school_id = null,$class_id =null, $academic_year_id =null,$alumni =null)
    {
        $this->db->select("S.*");
        $this->db->from('students AS S');        
        $this->db->join('enrollments AS E', 'E.student_id = S.id');
  
        $this->db->where("S.school_id=$school_id ");   
    
        $this->db->where('E.class_id', $class_id);   
    
        $this->db->where('E.academic_year_id', $academic_year_id); 
        $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
  
        $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
       $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        $this->db->where('S.is_hostel_member', 1);
        if($alumni)
        {
            $this->db->where("S.status_type != 'regular'"); 
        }
        else
        {
            $this->db->where('S.status_type', 'regular'); 
        }
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  

    }
    public function get_invoice_list($school_id = null, $due = null, $academic_year_id = null){
        
        $this->db->select('I.*, SC.school_name, IH.title AS head, S.name AS student_name,S.father_name AS father_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');  
        
        if($due){
            $this->db->where('I.paid_status !=', 'paid');  
            
        }  
        
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
           
        }   
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.student_id', $this->session->userdata('profile_id'));
       }  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('I.school_id', $this->session->userdata('school_id'));
       } 
        
        if($academic_year_id){
            $this->db->where('I.academic_year_id', $academic_year_id); 
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
       
       // $this->db->group_by('I.student_id');    
       $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result(); 
        // print_r($this->db->last_query());exit       
    }
    
    public function get_student_list_by_section($school_id = null, $section_id = null){
        
        $school = $this->get_school_by_id($school_id);
        
        $this->db->select('E.roll_no, S.name, S.id');
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        
        if(!empty($school)){
             $this->db->where('E.academic_year_id', $school->academic_year_id); 
             $this->db->where('E.school_id', $school_id); 
        } 
        
        if($section_id)
        {
            $this->db->where('E.section_id', $section_id);
        }
       
       
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        $this->db->where('S.status_type', 'regular');

        return $this->db->get()->result();        
    }
    
    public function get_user_list($school_id, $type) {
        
        if ($type == 'teacher') {
            
            $this->db->select('T.name, T.user_id, T.responsibility AS designation,  U.username, U.role_id');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left');
          //  $this->db->where('T.salary_grade_id >', 0);
            $this->db->where('T.school_id', $school_id);
            $this->db->where('T.alumni', '0');
            $this->db->order_by('T.id', 'ASC');
            return $this->db->get()->result();
            
        } elseif ($type == 'employee') { 
            
            $this->db->select('E.name, E.user_id,  U.username, U.role_id, D.name AS designation');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left'); 
            //$this->db->where('E.salary_grade_id >', 0);
             $this->db->where('E.school_id', $school_id);
             $this->db->where('E.alumni', '0');
            $this->db->order_by('E.id', 'ASC');
            return $this->db->get()->result();
            
        } else {
            return array();
        }
    }
    public function get_user_list1($school_id, $type) {
        $teacher =  $employees=  array();
        if ($type == 'teacher' ||  $type == 'all') {
            
            $this->db->select('T.name, T.user_id, T.responsibility AS designation,  U.username, U.role_id');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left');
          //  $this->db->where('T.salary_grade_id >', 0);
            $this->db->where('T.school_id', $school_id);
            $this->db->where('T.alumni', '0');
            $this->db->where('T.smc', 'no');
            $this->db->order_by('T.id', 'ASC');
            $teacher= $this->db->get()->result();
            
        } 
        if ($type == 'employee' ||  $type == 'all') { 
            
            $this->db->select('E.name, E.user_id,  U.username, U.role_id, D.name AS designation');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left'); 
            //$this->db->where('E.salary_grade_id >', 0);
             $this->db->where('E.school_id', $school_id);
             $this->db->where('E.alumni', '0');
             $this->db->where('E.smc', 'no');
            $this->db->order_by('E.id', 'ASC');
            $employees=  $this->db->get()->result();
            
        }
         if(empty($teacher) && empty($employees)) {
            return array();
        }
        else
        {
           // var_dump($teacher,$employees);
            return array_merge($teacher,$employees);
        }
    }
    public function get_ledger_list($school_id,$onlyassets =null,$category = null){
        $this->db->select('AL.*,AG.name as group_name,AG.base_id, AG.type_id');
        $this->db->from('account_ledgers AS AL');        
        $this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = AL.school_id', 'left');
        $this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
        $this->db->where('SC.financial_year_id = ALD.financial_year_id');
        if($school_id){
            $this->db->where('Al.school_id', $school_id);       
        }
       
        if($onlyassets){
            $this->db->where('AG.type_id', 3); 
        }      
        if($category)
        {
            $this->db->where('AL.category', $category); 

        }
        $result = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $result->result();       
    }
    
    public function get_section_by_class($school_id,$class_id){
        $this->db->select('SE.*');
        $this->db->from("sections SE");        
        $this->db->join('classes AS C', 'C.id = SE.class_id', 'left');
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->group_start();
            $this->db->where('SE.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('C.teacher_id', $this->session->userdata('profile_id'));
            $this->db->group_end();
            if ($this->session->userdata('default_data') ==1)
            {
                $this->db->or_where('C.school_id','0'); 
            }
            else
            {
                $this->db->where('C.school_id', $this->session->userdata('school_id'));   
            }

        }
        else{
            if ($this->session->userdata('default_data') ==1)
            {
                $this->db->or_where('C.school_id','0'); 
            }
            else
            {
                $this->db->where('C.school_id', $school_id);   
            }
        }
        $this->db->where('C.id', $class_id);   

       return $this->db->get()->result();       
    }  

    public function disciplines($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);        
        if ($this->session->userdata('default_data') ==1)
        {
            $this->db->or_where('D.school_id','0'); 
        }
        else
        {
            $this->db->where('school_id', $school_id);   
        }
        return $this->db->get()->result();       
    }  

    public function get_class_by_school($table,$status,$school_id){
    	$this->db->select('*');
        $this->db->from($table);        
        $this->db->where('status',$status);      
        if ($this->session->userdata('default_data') ==1)
        {
            $this->db->where('school_id', '0');   

        }
        else
        {
            $this->db->where('school_id', $school_id);   


        }
        return $this->db->get()->result(); 
    }

       public function get_class_by_sections($table,$status,$school_id,$class_id){
    	$this->db->select('*');
        $this->db->from($table);        
        $this->db->where('status',$status);      
        if ($this->session->userdata('default_data') ==1)
        {
            $this->db->where('school_id', '0');   

        }
        else
        {
            $this->db->where('school_id', $school_id);   

        }
        $this->db->where('class_id',$class_id); 
        return $this->db->get()->result(); 
    }

     public function get_payscale_category_by_school($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
       $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
 
}

     