<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Holiday_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_holiday_list($school_id = null){
        
        $this->db->select('H.*, S.school_name');
        $this->db->from('holidays AS H');
        $this->db->join('schools AS S', 'S.id = H.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('H.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $this->db->order_by('H.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    public function get_holiday_users($school_id,$role_id =null){
        $this->db->select('U.*');
        $this->db->from('users AS U');
        $this->db->where('U.school_id', $school_id);
        return $this->db->get()->result();
    }
  
    public function get_single_holiday($holiday_id){
        
        $this->db->select('H.*, S.school_name');
        $this->db->from('holidays AS H');
        $this->db->join('schools AS S', 'S.id = H.school_id', 'left');
        $this->db->where('H.id', $holiday_id);
        return $this->db->get()->row();
        
    }
     function duplicate_check($school_id, $title, $date_from, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('title', $title);
        $this->db->where('date_from', date('Y-m-d', strtotime($date_from)));
        return $this->db->get('holidays')->num_rows();            
    }

}
