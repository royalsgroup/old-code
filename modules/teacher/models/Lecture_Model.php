<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lecture_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_lecture_list($school_id = null, $academic_year_id = null, $class_id = null, $section_id = null,$start=null,$limit=null,$search_text=''){
         
        $this->db->select('L.*, T.name AS teacher, SC.school_name, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('video_lectures AS L');
        $this->db->join('teachers AS T', 'T.id = L.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = L.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = L.school_id', 'left');
          if($search_text!=''){
			$this->db->like('L.lecture_title ', $search_text);
		} 
        if($academic_year_id){
            $this->db->where('L.academic_year_id', $academic_year_id);
        }
        
        if($class_id){
             $this->db->where('L.class_id', $class_id);
        } 
        if($section_id){
             $this->db->where('L.section_id', $section_id);
        } 

        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->group_start();
            $this->db->where('L.school_id', $school_id); 
             $this->db->or_where('L.school_id','0'); 
             $this->db->group_end();
        }        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->group_start();
            $this->db->where('L.school_id', $this->session->userdata('school_id'));
             $this->db->or_where('L.school_id','0'); 
             $this->db->group_end();
        }
     
	if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('L.teacher_id', $this->session->userdata('profile_id'));
        }
		 
	if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('L.section_id', $this->session->userdata('section_id'));
        }	 
	$this->db->where('SC.status', 1);	 
        $this->db->order_by('L.id', 'DESC');
         if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        
        $result = $this->db->get();
       // echo $this->db->last_query();

        //die();
        return $result->result();
        
    }
	     public function get_lecture_list_total($school_id = null, $academic_year_id = null, $class_id = null, $section_id = null,$search_text =''){
         
        $this->db->select('L.*, T.name AS teacher, SC.school_name, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('video_lectures AS L');
        $this->db->join('teachers AS T', 'T.id = L.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = L.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = L.school_id', 'left');
         if($search_text!=''){
			$this->db->like('L.lecture_title ', $search_text);
		} 
        if($academic_year_id){
            $this->db->where('L.academic_year_id', $academic_year_id);
        }
        
        if($class_id){
             $this->db->where('L.class_id', $class_id);
        } 
        if($section_id){
             $this->db->where('L.section_id', $section_id);
        } 
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('L.school_id', $school_id); 
             $this->db->or_where('L.school_id','0'); 
        }        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('L.school_id', $this->session->userdata('school_id'));
             $this->db->or_where('L.school_id','0'); 
        }
	
	if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('L.teacher_id', $this->session->userdata('profile_id'));
        }
		 
	if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('L.section_id', $this->session->userdata('section_id'));
        }	 
	$this->db->where('SC.status', 1);	 
        $this->db->order_by('L.id', 'DESC');
        
        
        return $this->db->get()->num_rows();
        
    }
    
    
    public function get_single_lecture($id){
        
        $this->db->select('L.*, T.name AS teacher, SC.school_name, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year,T.name AS teacher,T.user_id AS t_user_id');
        $this->db->from('video_lectures AS L');
        $this->db->join('teachers AS T', 'T.id = L.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = L.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = L.school_id', 'left');
        $this->db->where('L.id', $id);
        return $this->db->get()->row();    
        
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
    public function get_teacher_by_subject($school_id = null, $class_id = null){
        
        $this->db->select('T.*');
        $this->db->join('subjects AS S', 'S.teacher_id = T.id', 'left');
        $this->db->join('schools AS SC', 'SC.id = T.school_id', 'left');
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('T.school_id', $school_id); 
        }        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('T.school_id', $this->session->userdata('school_id'));
        }
        
        if($class_id){
            $this->db->where('S.class_id', $class_id);
        }
        
        $this->db->order_by('T.id', 'DESC');        
        
        return $this->db->get()->result();
    }

     public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
}
