<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Image_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
      
    public function get_image_list($school_id = null){
        
        $this->db->select('GI.*, S.school_name, G.title');
        $this->db->from('gallery_images AS GI');
        $this->db->join('galleries AS G', 'G.id = GI.gallery_id', 'left');
         $this->db->join('schools AS S', 'S.id = GI.school_id', 'left');
         
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('GI.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('GI.school_id', $school_id);
        }  
if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('GI.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}		
        $this->db->order_by('GI.id', 'DESC');
        
        
        return $this->db->get()->result();        
    }
    
    public function get_single_image($id){
        
        $this->db->select('GI.*, S.school_name, G.title');
        $this->db->from('gallery_images AS GI');
        $this->db->join('galleries AS G', 'G.id = GI.gallery_id', 'left');
          $this->db->join('schools AS S', 'S.id = GI.school_id', 'left');
        $this->db->where('GI.id', $id);
        return $this->db->get()->row();        
    }

}