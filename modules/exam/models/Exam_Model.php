<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Exam_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    public function get_school_by_id($school_id){

        $schoolData = $this->db->query("select * from schools where id ='$school_id'")->row();
        return $schoolData;
     }
     
    public function get_all_exam_list($school_id = null, $academic_year_id = null){
        
        // $this->db->select('E.*, S.school_name, AY.session_year');
        // $this->db->from('exams AS E');
        // $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        // $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
                
         
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
        }
        
        // if($school_id){
        //     $this->db->where('E.school_id', $school_id);
        //     $this->db->or_where('E.school_id', 0);
        // }
        
        // if($academic_year_id){        
        //     $this->db->where('E.academic_year_id', $academic_year_id);
        // }       
        
        // $res = $this->db->get()->result();


        $sql = "SELECT `E`.*, `S`.`school_name`, `AY`.`session_year` FROM `exams` AS `E` LEFT JOIN `academic_years` AS `AY` ON `AY`.`id` = `E`.`academic_year_id` LEFT JOIN `schools` AS `S` ON `S`.`id` = `E`.`school_id` WHERE `E`.`academic_year_id` = '$academic_year_id' and (`E`.`school_id` = '$school_id' OR `E`.`school_id` =0) ";
            
       $res = $this->db->query($sql)->result();

        //echo $this->db->last_query();die;
        return $res ;
        
    }

    
     public function get_exam_list($school_id = null, $academic_year_id = null){
        
        $this->db->select('E.*, S.school_name, AY.session_year');
        $this->db->from('exams AS E');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
                
         
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
        }
        
        if($school_id){
            $this->db->where('E.school_id', $school_id);
        }
        
        if($academic_year_id){        
            $this->db->where('E.academic_year_id', $academic_year_id);
        }       
        
        return $this->db->get()->result();
        
    }
    
     public function get_single_exam($id){
        
        $this->db->select('E.*, AY.session_year');
        $this->db->from('exams AS E');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');        
        $this->db->where('E.id', $id);
        return $this->db->get()->row();
        
    }
    
     function duplicate_check($school_id, $academic_year_id, $title, $id = null ,$practical = 0){           
                 
        if($id){
            $this->db->where_not_in('id', $id);
        }
        
        $this->db->where('school_id', $school_id);
        $this->db->where('title', $title);
        $this->db->where('academic_year_id', $academic_year_id);
        $this->db->where('practical', $practical);

        return $this->db->get('exams')->num_rows();            
    }

}
