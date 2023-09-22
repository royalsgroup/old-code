<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class District_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('districts')->num_rows();            
    }
	public function get_district_list(){        
        $this->db->select('D.*,S.name as state,Z.name as zone,SZ.name as subzone');
        $this->db->from('districts AS D');  
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');
        return $this->db->get()->result();
        
    }	
	public function get_single_district($id){
		 $this->db->select('D.*,S.name as state,S.id as state_id,Z.id as zone_id,SZ.name as subzone');
        $this->db->from('districts AS D');  
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');       
		$this->db->where('D.id',$id);		
        return $this->db->get()->row();
	}
	
}
