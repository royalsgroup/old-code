<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itemsupplier_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($code, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('code', $code);
        return $this->db->get('item_store')->num_rows();            
    }
	public function get_itemsupplier_list($school_id = null){        
        $this->db->select('IS.*, S.school_name');
        $this->db->from('item_supplier AS IS');       
        $this->db->join('schools AS S', 'S.id = IS.school_id', 'left');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('IS.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('IS.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('IS.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
}
