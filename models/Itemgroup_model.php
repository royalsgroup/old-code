<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itemgroup_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('item_groups')->num_rows();            
    }
	public function get_itemgroup_list($school_id = null){        
        $this->db->select('IG.*, S.school_name');
        $this->db->from('item_groups AS IG');       
        $this->db->join('schools AS S', 'S.id = IG.school_id', 'left');
        if($school_id != null){
            $this->db->where('IG.school_id', $school_id);
        }

        return $this->db->get()->result();
        
    }
}
