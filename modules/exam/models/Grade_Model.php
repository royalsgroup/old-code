<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grade_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    public function get_school_by_id($school_id){

        $schoolData = $this->db->query("select * from schools where id ='$school_id'")->row();
        return $schoolData;
     }

    public function get_all_garde_list($school_id = null,$academic_year_id=''){
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
        }

        // $this->db->select('G.*, S.school_name');
        // $this->db->from('grades AS G');
        // $this->db->join('schools AS S', 'S.id = G.school_id', 'left');
        // $this->db->join('academic_years AS AY', 'AY.id = G.academic_year_id', 'left');
        // 
        // $this->db->where('G.academic_year_id', $academic_year_id);
        // if($school_id){
        //     $this->db->where('G.school_id', $school_id);
        //    // $this->db->or_where('G.school_id', 0);
        // }
    
        $sql = "SELECT `G`.*, `S`.`school_name` FROM `grades` AS `G` LEFT JOIN `schools` AS `S` ON `S`.`id` = `G`.`school_id` LEFT JOIN `academic_years` AS `AY` ON `AY`.`id` = `G`.`academic_year_id` WHERE `G`.`academic_year_id` = '$academic_year_id' AND (`G`.`school_id` = '$school_id' OR `G`.`school_id` = '0')";
            
       $res = $this->db->query($sql)->result();
        
        //echo "<pre>"; print_r($res);die;
        //echo $this->db->last_query();die;
        return $res ;
    }

    public function get_garde_list($school_id = null){
        
        $this->db->select('G.*, S.school_name');
        $this->db->from('grades AS G');
        $this->db->join('schools AS S', 'S.id = G.school_id', 'left');
        
        if($school_id){
            $this->db->where('G.school_id', $school_id);
        }
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $this->db->where('G.school_id', $this->session->userdata('school_id'));
        }
        return $this->db->get()->result();
    }
   
    
    function duplicate_check($field, $school_id, $value, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where($field, $value);
        $this->db->where('school_id', $school_id);
        return $this->db->get('grades')->num_rows();            
    }

}
