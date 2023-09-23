<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notice_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_notice_list($school_id = null){
        
        $this->db->select('N.*, S.school_name, R.name');
        $this->db->from('notices AS N');
        $this->db->join('roles AS R', 'R.id = N.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = N.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('N.school_id', $this->session->userdata('school_id'));
        }
         if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('N.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('N.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $this->db->order_by('N.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_notice($id){
        
        $this->db->select('N.*, S.school_name, R.name');
        $this->db->from('notices AS N');
        $this->db->join('roles AS R', 'R.id = N.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = N.school_id', 'left');
        $this->db->where('N.id', $id);
        
        return $this->db->get()->row();
        
    }
    public function get_notice_users($school_id,$role_id =null){
        
        $this->db->select('U.*');
        $this->db->from('users AS U');
        if($role_id)
        {
            $this->db->where('U.role_id', $role_id);
        }
        if($role_id != SUPER_ADMIN) $this->db->where('U.school_id', $school_id);
        return $this->db->get()->result();
    }
    
     function duplicate_check($school_id, $title, $date, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('title', $title);
        $this->db->where('date', date('Y-m-d', strtotime($date)));
        return $this->db->get('notices')->num_rows();            
    }

}
