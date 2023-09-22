<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slider_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_slider_list($school_id = null){
        
        $this->db->select('S.*, SC.school_name');
        $this->db->from('sliders AS S');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('S.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('S.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('S.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_slider($news_id){
        
        $this->db->select('S.*, SC.school_name');
        $this->db->from('sliders AS S');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->where('S.id', $news_id);
        return $this->db->get()->row();
        
    }
      
}