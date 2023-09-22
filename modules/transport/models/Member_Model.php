<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    
    public function get_transport_fee($student_id){
        
        $this->db->select('RS.stop_fare, RS.yearly_stop_fare');
        $this->db->from('students AS S'); 
        $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
        $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
        $this->db->where('S.id', $student_id); 
        $this->db->where('S.is_transport_member', 1);
        return $this->db->get()->row(); 
        
    }
    public function get_transport_member($id){
        
        $this->db->select('TM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
        $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
        $this->db->where('TM.id', $id); 
        $this->db->where('S.is_transport_member', 1);
        return $this->db->get()->row(); 
    }
    public function get_transport_membership($student_id, $academic_year_id = null, $school_id = null){
        
        $this->db->select('TM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
        $this->db->where('S.id', $student_id); 
        if($school_id) {
            $this->db->where('S.school_id', $school_id); 
        }
        if($academic_year_id) {
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
        }
        $this->db->where('S.is_transport_member', 1);
        return $this->db->get()->row(); 
    }
    public function get_paid_fee_amount($month,$student_id,$income_head_id,$academic_year_id,$class_id){
		$this->db->select('sum(net_amount) as paid_amount, sum(discount) as discount_amount');
        $this->db->from('invoices');
		//$this->db->where('school_id', $school_id);         
		// $this->db->where('class_id', $class_id); 
       // $this->db->where('month', $month);         
		$this->db->where('student_id', $student_id);         
		$this->db->where('income_head_id', $income_head_id);  
        // $this->db->where('academic_year_id', $academic_year_id);       
		$this->db->where('paid_status', 'paid');         		
        $result= $this->db->get();
        //echo $this->db->last_query();
        $result = $result->row();
		if(isset($result->paid_amount)){
			return $result->paid_amount + $result->discount_amount;
		}
		else {
			return 0;
		}		
	}
    public function get_income_heads($school_id,$academic_year_id =null, $fee_type= null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type ='fee' or IH.head_type ='transport' or IH.head_type ='hostel')");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.status', 1);
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        if($fee_type)
        {
            $this->db->where('IH.head_type', $fee_type);
        }
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        return $this->db->get()->row();  
    }
    public function get_transport_member_list($is_transport_member = 1, $school_id = null, $academic_year_id = null,$limit = null,$start = null, $search_text =null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*, TM.route_stop_id, TM.id AS tm_id, TM.route_id, E.roll_no, C.name AS class_name, E.section_id');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('transport_members AS TM', 'TM.user_id = ST.user_id', 'left');
        // $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        if($is_transport_member){
            $this->db->where("TM.id >0");
        }
        $this->db->where('ST.status_type','regular');

        if($academic_year_id){
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where("exists( select id from transport_members TM where TM.user_id = ST.user_id and (TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0) )");

        if($school_id) {
            $this->db->where('ST.school_id', $school_id);
        }
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        } 
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }  
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
        
		// else if($this->session->userdata('dadmin') == 1 && $school_id==null){
		// 	$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		// }		
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('ST.name ', $search_text);
            $this->db->group_end();
		}      
        $this->db->order_by('TM.id', 'DESC');
        // if($is_transport_member){
        //     $this->db->group_by('TM.user_id', 'DESC');
        // }else{
        //     $this->db->group_by('E.student_id', 'DESC');
        // }
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
        }
        $result = $this->db->get();

        return $result->result();
    
        
    }
    public function get_transport_member_list_total($is_transport_member = 1, $school_id = null, $academic_year_id = null, $search_text =null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('transport_members AS TM', 'TM.user_id = ST.user_id', 'left');
        
        if($academic_year_id){
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where("exists( select id from transport_members TM where TM.user_id = ST.user_id and (TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0) )");
        if($is_transport_member){
            $this->db->where("TM.id >0");
        }
        $this->db->where('ST.status_type','regular');
        // $this->db->where('ST.is_transport_member', $is_transport_member);
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        } 
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }  
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}		
       
        
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('ST.name ', $search_text);
            $this->db->group_end();
		}      
        $result=$this->db->get()->num_rows();
		
		return $result;
        
    }
    public function get_transport_non_member_list($is_transport_member = 1, $school_id = null, $academic_year_id = null,$limit = null,$start = null, $search_text =null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*, E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        
        // if($academic_year_id){
        //     $this->db->where('E.academic_year_id', $academic_year_id);
        // }
                
        // $this->db->where('ST.is_transport_member', $is_transport_member);
        // $this->db->join('transport_members AS TM', 'TM.user_id = ST.user_id', 'left');

        if($academic_year_id){
            // $this->db->where("");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        $this->db->where("not exists( select id from transport_members TM where TM.user_id = ST.user_id and (TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0) )");
        // $this->db->where("TM.id is null");

        $this->db->where('ST.status_type', 'regular');

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        } 
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }  
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('ST.name ', $search_text);
            $this->db->group_end();
		}      
       
        
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        $result = $this->db->get();
        //  echo $this->db->last_query();
        return $result->result();
        
    }
    public function get_transport_non_member_list_total($is_transport_member = 1, $school_id = null, $academic_year_id = null, $search_text =null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('E.id');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        // $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        // $this->db->join('transport_members AS TM', 'TM.user_id = ST.user_id', 'left');

        if($academic_year_id){
            // $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        $this->db->where("not exists( select id from transport_members TM where TM.user_id = ST.user_id and (TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0) )");

        // $this->db->where('TM.id is null');

        // if($academic_year_id){
        //     $this->db->where('E.academic_year_id', $academic_year_id);
        // }
        $this->db->where('ST.status_type', 'regular');

        // $this->db->where('ST.is_transport_member', $is_transport_member);
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        } 
       
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }  
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
			
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('ST.name ', $search_text);
            $this->db->group_end();
		}              
        $result=$this->db->get()->num_rows();
        
        return $result;
        
    }

    
    public function get_single_route_details($id){
         
        $this->db->select('ST.*, SC.school_name, R.title AS route_name, R.vehicle_ids, RS.stop_name, RS.stop_km, RS.stop_fare, TM.id AS tm_id, TM.route_id, E.roll_no, C.name AS class_name, S.name AS section,G.name AS g_name,G.user_id AS g_user_id');
        $this->db->from('transport_members AS TM');
        $this->db->join('students AS ST', 'ST.user_id = TM.user_id', 'left');
        $this->db->join('enrollments AS E', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('guardians AS G', 'G.id = ST.guardian_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('routes AS R', 'R.id = TM.route_id', 'left');
        $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        $this->db->where('TM.id', $id);
        return $this->db->get()->row();
        
    }

    // For get old due
    public function get_invoice_list_prev($school_id, $student_id, $income_head_id =null){

        $this->db->select('I.*, SC.school_name,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where('I.student_id', $student_id);   
        $this->db->where('I.school_id', $school_id);
        if($income_head_id)
        {
            $this->db->where('I.income_head_id', $income_head_id);

        }
        $this->db->where('SC.status', 1);     
        $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result();      
   
    }
   
}
