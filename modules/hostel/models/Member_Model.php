<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     public function get_hostel_member_list($is_hostel_member = 1, $school_id = null, $academic_year_id = null ,$limit =null,$start=null,$search_text= null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*,SC.school_name, HM.id AS hm_id, H.name AS hostel_name, R.room_no, R.room_type, R.cost,R.yearly_room_rent, E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join(' students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('hostel_members AS HM', 'HM.user_id = ST.user_id', 'left');
        $this->db->join('hostels AS H', 'H.id = HM.hostel_id', 'left');
        $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        
        if($academic_year_id){
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where('ST.is_hostel_member', $is_hostel_member);
        if( $is_hostel_member) {
            $this->db->where('HM.id>0');
        }
        else {
            $this->db->where("not exists( select id from hostel_members HM where HM.user_id = ST.user_id and (HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0) )");

            // $this->db->where('coalesce(HM.id,0)=0');
        }


        if($school_id ){
            $this->db->where('ST.school_id', $school_id);
        }
        if($this->session->userdata('role_id') == TEACHER ){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        $this->db->where('ST.status_type','regular');

        $this->db->order_by('HM.id', 'DESC');
        if($search_text!=''){
			$this->db->like('ST.name ', $search_text);
		}  
        if($is_hostel_member){
            $this->db->group_by('HM.user_id', 'DESC');
        }else{
            $this->db->group_by('E.student_id', 'DESC');
        }
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        
        return $this->db->get()->result();
    }
    public function get_hostel_non_member_list($school_id = null, $academic_year_id = null ,$limit =null,$start=null,$search_text= null) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*,SC.school_name,  E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join(' students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        
       
        // $this->db->where('ST.is_hostel_member', $is_hostel_member);
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        $this->db->where("not exists( select id from hostel_members HM where HM.user_id = ST.user_id and (HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0) )");
            // $this->db->where('coalesce(HM.id,0)=0');

        if($school_id ){
            $this->db->where('ST.school_id', $school_id);
        }
        if($this->session->userdata('role_id') == TEACHER ){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        $this->db->where('ST.status_type','regular');
        if($search_text!=''){
			$this->db->like('ST.name ', $search_text);
		}  
       
        $this->db->group_by('E.student_id', 'DESC');
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        
        return $this->db->get()->result();
    }
    public function check_hostel_member($user_id,$school_id,$academic_year_id ) {
        $this->db->select('ST.id,E.academic_year_id,HM.user_id');
        $this->db->from('hostel_members AS HM');
        $this->db->join('students AS ST', 'HM.user_id = ST.user_id', 'left');
        $this->db->join('enrollments AS E', 'E.student_id = ST.id', 'left');
        $this->db->where('ST.school_id', $school_id);
        $this->db->where('E.academic_year_id', $academic_year_id);
        if($academic_year_id){
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        $this->db->where('ST.is_hostel_member', 1);
        
        $this->db->where('HM.user_id', $user_id);
        return $this->db->get()->result();
    }
    public function get_hostel_non_member_list_total($school_id = null, $academic_year_id = null,$search_text=null ) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*,SC.school_name, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        $this->db->where('ST.status_type','regular');
      
        $this->db->where("not exists( select id from hostel_members HM where HM.user_id = ST.user_id and (HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0) )");

        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where('ST.is_hostel_member', $is_hostel_member);
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }     
        if($this->session->userdata('role_id') == TEACHER ){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}		
        
        if($search_text!=''){
			$this->db->like('ST.name ', $search_text);
		}      
       
            $this->db->group_by('E.student_id', 'DESC');
        
        $result=$this->db->get()->num_rows();
		
		return $result;
    }
    public function get_hostel_member_list_total($is_hostel_member = 1, $school_id = null, $academic_year_id = null,$search_text=null ) {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*,SC.school_name, HM.id AS hm_id, H.name AS hostel_name, R.room_no, R.room_type, R.cost, E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('hostel_members AS HM', 'HM.user_id = ST.user_id', 'left');
        $this->db->join('hostels AS H', 'H.id = HM.hostel_id', 'left');
        $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        $this->db->where('ST.status_type','regular');
        if( $is_hostel_member) {
            $this->db->where('HM.id>0');
        }
        else {
            $this->db->where('coalesce(HM.id,0)=0');
        }

        if($academic_year_id){
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        // $this->db->where('ST.is_hostel_member', $is_hostel_member);
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }     
        if($this->session->userdata('role_id') == TEACHER ){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ST.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}		
        
        $this->db->order_by('HM.id', 'DESC');
        if($search_text!=''){
			$this->db->like('ST.name ', $search_text);
		}      
        if($is_hostel_member){
            $this->db->group_by('HM.user_id', 'DESC');
        }else{
            $this->db->group_by('E.student_id', 'DESC');
        }
        
        $result=$this->db->get()->num_rows();
		
		return $result;
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
    public function get_hostel_fee($student_id){
        
        $this->db->select('R.cost,R.yearly_room_rent');
        $this->db->from('students AS S'); 
        $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
        $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        $this->db->where('S.id', $student_id); 
        $this->db->where('S.is_hostel_member', 1);
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
    public function get_hostel_member($id){
        
        $this->db->select('HM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
        $this->db->where('HM.id', $id); 
        $this->db->where('S.is_hostel_member', 1);
        return $this->db->get()->row(); 
    }
    public function get_hostel_membership($student_id, $academic_year_id = null, $school_id = null){
        
        $this->db->select('HM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
        $this->db->where('S.id', $student_id); 
        if($school_id) {
            $this->db->where('S.school_id', $school_id); 
        }
        if($academic_year_id) {
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
        }
        $this->db->where('S.is_hostel_member', 1);
        return $this->db->get()->row(); 
    }
    
}
