<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subjects_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('payment_modes')->num_rows();            
    }
	public function get_subject_list($school_id = null){        
        $this->db->select('ASB.*, S.school_name, AD.name as discipline_name');
        $this->db->from('academic_subjects AS ASB');       		
        $this->db->join('schools AS S', 'S.id = ASB.school_id', 'left');		
		$this->db->join('academic_disciplines AS AD', 'AD.id = ASB.school_id', 'left');		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ASB.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('ASB.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('ASB.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
	public function get_subjects_with_class_list($school_id = null){
		 $this->db->select('ASB.*, S.school_name, AD.name as discipline_name');
        $this->db->from('academic_subjects AS ASB');       		
        $this->db->join('schools AS S', 'S.id = ASB.school_id', 'left');		
		$this->db->join('academic_disciplines AS AD', 'AD.id = ASB.school_id', 'left');		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('ASB.school_id', $this->session->userdata('school_id'));
        }
	}
	
}
