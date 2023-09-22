<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classes_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_class_list($school_id = null){
        
        $this->db->select('C.*, S.school_name, T.name AS teacher');
        $this->db->from('classes AS C');
        $this->db->join('teachers AS T', 'T.id = C.teacher_id', 'left');
        $this->db->join('schools AS S', 'S.id = C.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('C.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('C.school_id', $school_id);
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('C.school_id', $school_id);
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('S.district_id', $this->session->userdata('district_id'));
		}
        return $this->db->get()->result();
        
    }
    

    
    function duplicate_check($school_id, $name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('classes')->num_rows();            
    }
	function get_class_id($school_id, $name){
		$this->db->select('C.id');
        $this->db->from('classes AS C');
		 $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
		$query = $this->db->get();
		return $query->row();
		
	}
	 public function get_single_class($id){
        
        $this->db->select('C.*, SC.school_name, T.name AS teacher,A.level,A.stream,A.board');
        $this->db->from('classes AS C');
        $this->db->join('teachers AS T', 'T.id = C.teacher_id', 'left');        
        $this->db->join('schools AS SC', 'SC.id = C.school_id', 'left');
		$this->db->join('academic_standards AS A', 'A.id = C.academic_standard_id', 'left');
        $this->db->where('C.id', $id);
        return $this->db->get()->row();
        
    }
}
