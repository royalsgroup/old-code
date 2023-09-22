<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_student_list($class_id = null, $school_id = null, $academic_year_id = null,$start=null,$limit=null,$search_text='',$orderby = null,$order_dir = "ASC"){
            
        /*if(!$class_id){
            return;
        }*/
        $school_query = $orderby == "school_name" ?  ",(select school_name from schools where id=E.school_id) as school_name" : "";
        $this->db->select('S.*, E.roll_no, E.class_id,E.academic_year_id, E.class_id, E.section_id'. $school_query );
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }
        else {
            $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id and AY.is_running=1', 'left');
        }
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
            
		if($search_text!=''){
            $this->db->group_start();
			$this->db->like('S.name ', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('E.roll_no', $search_text);
            $this->db->or_like('S.admission_no', $search_text);
            $this->db->or_like('S.admission_date', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('SE.name', $search_text);

            $this->db->group_end();
            
		}
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') == STUDENT){
           $this->db->where('S.id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
           // $this->db->or_where('S.school_id','0');  
        } 
        if( $this->session->userdata('role_id') == TEACHER){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id') ); 
           // $this->db->or_where('S.school_id','0');  
        } 
		 if($school_id && $this->session->userdata('dadmin') == 1){
            $this->db->where('S.school_id', $school_id); 
           // $this->db->or_where('S,school_id','0');  
        } 
        $this->db->where('S.status_type', 'regular');
     
       if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
    if($orderby)
    {
        $this->db->order_by($orderby, $order_dir);
    }
        $this->db->group_by('S.id');

        $result=$this->db->get();	
		return $result->result();
        
    }
	 public function get_student_list_total($class_id = null, $school_id = null, $academic_year_id = null,$search_text=''){
        $this->db->select('S.*, SE.name as section_name, C.name as class_name');

        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id and AY.is_running=1', 'left');
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }
        else {
            $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id and AY.is_running=1', 'left');
        }
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
         if($search_text!=''){
			$this->db->group_start();
			$this->db->like('S.name ', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('SE.name', $search_text);
            $this->db->or_like('S.admission_no', $search_text);
            $this->db->or_like('E.roll_no', $search_text);
            $this->db->group_end();
		}      
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') == STUDENT){
           $this->db->where('S.id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        if( $this->session->userdata('role_id') == TEACHER){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id') ); 
           // $this->db->or_where('S.school_id','0');  
        } 
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
        } 
		 if($school_id && $this->session->userdata('dadmin') == 1){
            $this->db->where('S.school_id', $school_id); 
        } 
        $this->db->where('S.status_type', 'regular');
        $this->db->group_by('S.id');
        $result=$this->db->get();
        // echo $this->db->last_query();
		return $result->num_rows();;
        
    }
	public function get_alumnistudent_list($class_id = null, $school_id = null, $academic_year_id = null){
        $this->db->select('S.*, SC.school_name, E.roll_no, E.class_id,E.section_id, U.username, U.role_id,  C.name AS class_name');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
		//$this->db->where('E.section_id is NOT NULL', NULL, FALSE);
        //$this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');        
        if($academic_year_id!=NULL){
            $this->db->where('E.academic_year_id != ', $academic_year_id); 
        }
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
                
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') == STUDENT){
           $this->db->where('S.id', $this->session->userdata('profile_id'));
        }
        if( $this->session->userdata('role_id') == TEACHER){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id') ); 
           // $this->db->or_where('S.school_id','0');  
        } 
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
        } 
		if($this->session->userdata('dadmin') == 1 && $school_id){
			
            $this->db->where('S.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
			//$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}
        
        $this->db->order_by('E.roll_no', 'ASC');
		//$result=$this->db->get()->result();
       //print_r($this->db->last_query()); exit;
	    $result=$this->db->get()->result();
		$i=0;
		foreach($result as $r){
			// get section if not null
			if($r->section_id != NULL){
				 $this->db->select('S.*');
				$this->db->from('sections AS S');
				$this->db->where('S.id', $r->section_id);
				$section=$this->db->get()->row();     
				$result[$i]->section_name=$section->name;
			}
			else{
				$result[$i]->section_name='';
			}
			$i++;
		}
       
		return $result;
        
    }
    
    public function get_single_student($id,  $academic_year_id){
        
        $this->db->select('S.*,  SC.school_name, T.type, D.amount, D.title AS discount_title, SC.school_name, G.name as guardian, E.academic_year_id, E.roll_no, E.class_id, E.section_id, U.username, U.role_id, R.name AS role,  C.name AS class_name, coalesce(E.rte,S.rte) as rte');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        //$this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
        $this->db->join('student_types AS T', 'T.id = S.type_id', 'left');
        $this->db->where('S.id', $id);
        $this->db->where('E.academic_year_id', $academic_year_id);
        
        $res= $this->db->get()->row();  
// get section
/*if($res->section_id != NULL){
				 $this->db->select('S.*');
				$this->db->from('sections AS S');
				$this->db->where('S.id', $res->section_id);
				$section=$this->db->get()->row();     
				$res->section_name=$section->name;
			}
			else{
				$res->section_name='';
			}*/			
		return $res;
    }
        
    public function check_existing_students($school_id,$admission_numbers){
        
        $this->db->select('S.id,S.admission_no');
        $this->db->from('students AS S');
        $this->db->where('S.school_id', $school_id);
        $this->db->where_in('S.admission_no', $admission_numbers);
        return $this->db->get()->result_array();
        
    }  
        
    public function get_single_guardian($id){
        
        $this->db->select('G.*, U.username, U.role_id, R.name as role');
        $this->db->from('guardians AS G');
        $this->db->join('users AS U', 'U.id = G.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->where('G.id', $id);
        return $this->db->get()->row();
        
    }
    public function check_general_fee($school_id,$financial_year){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where('IH.head_type', "fee");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.financial_year_id', $financial_year);
        return $this->db->get()->row();  
    
        
    }
    public function get_discount($discount_id){
        $this->db->select('D.*');
        $this->db->from('discounts AS D'); 
        $this->db->where('D.id', $discount_id);  
        return $this->db->get()->row();
    }
    public function get_single_amount($school_id,$class_id,$income_heads_id){

        $this->db->select('FA.*');
        $this->db->from('fees_amount AS FA'); 
        $this->db->where('FA.income_head_id', $income_heads_id);
        $this->db->where('FA.class_id', $class_id);
        $this->db->where('FA.school_id', $school_id);
        $this->db->where('FA.status', 1);
        return $this->db->get()->row(); 
    }
    public function generate_invoice_no($school_id){		
		$this->db->select('I.*,S.school_code');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
        $this->db->limit(1);
		$this->db->where("I.school_id",$school_id);      
        $this->db->order_by('I.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
			$invoice_no="INV".$row->school_code."/".($arr[1] +1);
		}
		else{
			$invoice_no="INV".$row->school_code."/".'1000001';
		}		
		return $invoice_no;
	}
    public function get_invoice_list($school_id, $student_id){

        $this->db->select('I.*, SC.school_name,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where('I.student_id', $student_id);   
        $this->db->where('I.school_id', $school_id);
        $this->db->where('SC.status', 1);     
        $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result();      
   
}
public function get_students_by_sr($sr_numbers = array(),$school_id){
    $this->db->select('*');
    $this->db->from('students');
    $this->db->where_in('admission_no', $sr_numbers);
    $this->db->where('school_id', $school_id);
    return $this->db->get()->result();
} 
public function get_students_results_by_sr($sr_numbers = array()){
    $this->db->select('*');
    $this->db->from('temp_result');
    $this->db->where_in('sr', $sr_numbers);
    return $this->db->get()->result_array();
} 

    public function get_activity_list($student_id){
        
        $this->db->select('SA.*, ST.name AS student, ST.phone, C.name AS class_name, S.name as section, AY.session_year');
        $this->db->from('student_activities AS SA');
        $this->db->join('students AS ST', 'ST.id = SA.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = SA.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = SA.section_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SA.academic_year_id', 'left');
        $this->db->where('SA.student_id', $student_id);
        return $this->db->get()->result();
    } 
    
    
    function duplicate_check($username, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('username', $username);
        return $this->db->get('users')->num_rows();
    }
	function duplicate_check_roll_no($roll_no, $id = null,$school_id=null,$class_id=null,$section_id=null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
		if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
		if ($class_id) {
            $this->db->where('class_id', $class_id);
        }
		if ($section_id) {
            $this->db->where('section_id', $section_id);
        }
        $this->db->where('roll_no', $roll_no);
        return $this->db->get('enrollments')->num_rows();
    }
	function generate_student_code($school_id){
		$this->db->select('School.*,Sankul.name as sankul,S.name as state,Z.name as zone,SZ.name as subzone,D.name as district,B.name as block');
        $this->db->from('schools AS School'); 
		$this->db->join('sankul AS Sankul', 'Sankul.id = School.sankul_id', 'left');			
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');   
		$this->db->where('School.id',$school_id);		
        $school=$this->db->get()->row();
		$state=strtoupper(substr($school->state,0,2));
		$zone=strtoupper(substr($school->zone,0,2));
		$subzone=strtoupper(substr($school->subzone,0,2));
		$district=strtoupper(substr($school->district,0,2));
		$block=strtoupper(substr($school->block,0,2));
		$sankul=strtoupper(substr($school->sankul,0,2));
		// get last 5 digit of school code and +1
		$this->db->select('S.*');
        $this->db->from('students AS S'); 
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$student=$this->db->get()->row();
		//print_r($school); exit;
		if(!empty($student)){
			$code_no=(substr($student->student_code,strlen($student->student_code)-3))+1;
			$formated_code=sprintf("%03d", $code_no);
			$code=$state.$zone.$subzone.$district.$block.$sankul."-ST".$formated_code;
		}
		else{
			$code=$state.$zone.$subzone.$district.$block.$sankul."-ST001";
		}
		return $code;
	}

    public function dropStudent($studentId, $dropDate, $dropReason)
    {
        $this->db->set('drop_date', $dropDate);
        $this->db->set('drop_reason', $dropReason);
        $this->db->set('status_type', 'drop');
        $this->db->where('id', $studentId);
        $this->db->update('students');
        return true;
    }
    
    public function pass_student($studentId)
    {
        $this->db->set('status_type', 'passed');
        $this->db->where('id', $studentId);
        $this->db->update('students');
        return true;
    }
    public function unalumni_student($studentId)
    {
        $this->db->set('drop_date', null);
        $this->db->set('drop_reason', null);
        $this->db->set('status_type', 'regular');
        $this->db->where('id', $studentId);
        $this->db->update('students');
        return true;
    }
    public function unalumni_student_bulk($studentIds)
    {
        $this->db->set('transfer_date', null);
        $this->db->set('transfer_reason', null);
        $this->db->set('drop_date', null);
        $this->db->set('drop_reason', null);
        $this->db->set('status_type', 'regular');
        $this->db->where_in('id', $studentIds);
        $this->db->update('students');
        return true;
    }
    public function bulk_pass_student($studentIds)
    {
        $this->db->set('status_type', 'passed');
        $this->db->where('id IN (' . implode(',', array_map('intval', $studentIds)) . ')');
        $this->db->update('students');
        return true;
    }

    public function transferStudent($studentId, $transferDate, $transferReason)
    {
        $this->db->set('transfer_date', $transferDate);
        $this->db->set('transfer_reason', $transferReason);
        $this->db->set('status_type', 'transfer');
        $this->db->where('id', $studentId);
        $this->db->update('students');
        return true;
    }
   
    public function getAllAlumniStudentList($class_id = null, $school_id = null, $academic_year_id = null,$start=null,$limit=null, $search_text='',$orderby = null,$order_dir = "ASC"){

        /*if(!$class_id){
            return;
        }*/
        
        $this->db->select("S.*, SC.school_name, E.roll_no, E.class_id, U.username, U.role_id,  C.name AS class_name, SE.name AS section_name,CONCAT(transfer_reason,drop_reason,reason_of_seperation) as alumni_reason
        ,(select session_year from academic_years where id=E.academic_year_id) as academic_year");
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        // if($academic_year_id!=NULL){
        //     $this->db->where('E.academic_year_id != ', $academic_year_id); 
        // }
        /*if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }*/
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('S.name ', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('SE.name', $search_text);
            $this->db->or_like('E.roll_no', $search_text);
            $this->db->group_end();
		}     
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
                
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') == STUDENT){
           $this->db->where('S.id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
			
            $this->db->where('S.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
			//$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}

        $this->db->where('SC.status', 1);
        //$this->db->where('S.status_type', 'drop');
        $this->db->where_in('S.status_type', ['drop','transfer','passed']);
        $this->db->order_by('E.roll_no', 'ASC');
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
		}
        if($orderby)
        {
            $this->db->order_by($orderby, $order_dir);
        }
        $result =  $this->db->get();
        // echo $this->db->last_query();
        return $result->result();
    }
	public function getAllAlumniStudentListTotal($class_id = null, $school_id = null, $academic_year_id = null,$search_text='', $orderby = null,$order_dir = "ASC"){
         
        //$this->db->select('S.*, SC.school_name, E.roll_no, E.class_id, U.username, U.role_id,  C.name AS class_name, SE.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        //  if($academic_year_id!=NULL){
        //     $this->db->where('E.academic_year_id != ', $academic_year_id); 
        // }
       /* if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }*/
        if($search_text!=''){
			$this->db->group_start();
			$this->db->like('S.name ', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('SE.name', $search_text);
            $this->db->or_like('E.roll_no', $search_text);
            $this->db->group_end();
		}     
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
                
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') == STUDENT){
           $this->db->where('S.id', $this->session->userdata('profile_id'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
			
            $this->db->where('S.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
			//$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}

        $this->db->where('SC.status', 1);        
        $this->db->where_in('S.status_type', ['drop','transfer','passed']);
        $this->db->order_by('E.roll_no', 'ASC');
                
        $result=$this->db->get()->num_rows();		
		return $result;
        
    }


       public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
}
