<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Block_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('blocks')->num_rows();            
    }
	public function get_block_list(){        
        $this->db->select('B.*,S.name as state,Z.name as zone,SZ.name as subzone,D.name as district');
        $this->db->from('blocks AS B');  
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');
        return $this->db->get()->result();
        
    }	
	public function get_single_block($id){
		 $this->db->select('B.*,S.id as state_id,Z.id as zone_id,SZ.id as subzone_id,D.id as district_id');
        $this->db->from('blocks AS B');  
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');   
		$this->db->where('B.id',$id);		
        return $this->db->get()->row();
	}
}
