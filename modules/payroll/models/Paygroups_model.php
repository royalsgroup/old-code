<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paygroups_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
       
	function duplicate_check($school_id, $name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('pay_groups')->num_rows();            
    }
	function duplicate_check_code($school_id, $code, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('group_code', $code);
        $this->db->where('school_id', $school_id);
        return $this->db->get('pay_groups')->num_rows();            
    }
	public function get_paygroup_list($school_id = null){        
        $this->db->select('PG.*, S.school_name');
        $this->db->from('pay_groups AS PG');       		
        $this->db->join('schools AS S', 'S.id = PG.school_id', 'left');		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('PG.school_id', $this->session->userdata('school_id'));
            $this->db->or_where('school_id','0'); 
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('PG.school_id', $school_id);
            $this->db->or_where('school_id','0'); 
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('PG.school_id', $school_id);
            $this->db->or_where('school_id','0'); 
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
	public function get_list_by_school($school_id){
		 $this->db->select('PG.*');
         $this->db->from('pay_groups AS PG'); 
		$this->db->where('PG.school_id', $school_id);
		$this->db->or_where('PG.school_id', 0);
		 return $this->db->get()->result();
	}
}
