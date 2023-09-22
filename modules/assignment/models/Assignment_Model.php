<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assignment_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_assignment_list($class_id = null , $school_id = null, $academic_year_id = null){
         
        $this->db->select('A.*, SC.school_name, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('assignments AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = A.school_id', 'left');
        
        if($academic_year_id){
            $this->db->where('A.academic_year_id', $academic_year_id);
        }
        if($class_id > 0){
             $this->db->where('A.class_id', $class_id);
        }  
        if($school_id && $this->session->userdata('role_id') == TEACHER){
            $this->db->group_start();

            $this->db->where('LC.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('C.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('S.teacher_id', $this->session->userdata('profile_id'));
            $this->db->group_end();

        }  
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('A.school_id', $school_id); 
        }        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('A.school_id', $this->session->userdata('school_id'));
        }
        $this->db->order_by('A.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    public function get_student_list($school_id, $class_id = null, $section_id =null, $academic_year_id = null ){
        
        $this->db->select('S.user_id,S.email, S.phone, S.name, G.name AS g_name, G.email AS g_email, G.user_id AS g_user_id, G.phone AS g_phone');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);
        if($class_id)
        {
            $this->db->where('E.class_id', $class_id);
            $this->db->where('E.section_id', $class_id);
        }
        $this->db->where('E.school_id', $school_id);
        $this->db->where('S.status_type', 'regular');        
        return $this->db->get()->result();
        
    }
    public function get_single_assignment($id){
        
        $this->db->select('A.*,  SC.school_name, C.name AS class_name, S.name AS subject');
        $this->db->from('assignments AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = A.subject_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = A.school_id', 'left');
        $this->db->where('A.id', $id);
        return $this->db->get()->row();        
    } 
}
