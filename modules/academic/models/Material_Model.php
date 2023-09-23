<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Material_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_school($school_id){

        $schoolData = $this->db->query("select * from schools where id ='$school_id'")->row();
        return $schoolData;
     }
     public function get_material_list($class_id = null, $school_id = null,$start=null,$limit=null,$search_text='' ){
        
        if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        }

        $schoolData = $this->get_school($school_id);
        //echo "<pre>";print_r($schoolData);die;
         $academic_year_id = $schoolData->academic_year_id;
        $this->db->select('SM.*, SC.school_name, C.name AS class_name, S.name AS subject');
        $this->db->from('study_materials AS SM');
        $this->db->join('classes AS C', 'C.id = SM.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SM.subject_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SM.school_id', 'left');

        if($academic_year_id){
            $this->db->where('SM.academic_year_id', $academic_year_id);
        }
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->group_start();
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('C.teacher_id', $this->session->userdata('profile_id'));
            $this->db->group_end();

        }
        if($search_text!=''){
		
			$this->db->like('SM.title ', $search_text);
		}    
        if($class_id){
            $this->db->where('SM.class_id', $class_id);
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('SM.school_id', $school_id); 
        }   
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('SM.school_id', $this->session->userdata('school_id'));
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('SM.school_id', $school_id);
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}	
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }	
        $this->db->order_by('SM.id', 'DESC');
        return $this->db->get()->result();
        
    }
    public function get_material_list_total($class_id = null, $school_id = null,$search_text='' ){
        
        if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        }
         
        $this->db->select('SM.*, SC.school_name, C.name AS class_name, S.name AS subject');
        $this->db->from('study_materials AS SM');
        $this->db->join('classes AS C', 'C.id = SM.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SM.subject_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SM.school_id', 'left');

        $schoolData = $this->get_school($school_id);
        $academic_year_id = $schoolData->academic_year_id;
        if($academic_year_id){
            $this->db->where('SM.academic_year_id', $academic_year_id);
        }


        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
        }
        if($search_text!=''){
		
			$this->db->like('SM.title ', $search_text);
		}    
        if($class_id){
            $this->db->where('SM.class_id', $class_id);
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('SM.school_id', $school_id); 
        }   
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('SM.school_id', $this->session->userdata('school_id'));
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('SM.school_id', $school_id);
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}	
        $this->db->order_by('SM.id', 'DESC');
        $result=$this->db->get()->num_rows();
		
		return $result;
        
    }
    public function get_student_list($school_id, $class_id, $section_id, $academic_year_id = null ){
        
        $this->db->select('S.user_id,S.email, S.phone, S.name, G.name AS g_name, G.email AS g_email, G.user_id AS g_user_id, G.phone AS g_phone');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);
        $this->db->where('E.class_id', $class_id);
        $this->db->where('E.section_id', $class_id);
        $this->db->where('E.school_id', $school_id);
        $this->db->where('S.status_type', 'regular');        
        return $this->db->get()->result();
        
    }
    
    public function get_single_material($id){
        
        $this->db->select('SM.*, SC.school_name, C.name AS class_name, S.name AS subject');
        $this->db->from('study_materials AS SM');
        $this->db->join('classes AS C', 'C.id = SM.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SM.subject_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SM.school_id', 'left');
        $this->db->where('SM.id', $id);
        return $this->db->get()->row();
        
    } 

     public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
}
