<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EmploymentTypes_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
	function duplicate_check($school_id, $name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('employment_types')->num_rows();            
    }
               
	public function get_employment_type_list($school_id = null){        
        $this->db->select('ET.*, S.school_name');
        $this->db->from('employment_types AS ET');       		
        $this->db->join('schools AS S', 'S.id = ET.school_id', 'left');		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where_in('ET.school_id', array($this->session->userdata('school_id'),0));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where_in('ET.school_id', array($school_id,0));
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where_in('ET.school_id', array($school_id,0));
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
	public function get_employment_types_by_employee($employee_id){
		 $this->db->select('ET.*');
        $this->db->from('employee_employment_types AS EET');
        $this->db->join('employment_types AS ET', 'ET.id = EET.employment_type_id', 'left');
		$this->db->where('EET.employee_id', $employee_id);
		return $this->db->get()->result();
	}
	
     public function get_payscale_category_by_school($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
       $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
        }
	
}
