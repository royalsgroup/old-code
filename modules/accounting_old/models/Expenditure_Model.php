<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenditure_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
 
    public function get_expenditure_list($school_id = null){
        
        $this->db->select('E.*, S.school_name, H.title AS head, AY.session_year');
        $this->db->from('expenditures AS E');        
        $this->db->join('expenditure_heads AS H', 'H.id = E.expenditure_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
       
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
        
        
        $this->db->order_by('E.id', 'DESC');
        
        return $this->db->get()->result();        
    }
    public function get_single_expenditure($id){
        
        $this->db->select('E.*, S.school_name,  H.title AS head, AY.session_year');
        $this->db->from('expenditures AS E');        
        $this->db->join('expenditure_heads AS H', 'H.id = E.expenditure_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.id', $id); 
        return $this->db->get()->row();        
    }
}
