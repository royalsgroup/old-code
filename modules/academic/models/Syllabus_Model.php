<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Syllabus_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_school($school_id){

        $schoolData = $this->db->query("select * from schools where id ='$school_id'")->row();
        return $schoolData;
     }

     public function get_syllabus_list($class_id = null, $school_id = null,$start=null,$limit=null,$search_text=''){
        
        if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        }

        $schoolData = $this->get_school($school_id);
        $academic_year_id = $schoolData->academic_year_id;
        $this->db->select('SY.*, SC.school_name, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('syllabuses AS SY');
        $this->db->join('classes AS C', 'C.id = SY.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SY.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SY.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SY.school_id', 'left');

        $this->db->where('SY.academic_year_id',$academic_year_id);
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->group_start();

            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('C.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('S.teacher_id', $this->session->userdata('profile_id'));
            $this->db->group_end();



        }
        
        if($class_id){
            $this->db->where('SY.class_id', $class_id);
        }
        if($search_text!=''){
		
			$this->db->like('SY.title ', $search_text);
		}    
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('SY.school_id', $school_id); 
             $this->db->or_where('SY.school_id','0');
        } 
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('SY.school_id', $this->session->userdata('school_id'));
             $this->db->or_where('SY.school_id','0');
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('SY.school_id', $school_id);
            $this->db->or_where('SY.school_id','0');
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}	
        

        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }	
        $this->db->order_by('SY.id', 'DESC');
       
        $res =  $this->db->get()->result();
        //  echo $this->db->last_query();
        // die();
        return $res ;
        
    }
    public function get_syllabus_list_total($class_id = null, $school_id = null,$search_text=''){
        
        if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        }

        $schoolData = $this->get_school($school_id);
        $academic_year_id = $schoolData->academic_year_id;
        $this->db->select('SY.*, SC.school_name, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('syllabuses AS SY');
        $this->db->join('classes AS C', 'C.id = SY.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SY.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SY.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SY.school_id', 'left');
            
        $this->db->where('SY.academic_year_id',$academic_year_id);
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
        }
        if($search_text!=''){
		
			$this->db->like('SY.title ', $search_text);
		}    
        if($class_id){
            $this->db->where('SY.class_id', $class_id);
        }
        
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('SY.school_id', $school_id); 
             $this->db->or_where('SY.school_id','0');
        } 
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('SY.school_id', $this->session->userdata('school_id'));
             $this->db->or_where('SY.school_id','0');
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('SY.school_id', $school_id);
            $this->db->or_where('SY.school_id','0');
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('SC.district_id', $this->session->userdata('district_id'));
		}	
        

        $this->db->order_by('SY.id', 'DESC');
       
        $result=$this->db->get()->num_rows();
		
		return $result;
        
    }
    
    public function get_single_syllabus($id){
        
        $this->db->select('SY.*, SC.school_name, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('syllabuses AS SY');
        $this->db->join('classes AS C', 'C.id = SY.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = SY.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SY.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = SY.school_id', 'left');
        $this->db->where('SY.id', $id);
        return $this->db->get()->row();
        
    } 


    public function get_disciplines($id){
        
        $this->db->select('academic_disciplines.name,academic_disciplines.id');
        $this->db->from('syllabuses ');
        $this->db->join('academic_disciplines', 'syllabuses.disciplines = academic_disciplines.id');
        $this->db->where('syllabuses.id', $id);
        return $this->db->get()->row();
        
    } 

    public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
}
