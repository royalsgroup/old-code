<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itemcategory_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('item_category', $name);
        return $this->db->get('item_category')->num_rows();            
    }
	public function get_itemcategory_list($school_id = null){        
        $this->db->select('IC.*, S.school_name');
        $this->db->from('item_category AS IC');       
        $this->db->join('schools AS S', 'S.id = IC.school_id', 'left');
        if($school_id != null){
            $this->db->where('IC.school_id', $school_id);
        }
        
      
        return $this->db->get()->result();
        
    }
}
