<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_student_list($school_id = null, $class_id = null, $section_id = null, $academic_year_id = null){
        
        $this->db->select('S.*, E.roll_no, E.class_id, E.section_id, U.username, U.role_id,  C.name AS class_name, SE.name AS section,U.id as s_user_id, G.user_id as g_user_id,G.name as g_name');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);
     //   $this->db->where('S.alumni', 0);
        
        if($section_id){
            $this->db->where('E.section_id', $section_id);
        }
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('SE.teacher_id', $this->session->userdata('profile_id'));
        }
        
        $this->db->where('S.school_id', $school_id);
       
        return $this->db->get()->result();
        
    }    
    public function get_student_list_total($school_id = null, $class_id = null, $section_id = null, $academic_year_id = null){
        
        $this->db->select('S.*, E.roll_no, E.class_id, E.section_id, U.username, U.role_id,  C.name AS class_name, SE.name AS section,U.id as s_user_id, G.user_id as g_user_id,G.name as g_name');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);
     //   $this->db->where('S.alumni', 0);
        
        if($section_id){
            $this->db->where('E.section_id', $section_id);
        }
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('SE.teacher_id', $this->session->userdata('profile_id'));
        }
        
        $this->db->where('S.school_id', $school_id);
       
        $result=$this->db->get()->num_rows();
		
		return $result;
        
    }    
   
    public function get_student_attendance_list($school_id, $academic_year_id, $class_id, $section_id = null){
         
        $this->db->select('E.roll_no,  S.id, S.name');
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);     
        if($section_id){
          $this->db->where('E.section_id', $section_id); 
        }
        $this->db->where('S.school_id', $school_id);        
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        return $this->db->get()->result();    
    } 
    public function get_single_student($student_id ){
        $this->db->select('S.*, U.username, U.role_id,U.id as s_user_id, G.user_id  as g_user_id,G.name as g_name');
        $this->db->from('students AS S');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->where('S.id', $student_id);
        return $this->db->get()->row();       
    } 
     public function get_holiday_list($school_id = null){
        
        $this->db->select('H.*, S.school_name');
        $this->db->from('holidays AS H');
        $this->db->join('schools AS S', 'S.id = H.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('H.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $this->db->order_by('H.id', 'DESC');
        
        return $this->db->get()->result();
        
    }

}
