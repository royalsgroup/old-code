<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accountgroups_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('account_groups')->num_rows();            
    }
	public function get_accountgroup_list($school_id = null,$category =null){  
        $this->db->select('AG.*, S.school_name,AT.name as type_name,IFNULL(AB.name,"") as `base_name`');
        $this->db->from('account_groups AS AG');       		
        $this->db->join('schools AS S', 'S.id = AG.school_id', 'left');
		$this->db->join('account_types AS AT', 'AT.id = AG.type_id', 'left');
		$this->db->join('account_base AS AB', 'AB.id = AG.base_id', 'left');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where_in('AG.school_id', array(0,$this->session->userdata('school_id')));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!= null){
            $this->db->where_in('AG.school_id', array(0,$school_id));
        }
		if($this->session->userdata('dadmin') == 1 && $school_id!=null){
            $this->db->where_in('AG.school_id', array(0,$school_id));
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$ids=$this->session->userdata('dadmin_school_ids');
			array_push($ids,0);
			$this->db->where_in('S.id', $ids);
		}
        if($category)
        {
            $this->db->where('AG.category', $category);
        }
        return $this->db->get()->result();
        
    }
    public function get_accountgroup_by_types($school_id,$group_types = null){  
        $this->db->select('AG.*');
        $this->db->from('account_groups AS AG');   
        $this->db->group_start();    		
        $this->db->where('AG.school_id', $school_id);
        $this->db->or_where('AG.school_id', 0);
        $this->db->group_end();
        if($group_types)
		{
			$this->db->where_in('AG.type_id', $group_types); 
		}
        return $this->db->get()->result();
        
    }
    
	public function get_accountgroup_list_by_school($school_id = null){  
        $this->db->select('AG.*');
        $this->db->from('account_groups AS AG');       		      
        $this->db->where_in('AG.school_id', array(0,$school_id));      
        return $this->db->get()->result();
        
    }
	public function get_accountgroup_by_slug($school_id = null,$slug = null){        
        $this->db->select('AG.*');
        $this->db->from('account_groups AS AG');  
		if($school_id){
			$this->db->where('AG.school_id',$school_id);
		}
		if($slug){
			$this->db->where('AG.slug',$slug);
		}        
        return $this->db->get()->row();
        
    }
	public function get_accountgroup_by_name($school_id = null,$name = null){        
        $this->db->select('AG.*');
        $this->db->from('account_groups AS AG');  
		if($school_id){
			$this->db->where('AG.school_id',$school_id);
		}
		if($slug){
			$this->db->where('AG.name',$name);
		}        
        return $this->db->get()->row();
        
    }
	
}
