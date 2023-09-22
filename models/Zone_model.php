<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Zone_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('zone')->num_rows();            
    }
	public function get_zone_list(){        
        $this->db->select('Z.*,S.name as state');
        $this->db->from('zone AS Z');       		        
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');
        return $this->db->get()->result();
        
    }	
	
}
