<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subzone_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('subzone')->num_rows();            
    }
	public function get_subzone_list(){        
        $this->db->select('SZ.*,S.name as state,Z.name as zone');
        $this->db->from('subzone AS SZ');       		        
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');
        return $this->db->get()->result();
        
    }	
	public function get_single_subzone($subzone_id){
		$this->db->select('SZ.*,S.name as state,S.id as state_id,Z.name as zone');
        $this->db->from('subzone AS SZ'); 
		$this->db->where('SZ.id',$subzone_id);
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');
        return $this->db->get()->row();
	}
	
}
