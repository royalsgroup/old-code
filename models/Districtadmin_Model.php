<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Districtadmin_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_districtadmin_list(){
        
        $this->db->select('DA.*,D.name as district_name, U.username, U.role_id, R.name AS role');
        $this->db->from('district_admin AS DA');
        $this->db->join('users AS U', 'U.id = DA.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
		$this->db->join('districts AS D', 'D.id = DA.district_id', 'left');
     
        return $this->db->get()->result();
        
    }
    
    public function get_single_districtadmin($id){
        
        $this->db->select('DA.*,D.name as district_name, U.username, U.role_id, R.name AS role,S.name as state_name,SN.name as sankul');
        $this->db->from('district_admin AS DA');
        $this->db->join('users AS U', 'U.id = DA.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
		$this->db->join('districts AS D', 'D.id = DA.district_id', 'left');
		$this->db->join('states AS S', 'S.id = DA.state_id', 'left');
		$this->db->join('sankul AS SN', 'SN.id = DA.sankul_id', 'left');

        $this->db->where('DA.id', $id);
        return $this->db->get()->row();
        
    }
    
     function duplicate_check($username, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('username', $username);
        return $this->db->get('users')->num_rows();            
    }
	function get_employees($state_id,$zone_id=0,$subzone_id=0,$district_id=0,$block_id=0,$sankul_id=0){
		$this->db->select('E.*,U.username');
        $this->db->from('employees AS E');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
		$this->db->join('users AS U', 'u.id = E.user_id', 'left');
        $this->db->where('S.state_id', $state_id);
		//if($zone_id >0){
		$this->db->where('S.zone_id', $zone_id);
		//}
		//if($subzone_id >0){
		$this->db->where('S.subzone_id', $subzone_id);
		//}
		//if($district_id >0){
		$this->db->where('S.district_id', $district_id);
	//	}
		//if($block_id >0){
		$this->db->where('S.block_id', $block_id);
		//}if($sankul_id >0){
		$this->db->where('S.sankul_id', $sankul_id);
		//}
     
        return $this->db->get()->result();
	}
	function tmp($sankul_id=0,$id){
		$this->db->select('Schools.*,S.id as state_id1, Z.id as zone_id1, SZ.id as subzone_id1, D.id as district_id1,B.id as block_id1');
		$this->db->from('schools AS Schools'); 
		$this->db->join('sankul AS Sankul', 'Sankul.id = Schools.sankul_id', 'left');   
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');  
		//$this->db->where('S.sankul_id', $sankul_id);		
		$this->db->where('Schools.id',$id);	
		$this->db->where('Schools.sankul_id',$sankul_id);	
		
        $res=$this->db->get()->row();	
		/*foreach($res as $r){
			$this->db->set('state_id', $r->state_id1);
			$this->db->set('zone_id', $r->zone_id1);
			$this->db->set('subzone_id', $r->subzone_id1);
			$this->db->set('district_id', $r->district_id1);
			$this->db->set('block_id', $r->block_id1);			
			$this->db->where('id', $r->id);
			$this->db->update('schools'); 
		}*/
		return $res;
	}
}
