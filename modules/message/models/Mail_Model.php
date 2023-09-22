<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mail_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    } 
    
    public function get_email_list($school_id = null){
        
        $this->db->select('E.*, S.school_name, R.name AS receiver_type, AY.session_year ');
        $this->db->from('emails AS E');
        $this->db->join('roles AS R', 'R.id = E.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.email_type', 'general');
        
        $this->db->group_start();
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $this->db->or_where('E.school_id', 0);
        $this->db->group_end();

        $this->db->order_by('E.id', 'DESC');
        
        return $this->db->get()->result();    
    }
    
    public function get_single_email($id){
        
        $this->db->select('E.*, S.school_name, R.name AS receiver_type, AY.session_year ');
        $this->db->from('emails AS E');
        $this->db->join('roles AS R', 'R.id = E.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
         $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.id', $id);
        return $this->db->get()->row();    
    }
    public function get_result_user_list($school_id, $class_id ,$receiver_id)
    {
        $school = $this->get_school_by_id($school_id);                 

        $this->db->select('S.*, C.name AS class_name,TR.obtain_mark,TR.max_marks,TR.grade,TR.result,TR.percentage,S.admission_no,TR.id as result_id');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('temp_result AS TR', 'S.id = TR.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->where('E.academic_year_id', $school->academic_year_id);
        $this->db->where('E.class_id', $class_id);
        if($receiver_id){
            $this->db->where('S.user_id', $receiver_id);
        }
        $this->db->where('S.school_id', $school_id);
        $this->db->where("S.email !='' ");
        return $this->db->get()->result();

    }
    public function get_user_list($school_id, $role_id, $receiver_id, $class_id = null){
        
        if($role_id == SUPER_ADMIN){
               
            $this->db->select('SA.phone, SA.email, SA.name, U.username, U.role_id,  U.id');
            $this->db->from('system_admin AS SA');
            $this->db->join('users AS U', 'U.id = SA.user_id', 'left');
            if($receiver_id > 0){
                $this->db->where('SA.user_id', $receiver_id);
            }
                     
            return $this->db->get()->result();
            
        }elseif($role_id == STUDENT){            
                      
            $school = $this->get_school_by_id($school_id);                 
            
            $this->db->select('S.phone, S.email, S.name, U.username, U.role_id,  U.id, C.name AS class_name');
            $this->db->from('enrollments AS E');
            $this->db->join('students AS S', 'S.id = E.student_id', 'left');
            $this->db->join('users AS U', 'U.id = S.user_id', 'left');
            $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
            $this->db->where('E.academic_year_id', $school->academic_year_id);
            $this->db->where('E.class_id', $class_id);
            $this->db->where('S.school_id', $school_id);
            if($receiver_id > 0){
                $this->db->where('S.user_id', $receiver_id);
            }
            return $this->db->get()->result();
            
        }elseif($role_id == TEACHER){
            
            $this->db->select('T.phone, T.email, T.name, U.username, U.role_id,  U.id');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');
            $this->db->where('T.school_id', $school_id);
            if($receiver_id > 0){
                $this->db->where('T.user_id', $receiver_id);
            }
            return $this->db->get()->result();            
        
        }elseif($role_id == GUARDIAN){
            
            $this->db->select('G.phone, G.email, G.name, U.username, U.role_id,  U.id');
            $this->db->from('guardians AS G');
            $this->db->join('users AS U', 'U.id = G.user_id', 'left'); 
            $this->db->where('G.school_id', $school_id);
            if($receiver_id > 0){
                $this->db->where('G.user_id', $receiver_id);
            }
            return $this->db->get()->result();
            
        }else{
            
            $this->db->select('E.phone, E.email, E.name, U.username, U.role_id,  U.id');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->where('U.role_id', $role_id);
            
            if($receiver_id > 0){
                $this->db->where('E.user_id', $receiver_id);
            }
            
            if($role_id != SUPER_ADMIN){
                $this->db->where('E.school_id', $school_id);
            }            
            return $this->db->get()->result();
        }  
    }
}