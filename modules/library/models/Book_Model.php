<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Book_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_book_list($school_id = null){
        
        $this->db->select('B.*, S.school_name');
        $this->db->from('books AS B');
        $this->db->join('schools AS S', 'S.id = B.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('B.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('B.school_id', $school_id);
        }     
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('B.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}		
        $this->db->order_by('B.id', 'DESC');
        
        return $this->db->get()->result();        
    }
    
    public function get_single_book($book_id){
        
        
        $this->db->select('B.*, S.school_name');
        $this->db->from('books AS B');
        $this->db->join('schools AS S', 'S.id = B.school_id', 'left');
        $this->db->where('B.id', $book_id);
   
        return $this->db->get()->row();        
    }
    public function get_single_issue_detail($issue_id){
        $this->db->select('BI.*,LM.user_id,U.role_id as user_role_id');
        $this->db->from('book_issues AS BI');
        $this->db->join('library_members AS LM', 'LM.id = BI.library_member_id', 'left');
        $this->db->join('Users AS U', 'U.id = LM.user_id and LM.id = BI.library_member_id', 'left');
        $this->db->join('books AS B', 'B.id = BI.book_id', 'left');
        $this->db->where('BI.id', $issue_id);
   
        $result =  $this->db->get()->row();   
        return $result;     
    }
    public function get_book_list_ajax( $school_id = null,$search_text = null,$start=null,$limit=null){
        $this->db->select('B.*, S.school_name');
        $this->db->from('books AS B');
        $this->db->join('schools AS S', 'S.id = B.school_id', 'left');
         $this->db->where('B.school_id', $school_id); 
         $this->db->order_by('B.title', 'ASC');
         if($search_text!=''){
            $this->db->like('B.title', $search_text);
		}            
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
         }   
        $result=$this->db->get();		
		return $result->result();;
    }
	public function get_book_list_ajax_total( $school_id = null,$search_text = ""){
        $this->db->select('B.*, S.school_name');
        $this->db->from('books AS B');
        $this->db->join('schools AS S', 'S.id = B.school_id', 'left');
         $this->db->where('B.school_id', $school_id); 
        if($search_text!=''){
            $this->db->like('B.title', $search_text);
		}               
        
        $result=$this->db->get()->num_rows();		
		return $result;
        
    }
    public function get_book_issued_list($school_id = null, $academic_year_id = null) {
        
        $this->db->select('B.*, SC.school_name, BI.id AS issue_id, BI.is_returned, BI.due_date, BI.return_date, BI.issue_date, S.name, S.photo, S.phone');
        $this->db->distinct('S.id');
        $this->db->from('book_issues AS BI');
        $this->db->join('books AS B', 'B.id = BI.book_id', 'left');
        $this->db->join('library_members AS LM', 'LM.id = BI.library_member_id', 'left');
        $this->db->join('students AS S', 'S.user_id = LM.user_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = BI.school_id', 'left');
        
        if($academic_year_id){
            $this->db->where('BI.academic_year_id', $academic_year_id);
        }
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('S.user_id', $this->session->userdata('user_id'));
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('B.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('B.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('B.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('BI.is_returned', 'ASC');
        return $this->db->get()->result();
    }

    public function get_library_member_list($is_library_member = 1, $school_id = null , $academic_year_id = null,$start=null,$limit=null,$search_text='') {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*, SC.school_name, LM.id AS lm_id, LM.custom_member_id, E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('library_members AS LM', 'LM.user_id = ST.user_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        $this->db->where('ST.is_library_member', $is_library_member);
        
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ST.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == TEACHER ){
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        if($search_text!=''){
		
			$this->db->like('ST.name ', $search_text);
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
        
        $this->db->order_by('LM.id', 'DESC');
        
         if($is_library_member){
            $this->db->group_by('LM.user_id', 'DESC');
        }else{
            $this->db->group_by('E.student_id', 'DESC');
        }	
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }	
        $result= $this->db->get()->result();
		return $result;
    }
    public function get_library_member_list_total($is_library_member = 1, $school_id = null , $academic_year_id = null,$search_text='') {

        if(!$school_id){
            return;
        }
        
        $this->db->select('ST.*, SC.school_name, LM.id AS lm_id, LM.custom_member_id, E.roll_no, C.name AS class_name, S.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('library_members AS LM', 'LM.user_id = ST.user_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ST.school_id', 'left');
        $this->db->where('ST.is_library_member', $is_library_member);
        
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        if($search_text!=''){
		
			$this->db->like('ST.name ', $search_text);
		}      
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
        
        $this->db->order_by('LM.id', 'DESC');
        
         if($is_library_member){
            $this->db->group_by('LM.user_id', 'DESC');
        }else{
            $this->db->group_by('E.student_id', 'DESC');
        }		
        $result=$this->db->get()->num_rows();
		
		return $result;
    }
    
    public function update_qty($book_id, $type = null){
        
        if($type == 'issue'){
            $sql = "UPDATE books SET qty = qty - 1 WHERE id = $book_id";
        }else{
            $sql = "UPDATE books SET qty = qty + 1 WHERE id = $book_id";
        }
        
        $this->db->query($sql);
    }

}
