<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Section_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_section_list($class_id = null, $school_id = null){
        
       if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        }
        
        $this->db->select('S.*, SC.school_name, C.name AS class_name, T.name AS teacher');
        $this->db->from('sections AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        /*if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
        }*/
        
        if($class_id > 0){
            $this->db->where('S.class_id', $class_id); 
        }
        
               
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('S.school_id', $school_id); 
        } 
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('S.school_id', $school_id);
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}		
        
        $this->db->order_by('S.class_id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_section($id){
        
        $this->db->select('S.*, C.name AS class_name, T.name AS teacher');
        $this->db->from('sections AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->where('S.id', $id);
        return $this->db->get()->row();
        
    }
    
    function duplicate_check($school_id, $class_id, $name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('class_id', $class_id);
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('sections')->num_rows();            
    }
	function get_section_id($school_id,$class_id, $name){
		$this->db->select('S.id');
        $this->db->from('sections AS S');
		 $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
		$this->db->where('class_id', $class_id);
		$query = $this->db->get();
		return $query->row();
		
	}
 
}
