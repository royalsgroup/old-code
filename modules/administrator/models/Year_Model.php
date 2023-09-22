<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Year_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_year_list($school_id = null){
        
        $this->db->select('AY.*, S.school_name');
        $this->db->from('academic_years AS AY');
        $this->db->join('schools AS S', 'S.id = AY.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
        {
            $this->db->where('AY.school_id', $this->session->userdata('school_id'));
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN)
        {
            $this->db->where('AY.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('AY.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}	
        $this->db->order_by('AY.id', 'ASC');
        return $this->db->get()->result();
        
    }
    function duplicate_check($session_year, $school_id, $id = null )
    {           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('session_year', $session_year);
        return $this->db->get('academic_years')->num_rows();            
    }
	function get_year_id($school_id,$start_year,$end_year){
		$this->db->select('AY.id');
        $this->db->from('academic_years AS AY');
		$this->db->where('start_year', $start_year);
		$this->db->where('end_year', $end_year);
		 //$this->db->where('session_year', $session_year);
        $this->db->where('school_id', $school_id);		
		$query = $this->db->get();
		return $query->row();
		
	}
}
